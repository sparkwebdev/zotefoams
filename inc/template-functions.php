<?php

/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Zotefoams
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function zotefoams_body_classes($classes)
{
    // Adds a class of hfeed to non-singular pages.
    if (! is_singular()) {
        $classes[] = 'hfeed';
    }

    return $classes;
}
add_filter('body_class', 'zotefoams_body_classes');

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function zotefoams_pingback_header()
{
    if (is_singular() && pings_open()) {
        printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
    }
}
add_action('wp_head', 'zotefoams_pingback_header');


/**
 * Determine/map labels to category
 */
function zotefoams_map_cat_label($label)
{
    switch ($label) {
        case 'Case Studies':
            return 'View Case Study';
        case 'News':
        case 'Blog':
            return 'Read This Article';
        case 'Videos':
            return 'Watch Video';
        default:
            return 'View ' . rtrim($label, 's');
    }
}
add_action('wp_head', 'zotefoams_pingback_header');


/**
 * Get the ID of a page by its title.
 *
 * @param string $title The page title to search for.
 * @return int|null The page ID or null if not found.
 */
function zotefoams_get_page_id_by_title($title)
{
    global $wpdb;

    $page_id = $wpdb->get_var($wpdb->prepare(
        "SELECT ID FROM $wpdb->posts 
        WHERE post_type = 'page' 
        AND post_title COLLATE utf8mb4_general_ci = %s 
        LIMIT 1",
        $title
    ));

    return $page_id ?: null;
}

/**
 * Get the ID of the WordPress "Posts Page."
 *
 * Returns the assigned posts page ID or falls back to "News Centre" if not set.
 *
 * @return int The posts page ID.
 */
function zotefoams_get_page_for_posts_id()
{
    $page_for_posts = get_option('page_for_posts', true); // WordPress "Posts Page"
    $posts_page_id = !empty($page_for_posts) ? $page_for_posts : zotefoams_get_page_id_by_title('News Centre');
    return $posts_page_id;
}


/**
 * Check if a given URL is a file.
 *
 * @param string $link_url The URL to check.
 * @return bool True if the URL is a file, false otherwise.
 */
function zotefoams_is_file($link_url)
{
    return wp_check_filetype($link_url)['ext'] ? true : false;
}

/**
 * Get the first YouTube video URL from a WordPress post.
 *
 * @param int $post_id The ID of the post.
 * @return string|null The YouTube video URL or null if no video is found.
 */
function zotefoams_get_first_youtube_url($post_id)
{
    // Get the post content
    $post = get_post($post_id);

    if (! $post || empty($post->post_content)) {
        return null;
    }

    // Parse the blocks from the post content
    $blocks = parse_blocks($post->post_content);

    // Loop through the blocks to find the first YouTube embed block
    foreach ($blocks as $block) {
        if ($block['blockName'] === 'core/embed') {
            // Return the embed URL from the block attributes
            if (isset($block['attrs']) && isset($block['attrs']['url'])) {
                return $block['attrs']['url'];
            }
        }
    }

    // Return null if no YouTube embed block is found
    return null;
}


/**
 * Get the YouTube cover image URL from the first YouTube embed block.
 *
 * @param int $post_id The ID of the post.
 * @return string|null The URL of the YouTube cover image or null if no video is found.
 */
function zotefoams_youtube_cover_image($url)
{

    if ($url) {
        // Extract the video ID from the URL
        parse_str(parse_url($url, PHP_URL_QUERY), $query_vars);
        $video_id = $query_vars['v'] ?? null;

        // Return the YouTube cover image URL if the video ID exists
        if ($video_id) {
            return 'https://img.youtube.com/vi/' . $video_id . '/hqdefault.jpg';
        }
    }

    // Return null if no YouTube embed block or video ID is found
    return null;
}


// Set a flag to indicate the video overlay should be output.
function require_video_overlay()
{
    $GLOBALS['video_overlay_required'] = true;
}

// Output the video overlay in the footer if it was requested.
function insert_video_overlay()
{
    // Only output if the flag is set.
    if (empty($GLOBALS['video_overlay_required'])) {
        return;
    }
    // Guard to prevent duplicate output.
    static $overlay_inserted = false;
    if ($overlay_inserted) {
        return;
    }
    $overlay_inserted = true;
?>
    <!-- Video Overlay Structure -->
    <div id="video-overlay" style="display:none;">
        <div id="overlay-content">
            <button id="close-video">Close</button>
            <iframe id="video-iframe" width="100%" height="100%" frameborder="0" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen></iframe>
        </div>
    </div>
<?php
}
add_action('wp_footer', 'insert_video_overlay');
