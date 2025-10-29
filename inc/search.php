<?php
/**
 * Search Functionality and Customizations
 *
 * @package Zotefoams
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Customize the search form HTML structure.
 *
 * @param string $form The search form HTML.
 * @return string Modified search form HTML.
 */
function zotefoams_filter_search_form($form)
{
    $form = str_replace('class="search-submit"', 'class="search-submit btn blue"', $form);
    $form = str_replace('class="search-field"', 'class="search-field zf"', $form);
    return $form;
}
add_filter('get_search_form', 'zotefoams_filter_search_form');

/**
 * Add custom rewrite rules for search functionality.
 * 
 * Creates a /search/ endpoint that redirects to the search page.
 * This handles the fallback when JavaScript is disabled and the 
 * utility menu search link (/search/) is clicked directly.
 */
function zotefoams_custom_search_rewrite()
{
    // Handle /search/ URL to show empty search results page
    add_rewrite_rule('^search/?$', 'index.php?s=', 'top');
    // Handle /search/term/ URLs for direct search links
    add_rewrite_rule('^search/([^/]+)/?$', 'index.php?s=$matches[1]', 'top');
}
add_action('init', 'zotefoams_custom_search_rewrite');

// Load enhanced search results functionality
require get_template_directory() . '/inc/search-results.php';