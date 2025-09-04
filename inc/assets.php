<?php
/**
 * Asset Management - CSS and JavaScript enqueuing
 *
 * @package Zotefoams
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Enqueue theme assets (styles and scripts).
 */
function zotefoams_enqueue_assets()
{
    // Enqueue main theme styles
    wp_enqueue_style('zotefoams-style', get_stylesheet_uri(), array(), _S_VERSION);
    wp_style_add_data('zotefoams-style', 'rtl', 'replace');

    // Enqueue critical JavaScript bundle in head as BLOCKING for immediate functionality
    wp_enqueue_script('zotefoams-critical', get_template_directory_uri() . '/js/critical.js', array(), _S_VERSION, false);

    // Load Swiper CSS and JS from CDN first
    wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css');
    wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), null, true);

    // Enqueue jQuery and main theme bundle (ES module build)
    wp_enqueue_script('jquery');
    wp_enqueue_script('zotefoams-bundle', get_template_directory_uri() . '/js/bundle.js', array(), _S_VERSION, true);

    // Enqueue comment reply script on single posts/pages with comments open
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    // Global AJAX handler for theme
    wp_localize_script('zotefoams-bundle', 'zotefoams_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('zotefoams_nonce'),
    ));


    // Load Animate.css for animations
    wp_enqueue_style('animate-css', 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css', array(), '4.1.1');

    // Load Google Fonts with preload optimization
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap', array(), null);
    
    // Load EB Garamond for our-history page (overwrites handle exactly like original)
    if (is_page_template('page-our-history.php')) {
        wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@1,400;1,600&display=swap', array(), null);
    }
}
add_action('wp_enqueue_scripts', 'zotefoams_enqueue_assets');

/**
 * Add preload attribute to Google Fonts for performance.
 *
 * @param string $html The link tag HTML.
 * @param string $handle The handle of the stylesheet.
 * @param string $href The href attribute.
 * @param string $media The media attribute.
 * @return string Modified HTML with preload attribute.
 */
function add_preload_to_google_fonts($html, $handle, $href, $media)
{
    if ('google-fonts' === $handle) {
        $html  = '<link rel="preload" as="style" href="' . esc_url($href) . '" />';
        $html .= "\n" . '<link rel="stylesheet" id="' . esc_attr($handle) . '-css" href="' . esc_url($href) . '" media="' . esc_attr($media) . '">';
    }
    return $html;
}
add_filter('style_loader_tag', 'add_preload_to_google_fonts', 10, 4);