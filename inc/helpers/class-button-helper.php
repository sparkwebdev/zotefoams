<?php
/**
 * Button Helper Class
 *
 * Centralized button rendering with consistent styling and functionality.
 *
 * @package Zotefoams
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Zotefoams_Button_Helper
{
    /**
     * Button style presets.
     *
     * @var array
     */
    private static $button_styles = [
        'primary'         => 'btn blue',
        'secondary'       => 'btn black outline',
        'white'          => 'btn white outline',
        'download'       => 'btn black outline download',
        'arrow'          => 'hl arrow',
        'read-more'      => 'hl arrow read-more',
    ];

    /**
     * Render button from ACF link field.
     *
     * @param array|null $button ACF link field array
     * @param array $args Button arguments
     * @return string
     */
    public static function render($button, $args = [])
    {
        if (empty($button) || empty($button['url'])) {
            return '';
        }

        $defaults = [
            'style'        => 'secondary',
            'class'        => '',
            'text'         => '',
            'icon'         => '',
            'wrapper'      => '',
            'detect_file'  => true,
        ];

        $args = wp_parse_args($args, $defaults);

        // Get button text
        $text = $args['text'] ?: $button['title'] ?: 'Read more';

        // Auto-detect file downloads
        if ($args['detect_file'] && Zotefoams_Image_Helper::is_file_url($button['url'])) {
            $args['style'] = 'download';
            $args['icon'] = $args['icon'] ?: 'download';
            $text = $text === 'Read more' ? 'Download' : $text;
        }

        // Build CSS classes
        $css_classes = self::get_button_classes($args);

        // Build attributes
        $url = esc_url($button['url']);
        $target = !empty($button['target']) ? ' target="' . esc_attr($button['target']) . '"' : '';
        $rel = self::get_rel_attribute($button);

        // Render icon if specified
        $icon_html = self::render_icon($args['icon']);

        // Build button HTML
        $button_html = sprintf(
            '<a href="%s" class="%s"%s%s>%s%s</a>',
            $url,
            esc_attr($css_classes),
            $target,
            $rel,
            $icon_html,
            esc_html($text)
        );

        // Wrap if wrapper specified
        if (!empty($args['wrapper'])) {
            $button_html = sprintf(
                '<%1$s>%2$s</%1$s>',
                esc_attr($args['wrapper']),
                $button_html
            );
        }

        return $button_html;
    }

    /**
     * Render multiple buttons.
     *
     * @param array $buttons Array of button data
     * @param array $args Global arguments for all buttons
     * @return string
     */
    public static function render_multiple($buttons, $args = [])
    {
        if (empty($buttons) || !is_array($buttons)) {
            return '';
        }

        $html = '';
        foreach ($buttons as $button) {
            $html .= self::render($button, $args);
        }

        return $html;
    }

    /**
     * Get button CSS classes.
     *
     * @param array $args Button arguments
     * @return string
     */
    private static function get_button_classes($args)
    {
        $classes = [];

        // Add style preset
        if (isset(self::$button_styles[$args['style']])) {
            $classes[] = self::$button_styles[$args['style']];
        } else {
            $classes[] = $args['style']; // Use custom style directly
        }

        // Add additional classes
        if (!empty($args['class'])) {
            $classes[] = $args['class'];
        }

        return implode(' ', array_filter($classes));
    }

    /**
     * Get rel attribute for external links.
     *
     * @param array $button Button data
     * @return string
     */
    private static function get_rel_attribute($button)
    {
        $url = $button['url'] ?? '';
        $target = $button['target'] ?? '';

        // Add rel="noopener noreferrer" for external links with target="_blank"
        if ($target === '_blank' && self::is_external_url($url)) {
            return ' rel="noopener noreferrer"';
        }

        return '';
    }

    /**
     * Check if URL is external.
     *
     * @param string $url URL to check
     * @return bool
     */
    private static function is_external_url($url)
    {
        $site_url = home_url();
        return strpos($url, $site_url) !== 0 && filter_var($url, FILTER_VALIDATE_URL);
    }

    /**
     * Render button icon.
     *
     * @param string $icon Icon type or URL
     * @return string
     */
    private static function render_icon($icon)
    {
        if (empty($icon)) {
            return '';
        }

        // Handle built-in icons
        $icon_map = [
            'download'    => '/images/icon-download.svg',
            'arrow-right' => '/images/icon-right-arrow-blue.svg',
            'arrow-left'  => '/images/icon-left-arrow-blue.svg',
            'external'    => '/images/icon-external.svg',
        ];

        if (isset($icon_map[$icon])) {
            $icon_url = get_template_directory_uri() . $icon_map[$icon];
            return sprintf(
                '<img src="%s" alt="" class="btn-icon" /> ',
                esc_url($icon_url)
            );
        }

        // Handle custom icon URL
        if (filter_var($icon, FILTER_VALIDATE_URL)) {
            return sprintf(
                '<img src="%s" alt="" class="btn-icon" /> ',
                esc_url($icon)
            );
        }

        return '';
    }

    /**
     * Create button data array (for programmatic button creation).
     *
     * @param string $url Button URL
     * @param string $title Button text
     * @param string $target Button target (_blank, _self, etc.)
     * @return array
     */
    public static function create_button_data($url, $title, $target = '_self')
    {
        return [
            'url'    => $url,
            'title'  => $title,
            'target' => $target,
        ];
    }

    /**
     * Render CTA section with multiple buttons.
     *
     * @param array $buttons Array of button data
     * @param array $args Section arguments
     * @return string
     */
    public static function render_cta_section($buttons, $args = [])
    {
        if (empty($buttons)) {
            return '';
        }

        $defaults = [
            'wrapper_class' => 'cta-buttons',
            'title'         => '',
            'description'   => '',
        ];

        $args = wp_parse_args($args, $defaults);

        $html = sprintf('<div class="%s">', esc_attr($args['wrapper_class']));

        if (!empty($args['title'])) {
            $html .= sprintf('<h3>%s</h3>', esc_html($args['title']));
        }

        if (!empty($args['description'])) {
            $html .= sprintf('<p>%s</p>', wp_kses_post($args['description']));
        }

        $html .= self::render_multiple($buttons, $args);
        $html .= '</div>';

        return $html;
    }
}