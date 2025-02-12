<?php
// Allow excerpt for pages
add_post_type_support( 'page', 'excerpt' );

// Function to change "posts" to "News Centre" in the admin side menu
function change_post_menu_label() {
  global $menu;
  global $submenu;
  $menu[5][0] = 'News Centre';
  $menu[5][6] = 'dashicons-megaphone';
  $submenu['edit.php'][5][0] = 'News Centre';
  $submenu['edit.php'][10][0] = 'Add Item';
  $submenu['edit.php'][16][0] = 'Tags';
  echo '';
}
add_action( 'admin_menu', 'change_post_menu_label' );
// Function to change post object labels to "news centre"
function change_post_object_label() {
  global $wp_post_types;
  $labels = &$wp_post_types['post']->labels;
  $labels->name = 'News Centre';
  $labels->singular_name = 'News Centre Item';
  $labels->add_new = 'Add News Centre Item';
  $labels->add_new_item = 'Add News Centre Item';
  $labels->edit_item = 'Edit News Centre Item';
  $labels->new_item = 'News Centre Item';
  $labels->view_item = 'View News Centre Item';
  $labels->search_items = 'Search News Centre';
  $labels->not_found = 'No News Centre item found';
  $labels->not_found_in_trash = 'No News Centre items found in Trash';
}
add_action( 'init', 'change_post_object_label' );


function zoatfoams_allowed_block_types($allowed_blocks, $editor_context) {
if (!empty($editor_context->post)) {
    if ($editor_context->post->post_type === 'post') {
        return [
          'core/heading',
          'core/paragraph',
          'core/list',
          'core/table',
          // 'core/pullquote',
          'core/image',
          'core/video',
          // 'core/gallery',
          'core/file',
          'core/audio',
          // 'core/columns',
          // 'core/group',
          // 'core/row',
          'core/shortcode',
          'core/html',
          'core/embed',
          'acf/quote-box',
          'acf/highlight-box',
          'acf/related-links-box',
        ];
    } elseif ($editor_context->post->post_type === 'page') {
        return [
            'core/paragraph',
        ];
    }
}

return $allowed_blocks;
}
add_filter('allowed_block_types_all', 'zoatfoams_allowed_block_types', 10, 2);
