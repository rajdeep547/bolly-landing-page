#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT_DIR"

echo "Starting Bolly WordPress environment..."
docker compose up -d

echo "Waiting for containers..."
sleep 15

wp() {
  docker compose exec -T wpcli wp "$@" --allow-root
}

for _ in $(seq 1 20); do
  if wp core is-installed >/dev/null 2>&1; then
    break
  fi

  wp core install \
    --url=http://localhost:8080 \
    --title="Bolly" \
    --admin_user=admin \
    --admin_password=admin123 \
    --admin_email=admin@example.com \
    --skip-email >/dev/null 2>&1 || true

  sleep 4
done

wp plugin install elementor --activate
wp plugin activate bolly-landing
wp rewrite structure '/%postname%/' --hard
wp option update show_on_front page
wp eval 'Bolly_Installer::create_landing_page();'

PAGE_ID="$(wp post list --post_type=page --name=bolly-landing --field=ID)"
if [[ -n "$PAGE_ID" ]]; then
  wp option update page_on_front "$PAGE_ID"
fi

echo ""
echo "Setup complete."
echo "Site:  http://localhost:8080"
echo "Admin: http://localhost:8080/wp-admin"
echo "Login: admin / admin123"
