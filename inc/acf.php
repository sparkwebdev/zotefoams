<?php 

// Enable ACF local JSON feature
add_filter('acf/settings/save_json', 'zotefoams_acf_json_save_point');
function zotefoams_acf_json_save_point($path) {
    return plugin_dir_path(__FILE__) . 'acf/acf-json';
}

add_filter('acf/settings/load_json', 'zotefoams_acf_json_load_point');
function zotefoams_acf_json_load_point($paths) {
    unset($paths[0]);
    $paths[] = plugin_dir_path(__FILE__) . 'acf/acf-json';
    return $paths;
}

/**
 * Populate the ACF field with a list of WPForms.
 *
 * This function hooks into ACF's `acf/load_field` filter to dynamically 
 * populate the choices for the field named "show_hide_forms_form" 
 * with available WPForms.
 *
 * @link https://www.advancedcustomfields.com/resources/acf-load_field/
 */
add_filter('acf/load_field/name=show_hide_forms_form', 'zotefoams_populate_acf_with_wpforms');
function zotefoams_populate_acf_with_wpforms($field) {
    // Clear existing choices
    $field['choices'] = [];

    // Get the list of WPForms
    if (class_exists('WPForms')) {
        $forms = wpforms()->form->get();
        if ($forms) {
            foreach ($forms as $form) {
                $form_data = wpforms()->form->get($form->ID); // Get full form data
                $form_name = $form_data->post_title; // Get the form's title
                $field['choices'][$form->ID] = $form_name;
            }
        }
    }

    return $field;
}


/**
 * Populate the ACF 'associated_brands' field with a list of Brands Child pages.
 *
 * @link https://www.advancedcustomfields.com/resources/acf-load_field/
 */

 add_filter('acf/load_field/name=associated_brands', 'zotefoams_populate_acf_with_brands');
 function zotefoams_populate_acf_with_brands($field) {
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