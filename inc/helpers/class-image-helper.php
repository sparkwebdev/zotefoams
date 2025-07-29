<?php
/**
 * Image Helper Class
 *
 * Centralized image handling with fallback logic and optimization.
 *
 * @package Zotefoams
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Zotefoams_Image_Helper
{
    /**
     * Default fallback images by context.
     *
     * @var array
     */
    private static $fallback_images = [
        'thumbnail'        => '/images/placeholder-thumbnail.png',
        'thumbnail-square' => '/images/placeholder-thumbnail-square.png',
        'large'           => '/images/placeholder.png',
        'banner'          => '/images/placeholder.png',
    ];

    /**
     * Get image URL with fallback handling.
     *
     * @param mixed $image ACF image field or attachment ID
     * @param string $size WordPress image size
     * @param string $context Image context for fallback selection
     * @param bool $use_placeholder Whether to return placeholder images when no image exists
     * @return string|false
     */
    public static function get_image_url($image, $size = 'large', $context = 'large', $use_placeholder = true)
    {
        // Handle ACF image array
        if (is_array($image)) {
            return self::get_acf_image_url($image, $size, $context, $use_placeholder);
        }

        // Handle attachment ID
        if (is_numeric($image)) {
            return self::get_attachment_image_url($image, $size, $context, $use_placeholder);
        }

        // Handle post thumbnail
        if ($image === 'thumbnail' && is_singular()) {
            $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), $size);
            return $thumbnail_url ?: ($use_placeholder ? self::get_fallback_url($context) : false);
        }

        return $use_placeholder ? self::get_fallback_url($context) : false;
    }

    /**
     * Get image URL from ACF image array.
     *
     * @param array $image ACF image array
     * @param string $size Image size
     * @param string $context Fallback context
     * @param bool $use_placeholder Whether to return placeholder images
     * @return string|false
     */
    private static function get_acf_image_url($image, $size, $context, $use_placeholder = true)
    {
        // Check for specific size
        if (isset($image['sizes'][$size])) {
            return esc_url($image['sizes'][$size]);
        }

        // Fallback to full URL
        if (isset($image['url'])) {
            return esc_url($image['url']);
        }

        return $use_placeholder ? self::get_fallback_url($context) : false;
    }

    /**
     * Get image URL from attachment ID.
     *
     * @param int $attachment_id Attachment ID
     * @param string $size Image size
     * @param string $context Fallback context
     * @param bool $use_placeholder Whether to return placeholder images
     * @return string|false
     */
    private static function get_attachment_image_url($attachment_id, $size, $context, $use_placeholder = true)
    {
        $image_url = wp_get_attachment_image_url($attachment_id, $size);
        return $image_url ?: ($use_placeholder ? self::get_fallback_url($context) : false);
    }

    /**
     * Get fallback image URL.
     *
     * @param string $context Image context
     * @return string
     */
    private static function get_fallback_url($context)
    {
        $fallback = self::$fallback_images[$context] ?? self::$fallback_images['large'];
        return get_template_directory_uri() . $fallback;
    }

    /**
     * Get image alt text with fallback.
     *
     * @param mixed $image ACF image field or attachment ID
     * @param string $fallback Fallback alt text
     * @return string
     */
    public static function get_image_alt($image, $fallback = '')
    {
        // Handle ACF image array
        if (is_array($image) && isset($image['alt'])) {
            return esc_attr($image['alt'] ?: $fallback);
        }

        // Handle attachment ID
        if (is_numeric($image)) {
            $alt = get_post_meta($image, '_wp_attachment_image_alt', true);
            return esc_attr($alt ?: $fallback);
        }

        return esc_attr($fallback);
    }

    /**
     * Render complete image HTML with responsive handling.
     *
     * @param mixed $image Image data
     * @param array $args Image arguments
     * @return string
     */
    public static function render_image($image, $args = [])
    {
        $defaults = [
            'size'    => 'large',
            'context' => 'large',
            'alt'     => '',
            'class'   => '',
            'lazy'    => true,
            'width'   => '',
            'height'  => '',
        ];

        $args = wp_parse_args($args, $defaults);

        $src = self::get_image_url($image, $args['size'], $args['context']);
        $alt = self::get_image_alt($image, $args['alt']);
        $class = $args['class'] ? ' class="' . esc_attr($args['class']) . '"' : '';
        $lazy = $args['lazy'] ? ' loading="lazy"' : '';
        $width = $args['width'] ? ' width="' . esc_attr($args['width']) . '"' : '';
        $height = $args['height'] ? ' height="' . esc_attr($args['height']) . '"' : '';

        return sprintf(
            '<img src="%s" alt="%s"%s%s%s%s />',
            $src,
            $alt,
            $class,
            $lazy,
            $width,
            $height
        );
    }

    /**
     * Get responsive image srcset.
     *
     * @param mixed $image Image data
     * @param string $size Base image size
     * @return string
     */
    public static function get_image_srcset($image, $size = 'large')
    {
        if (is_array($image) && isset($image['ID'])) {
            return wp_get_attachment_image_srcset($image['ID'], $size);
        }

        if (is_numeric($image)) {
            return wp_get_attachment_image_srcset($image, $size);
        }

        return '';
    }

    /**
     * Check if URL is a file (for download links).
     *
     * @param string $url URL to check
     * @return bool
     */
    public static function is_file_url($url)
    {
        return (bool) wp_check_filetype($url)['ext'];
    }

    /**
     * Get file icon based on extension.
     *
     * @param string $url File URL
     * @return string Icon URL
     */
    public static function get_file_icon($url)
    {
        $file_type = wp_check_filetype($url);
        $extension = $file_type['ext'] ?? '';

        $icon_map = [
            'pdf'  => '/images/icon-pdf.svg',
            'doc'  => '/images/icon-doc.svg',
            'docx' => '/images/icon-doc.svg',
            'xls'  => '/images/icon-xls.svg',
            'xlsx' => '/images/icon-xls.svg',
        ];

        $icon = $icon_map[$extension] ?? '/images/icon-download.svg';
        return get_template_directory_uri() . $icon;
    }
}