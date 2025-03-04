<?php 
// Allow for passed variables, as well as ACF values
$title = get_sub_field('box_columns_title');
$button = get_sub_field('box_columns_button'); // ACF Link field
$behaviour = get_sub_field('box_columns_behaviour'); // Pick / Children / Manual
$page_id = get_sub_field('box_columns_parent_id'); // Selected parent page
$manual_items = get_sub_field('box_columns_items'); // Manual items
$posts_page_id = zotefoams_get_page_for_posts_id();

// Check if we need to pull categories instead of child pages
$use_categories = ($behaviour === 'children' && $page_id == $posts_page_id);

?>

<div class="box-columns cont-m padding-t-b-100 theme-none">
    
    <div class="title-strip margin-b-30">
        <?php if ($title): ?>
            <h3 class="fs-500 fw-600"><?php echo esc_html($title); ?></h3>
        <?php endif; ?>
        <?php if ($button): ?>
            <a href="<?php echo esc_url($button['url']); ?>" class="btn black outline" target="<?php echo esc_attr($button['target']); ?>">
                <?php echo esc_html($button['title']); ?>
            </a>
        <?php endif; ?>
    </div>

    <div class="box-items">
        <?php 
        if ($use_categories) {
            // Fetch all top-level categories
            $categories = get_categories([
                'parent' => 0, // Only top-level categories
                // 'hide_empty' => false, // Show empty categories
                // 'orderby' => 'name',
                // 'order' => 'ASC',
            ]);

            if ($categories): 
                foreach ($categories as $category): 
                    $category_link = get_category_link($category->term_id);
                    $cat_image = get_field('category_image', 'category_'.$category->term_id);
                    $thumbnail_url = wp_get_attachment_image_url( $cat_image, 'full' );
                    if (!$thumbnail_url) {
                        $thumbnail_url = get_template_directory_uri() . '/images/placeholder-thumbnail.png';
                    }
                    ?>
                    <div class="box-item light-grey-bg">
                        <div class="box-content padding-40">
                            <div>
                                <p class="fs-400 fw-semibold margin-b-20"><?php echo esc_html($category->name); ?></p>
                                <?php if (!empty($category->description)): ?>
                                    <div class="margin-b-20 grey-text"><?php echo esc_html($category->description); ?></div>
                                <?php endif; ?>
                            </div>
                            <a href="<?php echo esc_url($category_link); ?>" class="hl arrow">
                                View <?php echo esc_html($category->name); ?>
                            </a>
                        </div>
                        <div class="box-image image-cover" style="background-image:url('<?php echo $thumbnail_url; ?>');"></div>
                    </div>
                    <?php
                endforeach; 
            endif;

        } elseif ($behaviour === 'pick') {
            $page_ids = get_sub_field('box_columns_page_ids');
            if ($page_ids) {
                // Either loop directly or use a query. If your field returns IDs, you can do:
                foreach ($page_ids as $page_id) : 
                    $page_title = get_the_title($page_id);
                    $page_link = get_permalink($page_id);
                    $thumbnail_url = get_the_post_thumbnail_url( $page_id, 'full' );
                    if (!$thumbnail_url) {
                        $thumbnail_url = get_template_directory_uri() . '/images/placeholder-thumbnail.png';
                    }
                    ?>
                    <div class="box-item light-grey-bg">
                        <div class="box-content padding-40">
                            <div>
                                <p class="fs-400 fw-semibold margin-b-20"><?php echo esc_html($page_title); ?></p>
                                <?php 
                                if (get_the_excerpt($page_id)) {
                                    echo '<div class="margin-b-20 grey-text">';
                                    echo get_the_excerpt($child->ID);
                                    echo '</div>';
                                }
                                ?>
                            </div>
                            <a href="<?php echo esc_url($page_link); ?>" class="hl arrow">Read more</a>
                        </div>
                        <div class="box-image image-cover" style="background-image:url('<?php echo $thumbnail_url; ?>');"></div>
                    </div>
                    <?php endforeach; ?>
            <?php }

        } elseif ($behaviour === 'children') {
            // Fetch child pages
            $child_pages = get_pages([
                'parent' => $page_id,
                'sort_column' => 'menu_order',
                'sort_order' => 'ASC',
            ]);

            if ($child_pages):
                foreach ($child_pages as $child):
                    $child_id = $child->ID;
                    $child_title = get_the_title($child_id);
                    $child_link = get_permalink($child_id);
                    $thumbnail_url = get_the_post_thumbnail_url( $child_id, 'full' );
                    if (!$thumbnail_url) {
                        $thumbnail_url = get_template_directory_uri() . '/images/placeholder-thumbnail.png';
                    }
                    ?>
                    <div class="box-item light-grey-bg">
                        <div class="box-content padding-40">
                            <div>
                                <p class="fs-400 fw-semibold margin-b-20"><?php echo esc_html($child_title); ?></p>
                                <?php 
                                if (get_the_excerpt($child->ID)) {
                                    echo '<div class="margin-b-20 grey-text">';
                                    echo get_the_excerpt($child->ID);
                                    echo '</div>';
                                }
                                ?>
                            </div>
                            <a href="<?php echo esc_url($child_link); ?>" class="hl arrow">Read more</a>
                        </div>
                        <div class="box-image image-cover" style="background-image:url('<?php echo $thumbnail_url; ?>');"></div>
                    </div>
                    <?php
                endforeach;
            endif;

        } elseif ($behaviour === 'manual' && $manual_items) {
            // Display manually added items
            foreach ($manual_items as $item):
                $item_title = $item['box_columns_item_title'] ?? '';
                $item_description = $item['box_columns_item_description'] ?? '';
                $item_button = $item['box_columns_item_button'] ?? '';
                $item_image = $item['box_columns_item_image'] ?? null;
                
                // Extract 'large' size image URL with fallback
                $image_url = $item_image ? $item_image['sizes']['thumbnail'] : get_template_directory_uri() . '/images/placeholder.png';
                ?>
                <div class="box-item light-grey-bg">
                    <div class="box-content padding-40">
                        <div>
                            <?php if ($item_title): ?>
                                <p class="fs-400 fw-semibold margin-b-20"><?php echo esc_html($item_title); ?></p>
                            <?php endif; ?>
                            <?php if ($item_description): ?>
                                <div class="margin-b-20 grey-text"><?php echo wp_kses_post($item_description); ?></div>
                            <?php endif; ?>
                        </div>
                        <?php if ($item_button): ?>
                            <a href="<?php echo esc_url($item_button['url']); ?>" class="hl arrow" target="<?php echo esc_attr($item_button['target']); ?>">
                                <?php echo esc_html($item_button['title']); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="box-image image-cover" style="background-image:url('<?php echo esc_url($image_url); ?>');"></div>
                </div>
            <?php endforeach;
        }
        ?>
    </div>

</div>
