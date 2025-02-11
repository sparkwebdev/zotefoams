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

		<div class="cont-m margin-t-70 margin-b-70">
			<?php 
			set_query_var('content_type', 'category'); // 'post', 'page', or 'category'
			set_query_var('content_ids', '1,6,9,10'); // Post/page/category IDs
			get_template_part('template-parts/components/component-cta-picker'); ?>
		</div>

	</main><!-- #main -->

<?php
get_footer();
