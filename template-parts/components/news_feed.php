<?php
// Get field data using safe helper functions
$section_title = zotefoams_get_sub_field_safe('news_feed_title', '', 'string');
$button        = zotefoams_get_sub_field_safe('news_feed_button', [], 'url');
$behaviour     = zotefoams_get_sub_field_safe('news_feed_behaviour', '', 'string');
$post_ids      = zotefoams_get_sub_field_safe('news_feed_post_ids', [], 'array');
$news_items    = zotefoams_get_sub_field_safe('news_feed_items', [], 'array');

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

<?php
// Generate classes to match original structure exactly
$wrapper_classes = 'news-feed cont-m padding-t-b-100 theme-none';
?>

<div class="<?php echo $wrapper_classes; ?>">

    <?php echo zotefoams_render_title_strip($section_title, $button); ?>

    <div class="feed-items">
        <?php if ($news_items) : ?>
            <?php foreach ($news_items as $news_item) :
                if ($behaviour === 'manual') {
                    $image     = $news_item['news_feed_image'] ?? null;
                    $category  = $news_item['news_feed_category'] ?? '';
                    $item_title = $news_item['news_feed_title'] ?? '';
                    $link      = is_array($news_item['news_feed_link'] ?? null) ? $news_item['news_feed_link'] : null;
					$start_date = '';
					$event_name = '';
					$headingClass = "fs-400 fw-semibold margin-b-80";
                } else {
                    $image     = get_the_post_thumbnail_url($news_item->ID, 'large');
                    $categories = get_the_category($news_item->ID);
                    $category  = !empty($categories) ? $categories[0]->name : '';
                    $item_title = get_the_title($news_item->ID);
                    $link      = ['url' => get_permalink($news_item->ID), 'title' => 'Read More'];
					$start_date = get_field('event_start_date', $news_item->ID);
					$event_name = get_field('event_name', $news_item->ID);
					$headingClass = $start_date ? "fs-400 fw-semibold" : "fs-400 fw-semibold margin-b-80";
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

                        <?php if ($item_title || $event_name) : 
							if ($event_name) {
								echo '<h3 class="'.$headingClass.'">' . $event_name . '</h3>';
							} else {
								echo '<h3 class="'.$headingClass.'">' . esc_html($item_title) . '</h3>';
							}	                        
                        ?>
                        <?php endif; ?>
                        
                        <?php 
							if ($start_date) {
								$end_date = get_field('event_end_date', $news_item->ID);
								$venue = get_field('event_venue', $news_item->ID);
								$country = get_field('event_country', $news_item->ID);
								$stand_number = get_field('event_stand_number', $news_item->ID);
								if ($start_date) :
								    $dateRange = zotefoams_format_event_date_range($start_date, $end_date);
								    echo '<h3 class="fs-400 fw-semibold grey-text">' . $dateRange . '</h3>';
								endif;
								if ($venue || $country || $stand_number) :
								    echo '<h4 class="fs-300 fw-regular grey-text">' . implode(', ', [$venue, $country, $stand_number]) . '</h4>';
								endif;
								echo '<div class="margin-b-50">';
								get_template_part('template-parts/parts/events_details_short');
								echo '</div>';
							} ?>
                    </div>

                    <?php if ($link && !empty($link['url'])) : ?>
                        <?php echo zotefoams_render_link($link, [
                            'class' => 'hl arrow read-more'
                        ]); ?>
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