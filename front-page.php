<?php
/*
Template Name: Homepage
*/
?>

	<?php get_header(); ?>
 
	<?php get_template_part('template-parts/b1-Image-banner'); ?>

	<?php get_template_part('template-parts/01a-split-video-one'); ?>

	<?php get_template_part('template-parts/02-dual-carousel'); ?>

	<?php get_template_part('template-parts/04-news-feed'); ?>

	<?php get_template_part('template-parts/05-document-list'); ?>

	<div class="cont-m margin-t-70 margin-b-70">
		<?php 
			include_template_part('template-parts/components/component-cta-picker', [
				'content_type' => 'page', // 'post', 'page', or 'category'
				'content_ids' => '756,9,16' // Post/page/category IDs
			]);
		?>
	</div>


	<?php get_footer(); ?>
















