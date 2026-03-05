<?php
/**
 * Component Library — rewrite rules, access control, template routing.
 *
 * @package Zotefoams
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register the component-library rewrite rule and query var.
 */
function zotefoams_component_library_init()
{
    add_rewrite_rule('^component-library/?$', 'index.php?component_library=1', 'top');
}
add_action('init', 'zotefoams_component_library_init');

/**
 * Whitelist the query var.
 */
function zotefoams_component_library_query_vars($vars)
{
    $vars[] = 'component_library';
    return $vars;
}
add_filter('query_vars', 'zotefoams_component_library_query_vars');

/**
 * Load the component library template when the query var is set.
 */
function zotefoams_component_library_template($template)
{
    if (!get_query_var('component_library')) {
        return $template;
    }

    // Restrict to logged-in users who can edit posts
    if (!is_user_logged_in() || !current_user_can('edit_posts')) {
        wp_safe_redirect(wp_login_url(home_url('/component-library')));
        exit;
    }

    $custom = locate_template('page-component-library.php');
    return $custom ?: $template;
}
add_filter('template_include', 'zotefoams_component_library_template');
