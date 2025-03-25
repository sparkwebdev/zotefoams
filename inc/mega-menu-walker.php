<?php
class Mega_Menu_Walker extends Walker_Nav_Menu {
    private $mega_menu_markup = array();

    /**
     * Starts the element output.
     */
    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $args = is_array($args) ? (object) $args : $args;
        
        // Build classes
        $classes = !empty($item->classes) ? (array) $item->classes : array();
        $classes[] = 'menu-item-' . $item->ID;
        if ($depth > 0) {
            $classes[] = 'sub-item';
        }
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        // Build link attributes
        $atts = array(
            'title'  => !empty($item->attr_title) ? sanitize_text_field($item->attr_title) : '',
            'target' => !empty($item->target) ? sanitize_text_field($item->target) : '',
            'rel'    => !empty($item->xfn) ? sanitize_text_field($item->xfn) : '',
            'href'   => !empty($item->url) ? esc_url($item->url) : '',
        );
        
        // Add ARIA controls for top-level items with children (for mega menus)
        if (!empty($item->has_children) && $depth === 0) {
            $atts['aria-controls'] = 'mega-menu-' . $item->ID;
            $atts['aria-expanded'] = 'false';
        }
        
        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);
        
        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value = ($attr === 'href') ? esc_url($value) : esc_attr($value);
                $attributes .= " $attr=\"$value\"";
            }
        }
        
        // Build item output
        $before = isset($args->before) ? $args->before : '';
        $link_before = isset($args->link_before) ? $args->link_before : '';
        $link_after = isset($args->link_after) ? $args->link_after : '';
        $after = isset($args->after) ? $args->after : '';
        
        $output .= '<li' . $class_names . '>';
        $output .= $before;
        // Output the link
        if ($item->url === '#' || empty($item->url)) {
            // Output as label if URL is '#' or empty
            $output .= '<span class="fs-100 menu-label uppercase grey-text">' . $link_before . esc_html($item->title) . $link_after . '</span>';
        } else {
            // Output as link
            $output .= '<a' . $attributes . '>' . $link_before . esc_html($item->title) . $link_after . '</a>';
        }

        // Output the toggle button for items with children up to 3 levels deep.
        if (!empty($item->has_children) && $depth < 3) {
            $output .= '<button class="dropdown-toggle" aria-label="Toggle submenu"></button>';
        }
        $output .= $after;
        
        $output .= apply_filters('walker_nav_menu_start_el', '', $item, $depth, $args);
    }

    /**
     * Ends the element output.
     */
    public function end_el(&$output, $item, $depth = 0, $args = array()) {
        $output .= '</li>';
    }

    /**
     * Handles display of elements and mega menu creation.
     */
    public function display_element($element, &$children_elements, $max_depth, $depth, $args, &$output) {
        $args = is_array($args) ? (object) $args : $args;
        $id_field = $this->db_fields['id'];
        
        if (!$element) return;
        
        $element->has_children = !empty($children_elements[$element->$id_field]);
        $this->start_el($output, $element, $depth, $args);

        if ($element->has_children && $depth === 0) {
            // Capture submenu output in temp variable
            $temp_output = '';
            if (isset($children_elements[$element->$id_field])) {
                foreach ($children_elements[$element->$id_field] as $child) {
                    $this->display_element($child, $children_elements, $max_depth, $depth + 1, $args, $temp_output);
                }
            }
            
            // Output mobile menu inline
            $output .= '<ul class="sub-menu sub-menu--mobile">' . $temp_output . '</ul>';
            
            // Create mega menu markup
            $menu_title = !empty($element->attr_title) ? esc_html($element->attr_title) : esc_html($element->title);
            $menu_description = !empty($element->description) ? esc_html($element->description) : 'Explore our selection of products and services.';
            $menu_url = !empty($element->url) ? esc_url($element->url) : '#';
            $mega_menu_id = 'mega-menu-' . $element->$id_field;
            
            $this->mega_menu_markup[$element->$id_field] = 
                '<div class="mega-menu" id="' . esc_attr($mega_menu_id) . '" role="region" aria-labelledby="menu-item-' . esc_attr($element->$id_field) . '">' .
                    '<div class="mega-menu-wrapper">' .
                        '<div class="mega-menu-intro">' .
                            '<h2 class="fs-300 fw-regular">' . $menu_title . '</h2>' .
                            '<p class="grey-text">' . $menu_description . '</p>' .
                        '</div>' .
                        '<div class="mega-menu-content">' .
                            '<div class="mega-menu-section">' .
                                '<h3 class="fs-100 uppercase blue-text">' . $menu_title . '</h3>' .
                                '<ul class="sub-menu">' . $temp_output . '</ul>' .
                            '</div>' .
                        '</div>' .
                    '</div>' .
                '</div>';
            
            unset($children_elements[$element->$id_field]);
        } elseif ($element->has_children) {
            if (isset($children_elements[$element->$id_field]) && ($max_depth == 0 || $max_depth > $depth + 1)) {
                $output .= '<ul class="sub-menu">';
                foreach ($children_elements[$element->$id_field] as $child) {
                    $this->display_element($child, $children_elements, $max_depth, $depth + 1, $args, $output);
                }
                $output .= '</ul>';
                unset($children_elements[$element->$id_field]);
            }
        }
        
        $this->end_el($output, $element, $depth, $args);
    }

    /**
     * Outputs the complete navigation markup.
     */
    public function walk($elements, $max_depth, ...$args) {
        $items = parent::walk($elements, $max_depth, ...$args);
        $mega_output = '';
        
        if (!empty($this->mega_menu_markup)) {
            $mega_output = '<div class="mega-menu-container">' . implode('', $this->mega_menu_markup) . '</div>';
        }
        
        return '<ul id="menu-primary" class="menu nav-menu">' . $items . '</ul>' . $mega_output;
    }
}
?>
