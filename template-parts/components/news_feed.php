<?php
$section_title = get_sub_field('news_feed_title');
$button        = get_sub_field('news_feed_button'); // ACF Link field
$behaviour     = get_sub_field('news_feed_behaviour');
$post_ids      = get_sub_field('news_feed_post_ids');
$news_items    = get_sub_field('news_feed_items');

// Determine items based on behaviour
if ($behaviour === 'pick' && !empty($post_ids)) {
    $args = [
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'orderby'        => 'post__in',
        'post__in'       => $post_ids,
        'posts_per_page' => -1
    ];
    $news_items = get_posts($args);
}
?>

<div class="news-feed cont-m padding-t-b-100 theme-none">

    <div class="title-strip margin-b-30">
        <?php if ($section_title) : ?>
            <h3 class="fs-500 fw-600"><?php echo esc_html($section_title); ?></h3>
        <?php endif; ?>

        <?php if ($button) : ?>
            <a href="<?php echo esc_url($button['url']); ?>" class="btn black outline" target="<?php echo esc_attr($button['target']); ?>">
                <?php echo esc_html($button['title']); ?>
            </a>
        <?php endif; ?>
    </div>

    <div class="feed-items">
        <?php if ($news_items) : ?>
            <?php foreach ($news_items as $news_item) :
                if ($behaviour === 'manual') {
                    $image     = $news_item['news_feed_image'] ?? null;
                    $category  = $news_item['news_feed_category'] ?? '';
                    $item_title = $news_item['news_feed_title'] ?? '';
                    $link      = is_array($news_item['news_feed_link'] ?? null) ? $news_item['news_feed_link'] : null;
                } else {
                    $image     = get_the_post_thumbnail_url($news_item->ID, 'large');
                    $categories = get_the_category($news_item->ID);
                    $category  = !empty($categories) ? $categories[0]->name : '';
                    $item_title = get_the_title($news_item->ID);
                    $link      = ['url' => get_permalink($news_item->ID), 'title' => 'Read More'];
                }

                $image_url = $image ? (is_array($image) ? $image['sizes']['large'] : $image) : null;
            ?>
                <div class="feed-item"
                    <?php if ($link && !empty($link['url'])) : ?>
                    data-clickable-url="<?php echo esc_url($link['url']); ?>"
                    <?php endif; ?>>

                    <?php if ($image_url) : ?>
                        <div class="feed-image image-cover" style="background-image:url('<?php echo esc_url($image_url); ?>');"></div>
                    <?php endif; ?>

                    <div class="feed-content padding-40">
                        <?php if ($category) : ?>
                            <p class="fs-100 margin-b-20 grey-text"><?php echo esc_html($category); ?></p>
                        <?php endif; ?>

                        <?php if ($item_title) : ?>
                            <p class="fs-400 fw-semibold margin-b-80"><?php echo esc_html($item_title); ?></p>
                        <?php endif; ?>
                    </div>

                    <?php if ($link && !empty($link['url'])) : ?>
                        <a href="<?php echo esc_url($link['url']); ?>" class="hl arrow read-more" target="<?php echo esc_attr($link['target'] ?? ''); ?>">
                            <?php echo esc_html($link['title']); ?>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <?php
            // Layout spacing fix for 1 or 2 items
            $count = count($news_items);
            if ($count === 1) {
                echo '<div class="feed-item-spacer"></div><div class="feed-item-spacer"></div>';
            } elseif ($count === 2) {
                echo '<div class="feed-item-spacer"></div>';
            }
            ?>
        <?php endif; ?>
    </div>

</div>