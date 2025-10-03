<?php

class Zotefoams_Navigation_Helper
{
    /**
     * Get dynamic back navigation URL and text based on referrer and parent page
     *
     * @param int|null $post_id Optional post ID. Defaults to current post.
     * @return array|null Array with 'url' and 'text' keys, or null if no parent page
     */
    public static function get_dynamic_back_link($post_id = null)
    {
        if (!$post_id) {
            $post_id = get_the_ID();
        }

        $parent_id = wp_get_post_parent_id($post_id);
        if (!$parent_id) {
            return null;
        }

        $parent_title = get_the_title($parent_id);
        $parent_url = get_permalink($parent_id);
        $referrer = wp_get_referer();

        // Determine back URL and text based on referrer
        // Treat home page referrer as invalid (likely due to caching/server config)
        $home_urls = array(home_url('/'), home_url(), trailingslashit(home_url()));
        $is_home_referrer = $referrer && in_array(rtrim($referrer, '/'), array_map(function($url) { return rtrim($url, '/'); }, $home_urls));
        
        if ($referrer && !$is_home_referrer && (rtrim($referrer, '/') !== rtrim($parent_url, '/'))) {
            // User came from somewhere else (not home), link back to referrer
            $back_url = esc_url($referrer);
            $referrer_id = url_to_postid($referrer);
            if ($referrer_id) {
                $referrer_title = get_the_title($referrer_id);
                $back_text = sprintf(__('Back to %s', 'zotefoams'), $referrer_title);
            } else {
                $back_text = __('Back', 'zotefoams');
            }
        } else {
            // User came from parent, home page, or no referrer - link to parent
            $back_url = esc_url($parent_url);
            $back_text = sprintf(__('Back to %s', 'zotefoams'), $parent_title);
        }

        return array(
            'url' => $back_url,
            'text' => $back_text
        );
    }

    /**
     * Render dynamic back navigation link
     *
     * @param int|null $post_id Optional post ID. Defaults to current post.
     * @param string $css_class Optional CSS class for the link. Defaults to empty.
     * @return void
     */
    public static function render_dynamic_back_link($post_id = null, $css_class = '')
    {
        $nav_data = self::get_dynamic_back_link($post_id);
        if (!$nav_data) {
            return;
        }

        $class_attr = $css_class ? ' class="' . esc_attr($css_class) . '"' : '';
        echo '<a href="' . $nav_data['url'] . '"' . $class_attr . '>« ' . esc_html($nav_data['text']) . '</a>';
    }
}