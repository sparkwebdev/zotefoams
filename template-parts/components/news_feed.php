<?php 
// Allow for passed variables, as well as ACF values
$title = get_sub_field('news_feed_title');
$button = get_sub_field('news_feed_button'); // ACF Link field
$news_items = get_sub_field('news_feed_items');
?>

<div class="news-feed cont-m padding-t-b-100">
    
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

    <div class="feed-items">
        <?php if ($news_items): ?>
            <?php foreach ($news_items as $news_item): ?>
                <?php 
                    $image = $news_item['news_feed_image'];
                    $category = $news_item['news_feed_category'];
                    $title = $news_item['news_feed_title'];
                    $link = $news_item['news_feed_link']; // ACF Link field

                    // Extract 'large' size image URL with fallback
                    $image_url = $image ? $image['sizes']['large'] : get_template_directory_uri() . '/images/placeholder.png';
                ?>
                <div class="feed-item">
                    <div class="feed-image image-cover" style="background-image:url('<?php echo esc_url($image_url); ?>');"></div>
                    <div class="feed-content padding-40">
                        <?php if ($category): ?>
                            <p class="fs-100 margin-b-20 grey-text"><?php echo esc_html($category); ?></p>
                        <?php endif; ?>
                        <?php if ($title): ?>
                            <p class="fs-400 fw-semibold margin-b-80"><?php echo esc_html($title); ?></p>
                        <?php endif; ?>
                    </div>
                    <?php if ($link): ?>
                        <a href="<?php echo esc_url($link['url']); ?>" class="hl arrow" target="<?php echo esc_attr($link['target']); ?>">
                            <?php echo esc_html($link['title']); ?>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>
