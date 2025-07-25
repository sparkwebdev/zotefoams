<?php
/**
 * Theme Helper Class
 *
 * Centralized theme styling and CSS class management.
 *
 * @package Zotefoams
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Zotefoams_Theme_Helper
{
    /**
     * Theme style presets.
     *
     * @var array
     */
    private static $theme_styles = [
        'light' => [
            'wrapper' => 'light-grey-bg theme-light',
            'text'    => 'black-text',
            'button'  => 'black',
            'arrow'   => 'black',
        ],
        'dark' => [
            'wrapper' => 'black-bg white-text theme-dark',
            'text'    => 'white-text',  
            'button'  => 'white',
            'arrow'   => 'white',
        ],
        'none' => [
            'wrapper' => 'theme-none',
            'text'    => '',
            'button'  => 'black',
            'arrow'   => 'black',
        ],
        'blue' => [
            'wrapper' => 'blue-bg white-text theme-blue',
            'text'    => 'white-text',
            'button'  => 'white',
            'arrow'   => 'white',
        ],
    ];

    /**
     * Spacing utility classes.
     *
     * @var array
     */
    private static $spacing_classes = [
        'padding-t-b-100' => 'padding-t-b-100',
        'padding-t-b-70'  => 'padding-t-b-70',
        'padding-t-b-50'  => 'padding-t-b-50',
        'padding-100'     => 'padding-100',
        'padding-50'      => 'padding-50',
        'padding-30'      => 'padding-30',
        'margin-b-30'     => 'margin-b-30',
        'margin-b-20'     => 'margin-b-20',
        'margin-t-30'     => 'margin-t-30',
    ];

    /**
     * Get theme styles based on page context.
     *
     * @param string $default_theme Default theme style
     * @return array
     */
    public static function get_contextual_theme($default_theme = 'none')
    {
        // Check for Markets/Industries pages
        $markets_page_id = zotefoams_get_page_id_by_title('Markets') ?: zotefoams_get_page_id_by_title('Industries');
        
        if ($markets_page_id) {
            $current_id = get_the_ID();
            $parent_id = wp_get_post_parent_id($current_id);
            
            if ($current_id === $markets_page_id || $parent_id === $markets_page_id) {
                return self::$theme_styles['light'];
            }
        }

        return self::$theme_styles[$default_theme] ?? self::$theme_styles['none'];
    }

    /**
     * Get theme style by name.
     *
     * @param string $theme Theme name
     * @return array
     */
    public static function get_theme_style($theme)
    {
        return self::$theme_styles[$theme] ?? self::$theme_styles['none'];
    }

    /**
     * Generate wrapper classes for components.
     *
     * @param array $args Component arguments
     * @return string
     */
    public static function get_wrapper_classes($args = [])
    {
        $defaults = [
            'component' => '',
            'theme'     => 'none',
            'spacing'   => 'padding-t-b-100',
            'container' => 'cont-m',
            'extra'     => '',
        ];

        $args = wp_parse_args($args, $defaults);
        
        // Get theme styles
        $theme_style = self::get_theme_style($args['theme']);
        
        $classes = array_filter([
            $args['component'],
            $theme_style['wrapper'],
            $args['spacing'],
            $args['extra'],
        ]);

        return esc_attr(implode(' ', $classes));
    }

    /**
     * Get button style based on theme.
     *
     * @param string $theme Theme name
     * @param string $style Button base style
     * @return string
     */
    public static function get_button_style($theme, $style = 'outline')
    {
        $theme_data = self::get_theme_style($theme);
        $button_color = $theme_data['button'];
        
        return "btn {$button_color} {$style}";
    }

    /**
     * Get arrow color based on theme.
     *
     * @param string $theme Theme name
     * @return string
     */
    public static function get_arrow_color($theme)
    {
        $theme_data = self::get_theme_style($theme);
        return $theme_data['arrow'];
    }

    /**
     * Get text color class based on theme.
     *
     * @param string $theme Theme name
     * @return string
     */
    public static function get_text_color($theme)
    {
        $theme_data = self::get_theme_style($theme);
        return $theme_data['text'];
    }

    /**
     * Build carousel navigation classes.
     *
     * @param string $color Arrow color (black, white, blue)
     * @return array
     */
    public static function get_carousel_nav_classes($color = 'black')
    {
        return [
            'wrapper' => "carousel-navigation {$color}",
            'prev'    => 'swiper-button-prev',
            'next'    => 'swiper-button-next',
        ];
    }

    /**
     * Get responsive classes for different screen sizes.
     *
     * @param array $breakpoints Breakpoint configuration
     * @return string
     */
    public static function get_responsive_classes($breakpoints = [])
    {
        $classes = [];
        
        foreach ($breakpoints as $size => $class) {
            switch ($size) {
                case 'mobile':
                    $classes[] = "mobile-{$class}";
                    break;
                case 'tablet':
                    $classes[] = "tablet-{$class}";
                    break;
                case 'desktop':
                    $classes[] = "desktop-{$class}";
                    break;
                default:
                    $classes[] = $class;
            }
        }
        
        return implode(' ', $classes);
    }

    /**
     * Generate animation classes.
     *
     * @param string $animation Animation type
     * @param string $delay Animation delay
     * @return string
     */
    public static function get_animation_classes($animation = '', $delay = '')
    {
        if (empty($animation)) {
            return '';
        }

        $classes = ['animate__animated'];
        
        if (strpos($animation, 'animate__') !== 0) {
            $animation = 'animate__' . $animation;
        }
        
        $classes[] = $animation;
        
        if (!empty($delay)) {
            if (strpos($delay, 'animate__delay-') !== 0) {
                $delay = 'animate__delay-' . $delay;
            }
            $classes[] = $delay;
        }
        
        return implode(' ', $classes);
    }

    /**
     * Check if current page should use specific theme.
     *
     * @param string $theme Theme to check for
     * @return bool
     */
    public static function should_use_theme($theme)
    {
        switch ($theme) {
            case 'light':
                return self::is_markets_context();
            case 'dark':
                return self::is_video_context();
            default:
                return false;
        }
    }

    /**
     * Check if current page is in Markets context.
     *
     * @return bool
     */
    private static function is_markets_context()
    {
        $markets_page_id = zotefoams_get_page_id_by_title('Markets') ?: zotefoams_get_page_id_by_title('Industries');
        
        if (!$markets_page_id) {
            return false;
        }
        
        $current_id = get_the_ID();
        $parent_id = wp_get_post_parent_id($current_id);
        
        return $current_id === $markets_page_id || $parent_id === $markets_page_id;
    }

    /**
     * Check if current page is in video context.
     *
     * @return bool
     */
    private static function is_video_context()
    {
        // Add logic for video context detection
        return has_category('videos') || is_category('videos');
    }
}