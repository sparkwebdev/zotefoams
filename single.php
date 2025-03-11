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
						if (has_excerpt()) {
							echo '<p class="lead-text"><strong>'.get_the_excerpt().'</strong></p>';
						}

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
				// Get current post categories and set the "Back to List" link to the first category archive (or fallback if none)
				$categories = get_the_category();
				$first_category_link = !empty($categories) ? get_category_link($categories[0]->term_id) : '#';
				?>

				<div class="post-navigation post-navigation--single margin-t-70 margin-b-70">
						<!-- Back to List Link -->
						<div class="nav-back-to-list">
								<a href="<?php echo esc_url($first_category_link); ?>">
										<?php echo esc_html__( '« Back to List', 'zotefoams' ); ?>
								</a>
						</div>

						<!-- Previous Post Link (Same Category) -->
						<div class="nav-previous">
								<?php 
								previous_post_link(
										'%link',
										'<span class="nav-subtitle">« ' . esc_html__( 'Previous Article', 'zotefoams' ) . '</span>',
										true,    // Ensure it's in the same category
										'',      // Excluded categories (none in this case)
										'category' // Specify the taxonomy
								); 
								?>
						</div>

						<!-- Next Post Link (Same Category) -->
						<div class="nav-next">
								<?php 
								next_post_link(
										'%link',
										'<span class="nav-subtitle">' . esc_html__( 'Next Article', 'zotefoams' ) . ' »</span>',
										true,    // Ensure it's in the same category
										'',      // Excluded categories (none in this case)
										'category' // Specify the taxonomy
								); 
								?>
						</div>
				</div>

			<?php endwhile; // End of the loop.
			?>
		</div>

		<hr class="separator" />

		<?php
		get_template_part( 'template-parts/latest-posts' );
		?>

<?php
get_footer();
