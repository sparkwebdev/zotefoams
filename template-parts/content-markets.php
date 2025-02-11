<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Zotefoams
 */

?>

<!-- Carousel 1 - image banner -->
<div class="image-banner swiper swiper-image">
	<div class="swiper-wrapper">
		<div class="swiper-slide image-cover" style="background-image:url(<?php echo get_template_directory_uri(); ?>/images/4164.jpg)" data-title="Aviation & Aerospace">
			<div class="swiper-inner padding-50 white-text">
				<div class="title-button">
					<h1 class="fw-extrabold fs-900 uppercase margin-b-30">A World Leader In Innovation</h1>
				</div>
				<p class="fw-bold fs-600 uppercase">High-performance supercritical foams made for quality applications in a wide range of markets.</p>
			</div>
			<div class="overlay"></div>
		</div>
	</div>
</div>

<div class="text-block cont-m margin-t-70 margin-b-100">
	<div class="text-block-inner">
		<p class="margin-b-20">Adaptable Materials</p>
		<p class="grey-text fs-600 fw-semibold"><b>We work with a variety of industries</b> to develop and adapt our supercritical foams to specific requirements. From lightweight aircraft interiors in Aviation and insulation to casings for EV batteries in Automotive, <b>Zotefoams' materials are adaptable with superior performance qualities</b>.</p>
	</div>
</div>

<?php get_template_part('template-parts/08-markets-list'); ?>

<div class="cont-m margin-t-70 margin-b-70">
	<?php 
	set_query_var('title', 'Case Studies');
	set_query_var('link', ['url' => '/knowledge-hub', 'title' => 'Visit Our Knowledge Hub', 'target' => '']);
	set_query_var('content_type', 'category'); // 'post', 'page', or 'category'
	set_query_var('content_ids', '1,6,9,10'); // Post/page/category IDs
	get_template_part('template-parts/components/component-cta-picker'); ?>
</div>
