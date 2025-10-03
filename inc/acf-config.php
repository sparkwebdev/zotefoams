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
 * This function hooks into ACF's `acf/load_field` filter to dynamically 
 * populate the choices for the field named "show_hide_forms_form" 
 * with available WPForms.
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
 * Populate ACF select field with brand pages from "Our brands" hierarchy.
 *
 * @param array $field The field array.
 * @return array Modified field array.
 */
function zotefoams_populate_acf_with_brands($field)
{
    // Clear existing choices
    $field['choices'] = [];

    // Get the page ID for 'Our brands' (case-insensitive)
    $brands_page_id = zotefoams_get_page_id_by_title('Our brands');

    if ($brands_page_id) {
        // Get child and grandchild pages
        $args = [
            'post_type'      => 'page',
            'post_parent'    => $brands_page_id,
            'posts_per_page' => -1,
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
        ];

        $child_pages = get_posts($args);

        if (!empty($child_pages)) {
            foreach ($child_pages as $page) {
                // Add child page
                $field['choices'][$page->ID] = $page->post_title;

                // Get grandchild pages
                $grandchild_args = [
                    'post_type'      => 'page',
                    'post_parent'    => $page->ID,
                    'posts_per_page' => -1,
                    'orderby'        => 'menu_order',
                    'order'          => 'ASC',
                ];

                $grandchild_pages = get_posts($grandchild_args);

                if (!empty($grandchild_pages)) {
                    foreach ($grandchild_pages as $grandchild) {
                        // Add grandchild page with indentation for clarity
                        $field['choices'][$grandchild->ID] = '— ' . $grandchild->post_title;
                    }
                }
            }
        }
    }

    return $field;
}
add_filter('acf/load_field/name=associated_brands', 'zotefoams_populate_acf_with_brands');

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