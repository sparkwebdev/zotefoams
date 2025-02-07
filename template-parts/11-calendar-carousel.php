
	<!-- Carousel 5 - Calendar Carousel -->
	<div class="cont-m padding-t-b-100">
		<div class="title-strip margin-b-30">
			<h3 class="fs-500 fw-600">Financial Calendar</h3>
			<!-- Navigation -->
			<div class="carousel-navigation black">
				<div class="carousel-navigation-inner">
					<div class="calendar-swiper-button-prev">
						<img src="<?php echo get_template_directory_uri(); ?>/images/left-arrow-black.svg" />
					</div>
					<div class="calendar-swiper-button-next">
						<img src="<?php echo get_template_directory_uri(); ?>/images/right-arrow-black.svg" />
					</div>
				</div>
			</div>
		</div>
		<div class="swiper calendar-carousel">
			<div class="swiper-wrapper">
				<div class="swiper-slide">
					<div class="swiper-slide-inner padding-20">
						<p>23rd January 2025</p>
						<p>Year-end trading update</p>
					</div>
				</div>
				<div class="swiper-slide">
					<div class="swiper-slide-inner padding-20">
						<p>23rd January 2025</p>
						<p>Announcement of 2025 preliminary results</p>
					</div>
				</div>
				<div class="swiper-slide">
					<div class="swiper-slide-inner padding-20">
						<p>23rd January 2025</p>
						<p>2025 Annual General Meeting and trading update</p>
					</div>
				</div>
				<div class="swiper-slide">
					<div class="swiper-slide-inner padding-20">
						<p>23rd January 2025</p>
						<p>Payment of final dividend for 2024</p>
					</div>
				</div>
				<div class="swiper-slide">
					<div class="swiper-slide-inner padding-20">
						<p>23rd January 2025</p>
						<p>Year-end trading update</p>
					</div>
				</div>
				<div class="swiper-slide">
					<div class="swiper-slide-inner padding-20">
						<p>23rd January 2025</p>
						<p>Announcement of 2025 preliminary results</p>
					</div>
				</div>
				<div class="swiper-slide">
					<div class="swiper-slide-inner padding-20">
						<p>23rd January 2025</p>
						<p>2025 Annual General Meeting and trading update</p>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		document.addEventListener('DOMContentLoaded', function () {
			// Carousel 5 - Calendar Carousel
			const swiperCalendar = new Swiper('.calendar-carousel', {
				loop: true,
				slidesPerView: 4,
				pagination: {
					el: '.swiper-pagination',
					clickable: true,
				},
				navigation: {
					prevEl: '.calendar-swiper-button-prev',
					nextEl: '.calendar-swiper-button-next',
				},
			});
		});
	</script>