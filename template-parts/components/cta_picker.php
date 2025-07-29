<?php
// Get field data using safe helper functions
$title         = zotefoams_get_sub_field_safe('cta_picker_title', '', 'string');
$link          = zotefoams_get_sub_field_safe('cta_picker_link', [], 'url');
$content_type  = zotefoams_get_sub_field_safe('cta_picker_content_type', null, 'string');
$show_latest   = zotefoams_get_sub_field_safe('cta_picker_show_latest', true, 'bool');
$item_count    = zotefoams_get_sub_field_safe('cta_picker_count', 3, 'int');
$content_ids   = zotefoams_get_sub_field_safe('cta_picker_content_ids', [], 'array');
$category_ids  = zotefoams_get_sub_field_safe('cta_picker_categories', [], 'array');
$documents     = zotefoams_get_sub_field_safe('cta_picker_documents', [], 'array');
$layout        = zotefoams_get_sub_field_safe('cta_picker_layout', '', 'string');

$layout_class_map = [
    'Grid'            => 'grid-layout',
    'Grid Tinted'     => 'grid-tinted-layout',
    'Grid Full-Width' => 'grid-full-width-layout',
    'List'            => 'list-layout'
];
$layout_class = $layout_class_map[$layout] ?? '';

// Get theme-aware wrapper classes
$wrapper_classes = Zotefoams_Theme_Helper::get_wrapper_classes([
    'component' => 'cta-picker-container ' . $layout_class,
    'theme'     => 'light',
    'spacing'   => 'padding-t-b-70',
    'container' => '',
]);

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

<div class="<?php echo $wrapper_classes; ?>">
    <div class="cont-m">
        <?php echo zotefoams_render_title_strip($title, $link); ?>

        <?php if (!empty($content_items)): ?>
            <div class="box-columns">
                <aside class="box-columns__items box-columns__items--<?php echo count($content_items); ?>">
                    <?php foreach ($content_items as $item): ?>
                        <?php
                        if ($content_type === 'category') {
                            $title_text = $item->name;
                            $excerpt = $item->description;
                            $link_url = get_category_link($item->term_id);
                            $thumbnail_id = get_field('category_image', 'category_' . $item->term_id);
                            $thumbnail_url = Zotefoams_Image_Helper::get_image_url($thumbnail_id, 'thumbnail', 'thumbnail');
                        } elseif ($content_type === 'documents') {
                            $title_text = $item['title'] ?? '';
                            $excerpt = '';
                            $link_url = $item['url'] ?? '#';
                            $thumbnail_url = $link_url;
                        } else {
                            $title_text = $item->post_title;
                            $excerpt = $item->post_excerpt;
                            $link_url = get_permalink($item->ID);
                            $thumbnail_url = Zotefoams_Image_Helper::get_image_url(get_post_thumbnail_id($item->ID), 'thumbnail', 'thumbnail');
                        }

                        // Image Helper already handles fallbacks
                        ?>
                        <div class="box-columns__item light-grey-bg">
                            <div class="box-columns__content padding-40">
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
                            <div class="box-columns__image image-cover" style="background-image:url('<?php echo esc_url($thumbnail_url); ?>');"></div>
                        </div>
                    <?php endforeach; ?>
                </aside>
            </div>
        <?php else: ?>
            <p>No content items found.</p>
        <?php endif; ?>
    </div>
</div>