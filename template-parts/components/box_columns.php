<?php
$title = get_sub_field('box_columns_title');
$button = get_sub_field('box_columns_button');
$behaviour = get_sub_field('box_columns_behaviour');
$page_id = get_sub_field('box_columns_parent_id');
$manual_items = get_sub_field('box_columns_items');
$posts_page_id = zotefoams_get_page_for_posts_id();
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
            $uncategorised_id = get_cat_ID('Uncategorised');
            $categories = get_categories(['parent' => 0, 'exclude' => $uncategorised_id]);

            foreach ($categories as $category):
                $cat_link = get_category_link($category->term_id);
                $image_id = get_field('category_image', 'category_' . $category->term_id);
                $image_url = wp_get_attachment_image_url($image_id, 'medium') ?: get_template_directory_uri() . '/images/placeholder-thumbnail.png';
        ?>
                <div class="box-item light-grey-bg">
                    <div class="box-content padding-40">
                        <p class="fs-400 fw-semibold margin-b-20"><?php echo esc_html($category->name); ?></p>
                        <?php if ($category->description): ?>
                            <div class="margin-b-20 grey-text"><?php echo esc_html($category->description); ?></div>
                        <?php endif; ?>
                        <a href="<?php echo esc_url($cat_link); ?>" class="hl arrow read-more">View <?php echo esc_html($category->name); ?></a>
                    </div>
                    <div class="box-image image-cover" style="background-image:url('<?php echo esc_url($image_url); ?>');"></div>
                </div>
            <?php endforeach;
        } elseif ($behaviour === 'pick') {
            $page_ids = get_sub_field('box_columns_page_ids');
            foreach ($page_ids as $pid):
                $thumb = get_the_post_thumbnail_url($pid, 'medium') ?: get_template_directory_uri() . '/images/placeholder-thumbnail.png';
            ?>
                <div class="box-item light-grey-bg">
                    <div class="box-content padding-40">
                        <p class="fs-400 fw-semibold margin-b-20"><?php echo esc_html(get_the_title($pid)); ?></p>
                        <?php if ($excerpt = get_the_excerpt($pid)): ?>
                            <div class="margin-b-20 grey-text"><?php echo esc_html($excerpt); ?></div>
                        <?php endif; ?>
                        <a href="<?php echo esc_url(get_permalink($pid)); ?>" class="hl arrow read-more">Read more</a>
                    </div>
                    <div class="box-image image-cover" style="background-image:url('<?php echo esc_url($thumb); ?>');"></div>
                </div>
            <?php endforeach;
        } elseif ($behaviour === 'children') {
            $child_pages = ($page_id == zotefoams_get_page_id_by_title('Knowledge Hub'))
                ? get_pages(['post_type' => 'knowledge-hub', 'parent' => 0, 'sort_column' => 'menu_order'])
                : get_pages(['parent' => $page_id, 'sort_column' => 'menu_order']);

            foreach ($child_pages as $child):
                $thumb = get_the_post_thumbnail_url($child->ID, 'medium') ?: get_template_directory_uri() . '/images/placeholder-thumbnail.png';
            ?>
                <div class="box-item light-grey-bg">
                    <div class="box-content padding-40">
                        <p class="fs-400 fw-semibold margin-b-20"><?php echo esc_html(get_the_title($child->ID)); ?></p>
                        <?php if ($excerpt = get_the_excerpt($child->ID)): ?>
                            <div class="margin-b-20 grey-text"><?php echo esc_html($excerpt); ?></div>
                        <?php endif; ?>
                        <a href="<?php echo esc_url(get_permalink($child->ID)); ?>" class="hl arrow read-more">Read more</a>
                    </div>
                    <div class="box-image image-cover" style="background-image:url('<?php echo esc_url($thumb); ?>');"></div>
                </div>
            <?php endforeach;
        } elseif ($behaviour === 'manual' && $manual_items) {
            foreach ($manual_items as $item):
                $img = $item['box_columns_item_image']['sizes']['thumbnail'] ?? get_template_directory_uri() . '/images/placeholder.png';
            ?>
                <div class="box-item light-grey-bg">
                    <div class="box-content padding-40">
                        <?php if ($item['box_columns_item_title']): ?>
                            <p class="fs-400 fw-semibold margin-b-20"><?php echo esc_html($item['box_columns_item_title']); ?></p>
                        <?php endif; ?>
                        <?php if ($item['box_columns_item_description']): ?>
                            <div class="margin-b-20 grey-text"><?php echo wp_kses_post($item['box_columns_item_description']); ?></div>
                        <?php endif; ?>
                        <?php if ($item['box_columns_item_button']): ?>
                            <a href="<?php echo esc_url($item['box_columns_item_button']['url']); ?>" class="hl arrow read-more" target="<?php echo esc_attr($item['box_columns_item_button']['target']); ?>">
                                <?php echo esc_html($item['box_columns_item_button']['title']); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="box-image image-cover" style="background-image:url('<?php echo esc_url($img); ?>');"></div>
                </div>
        <?php endforeach;
        }
        ?>
    </div>
</div>