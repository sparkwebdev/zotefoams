<?php
/**
 * Base Component Renderer Class
 *
 * Abstract base class for rendering theme components with common functionality.
 *
 * @package Zotefoams
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

abstract class Zotefoams_Component_Renderer
{
    /**
     * Component data from ACF fields.
     *
     * @var array
     */
    protected $data = [];

    /**
     * Component configuration options.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Constructor.
     *
     * @param array $config Component configuration
     */
    public function __construct($config = [])
    {
        $this->config = wp_parse_args($config, $this->get_default_config());
        $this->init_data();
    }

    /**
     * Get default configuration for the component.
     *
     * @return array
     */
    protected function get_default_config()
    {
        return [
            'wrapper_class' => '',
            'theme_style'   => 'theme-none',
            'padding'       => 'padding-t-b-100',
            'container'     => 'cont-m',
        ];
    }

    /**
     * Initialize component data from ACF fields.
     * Must be implemented by child classes.
     *
     * @return void
     */
    abstract protected function init_data();

    /**
     * Render the component.
     * Must be implemented by child classes.
     *
     * @return void
     */
    abstract public function render();

    /**
     * Get component wrapper classes.
     *
     * @return string
     */
    protected function get_wrapper_classes()
    {
        $classes = [
            $this->config['wrapper_class'],
            $this->config['theme_style'],
            $this->config['padding'],
        ];

        return esc_attr(implode(' ', array_filter($classes)));
    }

    /**
     * Get container class.
     *
     * @return string
     */
    protected function get_container_class()
    {
        return esc_attr($this->config['container']);
    }

    /**
     * Safely get field value with fallback.
     *
     * @param string $field_name Field name
     * @param mixed $default Default value
     * @param string $type Expected data type
     * @return mixed
     */
    protected function get_field($field_name, $default = '', $type = 'string')
    {
        return zotefoams_get_sub_field_safe($field_name, $default, $type);
    }

    /**
     * Get image URL with fallback.
     *
     * @param array|null $image ACF image array
     * @param string $size Image size
     * @param string $fallback Fallback image path
     * @return string
     */
    protected function get_image_url($image, $size = 'large', $fallback = '')
    {
        if (empty($image)) {
            return $fallback ?: get_template_directory_uri() . '/images/placeholder.png';
        }

        if (is_array($image) && isset($image['sizes'][$size])) {
            return esc_url($image['sizes'][$size]);
        }

        if (is_array($image) && isset($image['url'])) {
            return esc_url($image['url']);
        }

        return $fallback ?: get_template_directory_uri() . '/images/placeholder.png';
    }

    /**
     * Render button HTML.
     *
     * @param array $button ACF link field array
     * @param string $class Additional CSS classes
     * @param string $default_text Default button text
     * @return string
     */
    protected function render_button($button, $class = 'btn black outline', $default_text = 'Read more')
    {
        if (empty($button) || empty($button['url'])) {
            return '';
        }

        $url = esc_url($button['url']);
        $title = esc_html($button['title'] ?: $default_text);
        $target = !empty($button['target']) ? ' target="' . esc_attr($button['target']) . '"' : '';
        $class = esc_attr($class);

        return sprintf(
            '<a href="%s" class="%s"%s>%s</a>',
            $url,
            $class,
            $target,
            $title
        );
    }

    /**
     * Check if current page matches specific criteria.
     *
     * @param string $type Check type ('parent', 'template', 'slug')
     * @param mixed $value Value to check against
     * @return bool
     */
    protected function is_page_context($type, $value)
    {
        switch ($type) {
            case 'parent':
                $parent_id = wp_get_post_parent_id(get_the_ID());
                if (is_numeric($value)) {
                    return $parent_id == $value;
                }
                return $parent_id && get_the_title($parent_id) === $value;

            case 'template':
                return is_page_template($value);

            case 'slug':
                return is_page($value);

            default:
                return false;
        }
    }

    /**
     * Output component with wrapper.
     *
     * @param string $content Component content
     * @return void
     */
    protected function output_with_wrapper($content)
    {
        printf(
            '<div class="%s"><div class="%s">%s</div></div>',
            $this->get_wrapper_classes(),
            $this->get_container_class(),
            $content
        );
    }
}