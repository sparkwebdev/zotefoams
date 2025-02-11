
	<!-- Carousel 4 - Multi-Item Carousel -->
	<div class="swiper multi-item-carousel margin-t-100">
		<div class="title-strip margin-b-30 cont-m">
			<h3 class="fs-500 fw-600">High-Performance Foams</h3>
			<!-- Navigation -->
			<div class="carousel-navigation black">
				<div class="carousel-navigation-inner">
					<div class="multi-swiper-button-prev">
						<img src="<?php echo get_template_directory_uri(); ?>/images/left-arrow-black.svg" />
					</div>
					<div class="multi-swiper-button-next">
						<img src="<?php echo get_template_directory_uri(); ?>/images/right-arrow-black.svg" />
					</div>
				</div>
			</div>
		</div>
		<div class="swiper-wrapper">
			<div class="swiper-slide">
				<h3>Zotek®</h3>
				<p>Unique high-performance foams produced form Zotefoam's high pressure nitrogen expansion process.</p>
				<img src="<?php echo get_template_directory_uri(); ?>/images/foam.png" alt="Product 1">
				<a href="#" class="btn black outline">Explore Product</a>
			</div>
			<div class="swiper-slide">
				<h3>Zotek®</h3>
				<p>Unique high-performance foams produced form Zotefoam's high pressure nitrogen expansion process.</p>
				<img src="<?php echo get_template_directory_uri(); ?>/images/foam.png" alt="Product 2">
				<a href="#" class="btn black outline">Explore Product</a>
			</div>
			<div class="swiper-slide">
				<h3>Zotek®</h3>
				<p>Unique high-performance foams produced form Zotefoam's high pressure nitrogen expansion process.</p>
				<img src="<?php echo get_template_directory_uri(); ?>/images/foam.png" alt="Product 3">
				<a href="#" class="btn black outline">Explore Product</a>
			</div>
			<div class="swiper-slide">
				<h3>Zotek®</h3>
				<p>Unique high-performance foams produced form Zotefoam's high pressure nitrogen expansion process.</p>
				<img src="<?php echo get_template_directory_uri(); ?>/images/foam.png" alt="Product 4">
				<a href="#" class="btn black outline">Explore Product</a>
			</div>
			<div class="swiper-slide">
				<h3>Zotek®</h3>
				<p>Unique high-performance foams produced form Zotefoam's high pressure nitrogen expansion process.</p>
				<img src="<?php echo get_template_directory_uri(); ?>/images/foam.png" alt="Product 5">
				<a href="#" class="btn black outline">Explore Product</a>
			</div>
		</div>
		<div class="multi-swiper-scrollbar cont-m"></div>
	</div>

	<script type="text/javascript">
		document.addEventListener('DOMContentLoaded', function () {
			// Carousel 4 - Multi-Item Carousel
			const swiperMultiItem = new Swiper('.multi-item-carousel', {
				loop: false,
				slidesPerView: 3,
				spaceBetween: 20,
				navigation: {
					prevEl: '.multi-swiper-button-prev',
					nextEl: '.multi-swiper-button-next',
				},
				scrollbar: {
					el: '.multi-swiper-scrollbar', // Select your scrollbar container
					hide: false, // Optional: Hide scrollbar when not active
					draggable: false, // Optional: Make scrollbar draggable
				},
			});
		});
	</script>


	<style type="text/css">
	/* 10 - Multi item carousel
	--------------------------------------------- */

	.multi-item-carousel {
		padding: 50px;
	}

		.multi-item-carousel .swiper-slide {
			text-align: center;
			border: solid 1px #DDDDDD;	
		}

			.multi-item-carousel .swiper-slide > * {
				display: block;
				margin-left: auto;
				margin-right: auto;
			}

			.multi-item-carousel .swiper-slide h3 {
				font-size: 2em;
				font-weight: normal;
				margin-top: 60px;
				margin-bottom: 30px;
			}

			.multi-item-carousel .swiper-slide p {
				margin-bottom: 70px;
				max-width: 400px;
				color: #707070;
			}

			.multi-item-carousel .swiper-slide img {
				max-height: 360px;
				margin-bottom: 60px;
			}

			.multi-item-carousel .swiper-slide a.btn {
				display: inline-block;
				margin-bottom: 60px;
				font-size: 0.8em;
			}

		.multi-item-carousel .multi-swiper-scrollbar {
			height: 5px;
			margin-top: 30px;
			background: rgba(0, 0, 0, 0.15); /* Background of the scrollbar */
		}

		.multi-item-carousel .swiper-scrollbar-drag {
			background: #707070;
			height: 5px;
		}
		
	</style>



