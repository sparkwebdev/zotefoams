<?php

/**
 * Function to change "posts" to "News Centre" in the admin side menu
 */
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

/**
 * Function to change post object labels to "News Centre"
 */
function change_post_object_label() {
  global $wp_post_types;
  $labels = &$wp_post_types['post']->labels;
  $labels->name = 'News Centre';
  $labels->singular_name = 'Post';
  $labels->add_new = 'Add News Centre Item';
  $labels->add_new_item = 'Add News Centre Item';
  $labels->edit_item = 'Edit Item';
  $labels->new_item = 'News Centre Item';
  $labels->view_item = 'View Item';
  $labels->search_items = 'Search News Centre';
  $labels->not_found = 'No items found';
  $labels->not_found_in_trash = 'No items items found in Trash';
}
add_action( 'init', 'change_post_object_label' );


/**
 * Disable Gutenberg editor for all post types except standard posts, 'knowledge-hub', and biography pages.
 */
function disable_gutenberg_except_posts($use_block_editor, $post) {
    if (!$post) {
        return $use_block_editor; // Early return if no post object
    }

    // Allow Gutenberg for posts and 'knowledge-hub' custom post type
    if ($post->post_type === 'post' || $post->post_type === 'knowledge-hub') {
        return $use_block_editor;
    }

    // Allow Gutenberg for pages with the template 'page-biography.php'
    if ($post->post_type === 'page') {
        $template = get_page_template_slug($post->ID);
        if ($template === 'page-biography.php') { // Ensure this matches the actual filename
            return $use_block_editor;
        }
    }

    // Disable Gutenberg for everything else
    return false;
}
add_filter('use_block_editor_for_post', 'disable_gutenberg_except_posts', 10, 2);

/**
 * Hide the Classic Editor on Pages while keeping ACF fields.
 */
function remove_classic_editor_support() {
    $screen = get_current_screen();
    
    if (!$screen || $screen->post_type !== 'page') {
        return; // Ensure we are working within the page post type
    }

    $post_id = isset($_GET['post']) ? intval($_GET['post']) : 0;
    
    if ($post_id && get_page_template_slug($post_id) === 'page-biography.php') {
        return; // Do not remove editor for pages with the 'page-biography' template
    }

    remove_post_type_support('page', 'editor'); // Hide editor for all other pages
}
add_action('current_screen', 'remove_classic_editor_support');






function zoatfoams_allowed_block_types($allowed_blocks, $editor_context) {
  // Available options below:
  // 'core/legacy-widget',
  // 'core/widget-group',
  // 'core/archives',
  // 'core/avatar',
  // 'core/block',
  // 'core/button',
  // 'core/calendar',
  // 'core/categories',
  // 'core/comment-author-name',
  // 'core/comment-content',
  // 'core/comment-date',
  // 'core/comment-edit-link',
  // 'core/comment-reply-link',
  // 'core/comment-template',
  // 'core/comments',
  // 'core/comments-pagination',
  // 'core/comments-pagination-next',
  // 'core/comments-pagination-numbers',
  // 'core/comments-pagination-previous',
  // 'core/comments-title',
  // 'core/cover',
  // 'core/file',
  // 'core/footnotes',
  // 'core/heading',
  // 'core/home-link',
  // 'core/image',
  // 'core/latest-comments',
  // 'core/latest-posts',
  // 'core/list',
  // 'core/loginout',
  // 'core/media-text',
  // 'core/navigation',
  // 'core/navigation-link',
  // 'core/navigation-submenu',
  // 'core/page-list',
  // 'core/page-list-item',
  // 'core/pattern',
  // 'core/post-author',
  // 'core/post-author-biography',
  // 'core/post-author-name',
  // 'core/post-comments-form',
  // 'core/post-content',
  // 'core/post-date',
  // 'core/post-excerpt',
  // 'core/post-featured-image',
  // 'core/post-navigation-link',
  // 'core/post-template',
  // 'core/post-terms',
  // 'core/post-title',
  // 'core/query',
  // 'core/query-no-results',
  // 'core/query-pagination',
  // 'core/query-pagination-next',
  // 'core/query-pagination-numbers',
  // 'core/query-pagination-previous',
  // 'core/query-title',
  // 'core/read-more',
  // 'core/rss',
  // 'core/search',
  // 'core/shortcode',
  // 'core/site-logo',
  // 'core/site-tagline',
  // 'core/site-title',
  // 'core/social-link',
  // 'core/tag-cloud',
  // 'core/template-part',
  // 'core/term-description',
  // 'core/audio',
  // 'core/buttons',
  // 'core/code',
  // 'core/column',
  // 'core/columns',
  // 'core/details',
  // 'core/embed',
  // 'core/freeform',
  // 'core/group',
  // 'core/html',
  // 'core/missing',
  // 'core/more',
  // 'core/nextpage',
  // 'core/paragraph',
  // 'core/preformatted',
  // 'core/pullquote',
  // 'core/quote',
  // 'core/separator',
  // 'core/social-links',
  // 'core/spacer',
  // 'core/table',
  // 'core/text-columns',
  // 'core/verse',
  // 'core/video',
  // 'safe-svg/svg-icon',
  // 'core/post-comments',
  // 'wpforms/form-selector',
  // 'acf/quote-box',
  // 'acf/highlight-box',
  // 'acf/related-links-box',
if (!empty($editor_context->post)) {
    if ($editor_context->post->post_type === 'post') {
        return [
          'core/heading',
          'core/paragraph',
          'core/list',
          'core/list-item',
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
			'core/gallery',
          'acf/quote-box',
          'acf/highlight-box',
          'acf/related-links-box',
        ];
    } else {
        return [
            'core/paragraph',
			'core/heading',
          	'core/list'
        ];
    }
}

return $allowed_blocks;
}
add_filter('allowed_block_types_all', 'zoatfoams_allowed_block_types', 10, 2);
