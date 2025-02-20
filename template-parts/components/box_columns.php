<?php 
// Allow for passed variables, as well as ACF values
$title = get_sub_field('box_columns_title');
$button = get_sub_field('box_columns_button'); // ACF Link field
$behaviour = get_sub_field('box_columns_behaviour');
$page_ids = get_sub_field('box_columns_page_ids');
$parent_id = get_sub_field('box_columns_parent_id');
$items = get_sub_field('box_columns_items');

// Determine items based on behaviour
if ($behaviour === 'pick' && !empty($page_ids)) {
    $args = [
        'post_type'   => 'page',
        'post_status' => 'publish',
        'orderby'     => 'post__in',
        'post__in'    => $page_ids,
        'posts_per_page' => -1
    ];
    $items = get_posts($args);
} elseif ($behaviour === 'children' && !empty($parent_id)) {
    $args = [
        'post_type'   => 'page',
        'post_status' => 'publish',
        'post_parent' => $parent_id,
        'orderby'     => 'menu_order',
        'order'       => 'ASC',
        'posts_per_page' => -1
    ];
    $items = get_posts($args);
}
?>

<div class="box-columns cont-m padding-t-b-100">
    
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
        <?php if ($items): ?>
            <?php foreach ($items as $item): ?>
                <?php 
                    if ($behaviour === 'manual') {
                        $title = $item['box_columns_item_title'] ?? '';
                        $description = $item['box_columns_item_description'] ?? '';
                        $button = $item['box_columns_item_button'] ?? '';
                        $image = $item['box_columns_item_image'] ?? null;
                    } else {
                        $title = get_the_title($item->ID);
                        $description = get_the_excerpt($item->ID);
                        $button = ['url' => get_permalink($item->ID), 'title' => 'Read More'];
                        $image = get_the_post_thumbnail_url($item->ID, 'large');
                    }

                    // Extract image URL with fallback
                    $image_url = $image ? (is_array($image) ? $image['sizes']['large'] : $image) : get_template_directory_uri() . '/images/placeholder.png';
                ?>
                <div class="box-item light-grey-bg">
                    <div class="box-content padding-40">
                        <div>
                            <?php if ($title): ?>
                                <p class="fs-400 fw-semibold margin-b-20"><?php echo esc_html($title); ?></p>
                            <?php endif; ?>
                            <?php if ($description): ?>
                                <p class="margin-b-20 grey-text"><?php echo wp_kses_post($description); ?></p>
                            <?php endif; ?>
                        </div>
                        <?php if ($button): ?>
                            <a href="<?php echo esc_url($button['url']); ?>" class="hl arrow" target="<?php echo esc_attr($button['target'] ?? ''); ?>">
                                <?php echo esc_html($button['title']); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="box-image image-cover" style="background-image:url('<?php echo esc_url($image_url); ?>');"></div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>
