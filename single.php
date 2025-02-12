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
					<?php if ( has_post_thumbnail() ) { ?>
						<figure><?php zotefoams_post_thumbnail('large'); ?></figure>
					<?php } ?>

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

	<div class="cont-m margin-t-70 margin-b-70">
		<?php 
			include_template_part('template-parts/components/component-cta-picker', [
				'title' => 'Latest Updates',
				'link' => [
					'url' => '/news-centre',
					'title' => 'News Centre',
					'target' => ''
				],
				'content_type' => 'post', // 'post', 'page', or 'category'
			]);
		?>
	</div>

<?php
get_footer();
