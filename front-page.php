<?php
/*
Template Name: Homepage
*/
?>

	<?php get_header(); ?>

	<!-- Slider main container -->
	<div class="swiper">
		
		<!-- Additional required wrapper -->
		<div class="swiper-wrapper">
			<!-- Slides -->
			<div class="swiper-slide image-cover" style="background-image:url(<?php echo get_template_directory_uri(); ?>/images/home-banner-1.jpg)" data-title="Aviation & Aerospace">
				<div class="swiper-inner padding-50 white-text">
					<div class="title-button">
						<h1 class="fw-extrabold fs-900 uppercase margin-b-30">World leader in supercritical foams</h1>
						<a href="" class="btn white outline arrow">Discover more about us</a>
					</div>
					<p class="fw-bold fs-600 uppercase">High-performance supercritical foams made for quality applications in a wide range of markets.</p>
				</div>
				<div class="overlay"></div>
			</div>
			<div class="swiper-slide image-cover" style="background:blue" data-title="Something else">
				<div class="swiper-inner padding-50 white-text">
					<div class="title-button">
						<h1 class="fw-extrabold fs-900 uppercase margin-b-30">Another thing we do</h1>
						<a href="" class="btn white outline arrow">Find out more</a>
					</div>
					<p class="fw-bold fs-600 uppercase">High-performance supercritical foams made for quality applications in a wide range of markets.</p>
				</div>
				<div class="overlay"></div>
			</div>
			<div class="swiper-slide image-cover" style="background:green" data-title="Another thing">
				<div class="swiper-inner padding-50 white-text">
					<div class="title-button">
						<h1 class="fw-extrabold fs-900 uppercase margin-b-30">Last thing we talk about here</h1>
						<a href="" class="btn white outline arrow">Find out more</a>
					</div>
					<p class="fw-bold fs-600 uppercase">High-performance supercritical foams made for quality applications in a wide range of markets.</p>
				</div>
				<div class="overlay"></div>
			</div>
		</div>

		<!-- Only the next button -->
		<div class="swiper-button-next white-text">
			<p class="fw-regular fs-100 uppercase margin-r-15">NEXT: <span class="fw-bold"></span></p>
			<!-- Circle container -->
			<div class="circle-container">
				<svg class="circle-svg" viewBox="0 0 50 50" xmlns="http://www.w3.org/2000/svg">
					<circle class="circle-background" cx="25" cy="25" r="23"></circle>
					<circle class="circle-progress" cx="25" cy="25" r="23"></circle>
				</svg>
			</div>
		</div>

	</div>

	<!-- Half & Half image text block -->
	<div class="half-half cont-m padding-t-b-100">
		<div class="half video-container image-cover" style="background-image:url(<?php echo get_template_directory_uri(); ?>/images/home-nike-thumb.jpg)">
			<a href="https://www.youtube.com/watch?v=dQw4w9WgXcQ" class="video-link open-video-overlay">
				<img src="/wp-content/themes/zotefoams/images/youtube-play.svg" />
			</a>
		</div>
		<div class="half">
			<p class="fs-200 fw-regular margin-b-30">The Zotefoams Difference</p>
			<p class="fs-600 fw-semibold margin-b-40">A world leader in supercritical cellular materials technology with key operations in the UK, China, Denmark, Poland and the USA.</p>
			<p class="margin-b-50">Innovation is at the centre of everything we do. Our applications deliver key solutions in industries such as Aerospace, Automotive, Aviation, Construction & Insulation and Transportation.</p>
		</div>
	</div>

	<!-- Video Overlay Structure -->
	<div id="video-overlay" style="display:none;">
		<div id="overlay-content">
			<button id="close-video">Close</button>
			<iframe id="video-iframe" width="100%" height="100%" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		</div>
	</div>

	<div class="half-half-carousel black-bg white-text text-center">
		<div class="half padding-100">
			<p class="fs-200 fw-regular margin-b-30">Markets</p>
			<p class="fs-600 fw-semibold margin-b-40">The Zotefoams Difference</p>
			<img class="margin-b-30" src="<?php echo get_template_directory_uri(); ?>/images/black-carousel-small.jpg" />
			<p class="margin-b-50">Smarter material choices helped Hochdorf, Switzerland-based thermoforming expert Plastika Balumag reduce the weight of its parts for a wide range of aircraft applications. </p>
			<a href="" class="btn white outline">Find out more</a>
		</div>
		<div class="half image-cover" style="background-image:url(<?php echo get_template_directory_uri(); ?>/images/empty-plane-ai-generated-image.jpg)">
		</div>
	</div>

	<?php get_footer(); ?>