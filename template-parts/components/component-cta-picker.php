<?php
// Use passed variables instead of get_query_var()
$title = isset($title) ? $title : get_field('cta_picker_title');
$link = isset($link) ? $link : get_field('cta_picker_link');
$content_type = isset($content_type) ? $content_type : get_field('cta_picker_content_type');
$content_ids = isset($content_ids) ? $content_ids : get_field('cta_picker_content_ids');

// Ensure content IDs are in array format and converted to integers
$content_ids_array = !empty($content_ids) ? array_map('intval', explode(',', $content_ids)) : [];

// Define query parameters based on content type
if ($content_type === 'category') {
    $args = [
        'taxonomy'   => 'category',
        'include'    => $content_ids_array,
        'hide_empty' => false,
        'orderby'    => 'include'
    ];
    $content_items = get_terms($args);
} else {
    $args = [
        'post_type'   => in_array($content_type, ['post', 'page']) ? $content_type : 'post',
        'post_status' => 'publish',
    ];
    
    if (!empty($content_ids_array)) {
        $args['orderby'] = 'post__in';
        $args['post__in'] = $content_ids_array;
        $args['posts_per_page'] = -1;
    } elseif ($content_type === 'post') {
        $args['posts_per_page'] = 3;
    }

    $content_items = get_posts($args);

    if (!empty($content_ids_array)) {
        // Manually sort the posts based on post__in order - not sure why 'orderby' and 'post__in' working above, may be a bug
        $sorted_content_items = [];
        foreach ($content_ids_array as $id) {
            foreach ($content_items as $item) {
                if ((int) $item->ID === (int) $id) {
                    $sorted_content_items[] = $item;
                    break;
                }
            }
        }
        $content_items = $sorted_content_items;
    }
}

include_template_part('template-parts/components/parts/title-strip', [
    'title' => $title,
    'link' => $link
]);

if (!empty($content_items)) { ?>

    <aside class="box-items box-items--<?php echo count($content_items); ?>">
        <?php foreach ($content_items as $item) : ?>
            <div class="box-item light-grey-bg">
                <div class="box-content padding-40">
                    <div>
                        <p class="fs-400 fw-semibold margin-b-20">
                            <?php echo esc_html($content_type === 'category' ? $item->name : $item->post_title); ?>
                        </p>
                        <?php if ($content_type === 'category' && !empty($item->description)) : ?>
                            <p class="margin-b-20 grey-text"><?php echo esc_html($item->description); ?></p>
                        <?php elseif (($content_type === 'post' || $content_type === 'page') && !empty($item->post_excerpt)) : ?>
                            <p class="margin-b-20 grey-text"><?php echo esc_html($item->post_excerpt); ?></p>
                        <?php endif; ?>
                    </div>
                    <a href="<?php echo esc_url($content_type === 'category' ? get_category_link($item->term_id) : get_permalink($item->ID)); ?>" class="hl arrow">
                        View <?php echo esc_html($content_type === 'category' ? $item->name : 'More'); ?>
                    </a>
                </div>

                <?php
                if ($content_type === 'category') :
                    $cat_image = get_field('category_image', 'category_'.$item->term_id);
                    $thumbnail_url = wp_get_attachment_image_url( $cat_image, 'thumbnail' );
                else :
                    $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');
                endif;
                if (!$thumbnail_url) {
                    $thumbnail_url = get_template_directory_uri() . '/images/placeholder-thumbnail.png';
                }
                ?>
                <div class="box-image image-cover" style="background-image:url(<?php echo $thumbnail_url; ?>"></div>
            </div>
        <?php endforeach; ?>
    </aside>
<?php } else {
    echo '<p>No content items found.</p>';
}    
?>
