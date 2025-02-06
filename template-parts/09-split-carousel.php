
	<!-- Carousel 3 - Split Carousel -->
	<div class="swiper-carousel split-carousel light-grey-bg">
		
		<!-- Navigation -->
		<div class="navigation-wrapper">
			<div class="carousel-navigation black">
				<div class="carousel-navigation-inner">
					<div class="split-swiper-button-prev">
						<img src="<?php echo get_template_directory_uri(); ?>/images/left-arrow-black.svg" />
					</div>
					<div class="split-swiper-button-next">
						<img src="<?php echo get_template_directory_uri(); ?>/images/right-arrow-black.svg" />
					</div>
				</div>
			</div>
			<div class="split-swiper-pagination"></div>
		</div>

		<div class="swiper swiper-split">
			<div class="swiper-wrapper">
				<div class="swiper-slide">
					<img src="<?php echo get_template_directory_uri(); ?>/images/values_courage.png" alt="Materials">
					<div class="swiper-slide-content">
						<p class="fs-100 margin-b-30 grey-text">Our values</p>
						<p class="fs-600 fw-bold">Courage.</p>
						<p class="fs-600 fw-medium grey-text margin-b-40">The courage to take bold action to ensure that we succeed in tackling our challenges and opportunities.</p>
						<a href="#" class="btn black outline">Learn More</a>
					</div>
				</div>
				<div class="swiper-slide">
					<img src="<?php echo get_template_directory_uri(); ?>/images/values_courage.png" alt="Materials">
					<div class="swiper-slide-content">
						<p class="fs-100 margin-b-30 grey-text">Our values</p>
						<p class="fs-600 fw-bold">Courage.</p>
						<p class="fs-600 fw-medium grey-text margin-b-40">The courage to take bold action to ensure that we succeed in tackling our challenges and opportunities.</p>
						<a href="#" class="btn black outline">Learn More</a>
					</div>
				</div>
			</div>
		</div>
		
	</div>

	<script type="text/javascript">
		document.addEventListener('DOMContentLoaded', function () {
			// Carousel 3 - Split Carousel
			const swiperSplit = new Swiper('.swiper-split', {
				loop: true,
				navigation: {
					prevEl: '.split-swiper-button-prev',
					nextEl: '.split-swiper-button-next',
				},
				pagination: {
					el: '.split-swiper-pagination',
					clickable: true,
				},
			});
			swiperSplit.controller.control = swiperSplit;
		});
	</script>