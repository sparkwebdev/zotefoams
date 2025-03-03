
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
					<div class="swiper-slide-inner">
						<div class="date-wrapper">
							<label class="date">23</label>
							<label class="month-year">January 2025</label>
						</div>
						<p>Year-end trading update</p>
					</div>
				</div>
				<div class="swiper-slide">
					<div class="swiper-slide-inner">
						<div class="date-wrapper">
							<label class="date">18</label>
							<label class="month-year">March 2025</label>
						</div>
						<p>Announcemnt of 2025 preliminary results</p>
					</div>
				</div>
				<div class="swiper-slide">
					<div class="swiper-slide-inner">
						<div class="date-wrapper">
							<label class="date">22</label>
							<label class="month-year">May 2025</label>
						</div>
						<p>2025 Annual General Meeting and trading update</p>
					</div>
				</div>
				<div class="swiper-slide">
					<div class="swiper-slide-inner">
						<div class="date-wrapper">
							<label class="date">02</label>
							<label class="month-year">June 2025</label>
						</div>
						<p>Payment of final dividend for 2024</p>
					</div>
				</div>
				<div class="swiper-slide">
					<div class="swiper-slide-inner">
						<div class="date-wrapper">
							<label class="date">14</label>
							<label class="month-year">July 2025</label>
						</div>
						<p>Year-end trading update</p>
					</div>
				</div>
				<div class="swiper-slide">
					<div class="swiper-slide-inner">
						<div class="date-wrapper">
							<label class="date">23</label>
							<label class="month-year">August 2025</label>
						</div>
						<p>Year-end trading update</p>
					</div>
				</div>
				<div class="swiper-slide">
					<div class="swiper-slide-inner">
						<div class="date-wrapper">
							<label class="date">07</label>
							<label class="month-year">September 2025</label>
						</div>
						<p>Year-end trading update</p>
					</div>
				</div>
			</div>
		</div>
		<label class="calendar-carousel-note">All future dates are indicative and subject to change</label>
	</div>

	<script type="text/javascript">
		document.addEventListener('DOMContentLoaded', function () {
			// Carousel 5 - Calendar Carousel
			const swiperCalendar = new Swiper('.calendar-carousel', {
				loop: false,
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


	<style type="text/css">
	/* 11 - Calendar carousel
	--------------------------------------------- */

		.calendar-carousel .swiper-slide-inner {
			height: 120px;
			padding: 0px 10px;
		}

		.calendar-carousel .date-wrapper {
			float: left;
			margin-left: 10px;
			margin-right: 20px;
		}

			.calendar-carousel .date-wrapper > * {
				display: block;
			}

			.calendar-carousel .date-wrapper .date {
				font-size: 4.7em;
				margin-top: -7px;
				font-weight: lighter;
			}

			.calendar-carousel .date-wrapper .month-year {
				margin-top: -20px;
				font-size: 0.9em;
			}

		.calendar-carousel p {
			margin-top: 20px;
			font-size: 0.9em;
			color: #707070;
		}

		.calendar-carousel-note {
			color: #B5B5B5;
			margin-top: 15px;
			margin-left: 2px;
			font-size: 0.9em;
			display: block;
		}

	</style>