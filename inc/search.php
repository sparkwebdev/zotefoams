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
 */
function zotefoams_custom_search_rewrite()
{
    // Add custom rewrite rules if needed
    // add_rewrite_rule('^search/([^/]*)/?', 'index.php?s=$matches[1]', 'top');
}
add_action('init', 'zotefoams_custom_search_rewrite');