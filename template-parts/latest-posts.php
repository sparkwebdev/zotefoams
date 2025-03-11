<?php 
$args = [
  'post_type'      => 'post',
  'posts_per_page' => 3,
  'post__not_in'   => [ get_the_ID() ],
  'tax_query'      => [
      [
          'taxonomy' => 'category',
          'field'    => 'slug',
          'terms'    => ['uncategorised', 'videos'], // Adjust these slugs as needed
          'operator' => 'NOT IN',
      ]
  ],
];
$news_items = get_posts( $args );
$news_centre_ID = zotefoams_get_page_for_posts_id(); 
?>

<?php if ( $news_items ): ?>
<div class="news-feed cont-m padding-t-b-100 theme-none">
  <div class="title-strip margin-b-30">
    <h3 class="fs-500 fw-600">Latest Updates</h3>
    <a href="<?php echo esc_url( get_permalink( $news_centre_ID ) ); ?>" class="btn black outline">
      <?php echo esc_html( get_the_title( $news_centre_ID ) ); ?>
    </a>
  </div>

  <div class="feed-items">
    <?php 
    global $post; // make sure to declare global $post
    foreach ( $news_items as $post ):
      setup_postdata( $post ); // Now $post is set to the current item in the loop
      
      $image_url  = get_the_post_thumbnail_url( $post, 'large' ) ?: get_template_directory_uri() . '/images/placeholder.png';
      $categories = get_the_category();
      $category   = ! empty( $categories ) ? esc_html( $categories[0]->name ) : '';
    ?>
      <div class="feed-item" data-clickable-url="<?php echo esc_url( get_the_permalink() ); ?>">
        <div class="feed-image image-cover" style="background-image:url('<?php echo esc_url( $image_url ); ?>');"></div>
        <div class="feed-content padding-40">
            <?php if ( $category ): ?>
                <p class="fs-100 margin-b-20 grey-text"><?php echo $category; ?></p>
            <?php endif; ?>
            <p class="fs-400 fw-semibold margin-b-80"><?php the_title(); ?></p>
        </div>
        <a href="<?php echo esc_url( get_the_permalink() ); ?>" class="hl arrow read-more">
            Read More
        </a>
      </div>
    <?php endforeach; ?>
    <?php wp_reset_postdata(); // Reset global post data ?>
  </div>
</div>
<?php endif; ?>