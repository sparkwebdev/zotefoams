<?php
// Use passed variables instead of get_query_var()
$title = get_sub_field('cta_picker_title');
$link = get_sub_field('cta_picker_link');
$content_type = get_sub_field('cta_picker_content_type'); // Now null/empty by default
$show_latest = get_sub_field('cta_picker_show_latest') ?? true;
$item_count = get_sub_field('cta_picker_count') ?? 3;
$content_ids_array = get_sub_field('cta_picker_content_ids');
$category_ids = get_sub_field('cta_picker_categories');
$documents = get_sub_field('cta_picker_documents');
$layout = get_sub_field('cta_picker_layout');

// Define layout class based on selection
$layout_class = '';
if ($layout === 'Grid') {
    $layout_class = 'grid-layout';
} elseif ($layout === 'Grid Tinted') {
    $layout_class = 'grid-tinted-layout';
} elseif ($layout === 'Grid Full-Width') {
    $layout_class = 'grid-full-width-layout';
} elseif ($layout === 'List') {
    $layout_class = 'list-layout';
}

$content_items = [];

// Define query parameters
if ($content_type === 'category' && !empty($category_ids)) {
    $args = [
        'taxonomy'   => 'category',
        'include'    => $category_ids,
        'hide_empty' => false,
        'orderby'    => 'include'
    ];
    $content_items = get_terms($args);

} elseif (in_array($content_type, ['post', 'page']) && !$show_latest) {
    if (!empty($content_ids_array)) {
        $args = [
            'post_type'   => $content_type,
            'post_status' => 'publish',
            'orderby'     => 'post__in',
            'post__in'    => $content_ids_array,
            'posts_per_page' => -1
        ];
        $content_items = get_posts($args);
    }
} elseif ($content_type === 'post' && $show_latest) {
    $args = [
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
        'posts_per_page' => $item_count
    ];
    $content_items = get_posts($args);

} elseif ($content_type === 'documents' && !empty($documents)) {
    $content_items = $documents;
}
?>

<div class="cta-picker-container <?php echo esc_attr($layout_class); ?> light-grey-bg padding-t-b-70 theme-light">

    <div class="cont-m">

        <?php if ($title || $link) { ?>
            <div class="title-strip margin-b-30">
                <?php if ($title) : ?>
                    <h3 class="fs-500 fw-600"><?php echo esc_html($title); ?></h3>
                <?php endif; ?>

                <?php if ($link) :
                    $link_url = $link['url'] ?? '#';
                    $link_title = $link['title'] ?? 'Read More';
                    $link_target = !empty($link['target']) ? ' target="' . esc_attr($link['target']) . '"' : '';
                ?>
                    <a href="<?php echo esc_url($link_url); ?>" class="btn black outline"<?php echo $link_target; ?>>
                        <?php echo esc_html($link_title); ?>
                    </a>
                <?php endif; ?>
            </div>
        <?php }

        if (!empty($content_items)) { ?>

            <aside class="box-items box-items--<?php echo count($content_items); ?>">
                <?php foreach ($content_items as $item) : ?>
                    <div class="box-item light-grey-bg">
                        <div class="box-content padding-40">
                            <div>
                                <?php if ($content_type === 'category') : ?>
                                    <p class="fs-400 fw-semibold margin-b-20"><?php echo esc_html($item->name); ?></p>
                                    <?php if (!empty($item->description)) : ?>
                                        <p class="margin-b-20 grey-text"><?php echo esc_html($item->description); ?></p>
                                    <?php endif; ?>
                                <?php elseif ($content_type === 'post' || $content_type === 'page') : ?>
                                    <p class="fs-400 fw-semibold margin-b-20"><?php echo esc_html($item->post_title); ?></p>
                                    <?php if (!empty($item->post_excerpt)) : ?>
                                        <p class="margin-b-20 grey-text"><?php echo esc_html($item->post_excerpt); ?></p>
                                    <?php endif; ?>
                                <?php elseif ($content_type === 'documents') : ?>
                                    <p class="fs-400 fw-semibold margin-b-20"><?php echo esc_html($item['title']); ?></p>
                                <?php endif; ?>
                            </div>
                            <a href="<?php echo esc_url(
                                $content_type === 'category' ? get_category_link($item->term_id) :
                                ($content_type === 'documents' ? $item['url'] : get_permalink($item->ID))
                            ); ?>" class="hl arrow">
                                View <?php echo esc_html($content_type === 'category' ? $item->name : 'More'); ?>
                            </a>
                        </div>

                        <?php
                        if ($content_type === 'category') :
                            $cat_image = get_field('category_image', 'category_'.$item->term_id);
                            $thumbnail_url = wp_get_attachment_image_url($cat_image, 'thumbnail');
                        elseif ($content_type === 'documents') :
                            $thumbnail_url = $item['url']; // Direct document thumbnail
                        else :
                            $thumbnail_url = get_the_post_thumbnail_url($item->ID, 'thumbnail');
                        endif;
                        if (!$thumbnail_url) {
                            $thumbnail_url = get_template_directory_uri() . '/images/placeholder-thumbnail.png';
                        }
                        ?>
                        <div class="box-image image-cover" style="background-image:url(<?php echo esc_url($thumbnail_url); ?>);"></div>
                    </div>
                <?php endforeach; ?>
            </aside>
        <?php } else {
            echo '<p>No content items found.</p>';
        } ?>
    </div>
</div>
