<?php
/**
 * Carousel Helper Class
 *
 * Centralized carousel functionality and navigation rendering.
 *
 * @package Zotefoams
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Zotefoams_Carousel_Helper
{
    /**
     * Render standard carousel navigation.
     *
     * @param array $args Navigation arguments
     * @return string
     */
    public static function render_navigation($args = [])
    {
        $defaults = [
            'color'      => 'black',
            'prev_class' => 'swiper-button-prev',
            'next_class' => 'swiper-button-next',
            'wrapper'    => true,
        ];

        $args = wp_parse_args($args, $defaults);

        $prev_icon = self::get_arrow_icon('left', $args['color']);
        $next_icon = self::get_arrow_icon('right', $args['color']);

        $nav_html = sprintf(
            '<div class="%s">%s</div><div class="%s">%s</div>',
            esc_attr($args['prev_class']),
            $prev_icon,
            esc_attr($args['next_class']),
            $next_icon
        );

        if ($args['wrapper']) {
            $wrapper_class = "carousel-navigation {$args['color']}";
            $nav_html = sprintf(
                '<div class="%s"><div class="carousel-navigation-inner">%s</div></div>',
                esc_attr($wrapper_class),
                $nav_html
            );
        }

        return $nav_html;
    }

    /**
     * Get arrow icon HTML.
     *
     * @param string $direction left or right
     * @param string $color black, white, or blue
     * @return string
     */
    private static function get_arrow_icon($direction, $color)
    {
        $icon_map = [
            'left-black'  => '/images/left-arrow-black.svg',
            'right-black' => '/images/right-arrow-black.svg',
            'left-white'  => '/images/left-arrow-white.svg',
            'right-white' => '/images/right-arrow-white.svg',
            'left-blue'   => '/images/icon-left-arrow-blue.svg',
            'right-blue'  => '/images/icon-right-arrow-blue.svg',
        ];

        $icon_key = "{$direction}-{$color}";
        $icon_path = $icon_map[$icon_key] ?? $icon_map["right-black"];
        $icon_url = get_template_directory_uri() . $icon_path;

        return sprintf(
            '<img src="%s" alt="%s arrow" />',
            esc_url($icon_url),
            esc_attr(ucfirst($direction))
        );
    }

    /**
     * Generate Swiper configuration object.
     *
     * @param array $args Configuration arguments
     * @return array
     */
    public static function get_swiper_config($args = [])
    {
        $defaults = [
            'slides_per_view' => 1,
            'space_between'   => 30,
            'loop'           => true,
            'autoplay'       => false,
            'speed'          => 500,
            'breakpoints'    => [],
            'navigation'     => true,
            'pagination'     => false,
        ];

        return wp_parse_args($args, $defaults);
    }

    /**
     * Render carousel slide content wrapper.
     *
     * @param string $content Slide content
     * @param array $args Slide arguments
     * @return string
     */
    public static function render_slide($content, $args = [])
    {
        $defaults = [
            'class'     => 'swiper-slide',
            'theme'     => '',
            'animation' => '',
        ];

        $args = wp_parse_args($args, $defaults);

        $classes = [$args['class']];
        
        if (!empty($args['theme'])) {
            $theme_data = Zotefoams_Theme_Helper::get_theme_style($args['theme']);
            $classes[] = $theme_data['wrapper'];
        }

        if (!empty($args['animation'])) {
            $classes[] = Zotefoams_Theme_Helper::get_animation_classes($args['animation']);
        }

        return sprintf(
            '<div class="%s">%s</div>',
            esc_attr(implode(' ', array_filter($classes))),
            $content
        );
    }

    /**
     * Render carousel title strip with navigation.
     *
     * @param string $title Carousel title
     * @param array $nav_args Navigation arguments
     * @return string
     */
    public static function render_title_strip($title, $nav_args = [])
    {
        if (empty($title) && empty($nav_args)) {
            return '';
        }

        $html = '<div class="title-strip margin-b-30">';
        
        if (!empty($title)) {
            $html .= sprintf('<h3 class="fs-500 fw-600">%s</h3>', esc_html($title));
        }

        if (!empty($nav_args)) {
            $html .= self::render_navigation($nav_args);
        }

        $html .= '</div>';

        return $html;
    }

    /**
     * Get carousel wrapper classes based on type.
     *
     * @param string $type Carousel type
     * @param array $args Additional arguments
     * @return string
     */
    public static function get_carousel_classes($type, $args = [])
    {
        $base_classes = [
            'dual'       => 'swiper-dual-carousel',
            'multi'      => 'multi-item-carousel',
            'split'      => 'split-carousel',
            'calendar'   => 'calendar-carousel',
        ];

        $classes = [$base_classes[$type] ?? 'swiper'];

        // Add variant classes
        if (!empty($args['variant'])) {
            $classes[] = "{$base_classes[$type]}--{$args['variant']}";
        }

        // Add theme classes
        if (!empty($args['theme'])) {
            $theme_data = Zotefoams_Theme_Helper::get_theme_style($args['theme']);
            $classes[] = $theme_data['wrapper'];
        }

        // Add alignment
        if (!empty($args['align'])) {
            $classes[] = "text-{$args['align']}";
        }

        return esc_attr(implode(' ', array_filter($classes)));
    }

    /**
     * Check if carousel should use specific theme based on context.
     *
     * @return string
     */
    public static function get_contextual_theme()
    {
        if (Zotefoams_Theme_Helper::should_use_theme('light')) {
            return 'light';
        }

        if (Zotefoams_Theme_Helper::should_use_theme('dark')) {
            return 'dark';
        }

        return 'none';
    }
}