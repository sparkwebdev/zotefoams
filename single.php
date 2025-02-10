<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Zotefoams
 */

get_header();
?>

	<main id="primary" class="site-main">

		<?php
		while ( have_posts() ) :
			the_post();
		?>
			<div class="cont-xs margin-t-70 margin-b-70">
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="entry-header">
						<?php
						// Loop through each category and display it in a span
						$categories = get_the_category();
						if ( ! empty( $categories ) ) {
							echo '<div class="margin-b-20 grey-text">';
							foreach ( $categories as $category ) {
								echo '<span class="tag ">' . esc_html( $category->name ) . '</span> ';
							}
							echo '</div>';
						}
						the_title( '<h1 class="fs-600 fw-semibold margin-b-20">', '</h1>' );

						if ( 'post' === get_post_type() ) :
							?>
							<div class="margin-b-20 grey-text">
								<?php
								zotefoams_posted_on();
								?>
							</div>
						<?php endif; ?>
					</header><!-- .entry-header -->

					<figure><?php zotefoams_post_thumbnail('large'); ?></figure>

					<div class="entry-content">
						<?php
						echo '<p class="lead-text"><strong>'.get_the_excerpt().'</strong></p>';

						the_content();

						wp_link_pages(
							array(
								'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'zotefoams' ),
								'after'  => '</div>',
							)
						);
						?>
					</div><!-- .entry-content -->

				</article><!-- #post-<?php the_ID(); ?> -->
				<?php

				$first_category_link = !empty($categories) ? get_category_link($categories[0]->term_id) : '#'; // Link to the first category archive

				$navigation_args = array(
					'prev_text' => '<a href="' . esc_url($first_category_link) . '">' . esc_html__( '« Back to List', 'zotefoams' ) . '</a>',
					'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next Article ', 'zotefoams' ) . '»</span>',
				);

				echo '<div class="margin-t-70 margin-b-70">';
					the_post_navigation($navigation_args);
				echo '</div>';

			endwhile; // End of the loop.
			?>
		</div>
	</main><!-- #main -->

	<hr class="separator" />
		<aside class="cont-m margin-t-70 margin-b-70">
			<div class="articles-header">
				<h2>Latest Updates</h2>
				<a href="<?php echo get_the_permalink(get_option('page_for_posts', true)) ?>" class="btn black outline"><?php echo get_the_title(get_option('page_for_posts', true)); ?></a>
			</div>
			<div class="articles articles--grid-alt margin-t-30">

			<?php
			$args = array(
				'post_type' => 'post',
				'orderby' => 'date',
				'order' => 'DESC',
				'posts_per_page' => '3',
			);

			$custom_query = new WP_Query($args);

			if ($custom_query->have_posts()) :
				while ($custom_query->have_posts()) : $custom_query->the_post();
			?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> data-clickable-url="<?php echo esc_url(get_the_permalink()); ?>">
				<?php
						$thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');
						// Set a default placeholder image if no thumbnail is found
						if (!$thumbnail_url) {
							$thumbnail_url = get_template_directory_uri() . '/images/placeholder-thumbnail.png';
						}

						echo '<img src="' . esc_url($thumbnail_url) . '" alt="">';
				?>
				<div class="articles__content">
					<?php
						$categories = get_the_category();
						if ( ! empty( $categories ) ) {
							echo '<div class="tags margin-b-20">';
							foreach ( $categories as $category ) {
								echo '<span class="tag">' . esc_html( $category->name ) . '</span> ';
							}
							echo '</div>';
						}
						$cat_more_link = esc_url( get_the_permalink());
						
						the_title( '<h3 class="fs-400 fw-semibold margin-b-20">', '</h3>' );

						if ( ! empty( $categories ) ) {
							switch ($categories[0]->name) {
								case 'Case Studies':
									$cat_more_link_label = 'View Case Study';
									$layout = "grid";
									break;
								case 'News':
								case 'Blog':
									$cat_more_link_label = 'Read Article';
									break;
								default:
									$cat_more_link_label = 'View '.rtrim($categories[0]->name, 's');
							}
							echo '<p class="articles__cta"><a href="' . $cat_more_link . '">' . $cat_more_link_label . '</a> &gt;</p>';
						}
						
					?>
				</div>
			</article><!-- #post-<?php the_ID(); ?> -->
			<?php
				endwhile; 
				wp_reset_postdata(); // Reset post data after custom query
			endif;
			?>
</div>






		</aside>
<?php
get_footer();
