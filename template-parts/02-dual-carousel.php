

	<!-- Carousel 2 - dual carousel -->
	<div class="swiper-dual-carousel black-bg white-text text-center">
	  <div class="swiper swiper-dual-carousel-text">
		<div class="swiper-wrapper">
		  <div class="swiper-slide">
			<div class="slide-inner">
			  <p class="fs-200 fw-regular margin-b-30">Markets</p>
			  <p class="fs-600 fw-semibold margin-b-40">The Zotefoams Difference</p>
			  <div class="slide-sub-content">
				<img class="margin-b-30" src="<?php echo get_template_directory_uri(); ?>/images/black-carousel-small.jpg" />
				<p class="margin-b-30">Smarter material choices helped Hochdorf, Switzerland-based thermoforming expert Plastika Balumag reduce the weight of its parts for a wide range of aircraft applications. </p>
				<a href="" class="btn white outline">Find out more</a>
			  </div>
			</div>
		  </div>
		  <div class="swiper-slide">
			<div class="slide-inner">
			  <p class="fs-200 fw-regular margin-b-30">Other</p>
			  <p class="fs-600 fw-semibold margin-b-40">Something else</p>
			  <div class="slide-sub-content">
				<img class="margin-b-30" src="<?php echo get_template_directory_uri(); ?>/images/insulation.png" />
				<p class="margin-b-30">Smarter material choices helped Hochdorf, Switzerland-based thermoforming expert Plastika Balumag reduce the weight of its parts for a wide range of aircraft applications.</p>
				<a href="" class="btn white outline">Find out more</a>
			  </div>
			</div>
		  </div>
		  <div class="swiper-slide">
			<div class="slide-inner">
			  <p class="fs-200 fw-regular margin-b-30">Other</p>
			  <p class="fs-600 fw-semibold margin-b-40">Something else</p>
			  <div class="slide-sub-content">
				<img class="margin-b-30" src="<?php echo get_template_directory_uri(); ?>/images/insulation.png" />
				<p class="margin-b-30">Smarter material choices helped Hochdorf, Switzerland-based thermoforming expert Plastika Balumag reduce the weight of its parts for a wide range of aircraft applications.</p>
				<a href="" class="btn white outline">Find out more</a>
			  </div>
			</div>
		  </div>
		</div>

		<!-- Navigation -->
		<div class="carousel-navigation white">
		  <div class="carousel-navigation-inner">
			<div class="swiper-button-next-dual-carousel">
			  <img src="<?php echo get_template_directory_uri(); ?>/images/left-arrow-white.svg" />
			</div>
			<div class="swiper-button-prev-dual-carousel">
			  <img src="<?php echo get_template_directory_uri(); ?>/images/right-arrow-white.svg" />
			</div>
		  </div>

		  <div class="swiper-pagination"></div>
		</div>

	  </div>

	  <div class="swiper swiper-dual-carousel-image">
		<div class="swiper-wrapper">
		  <div class="swiper-slide">
			<div class="swiper-inner white-text">
			  <img src="<?php echo get_template_directory_uri(); ?>/images/empty-plane-ai-generated-image.jpg">
			</div>
		  </div>
		  <div class="swiper-slide">
			<div class="swiper-inner white-text">
			  <img src="<?php echo get_template_directory_uri(); ?>/images/man-suit-is-holding-tablet-front-car-with-graph-it.jpg">
			</div>
		  </div>
		  <div class="swiper-slide">
			<div class="swiper-inner white-text">
			  <img src="<?php echo get_template_directory_uri(); ?>/images/home-banner-1.jpg">
			</div>
		  </div>
		</div>
	  </div>
	</div>


	<script type="text/javascript">
		document.addEventListener('DOMContentLoaded', function () {
			// Carousel 2 - dual carousel
			const swiperText = new Swiper('.swiper-dual-carousel-text', {
			  loop: true,
			  effect: 'fade',
			  fadeEffect: {
				crossFade: true
			  },
			  speed: 1000,
			  navigation: {
				nextEl: '.swiper-button-next-dual-carousel',
				prevEl: '.swiper-button-prev-dual-carousel',
			  },
			});

			// Initialize the image carousel (without fade effect)
			const swiperImage = new Swiper('.swiper-dual-carousel-image', {
			  loop: true,
			  spaceBetween: 10,
			  navigation: {
				nextEl: '.swiper-button-next-dual-carousel',
				prevEl: '.swiper-button-prev-dual-carousel',
			  },
			});

			// Sync the carousels so that they move together
			swiperText.controller.control = swiperImage;
			swiperImage.controller.control = swiperText;
		});
	</script>

	<style type="text/css">
	/* 02 - Dual carousel
	--------------------------------------------- */
	.swiper-dual-carousel {
	  display: flex;
	  align-items: center;
	  justify-content: center;
	  align-items: stretch;  /* Ensure both child divs stretch to fill height */
	}
	.swiper-dual-carousel > div {
	  width: 50%;
	}

	.swiper-dual-carousel-image .swiper-wrapper {
	  align-items: center;
	}
	.swiper-dual-carousel-image .swiper-inner {
	  height: 100%;
	}
	.swiper-dual-carousel-image .swiper-inner img {
	  object-fit: cover;
	  height: 100%;
	}

	.swiper-dual-carousel-text {
	  display: flex;
	  align-items: center;
	  justify-content: center;
	  flex-direction: column;
	  position: relative;
	}

	.swiper-dual-carousel-text .swiper-slide {
	  padding: 70px 0 180px;
	}
	.swiper-dual-carousel-text .slide-sub-content {
	  max-width: 300px;
	  margin: 0 auto;
	}

	.swiper-dual-carousel .carousel-navigation {
	  display: flex;
	  flex-direction: column;
	  align-items: center;
	  justify-content: center;
	  position: absolute;
	  margin-left: auto;
	  margin-right: auto;
	  left: 0;
	  right: 0;
	  bottom: 50px;
	  text-align: center;
	  z-index: 1;
	}

	.swiper-pagination-bullet {
	  width: 9px;  /* Set width for the bullets */
	  height: 9px;  /* Set height for the bullets */
	  background-color: #fff;  /* Set default background color for the bullets */
	  border-radius: 50%;  /* Make the bullets round */
	  opacity: 0.6;  /* Optional: makes the bullets slightly transparent */
	  transition: opacity 0.3s;  /* Optional: add transition for better effect */
	  display: inline-block;
	  margin: 0 3px;
	}

	.swiper-pagination-bullet-active {
	  background-color: #3B82F6;  /* Set active bullet color */
	  opacity: 1;  /* Ensure the active bullet is fully opaque */
	}
	</style>