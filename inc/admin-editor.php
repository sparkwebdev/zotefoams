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


/**
 * Disable Gutenberg for all post types except 'post'.
 */
function disable_gutenberg_except_posts($use_block_editor, $post) {
    if ($post->post_type !== 'post') {
        return false; // Disable for everything except posts
    }
    return $use_block_editor; // Enable for posts
}
add_filter('use_block_editor_for_post', 'disable_gutenberg_except_posts', 10, 2);


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
    } elseif ($editor_context->post->post_type === 'page') {
        return [
            'core/paragraph',
        ];
    }
}

return $allowed_blocks;
}
add_filter('allowed_block_types_all', 'zoatfoams_allowed_block_types', 10, 2);
