<?php
/**
 * The template for displaying home (posts) page
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Zotefoams
 */

get_header();
?>

	<main id="primary" class="site-main">

		<div class="text-banner cont-m margin-t-70 margin-b-70">
			<h1 class="uppercase grey-text fs-800 fw-extrabold"><?php echo get_the_title( get_option('page_for_posts', true)); ?></h1>
			<h2 class="uppercase black-text fs-800 fw-extrabold">The World of Zotefoams</h2>
		</div>

		<div class="text-banner-split half-half padding-b-100">
			<div class="half video-container image-cover" style="background-image:url(<?php echo get_template_directory_uri(); ?>/images/who-we-are.jpg)">
			</div>
			<div class="half black-bg white-text padding-100">
				<div class="text-banner-text">
					<p class="fs-200 fw-regular margin-b-30">News Centre</p>
					<p class="fs-600 fw-semibold margin-b-100">Welcome to the world of Zotefoams. Here you can find links our latest news articles, blogs, webinars and events.</p>
				</div>
			</div>
		</div>

		<div class="text-block cont-m margin-b-100">
			<div class="text-block-inner">
				<p class="margin-b-20">Everything Zotefoams</p>
				<p class="grey-text fs-600 fw-semibold">Aenean lacus dui <b>accumsan pharetra neque a rhoncus lacinia</b> nunc. Morbi sed elit id massa varius viverra quis at ipsum. Fusce at magna sed magna bibendum tempor.</p>
			</div>
		</div>

		<?php
		// List all categories
		$args = array(
			'orderby' => 'ID',
			'include' => '1,7,6,9',
			'hide_empty' => false,
		);
		$all_categories = get_categories($args);
		if ( ! empty( $all_categories ) ) {
			echo '<div class="box-columns feed-one-outer cont-m margin-b-100">';
				echo '<div class="box-items">';
					foreach ( $all_categories as $category ) { ?>
						<div class="box-item light-grey-bg">
							<div class="box-content padding-40">
								<div>
									<p class="fs-400 fw-semibold margin-b-20"><?php echo esc_html( $category->name ); ?></p>
									<?php if ( ! empty( $category->description ) ) : ?>
										<p class="margin-b-20 grey-text"><?php echo esc_html( $category->description ); ?></p>
									<?php endif; ?>
								</div>
								<a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" class="hl arrow">Latest <?php echo esc_html( $category->name ); ?></a>
							</div>
							<div class="box-image image-cover" style="background-image:url(<?php echo get_template_directory_uri(); ?>/images/home-banner-1.jpg)"></div>
						</div>
					<?php }
					echo '</div>';
				echo '</div>';
		}
		?>

	</main><!-- #main -->

<?php
get_footer();
