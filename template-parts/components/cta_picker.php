<?php
$title         = get_sub_field('cta_picker_title');
$link          = get_sub_field('cta_picker_link');
$content_type  = get_sub_field('cta_picker_content_type') ?: null;
$show_latest   = get_sub_field('cta_picker_show_latest') ?? true;
$item_count    = get_sub_field('cta_picker_count') ?? 3;
$content_ids   = get_sub_field('cta_picker_content_ids');
$category_ids  = get_sub_field('cta_picker_categories');
$documents     = get_sub_field('cta_picker_documents');
$layout        = get_sub_field('cta_picker_layout');

$layout_class_map = [
    'Grid'            => 'grid-layout',
    'Grid Tinted'     => 'grid-tinted-layout',
    'Grid Full-Width' => 'grid-full-width-layout',
    'List'            => 'list-layout'
];
$layout_class = $layout_class_map[$layout] ?? '';

$content_items = [];

if ($content_type === 'category' && !empty($category_ids)) {
    $content_items = get_terms([
        'taxonomy'   => 'category',
        'include'    => $category_ids,
        'hide_empty' => false,
        'orderby'    => 'include',
    ]);
} elseif (in_array($content_type, ['post', 'page'])) {
    if ($show_latest) {
        $content_items = get_posts([
            'post_type'      => $content_type,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
            'posts_per_page' => $item_count,
        ]);
    } elseif (!empty($content_ids)) {
        $content_items = get_posts([
            'post_type'      => $content_type,
            'post_status'    => 'publish',
            'orderby'        => 'post__in',
            'post__in'       => $content_ids,
            'posts_per_page' => -1,
        ]);
    }
} elseif ($content_type === 'documents' && !empty($documents)) {
    $content_items = $documents;
}
?>

<div class="cta-picker-container <?php echo esc_attr($layout_class); ?> light-grey-bg padding-t-b-70 theme-light">
    <div class="cont-m">
        <?php if ($title || $link): ?>
            <div class="title-strip margin-b-30">
                <?php if ($title): ?>
                    <h3 class="fs-500 fw-600"><?php echo esc_html($title); ?></h3>
                <?php endif; ?>
                <?php if ($link): ?>
                    <a href="<?php echo esc_url($link['url'] ?? '#'); ?>"
                        class="btn black outline"
                        target="<?php echo esc_attr($link['target'] ?? '_self'); ?>">
                        <?php echo esc_html($link['title'] ?? 'Read More'); ?>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($content_items)): ?>
            <aside class="box-items box-items--<?php echo count($content_items); ?>">
                <?php foreach ($content_items as $item): ?>
                    <?php
                    if ($content_type === 'category') {
                        $title_text = $item->name;
                        $excerpt = $item->description;
                        $link_url = get_category_link($item->term_id);
                        $thumbnail_id = get_field('category_image', 'category_' . $item->term_id);
                        $thumbnail_url = wp_get_attachment_image_url($thumbnail_id, 'thumbnail');
                    } elseif ($content_type === 'documents') {
                        $title_text = $item['title'] ?? '';
                        $excerpt = '';
                        $link_url = $item['url'] ?? '#';
                        $thumbnail_url = $link_url;
                    } else {
                        $title_text = $item->post_title;
                        $excerpt = $item->post_excerpt;
                        $link_url = get_permalink($item->ID);
                        $thumbnail_url = get_the_post_thumbnail_url($item->ID, 'thumbnail');
                    }

                    if (!$thumbnail_url) {
                        $thumbnail_url = get_template_directory_uri() . '/images/placeholder-thumbnail.png';
                    }
                    ?>
                    <div class="box-item light-grey-bg">
                        <div class="box-content padding-40">
                            <div>
                                <p class="fs-400 fw-semibold margin-b-20"><?php echo esc_html($title_text); ?></p>
                                <?php if (!empty($excerpt)): ?>
                                    <p class="margin-b-20 grey-text"><?php echo esc_html($excerpt); ?></p>
                                <?php endif; ?>
                            </div>
                            <a href="<?php echo esc_url($link_url); ?>" class="hl arrow">
                                View <?php echo esc_html($content_type === 'category' ? $title_text : 'More'); ?>
                            </a>
                        </div>
                        <div class="box-image image-cover" style="background-image:url('<?php echo esc_url($thumbnail_url); ?>');"></div>
                    </div>
                <?php endforeach; ?>
            </aside>
        <?php else: ?>
            <p>No content items found.</p>
        <?php endif; ?>
    </div>
</div>