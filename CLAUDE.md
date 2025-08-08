# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Theme Overview

This is the Zotefoams WordPress theme built on the Underscores (_s) starter theme framework. It's a custom corporate theme for Zotefoams with extensive ACF (Advanced Custom Fields) integration and component-based architecture.

## Development Commands

### Build and Development
- `npm run start` - Full development server with live reload (BrowserSync) and Rollup watch mode
- `npm run lint` - Lint and format SASS files with Stylelint and Prettier

### Code Quality
- `composer lint:wpcs` - Check PHP files against WordPress Coding Standards
- `composer lint:php` - Check PHP files for syntax errors  
- `composer make-pot` - Generate translation .pot file

### Testing
- `npm run test` - Run Playwright visual regression tests (desktop only, level 1 pages)
- `npm run test:all` - Run VRC tests on desktop only (all pages, with bail-out after 20 failures)

## Architecture

### Component System
The theme uses a modular component architecture with 31 reusable components in `template-parts/components/`:
- Carousels (dual, multi-item, split, calendar)
- Content blocks (text blocks, split layouts, highlight panels)
- Data components (maps, points, document lists)
- Interactive elements (tabbed content, show/hide, step sliders, interactive image with light/dark themes)
- Specialized widgets (financial documents, news feeds, sustainability goals)

**All components utilize:**
- Safe helper functions (`zotefoams_get_sub_field_safe()`) for ACF field validation and type checking
- Consistent CSS class patterns for maintainability
- Helper classes for common functionality:
  - `Zotefoams_Image_Helper` - Image handling with fallbacks
  - `Zotefoams_Button_Helper` - Button and link rendering
  - `Zotefoams_Theme_Helper` - Theme utilities
  - `Zotefoams_Carousel_Helper` - Carousel initialization
- HTML preservation for rich text content where appropriate

### Custom Blocks
Three custom Gutenberg blocks in `blocks/`:
- `highlight-box` - Promotional content blocks
- `quote-box` - Styled quote containers  
- `related-links-box` - Link collection blocks

### ACF Integration
Extensive ACF field groups and custom post types configured in `acf/acf-json/`. The theme heavily relies on ACF for flexible content management across all components.

### Build System & Assets
**Build Pipeline:** Rollup + PostCSS with Autoprefixer and cssnano
- `npm run start` - Development with BrowserSync and watch mode
- JavaScript bundled to `js/bundle.js` (ES modules, ~13.5KB minified)
- CSS compiled from SASS to `style.css` with PostCSS optimizations

**SASS Structure** in `src/sass/`:
- `abstracts/` - Variables, mixins, functions
- `base/` - Base styles, typography, elements  
- `design/components/` - Component-specific styles matching PHP components
- `design/common/` - Shared UI patterns (navigation, footer, buttons)

### Custom Templates
Specialized page templates:
- `page-article.php` - Article layout with sibling pagination
- `page-biography.php` - Staff biography pages
- `page-our-history.php` - Company history timeline
- `single-knowledge-hub.php` - Knowledge base articles

### JavaScript 
ES module system in `src/`:
- `src/main.js` - Main entry point importing all modules
- `src/components/` - Component-specific modules (carousel, accordion, video modal, etc.)
- `src/utils/` - Reusable utilities (DOM, site, device detection)
- Output: `js/bundle.js` with source maps for debugging

### Development Environment
- Uses Local by Flywheel (proxy: `https://zotefoams-phase-2.local/`)
- BrowserSync for live reload during development
- Playwright for visual regression testing
- PHPCS with WordPress coding standards
- Debug system available via `?zf_debug=1` URL parameter (admin only)

### Modular Functions Structure
The theme follows modern WordPress practices with a modular functions.php structure:
- `inc/theme-setup.php` - Core theme setup, image sizes, menus, theme support
- `inc/assets.php` - CSS/JS enqueuing, Google Fonts, CDN resources
- `inc/blocks.php` - Gutenberg block registration and customizations
- `inc/search.php` - Search form customization and rewrite rules
- `inc/acf-config.php` - ACF JSON paths, field population, admin styles
- `inc/analytics.php` - Google Analytics, LinkedIn tracking integration
- `inc/query-modifications.php` - Custom query modifications (events, etc.)
- `inc/content-filters.php` - Content output filters (password forms, etc.)
- `inc/integrations.php` - Third-party integrations (Mailchimp, forms)
- `functions-original-backup.php` - Original 715-line functions.php backup

## Key File Locations
- Theme functions: `functions.php` and `inc/` directory (modularized)  
- Component templates: `template-parts/components/` (31 components)
- Custom blocks: `blocks/`
- SASS source: `src/sass/` (moved from `sass/`)
- JavaScript source: `src/` (ES modules)
- Compiled assets: `style.css`, `js/bundle.js` 
- ACF configuration: `acf/acf-json/`

## Navigation Menus
Four registered menu locations:
- `primary_menu` - Main site navigation
- `utility_menu` - Secondary utility links
- `quick_links_menu` - Quick access links  
- `legal_menu` - Footer legal links

## Current State

### Analytics Integration
- Google Analytics configured via ACF field `google_analytics_measurement_id`
- LinkedIn Insight Tag hardcoded with partner ID '1827026' (ACF field ready but not implemented)
- Mailchimp form validation scripts loaded for embedded forms

### Search Configuration
- Custom rewrite rules handle /search/ and /search/term/ URLs
- Provides fallback for non-JS utility menu search link

### Image Sizes
- `thumbnail-square`: 350x350 (cropped)
- `thumbnail-product`: 690x460 (cropped)
- `small`: 700x9999 (not cropped)

### Known Issues to Address
See DEVELOPMENT-TRACKER.md for current issues, pending optimizations, and change history.