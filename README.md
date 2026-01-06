# MapWisePolitics WordPress Theme

This repository contains the custom WordPress theme used by MapWiseResearch to power **mapwisepolitics.us** and related properties.

The theme is purpose-built for publishing political analysis, election forecasts, and long-form articles. It prioritizes clarity, performance, and direct control over appearance without relying on page builders or heavy plugins.

---

## Purpose

This theme serves as the single source of truth for the visual identity and layout of MapWisePolitics. All site structure, typography, and presentation logic lives inside this repository.

### Design goals

- Maintain a clean, readable layout for long-form content
- Load quickly on desktop and mobile devices
- Avoid unnecessary dependencies and abstractions
- Support a Git-based deployment workflow

---

## Technology Stack

- **WordPress** (self-hosted)
- **PHP** (theme templates and hooks)
- **CSS** (vanilla, no frameworks)
- **JavaScript** (minimal, when required)

### Explicitly avoided

- Page builders (Elementor, WPBakery, Gutenberg layout blocks)
- Front-end frameworks
- Theme option panels or visual editors

---

## Installation

### Clone the repository

```bash
git clone https://github.com/MapWiseResearch/website_theme.git
```

### Move into WordPress themes directory

```bash
mv website_theme /path/to/wordpress/wp-content/themes/mapwisepolitics
```

### Activate the theme

1. Log in to the WordPress admin dashboard  
2. Navigate to **Appearance → Themes**  
3. Activate **MapWisePolitics**

---

## Updating the Theme

When the theme is installed using Git on the server:

```bash
cd wp-content/themes/mapwisepolitics
git pull origin main
```

After pulling updates:

- Clear any server-side or plugin-based caches
- Hard refresh the browser to ensure styles update


## Content Workflow

- Long-form articles and analysis are published as WordPress posts
- Static content pages use the default page template
- Presentation and layout changes are made directly in the theme
- All changes are versioned and deployed through Git

Business logic, data processing, or integrations should be implemented as plugins rather than added to the theme.

---

## Deployment Notes

This theme is intended for server-based WordPress installations such as:

- Virtual private servers
- Cloud instances
- Dedicated hosts

### Recommended workflow

1. Develop locally or in a staging environment  
2. Commit changes to this repository  
3. Deploy updates via Git pull or CI  

Avoid editing theme files through the WordPress admin editor in production.
