
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
				<img src="<?php echo get_template_directory_uri(); ?>/images/2023-annual-report.jpg" alt="Product 1">
				<p>Zotek®</p>
			</div>
			<div class="swiper-slide">
				<img src="<?php echo get_template_directory_uri(); ?>/images/2023-annual-report.jpg" alt="Product 1">
				<p>Zotek®</p>
			</div>
			<div class="swiper-slide">
				<img src="<?php echo get_template_directory_uri(); ?>/images/2023-annual-report.jpg" alt="Product 1">
				<p>Zotek®</p>
			</div>
			<div class="swiper-slide">
				<img src="<?php echo get_template_directory_uri(); ?>/images/2023-annual-report.jpg" alt="Product 1">
				<p>Zotek®</p>
			</div>
			<div class="swiper-slide">
				<img src="<?php echo get_template_directory_uri(); ?>/images/2023-annual-report.jpg" alt="Product 1">
				<p>Zotek®</p>
			</div>
		</div>
		<div class="cont-m">
			<div class="multi-swiper-scrollbar"></div>
		</div>
	</div>

	<script type="text/javascript">
		document.addEventListener('DOMContentLoaded', function () {
			// Carousel 4 - Multi-Item Carousel
			const swiperMultiItem = new Swiper('.multi-item-carousel', {
				loop: true,
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
	.multi-swiper-scrollbar {
		height: 8px; /* Adjust the height of the scrollbar */
		background: rgba(0, 0, 0, 0.15); /* Background of the scrollbar */
		margin-top: 30px;
	}

	.swiper-scrollbar-drag {
		background: #3B82F6; /* Color of the draggable part */
		height: 8px;
	}
	</style>



