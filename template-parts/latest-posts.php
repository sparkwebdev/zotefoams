<?php
$today = current_time('Ymd');

$args = [
    'post_type'      => 'post',
    'posts_per_page' => 3,
    'post__not_in'   => [get_the_ID()],
    'tax_query' => [
        [
            'taxonomy' => 'category',
            'field'    => 'slug',
            'terms'    => ['uncategorised', 'videos'],
            'operator' => 'NOT IN',
        ],
    ],
    'meta_query' => [
        'relation' => 'OR',

        // 1. Posts with no event_start_date (regular news posts, etc.)
        [
            'key'     => 'event_start_date',
            'compare' => 'NOT EXISTS',
        ],

        // 2. Upcoming events (event_start_date >= today)
        [
            'key'     => 'event_start_date',
            'value'   => $today,
            'compare' => '>=',
            'type'    => 'CHAR',
        ],

        // 3. Ongoing events (event_start_date < today AND event_end_date >= today)
        [
            'relation' => 'AND',
            [
                'key'     => 'event_start_date',
                'value'   => $today,
                'compare' => '<',
                'type'    => 'CHAR',
            ],
            [
                'key'     => 'event_end_date',
                'value'   => $today,
                'compare' => '>=',
                'type'    => 'CHAR',
            ],
        ],
    ],
];


$news_items      = get_posts($args);
$news_centre_ID  = zotefoams_get_page_for_posts_id();
?>

<?php if ($news_items) : ?>
  <div class="news-feed cont-m padding-t-b-100 theme-none">
    <div class="title-strip margin-b-30">
      <h3 class="fs-500 fw-600">Latest Updates</h3>
      <a href="<?php echo esc_url(get_permalink($news_centre_ID)); ?>" class="btn black outline">
        <?php echo esc_html(get_the_title($news_centre_ID)); ?>
      </a>
    </div>

    <div class="feed-items">
      <?php
      global $post;
      foreach ($news_items as $post) :
        setup_postdata($post);

        $image_url  = get_the_post_thumbnail_url($post, 'large') ?: get_template_directory_uri() . '/images/placeholder.png';
        $categories = get_the_category();
        $category   = ! empty($categories) ? esc_html($categories[0]->name) : '';
      ?>
        <div class="feed-item" data-clickable-url="<?php echo esc_url(get_permalink()); ?>">
          <div class="feed-image image-cover" style="background-image:url('<?php echo esc_url($image_url); ?>');"></div>
          <div class="feed-content padding-40">
            <?php if ($category) : ?>
              <p class="fs-100 margin-b-20 grey-text"><?php echo $category; ?></p>
            <?php endif; ?>
							<?php
							if (function_exists('get_field')) {
								$start_date = get_field('event_start_date', get_the_ID());
								$end_date = get_field('event_end_date', get_the_ID());
								$event_name = get_field('event_name');
								$dateRange = zotefoams_format_event_date_range($start_date, $end_date);
                $headingClass = $dateRange ? "fs-400 fw-semibold" : "fs-400 fw-semibold margin-b-80";
								if ($event_name) {
									echo '<h3 class="'.$headingClass.'">' . $event_name . '</h3>';
								} else {
									the_title('<h3 class="'.$headingClass.'">', '</h3>');
								}
								if ($start_date) {
									echo '<div class="margin-b-20">';
									get_template_part('template-parts/parts/events_details_short');
									echo '</div>';
								}
							} ?>
          </div>
          <a href="<?php echo esc_url(get_permalink()); ?>" class="hl arrow read-more">
            <?php esc_html_e('Read More', 'zotefoams'); ?>
          </a>
        </div>
      <?php endforeach; ?>
      <?php wp_reset_postdata(); ?>
    </div>
  </div>
<?php endif; ?>