$ErrorActionPreference = "Stop"
$ProjectRoot = Split-Path -Parent $PSScriptRoot

Set-Location $ProjectRoot

Write-Host "Starting Bolly WordPress environment..." -ForegroundColor Cyan
docker compose up -d

Write-Host "Waiting for containers..." -ForegroundColor Cyan
Start-Sleep -Seconds 15

function Invoke-Wp([string]$Command) {
    docker compose exec -T wpcli wp $Command --allow-root
}

$installed = $false
for ($i = 0; $i -lt 20; $i++) {
    try {
        Invoke-Wp "core is-installed" | Out-Null
        $installed = $true
        break
    } catch {
        try {
            Invoke-Wp "core install --url=http://localhost:8080 --title=Bolly --admin_user=admin --admin_password=admin123 --admin_email=admin@example.com --skip-email"
            $installed = $true
            break
        } catch {
            Start-Sleep -Seconds 4
        }
    }
}

if (-not $installed) {
    throw "WordPress did not become ready in time."
}

Invoke-Wp "plugin install elementor --activate"
Invoke-Wp "plugin activate bolly-landing"
Invoke-Wp "rewrite structure /%postname%/ --hard"
Invoke-Wp "option update show_on_front page"
Invoke-Wp "eval `"Bolly_Installer::create_landing_page();`""

$PageId = (Invoke-Wp "post list --post_type=page --name=bolly-landing --field=ID").Trim()
if ($PageId) {
    Invoke-Wp "option update page_on_front $PageId"
}

Write-Host ""
Write-Host "Setup complete." -ForegroundColor Green
Write-Host "Site:  http://localhost:8080"
Write-Host "Admin: http://localhost:8080/wp-admin"
Write-Host "Login: admin / admin123"
