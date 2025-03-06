<?php
// Toggle these flags to show/hide filters & columns.
$show_categories = true; // Should always be true.
$show_categories_filter = get_the_title($post->post_parent) == 'Technical Literature' ? false : true;
$show_categories_column = true;
$show_brands = true;
$show_brands_filter = get_the_title($post->post_parent) == 'Technical Literature' ? true : false;
$show_brands_column = false;
$has_multiple_filters = $show_categories_filter && $show_brands_filter;

// Retrieve the repeater field.
$documents_list = get_field( 'documents_list' );

if ( $documents_list ) {
    $documents_array = [];
    $categories = []; // key = category ID, value = category label.
    $brands = [];     // key = brand ID, value = brand title.
    
    foreach ( $documents_list as $row ) {
        // Ensure the 'file' field is valid before proceeding.
        if ( empty( $row['file'] ) || ! is_array( $row['file'] ) ) {
            continue; // Skip this iteration if file data is not available.
        }
        $file = $row['file'];

        // --- Category Processing (only if enabled) ---
        $category_id    = '';
        $category_label = '';
        $thumbnail_url  = '';
        if ( $show_categories ) {
            $category_id = intval( $row['category'] );
            $term = get_term( $category_id );
            if ( is_wp_error( $term ) || ! $term ) {
                $category_label = get_the_title();
                $thumbnail_url = get_template_directory_uri() . '/images/icon-01.svg';
            } else {
                $category_label = $term->name;
                $cat_image = get_field( 'category_image', 'category_' . $term->term_id );
                $thumbnail_url = $cat_image ? wp_get_attachment_image_url( $cat_image, 'small' ) : '';
                if ( ! $thumbnail_url ) {
                    $thumbnail_url = get_template_directory_uri() . '/images/icon-01.svg';
                }
            }
        }

        // --- Associated Brands Processing (only if enabled) ---
        $associated_brand_ids    = [];
        $associated_brand_labels = [];
        $all_brands = false;
        if ( $show_brands ) {
            // Check if "All Brands?" is set to true.
            $all_brands = isset( $row['all_brands'] ) && $row['all_brands'] ? true : false;
            
            if ( $all_brands ) {
                // Get the page ID of "Our Brands"
                $our_brands_page_id = zotefoams_get_page_id_by_title( 'Our Brands' );
                
                // Query all descendant pages (child, grandchild, etc.) of "Our Brands"
                $args = array(
                    'child_of'    => $our_brands_page_id,
                    'sort_column' => 'post_title',
                    'sort_order'  => 'ASC',
                    'post_type'   => 'page',
                );
                $child_pages = get_pages( $args );
                
                if ( ! empty( $child_pages ) ) {
                    foreach ( $child_pages as $child ) {
                        $brand_id = $child->ID;
                        $associated_brand_ids[] = $brand_id;
                        $brand_title = get_the_title( $brand_id );
                        $associated_brand_labels[] = $brand_title;
                        
                        // Build unique filter options.
                        if ( ! isset( $brands[ $brand_id ] ) ) {
                            $brands[ $brand_id ] = $brand_title;
                        }
                    }
                }
            } else {
                // Use the manually selected associated brands.
                $associated_brands_field = isset( $row['associated_brands'] ) ? $row['associated_brands'] : array();
                if ( ! is_array( $associated_brands_field ) ) {
                    $associated_brands_field = array( $associated_brands_field );
                }
                foreach ( $associated_brands_field as $brand_item ) {
                    $brand_id = intval( $brand_item );
                    if ( $brand_id ) {
                        $associated_brand_ids[] = $brand_id;
                        $brand_title = get_the_title( $brand_id );
                        $associated_brand_labels[] = $brand_title;
                        
                        // Build unique filter options.
                        if ( ! isset( $brands[ $brand_id ] ) ) {
                            $brands[ $brand_id ] = $brand_title;
                        }
                    }
                }
            }
        }
        
        // Append document data.
        $documents_array[] = (object) [
            'file'                    => $file,
            'category_id'             => $show_categories ? $category_id : '',
            'category_label'          => $show_categories ? $category_label : '',
            'category_image'          => $show_categories ? $thumbnail_url : '',
            'associated_brands'       => $show_brands ? $associated_brand_ids : array(),
            'associated_brands_label' => $show_brands ? $associated_brand_labels : array(),
            'all_brands'              => $show_brands ? $all_brands : false,
        ];
        
        // Build unique filter options.
        if ( $show_categories && $category_id && ! isset( $categories[ $category_id ] ) ) {
            $categories[ $category_id ] = $category_label;
        }
    }
}
?>

<div class="cont-m margin-b-100">
    <?php if ( ! empty( $documents_array ) ) : ?>
    <div data-component="file-list">
        <?php if ( ( $show_categories_filter && ! empty( $categories ) ) || ( $show_brands_filter && ! empty( $brands ) ) ) : ?>
            <div class="file-list__dropdown">
                <button id="filter-toggle" class="file-list__dropdown-button hl arrow">
                    Filter
                </button>
                <div id="filter-options" class="filter-toggle__options hidden">
                    <?php if ( ! empty( $categories ) ) : ?>
                    <div class="filter-group" data-filter-group="category" <?php echo $show_categories_filter ? '' : 'style="display:none;"'; ?>>
                        <?php if ( $has_multiple_filters ) : ?>
                        <strong>Category</strong>
                        <?php endif; ?>
                        <?php foreach ( $categories as $id => $label ) : ?>
                            <label class="filter-toggle__label">
                                <input 
                                    type="checkbox" 
                                    value="<?php echo esc_attr( $id ); ?>" 
                                    data-filter="category" 
                                    class="filter-options__checkbox"
                                >
                                <?php echo esc_html( $label ); ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    <?php if (! empty( $brands ) ) : ?>
                    <div class="filter-group" data-filter-group="brand" <?php echo $show_brands_filter ? '' : 'style="display:none;"'; ?>>
                        <?php if ( $has_multiple_filters ) : ?>
                        <strong>Brand</strong>
                        <?php endif; ?>
                        <?php foreach ( $brands as $id => $label ) : ?>
                            <label class="filter-toggle__label">
                                <input 
                                    type="checkbox" 
                                    value="<?php echo esc_attr( $id ); ?>" 
                                    data-filter="brand" 
                                    class="filter-options__checkbox"
                                >
                                <?php echo esc_html( $label ); ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <button id="file-list-show-all" class="file-list__show-all hidden">Reset Filters</button>
        <?php endif; ?>
        <div class="file-list">
            <table>
                <thead class="screen-reader-text">
                    <tr>
                        <?php if ( $show_categories_column ) : ?>
                        <th scope="col">Icon</th>
                        <th scope="col">Category</th>
                        <?php endif; ?>
                        <th scope="col">Title</th>
                        <?php if ( $show_brands_column ) : ?>
                        <th scope="col">Brand</th>
                        <?php endif; ?>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( $documents_array as $document ) : 
                        $file = $document->file;
                        $title = ! empty( $file['title'] ) ? $file['title'] : $file['filename'];
                        if ( $show_brands ) {
                            // If all_brands is true, display "All". Otherwise list the selected brands.
                            $brands_display = $document->all_brands ? 'All' : ( ! empty( $document->associated_brands_label ) ? implode( ', ', $document->associated_brands_label ) : '' );
                            $brand_data = ! empty( $document->associated_brands ) ? implode( ',', $document->associated_brands ) : '';
                        }
                    ?>
                        <tr 
                            class="file-list__item"
                            <?php if ( $show_categories ) : ?>
                                data-category="<?php echo esc_attr( $document->category_id ); ?>"
                            <?php endif; ?>
                            <?php if ( $show_brands ) : ?>
                                data-brand="<?php echo esc_attr( $brand_data ); ?>"
                            <?php endif; ?>
                            data-clickable-url="<?php echo esc_url( $file['url'] ); ?>"
                        >
                            <?php if ( $show_categories_column ) : ?>
                            <td class="file-list__item-icon">
                                <?php if ( ! empty( $document->category_image ) ) : ?>
                                    <img 
                                        src="<?php echo esc_url( $document->category_image ); ?>" 
                                        alt="<?php echo esc_attr( $document->category_label ); ?>" 
                                        class="icon"
                                    >
                                <?php endif; ?>
                            </td>
                            <td class="file-list__item-group">
                                <?php echo esc_html( $document->category_label ); ?>
                            </td>
                            <?php endif; ?>
                            <td class="file-list__item-title">
                                <?php echo esc_html( $title ); ?>
                            </td>
                            <?php if ( $show_brands_column ) : ?>
                            <td class="file-list__item-brands">
                                <?php echo esc_html( $brands_display ); ?>
                            </td>
                            <?php endif; ?>
                            <td class="file-list__item-action">
                                <a 
                                    href="<?php echo esc_url( $file['url'] ); ?>" 
                                    class="hl download" 
                                    target="_blank"
                                    aria-label="View <?php echo esc_attr( $title ); ?>"
                                >
                                    View
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php else : ?>
        <p><?php echo esc_html__( 'Sorry, no items currently available.', 'your-text-domain' ); ?></p>
    <?php endif; ?>
</div>
