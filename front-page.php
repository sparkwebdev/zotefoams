<?php
/*
Template Name: Homepage
*/
?>

	<?php get_header(); ?>
 
	<?php get_template_part('template-parts/b1-Image-banner'); ?>

	<?php get_template_part('template-parts/b2-text-banner'); ?>

	<?php get_template_part('template-parts/01-split-video'); ?>

	<?php get_template_part('template-parts/02-dual-carousel'); ?>

	<?php get_template_part('template-parts/03-icon-columns'); ?>

	<?php get_template_part('template-parts/04-news-feed'); ?>

	<?php get_template_part('template-parts/05-document-list'); ?>

	<?php get_template_part('template-parts/06-show-hide'); ?>

	<?php //get_template_part('template-parts/07-box-columns'); ?>

	<div class="cont-m margin-t-70 margin-b-70">
		<?php 
			include_template_part('template-parts/components/component-cta-picker', [
				'content_type' => 'page', // 'post', 'page', or 'category'
				'content_ids' => '756,9,16' // Post/page/category IDs
			]);
		?>
	</div>

	<?php get_template_part('template-parts/08-markets-list'); ?>

	<?php get_template_part('template-parts/09-split-carousel'); ?>

	<?php get_template_part('template-parts/10-multi-item-carousel'); ?>

	<?php get_template_part('template-parts/11-calendar-carousel'); ?>

	<?php get_template_part('template-parts/12-split-text'); ?>

	<?php get_template_part('template-parts/13-text-block'); ?>

	<?php get_template_part('template-parts/14-tabbed-split'); ?>

	<?php get_footer(); ?>
















