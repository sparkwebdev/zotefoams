<?php
/**
 * Zotefoams functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Zotefoams
 */

if (!defined('_S_VERSION')) {
    // Replace the version number of the theme on each release.
    define('_S_VERSION', '3.0.5');
}

/**
 * Load theme modules in order of dependency
 */

// Core theme setup and configuration
require get_template_directory() . '/inc/theme-setup.php';

// Asset management (CSS, JS, fonts)
require get_template_directory() . '/inc/assets.php';

// Block registration and Gutenberg integration
require get_template_directory() . '/inc/blocks.php';

// Search functionality and customizations
require get_template_directory() . '/inc/search.php';

// Advanced Custom Fields configuration
require get_template_directory() . '/inc/acf-config.php';

// ACF helper functions (must load early for components)
require get_template_directory() . '/inc/acf-helpers.php';

// Debug system for better developer experience
require get_template_directory() . '/inc/debug.php';

// Analytics integration (Google Analytics, LinkedIn, etc.)
require get_template_directory() . '/inc/analytics.php';

// Query modifications and custom post handling
require get_template_directory() . '/inc/query-modifications.php';

// Content filters and output modifications
require get_template_directory() . '/inc/content-filters.php';

// Third-party integrations (Mailchimp, forms, etc.)
require get_template_directory() . '/inc/integrations.php';

/**
 * Load helper classes and utilities
 */

// Template functions and utilities
require get_template_directory() . '/inc/template-functions.php';
require get_template_directory() . '/inc/template-tags.php';

// ACF integration and extensions
require get_template_directory() . '/inc/acf.php';

// Admin customizations
require get_template_directory() . '/inc/admin.php';
require get_template_directory() . '/inc/admin-editor.php';

// Navigation walker
require get_template_directory() . '/inc/mega-menu-walker.php';

// Helper classes
require get_template_directory() . '/inc/helpers/class-utility-functions.php';
require get_template_directory() . '/inc/helpers/class-theme-helper.php';
require get_template_directory() . '/inc/helpers/class-image-helper.php';
require get_template_directory() . '/inc/helpers/class-button-helper.php';
require get_template_directory() . '/inc/helpers/class-carousel-helper.php';
require get_template_directory() . '/inc/helpers/class-component-renderer.php';