# Submission Guide

## Project summary

Complete WordPress + Elementor implementation of the Bolly shampoo landing page with an interactive Three.js product bottle.

## Assignment checklist

| Requirement | Status | Implementation |
|-------------|--------|----------------|
| Landing page in WordPress + Elementor | Done | Plugin widgets + auto-created Elementor page |
| Design fidelity (layout, typography, colors, spacing) | Done | `assets/css/bolly-landing.css` |
| Interactive 3D product | Done | `assets/js/bolly-3d-bottle.js` with OrbitControls |
| Desktop cursor rotation | Done | OrbitControls drag |
| Mobile touch rotation | Done | OrbitControls touch support |
| Responsive (desktop, tablet, mobile, 320px) | Done | CSS breakpoints at 1100/768/480/320px |
| Clean, maintainable code | Done | Modular plugin structure |
| WordPress integration | Done | Plugin, shortcodes, Elementor widgets, installer |

## What to submit

Submit the full `frontend_assignment` folder or the generated zip from:

```bash
npm run package
```

Generated files:

- `dist/bolly-landing.zip` — WordPress plugin only
- `dist/frontend-assignment.zip` — complete project bundle

## How to run locally

### Option 1: Static preview (fastest)

```bash
npm run preview
```

Open `http://localhost:3456`

### Option 2: Full WordPress + Elementor (recommended for review)

**Requirements:** Docker Desktop

```bash
npm run wordpress:up
npm run wordpress:setup
```

| Item | Value |
|------|-------|
| Site | http://localhost:8080 |
| Admin | http://localhost:8080/wp-admin |
| Username | admin |
| Password | admin123 |

The landing page is created automatically and set as the homepage.

## Manual WordPress install (without Docker)

1. Copy `wordpress/bolly-landing` to `wp-content/plugins/`
2. Activate **Bolly Landing Page**
3. Install and activate **Elementor**
4. Go to **Bolly Landing** in the WP admin menu
5. Click **Create / Recreate Landing Page**
6. View the page or edit with Elementor

## Elementor template import

1. In WordPress admin, go to **Templates → Saved Templates**
2. Click **Import Templates**
3. Upload `elementor-templates/bolly-landing-page.json`
4. Create a new page and insert the imported template

Note: the **Bolly Landing Hero** widget must be available (plugin active).

## Key files

```
frontend_assignment/
├── docker-compose.yml
├── elementor-templates/bolly-landing-page.json
├── preview/index.html
├── scripts/
│   ├── setup-wordpress.ps1
│   ├── setup-wordpress.sh
│   ├── setup-wordpress.js
│   └── package-project.js
└── wordpress/bolly-landing/
    ├── bolly-landing.php
    ├── includes/
    ├── templates/
    └── assets/
```

## Testing checklist

- [ ] Landing page loads without theme header/footer (Canvas template)
- [ ] Bottle rotates on mouse drag (desktop)
- [ ] Bottle rotates on touch swipe (mobile)
- [ ] Layout works at 1440px, 1024px, 768px, 480px, 320px
- [ ] No horizontal scrolling at any breakpoint
- [ ] Elementor widget renders in editor and frontend

## Notes for reviewers

- Three.js is bundled locally in `assets/vendor/` for reliability
- Google Fonts (Montserrat) load via WordPress enqueue
- The 3D bottle is procedurally generated; no external model file is required
