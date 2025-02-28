<?php
class Mega_Menu_Walker extends Walker_Nav_Menu {
    // Store each top-level mega menu panel markup keyed by the menu item ID.
    private $mega_menu_markup = array();
    private $top_level_title = '';
    private $top_level_description = '';
    private $top_level_url = '#';

    /**
     * Starts the element output.
     */
    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        if ( is_array( $args ) ) {
            $args = (object) $args;
        }
        $before      = isset( $args->before ) ? $args->before : '';
        $link_before = isset( $args->link_before ) ? $args->link_before : '';
        $link_after  = isset( $args->link_after ) ? $args->link_after : '';
        $after       = isset( $args->after ) ? $args->after : '';

        $classes = !empty( $item->classes ) ? (array) $item->classes : array();
        $classes[] = 'menu-item-' . $item->ID;
        if ( $depth > 0 ) {
            $classes[] = 'sub-item';
        }
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

        $output .= '<li' . $class_names . '>';

        // Build link attributes.
        $atts = array(
            'title'  => !empty( $item->attr_title ) ? sanitize_text_field( $item->attr_title ) : '',
            'target' => !empty( $item->target ) ? sanitize_text_field( $item->target ) : '',
            'rel'    => !empty( $item->xfn ) ? sanitize_text_field( $item->xfn ) : '',
            'href'   => !empty( $item->url ) ? esc_url( $item->url ) : '',
        );
        if ( !empty( $item->has_children ) && $depth === 0 ) {
            $atts['aria-controls'] = 'mega-menu-' . $item->ID;
            $atts['aria-expanded'] = 'false';
        }
        $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( !empty( $value ) ) {
                $value = ($attr === 'href') ? esc_url( $value ) : esc_attr( $value );
                $attributes .= " $attr=\"$value\"";
            }
        }

        if ( $depth === 0 ) {
            $this->top_level_title = !empty( $item->attr_title ) ? esc_html( $item->attr_title ) : esc_html( $item->title );
            $this->top_level_description = !empty( $item->description ) ? esc_html( $item->description ) : '';
            $this->top_level_url = !empty( $item->url ) ? esc_url_raw( $item->url ) : '#';
        }

        $item_output  = $before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $link_before . esc_html( $item->title ) . $link_after;
        $item_output .= '</a>';
        $item_output .= $after;

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }

    /**
     * Ends the element output.
     */
    public function end_el(&$output, $item, $depth = 0, $args = array()) {
        $output .= '</li>';
    }

    /**
     * Overrides display_element() to capture mega menu markup for top-level items.
     *
     * For top-level items with children, we capture the submenu output into a temporary variable,
     * output a mobile menu inline, and build the enriched mega menu markup which is stored for later output.
     */
    public function display_element($element, &$children_elements, $max_depth, $depth, $args, &$output) {
        if ( is_array( $args ) ) {
            $args = (object) $args;
        }
        $id_field = $this->db_fields['id'];
        if ( ! $element ) {
            return;
        }
        $element->has_children = !empty( $children_elements[ $element->$id_field ] );

        $this->start_el( $output, $element, $depth, $args );

        if ( $element->has_children && $depth === 0 ) {
            $temp_output = '';
            if ( isset( $children_elements[ $element->$id_field ] ) ) {
                foreach ( $children_elements[ $element->$id_field ] as $child ) {
                    $this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $temp_output );
                }
            }
            // Output the mobile menu inline.
            $output .= '<ul class="sub-menu sub-menu--mobile">' . $temp_output . '</ul>';

            // Build enriched mega menu markup.
            $menu_title = !empty( $element->attr_title ) ? esc_html( $element->attr_title ) : esc_html( $element->title );
            $menu_description = !empty( $element->description ) ? esc_html( $element->description ) : 'Explore our selection of products and services.';
            $menu_url = !empty( $element->url ) ? esc_url( $element->url ) : '#';

            $mega_menu_id = 'mega-menu-' . $element->$id_field;
            $mega_markup  = '<div class="mega-menu" id="' . esc_attr( $mega_menu_id ) . '" role="region" aria-labelledby="menu-item-' . esc_attr( $element->$id_field ) . '">';
            $mega_markup .= '<div class="mega-menu-wrapper">';
            $mega_markup .= '<div class="mega-menu-intro">';
            $mega_markup .= '<h2 class="fs-300 fw-regular">' . $menu_title . '</h2>';
            $mega_markup .= '<p class="grey-text">' . $menu_description . '</p>';
            $mega_markup .= '</div>';
            $mega_markup .= '<div class="mega-menu-content">';
            $mega_markup .= '<div class="mega-menu-section">';
            $mega_markup .= '<h3 class="fs-100"><a class="uppercase blue-text" href="' . esc_url( $menu_url ) . '">' . $menu_title . '</a></h3>';
            $mega_markup .= '<ul class="sub-menu">' . $temp_output . '</ul>';
            $mega_markup .= '</div></div></div></div>';

            $this->mega_menu_markup[ $element->$id_field ] = $mega_markup;
            unset( $children_elements[ $element->$id_field ] );
        } elseif ( $element->has_children ) {
            if ( isset( $children_elements[ $element->$id_field ] ) && ( $max_depth == 0 || $max_depth > $depth + 1 ) ) {
                $output .= '<ul class="sub-menu">';
                foreach ( $children_elements[ $element->$id_field ] as $child ) {
                    $this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $output );
                }
                $output .= '</ul>';
                unset( $children_elements[ $element->$id_field ] );
            }
        }

        $this->end_el( $output, $element, $depth, $args );
    }

    /**
     * Overrides walk() to output the complete navigation markup.
     *
     * IMPORTANT: Set wp_nav_menu()’s items_wrap parameter to '%3$s' so that the walker controls the full markup.
     *
     * The final output will be:
     *   <ul id="menu-primary" class="menu nav-menu">
     *     <!-- navigation items -->
     *   </ul>
     *   <div class="mega-menu-container">
     *     <!-- captured mega menu panels -->
     *   </div>
     */
    public function walk($elements, $max_depth, ...$args) {
        $items = parent::walk($elements, $max_depth, ...$args);
        $mega_output = '';
        if ( !empty( $this->mega_menu_markup ) ) {
            $mega_output .= '<div class="mega-menu-container">';
            foreach ( $this->mega_menu_markup as $markup ) {
                $mega_output .= $markup;
            }
            $mega_output .= '</div>';
        }
        return '<ul id="menu-primary" class="menu nav-menu">' . $items . '</ul>' . $mega_output;
    }
}
?>
