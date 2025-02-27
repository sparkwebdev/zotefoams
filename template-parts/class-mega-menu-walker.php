<?php
class Mega_Menu_Walker extends Walker_Nav_Menu {
    private $current_item_title = '';
    private $current_item_description = '';

    /**
     * Start the element output.
     */
    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
    
        if ($depth > 0) {
            $classes[] = 'sub-item';
        }
    
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
    
        $output .= '<li' . $class_names . '>';
    
        $atts = array();
        $atts['title']  = !empty($item->attr_title) ? $item->attr_title : ''; // Title Attribute
        $atts['target'] = !empty($item->target) ? $item->target : '';
        $atts['rel']    = !empty($item->xfn) ? $item->xfn : '';
        $atts['href']   = !empty($item->url) ? $item->url : '';
    
        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);
    
        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        // **Store Top-Level Menu Item Title & URL**
        if ($depth == 0) {
            $this->top_level_title = !empty($item->attr_title) ? esc_html($item->attr_title) : esc_html($item->title);
            $this->top_level_description = !empty($item->description) ? esc_html($item->description) : '';
            $this->top_level_url = !empty($item->url) ? esc_url($item->url) : '#';  // Store URL for linking

            // Try to get excerpt from the linked page if no menu description is found
            if (empty($this->top_level_description)) {
                $post_id = url_to_postid($item->url);
                if ($post_id && get_post_status($post_id) == 'publish') {
                    $post = get_post($post_id);
                    if ($post) {
                        $this->top_level_description = !empty($post->post_excerpt) ? esc_html($post->post_excerpt) : '';
                    }
                }
            }
    
            // Fallback description if neither menu description nor page excerpt exists
            if (empty($this->top_level_description)) {
                $this->top_level_description = 'Explore our selection of products and services.';
            }
        }
    
        $item_output = $args->before;
        $item_output .= '<a'. $attributes .'>';
        $item_output .= $args->link_before . esc_html($item->title) . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;
    
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
    


    /**
     * Starts the list before the elements are added.
     */
    public function start_lvl(&$output, $depth = 0, $args = array()) {
        if ($depth === 0) {
            // Ensure title, URL, and description are pulled from the top-level item
            $menu_title = isset($this->top_level_title) ? $this->top_level_title : 'Menu';
            $menu_description = isset($this->top_level_description) ? $this->top_level_description : 'Discover more below.';
            $menu_url = isset($this->top_level_url) ? $this->top_level_url : '#';

            // Start mega menu container for top-level items
            $output .= '<div class="mega-menu"><div class="mega-menu-wrapper">';

            // **Dynamic intro section (always from top-level)**
            $output .= '<div class="mega-menu-intro">';
            $output .= '<h2 class="fs-300 fw-regular">' . esc_html($menu_title) . '</h2>';
            $output .= '<p class="grey-text">' . esc_html($menu_description) . '</p>';
            $output .= '</div>';

            // Start content section
            $output .= '<div class="mega-menu-content">';
            $output .= '<div class="mega-menu-section">';
            
            $output .= '<h3 class="fs-100">';
            $output .= '<a class=" uppercase blue-text" href="' . esc_url($menu_url) . '">' . esc_html($menu_title) . '</a>';
            $output .= '</h3>';

            $output .= '<ul class="sub-menu">';
        } else {
            $output .= '<ul class="sub-menu">';
        }
    }

    
    public function end_lvl(&$output, $depth = 0, $args = array()) {
        $output .= '</ul>';
        if ($depth === 0) {
            $output .= '</div></div></div>'; // Close .mega-menu-content, .mega-menu-wrapper, .mega-menu
        }
    }
    
}
?>
