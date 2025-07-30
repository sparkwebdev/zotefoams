<?php

/**
 * Allow excerpt for pages
 */
add_post_type_support('page', 'excerpt');

/**
 * Change "posts" to "News Centre" in the admin side menu
 * 
 * Customizes the WordPress admin menu to rebrand the default
 * "Posts" section as "News Centre" with appropriate icon and labels.
 * 
 * @return void
 */
function change_post_menu_label()
{
    global $menu;
    global $submenu;
    $menu[5][0] = 'News Centre';
    $menu[5][6] = 'dashicons-megaphone';
    $submenu['edit.php'][5][0] = 'News Centre';
    $submenu['edit.php'][10][0] = 'Add Item';
    $submenu['edit.php'][16][0] = 'Tags';
    echo '';
}
add_action('admin_menu', 'change_post_menu_label');

/**
 * Change post object labels to "News Centre"
 * 
 * Updates all WordPress post object labels to use "News Centre"
 * terminology instead of the default "Post" labels throughout
 * the admin interface.
 * 
 * @return void
 */
function change_post_object_label()
{
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
add_action('init', 'change_post_object_label');

/**
 * Disable Gutenberg for all post types except 'post'.
 */
function disable_gutenberg_except_posts($use_block_editor, $post)
{
    if (!$post) {
        return $use_block_editor; // Early return if no post object
    }

    // Allow Gutenberg for posts and 'knowledge-hub' custom post type
    if ($post->post_type === 'post' || ($post->post_type === 'page' && get_page_template_slug($post->ID) === 'page-article.php') || $post->post_type === 'knowledge-hub') {
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
function remove_classic_editor_support()
{
    $screen = get_current_screen();

    if (!$screen || $screen->post_type !== 'page') {
        return; // Ensure we are working within the page post type
    }

    $post_id = isset($_GET['post']) ? intval($_GET['post']) : 0;

    if ($post_id && get_page_template_slug($post_id) === 'page-biography.php' || $post_id && get_page_template_slug($post_id) === 'page-article.php') {
        return; // Do not remove editor for pages with the 'Biography' or 'Article' templates
    }

    remove_post_type_support('page', 'editor'); // Hide editor for all other pages
}
add_action('current_screen', 'remove_classic_editor_support');

function zoatfoams_allowed_block_types($allowed_blocks, $editor_context)
{
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
        if ($editor_context->post->post_type === 'post' || ($editor_context->post->post_type === 'page' && get_page_template_slug($editor_context->post->ID) === 'page-article.php')) {
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
                'core/columns',
                'core/group',
                'core/row',
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


/**
 * Assign unique IDs to flexible content layouts
 * 
 * Automatically generates and assigns unique IDs to ACF flexible content
 * layouts when a page is saved. This enables component-specific styling
 * and JavaScript targeting.
 * 
 * @param int $post_id The post ID being saved
 * @return void
 */
function assign_unique_ids_to_flexible_content($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (get_post_type($post_id) !== 'page') return;

    $field_name = 'page_content';

    if (!have_rows($field_name, $post_id)) return;

    $layouts = get_field($field_name, $post_id);

    foreach ($layouts as $index => $layout) {
        if (empty($layout['unique_id'])) {
            $unique_id_raw = uniqid('c_');
            $layout['unique_id'] = sanitize_html_class($unique_id_raw);

            update_sub_field(
                [$field_name, $index + 1, 'unique_id'],
                $layout['unique_id'],
                $post_id
            );
        }
    }
}
add_action('acf/save_post', 'assign_unique_ids_to_flexible_content', 20);


add_filter('acf/prepare_field/name=unique_id', function ($field) {
    // if (!current_user_can('administrator')) {
        return false;
    // }
    return $field;
});
// add_filter('acf/prepare_field/name=unique_id', function($field) {
//     $field['readonly'] = 1;
//     $field['disabled'] = 0; // Important: allow value to submit!
//     return $field;
// });
