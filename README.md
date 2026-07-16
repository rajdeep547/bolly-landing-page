# Bolly Landing Page — Complete Project

WordPress + Elementor recreation of the Bolly shampoo landing page with an interactive 3D product bottle.

## Project structure

```
frontend_assignment/
├── README.md
├── SUBMISSION.md
├── docker-compose.yml
├── package.json
├── preview/                         # Standalone HTML preview
├── elementor-templates/             # Importable Elementor page template
├── scripts/                         # Setup and packaging scripts
└── wordpress/bolly-landing/         # WordPress plugin
    ├── bolly-landing.php
    ├── includes/
    ├── templates/
    └── assets/
        ├── css/
        ├── js/
        └── vendor/                  # Bundled Three.js
```

## Requirements covered

1. **Landing page** — WordPress + Elementor with design-matched layout, typography, colors, and spacing
2. **Interactive 3D product** — Three.js bottle with cursor drag (desktop) and touch rotation (mobile)
3. **Responsive design** — Desktop, tablet, mobile, including 320px width
4. **Code quality** — Modular plugin, scoped CSS, local vendor assets, Elementor lifecycle hooks

## Quick start

### Preview without WordPress

```bash
npm run preview
```

Open `http://localhost:3456` and drag/swipe the bottle to rotate it.

### Full WordPress environment (Docker)

**Prerequisites:** [Docker Desktop](https://www.docker.com/products/docker-desktop/)

```bash
npm run wordpress:up
npm run wordpress:setup
```

| | |
|---|---|
| **Site** | http://localhost:8080 |
| **Admin** | http://localhost:8080/wp-admin |
| **Login** | `admin` / `admin123` |

The plugin auto-creates the landing page and sets it as the homepage.

**Windows PowerShell alternative:**

```powershell
.\scripts\setup-wordpress.ps1
```

**Stop containers:**

```bash
npm run wordpress:down
```

## WordPress plugin installation

### Automatic (included in this repo)

Copy `wordpress/bolly-landing` to `wp-content/plugins/bolly-landing/` and activate it.

On activation the plugin:

- Creates a **Bolly Landing** page
- Applies the Elementor canvas template
- Inserts the **Bolly Landing Hero** widget (when Elementor is active)

### Admin panel

Go to **Bolly Landing** in the WordPress sidebar to:

- View the live landing page
- Open **Edit with Elementor**
- Recreate the page if needed

### Shortcodes

```
[bolly_landing]
[bolly_3d_bottle]
```

### Elementor widgets

| Widget | Description |
|--------|-------------|
| **Bolly Landing Hero** | Full landing page (header + hero + 3D bottle + CTA) |
| **Bolly 3D Bottle** | Interactive bottle only |

Find them under the **Bolly** category in the Elementor panel.

### Import Elementor template

1. **Templates → Saved Templates → Import Templates**
2. Upload `elementor-templates/bolly-landing-page.json`
3. Insert into any page

## 3D interaction

- **Desktop:** click and drag on the bottle
- **Mobile/tablet:** touch and swipe
- Zoom and pan are disabled for a focused product experience

## Packaging for submission

```bash
npm run package
```

Creates:

- `dist/bolly-landing.zip` — plugin only
- `dist/frontend-assignment.zip` — full project

See [SUBMISSION.md](./SUBMISSION.md) for the reviewer checklist.

## Customization

| What | Where |
|------|-------|
| Colors, spacing, breakpoints | `assets/css/bolly-landing.css` |
| Bottle shape and label | `assets/js/bolly-3d-bottle.js` |
| Page copy | `templates/landing-page.php` |

## Tech stack

- WordPress 6.x
- Elementor (free)
- Three.js r128 + OrbitControls
- PHP 7.4+
- Docker (optional local environment)

## License

MIT
"# bolly-landing-page" 
