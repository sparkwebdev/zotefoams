<?php
// Get field data using safe helper functions
$title = zotefoams_get_sub_field_safe('box_columns_title', '', 'string');
$button = zotefoams_get_sub_field_safe('box_columns_button', [], 'url');
$behaviour = zotefoams_get_sub_field_safe('box_columns_behaviour', '', 'string');
$page_id = zotefoams_get_sub_field_safe('box_columns_parent_id', 0, 'int');
$manual_items = zotefoams_get_sub_field_safe('box_columns_items', [], 'array');
$posts_page_id = zotefoams_get_page_for_posts_id();
$use_categories = ($behaviour === 'children' && $page_id == $posts_page_id);

// Generate classes to match original structure exactly
$wrapper_classes = 'box-columns cont-m padding-t-b-100 theme-none';
?>

<div class="<?php echo esc_attr($wrapper_classes); ?>">
    <?php echo zotefoams_render_title_strip($title, $button); ?>

    <div class="box-columns__items">
        <?php
        if ($use_categories) {
            $uncategorised_id = get_cat_ID('Uncategorised');
            $categories = get_categories(['parent' => 0, 'exclude' => $uncategorised_id]);

            foreach ($categories as $category):
                $cat_link = get_category_link($category->term_id);
                $image_id = get_field('category_image', 'category_' . $category->term_id);
                $image_url = Zotefoams_Image_Helper::get_image_url($image_id, 'medium', 'thumbnail');
        ?>
                <div class="box-columns__item light-grey-bg">
                    <div class="box-columns__content padding-40">
                        <div>
                            <p class="fs-400 fw-semibold margin-b-20"><?php echo esc_html($category->name); ?></p>
                            <?php if ($category->description): ?>
                                <div class="margin-b-20 grey-text"><?php echo esc_html($category->description); ?></div>
                            <?php endif; ?>
                        </div>
                        <a href="<?php echo esc_url($cat_link); ?>" class="hl arrow read-more">View <?php echo esc_html($category->name); ?></a>
                    </div>
                    <div class="box-columns__image image-cover" style="background-image:url('<?php echo esc_url($image_url); ?>');"></div>
                </div>
            <?php endforeach;
        } elseif ($behaviour === 'pick') {
            $page_ids = zotefoams_get_sub_field_safe('box_columns_page_ids', [], 'array');
            foreach ($page_ids as $pid):
                $thumb = get_the_post_thumbnail_url($pid, 'medium') ?: get_template_directory_uri() . '/images/placeholder-thumbnail.png';
            ?>
                <div class="box-columns__item light-grey-bg">
                    <div class="box-columns__content padding-40">
                        <div>
                            <p class="fs-400 fw-semibold margin-b-20"><?php echo esc_html(get_the_title($pid)); ?></p>
                            <?php if ($excerpt = get_the_excerpt($pid)): ?>
                                <div class="margin-b-20 grey-text"><?php echo esc_html($excerpt); ?></div>
                            <?php endif; ?>
                        </div>
                        <a href="<?php echo esc_url(get_permalink($pid)); ?>" class="hl arrow read-more">Read more</a>
                    </div>
                    <div class="box-columns__image image-cover" style="background-image:url('<?php echo esc_url($thumb); ?>');"></div>
                </div>
            <?php endforeach;
        } elseif ($behaviour === 'children') {
            $child_pages = ($page_id == zotefoams_get_page_id_by_title('Knowledge Hub'))
                ? zotefoams_get_child_pages(0, ['post_type' => 'knowledge-hub'])
                : zotefoams_get_child_pages($page_id);

            foreach ($child_pages as $child):
                $thumb = get_the_post_thumbnail_url($child->ID, 'medium') ?: get_template_directory_uri() . '/images/placeholder-thumbnail.png';
            ?>
                <div class="box-columns__item light-grey-bg">
                    <div class="box-columns__content padding-40">
                        <div>
                            <p class="fs-400 fw-semibold margin-b-20"><?php echo esc_html(get_the_title($child->ID)); ?></p>
                            <?php if ($excerpt = get_the_excerpt($child->ID)): ?>
                                <div class="margin-b-20 grey-text"><?php echo esc_html($excerpt); ?></div>
                            <?php endif; ?>
                        </div>
                        <a href="<?php echo esc_url(get_permalink($child->ID)); ?>" class="hl arrow read-more">Read more</a>
                    </div>
                    <div class="box-columns__image image-cover" style="background-image:url('<?php echo esc_url($thumb); ?>');"></div>
                </div>
            <?php endforeach;
        } elseif ($behaviour === 'manual' && $manual_items) {
            foreach ($manual_items as $item):
                $img = $item['box_columns_item_image']['sizes']['medium'] ?? null;
            ?>
                <div class="box-columns__item light-grey-bg">
                    <div class="box-columns__content padding-40">
                        <div>
                            <?php if ($item['box_columns_item_title']): ?>
                                <p class="fs-400 fw-semibold margin-b-20"><?php echo esc_html($item['box_columns_item_title']); ?></p>
                            <?php endif; ?>
                            <?php if ($item['box_columns_item_description']): ?>
                                <div class="margin-b-20 grey-text"><?php echo wp_kses_post($item['box_columns_item_description']); ?></div>
                            <?php endif; ?>
                        </div>
                        <?php if ($item['box_columns_item_button']): ?>
                            <?php echo zotefoams_render_link($item['box_columns_item_button'], [
                                'class' => 'hl arrow read-more'
                            ]); ?>
                        <?php endif; ?>
                    </div>
                    <?php if ($img): ?>
                        <div class="box-columns__image image-cover" style="background-image:url('<?php echo esc_url($img); ?>');"></div>
                    <?php endif; ?>
                </div>
        <?php endforeach;
        }
        ?>
    </div>
</div>