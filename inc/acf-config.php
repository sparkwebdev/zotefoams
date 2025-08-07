<?php
/**
 * Advanced Custom Fields Configuration
 *
 * @package Zotefoams
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Set custom save point for ACF JSON files.
 *
 * @param string $path The current save path.
 * @return string Modified save path.
 */
function zotefoams_acf_json_save_point($path)
{
    return get_stylesheet_directory() . '/acf/acf-json';
}
add_filter('acf/settings/save_json', 'zotefoams_acf_json_save_point');

/**
 * Set custom load point for ACF JSON files.
 *
 * @param array $paths Array of paths to load from.
 * @return array Modified paths array.
 */
function zotefoams_acf_json_load_point($paths)
{
    unset($paths[0]); // Remove original path
    $paths[] = get_stylesheet_directory() . '/acf/acf-json';
    return $paths;
}
add_filter('acf/settings/load_json', 'zotefoams_acf_json_load_point');

/**
 * Populate ACF select field with WPForms.
 *
 * @param array $field The field array.
 * @return array Modified field array.
 */
function zotefoams_populate_acf_with_wpforms($field)
{
    // Reset choices
    $field['choices'] = array();

    // Check if WPForms is active
    if (!class_exists('WPForms')) {
        return $field;
    }

    // Get all forms
    $forms = wpforms()->form->get('', array('orderby' => 'title'));

    if (!empty($forms)) {
        $field['choices'][''] = 'Select a form...';
        foreach ($forms as $form) {
            $field['choices'][$form->ID] = $form->post_title;
        }
    } else {
        $field['choices'][''] = 'No forms found';
    }

    return $field;
}
add_filter('acf/load_field/name=show_hide_forms_form', 'zotefoams_populate_acf_with_wpforms');

/**
 * Populate ACF select field with brand taxonomy terms.
 *
 * @param array $field The field array.
 * @return array Modified field array.
 */
function zotefoams_populate_acf_with_brands($field)
{
    // Reset choices
    $field['choices'] = array();

    // Get brand terms
    $brands = get_terms(array(
        'taxonomy' => 'brands',
        'hide_empty' => false,
    ));

    if (!empty($brands) && !is_wp_error($brands)) {
        foreach ($brands as $brand) {
            $field['choices'][$brand->term_id] = $brand->name;
        }
    }

    return $field;
}
add_filter('acf/load_field/name=associated_brands', 'zotefoams_populate_acf_with_brands');

/**
 * Add custom admin column styles for Knowledge Hub post type.
 */
function zotefoams_add_knowledge_hub_admin_column_styles()
{
    $screen = get_current_screen();
    
    if ($screen && ($screen->post_type === 'knowledge-hub' || $screen->id === 'edit-knowledge-hub')) {
        echo '<style>
            .wp-list-table .column-taxonomy-knowledge_hub_category { width: 15%; }
            .wp-list-table .column-taxonomy-knowledge_hub_product { width: 15%; }
            .wp-list-table .column-taxonomy-knowledge_hub_market { width: 15%; }
            .wp-list-table .column-date { width: 10%; }
            .wp-list-table .column-title { width: 35%; }
            
            /* Knowledge Hub category colors */
            .knowledge-hub-category-article { background-color: #e3f2fd; padding: 2px 6px; border-radius: 3px; }
            .knowledge-hub-category-case-study { background-color: #f3e5f5; padding: 2px 6px; border-radius: 3px; }
            .knowledge-hub-category-technical-data { background-color: #e8f5e8; padding: 2px 6px; border-radius: 3px; }
        </style>';
    }
}
add_action('admin_head', 'zotefoams_add_knowledge_hub_admin_column_styles');

/**
 * Protect Knowledge Hub ACF gallery field from unintended interactions.
 * 
 * Prevents deletion and reordering of document attachments in the
 * Knowledge Hub ACF gallery field to maintain document integrity.
 */
function zotefoams_add_knowledge_hub_admin_inline_styles()
{
    $screen = get_current_screen();
    
    if (isset($screen->post_type) && $screen->post_type === 'knowledge-hub') {
        ?>
        <style>
            #acf-field_67c58a842cccb .acf-gallery-attachments .acf-gallery-attachment {
                pointer-events: none;
            }

            #acf-field_67c58a842cccb .actions .acf-gallery-remove,
            #acf-field_67c58a842cccb .acf-gallery-sort {
                display: none;
            }
        </style>
        <?php
    }
}
add_action('admin_head', 'zotefoams_add_knowledge_hub_admin_inline_styles');

/**
 * Enqueue admin scripts and styles for Interactive Image component.
 */
function zotefoams_enqueue_interactive_image_admin_assets()
{
    $screen = get_current_screen();
    
    // Only load on post edit screens where ACF is present
    if ($screen && in_array($screen->post_type, array('page', 'post')) && $screen->base === 'post') {
        // Enqueue admin CSS
        wp_enqueue_style(
            'zotefoams-admin-interactive-image',
            get_template_directory_uri() . '/css/admin-interactive-image.css',
            array('acf-input'),
            '1.5.1'
        );
        
        // Enqueue admin JavaScript
        wp_enqueue_script(
            'zotefoams-admin-interactive-image',
            get_template_directory_uri() . '/js/admin-interactive-image.js',
            array('jquery', 'acf-input'),
            '1.5.1',
            true
        );
    }
}
add_action('admin_enqueue_scripts', 'zotefoams_enqueue_interactive_image_admin_assets');