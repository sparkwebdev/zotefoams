<?php
/**
 * Common Utility Functions
 *
 * Collection of reusable utility functions for component development.
 *
 * @package Zotefoams
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Render component title with optional styling.
 *
 * @param string $title Title text
 * @param array $args Title arguments
 * @return string
 */
function zotefoams_render_title($title, $args = [])
{
    if (empty($title)) {
        return '';
    }

    $defaults = [
        'tag'   => 'h3',
        'class' => 'fs-500 fw-600',
        'wrapper' => '',
    ];

    $args = wp_parse_args($args, $defaults);
    $title_html = sprintf(
        '<%1$s class="%2$s">%3$s</%1$s>',
        esc_attr($args['tag']),
        esc_attr($args['class']),
        esc_html($title)
    );

    if (!empty($args['wrapper'])) {
        $title_html = sprintf(
            '<%1$s>%2$s</%1$s>',
            esc_attr($args['wrapper']),
            $title_html
        );
    }

    return $title_html;
}

/**
 * Get pages by parent with caching.
 *
 * @param int $parent_id Parent page ID
 * @param array $args Query arguments
 * @return array
 */
function zotefoams_get_child_pages($parent_id, $args = [])
{
    $defaults = [
        'post_type'      => 'page',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ];

    $args = wp_parse_args($args, $defaults);
    $args['post_parent'] = $parent_id;

    // Use transient caching for performance
    $cache_key = 'zotefoams_child_pages_' . md5(serialize($args));
    $pages = get_transient($cache_key);

    if (false === $pages) {
        $pages = get_posts($args);
        set_transient($cache_key, $pages, HOUR_IN_SECONDS);
    }

    return $pages;
}

/**
 * Render excerpt with length control.
 *
 * @param int|string $post_id Post ID or content
 * @param int $length Excerpt length in words
 * @param string $more More text
 * @return string
 */
function zotefoams_get_excerpt($post_id = null, $length = 20, $more = '...')
{
    if (is_numeric($post_id)) {
        $content = get_the_excerpt($post_id) ?: get_post_field('post_content', $post_id);
    } else {
        $content = $post_id ?: get_the_excerpt();
    }

    if (empty($content)) {
        return '';
    }

    $content = wp_strip_all_tags($content);
    $words = explode(' ', $content);

    if (count($words) > $length) {
        $words = array_slice($words, 0, $length);
        $content = implode(' ', $words) . $more;
    }

    return esc_html($content);
}

/**
 * Check if content should be displayed (not empty after processing).
 *
 * @param mixed $content Content to check
 * @return bool
 */
function zotefoams_has_content($content)
{
    if (is_array($content)) {
        return !empty(array_filter($content));
    }

    return !empty(trim(wp_strip_all_tags($content)));
}

/**
 * Generate title strip HTML for components.
 *
 * @param string $title Strip title
 * @param array $button Button data (ACF link field)
 * @param array $args Additional arguments
 * @return string
 */
function zotefoams_render_title_strip($title, $button = null, $args = [])
{
    if (empty($title) && empty($button)) {
        return '';
    }

    $defaults = [
        'wrapper_class' => 'title-strip margin-b-30',
        'title_args'    => [],
        'button_args'   => ['style' => 'secondary'],
    ];

    $args = wp_parse_args($args, $defaults);

    $html = sprintf('<div class="%s">', esc_attr($args['wrapper_class']));

    if (!empty($title)) {
        $html .= zotefoams_render_title($title, $args['title_args']);
    }

    if (!empty($button)) {
        $html .= Zotefoams_Button_Helper::render($button, $args['button_args']);
    }

    $html .= '</div>';

    return $html;
}

/**
 * Get contextual placeholder image based on context.
 *
 * @param string $context Image context (thumbnail, square, large, etc.)
 * @return string
 */
function zotefoams_get_placeholder_image($context = 'large')
{
    $placeholders = [
        'thumbnail'        => '/images/placeholder-thumbnail.png',
        'thumbnail-square' => '/images/placeholder-thumbnail-square.png',
        'large'           => '/images/placeholder.png',
        'banner'          => '/images/placeholder.png',
    ];

    $placeholder = $placeholders[$context] ?? $placeholders['large'];
    return get_template_directory_uri() . $placeholder;
}

/**
 * Clean and prepare content for display.
 *
 * @param string $content Raw content
 * @param string $context Content context (excerpt, full, etc.)
 * @return string
 */
function zotefoams_prepare_content($content, $context = 'full')
{
    if (empty($content)) {
        return '';
    }

    switch ($context) {
        case 'excerpt':
            return wp_trim_words(wp_strip_all_tags($content), 20, '...');
        
        case 'title':
            return sanitize_text_field($content);
        
        case 'full':
            return wp_kses_post($content);
        
        default:
            return esc_html($content);
    }
}

/**
 * Build responsive image attributes.
 *
 * @param mixed $image Image data
 * @param string $size Primary size
 * @param array $args Additional arguments
 * @return array
 */
function zotefoams_get_responsive_image_attrs($image, $size = 'large', $args = [])
{
    $defaults = [
        'alt'     => '',
        'lazy'    => true,
        'class'   => '',
        'context' => 'large',
    ];

    $args = wp_parse_args($args, $defaults);

    return [
        'src'     => Zotefoams_Image_Helper::get_image_url($image, $size, $args['context']),
        'alt'     => Zotefoams_Image_Helper::get_image_alt($image, $args['alt']),
        'class'   => $args['class'],
        'loading' => $args['lazy'] ? 'lazy' : '',
        'srcset'  => Zotefoams_Image_Helper::get_image_srcset($image, $size),
    ];
}