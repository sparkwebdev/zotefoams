
	<!-- Carousel 3 - Split Carousel -->
	<div class="swiper-carousel split-carousel light-grey-bg">
		
		<!-- Navigation -->
		<div class="navigation-wrapper">
			<div class="split-swiper-pagination"></div>
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
		</div>

		<div class="swiper swiper-split">
			<div class="swiper-wrapper">
				<div class="swiper-slide">
					<img src="<?php echo get_template_directory_uri(); ?>/images/values_courage.png" alt="Materials">
					<div class="swiper-slide-content">
						<p class="animate__animated fs-100 margin-b-30 grey-text">Our values</p>
						<p class="animate__animated fs-600 fw-bold">Courage 1</p>
						<p class="animate__animated fs-600 fw-medium grey-text margin-b-40">The courage to take bold action to ensure that we succeed in tackling our challenges and opportunities.</p>
						<a href="#" class="animate__animated btn black outline">Learn More</a>
					</div>
					<div class="spacer"></div>
				</div>
				<div class="swiper-slide">
					<img src="<?php echo get_template_directory_uri(); ?>/images/values_courage.png" alt="Materials">
					<div class="swiper-slide-content">
						<p class="animate__animated fs-100 margin-b-30 grey-text"  data-animation="animate__fadeInLeft">More values</p>
						<p class="animate__animated fs-600 fw-bold"  data-animation="animate__fadeInLeft">Courage 2</p>
						<p class="animate__animated fs-600 fw-medium grey-text margin-b-40"  data-animation="animate__fadeInLeft">The courage to take bold action to ensure that we succeed in tackling our challenges and opportunities.</p>
						<a href="#" class="animate__animated btn black outline"  data-animation="animate__fadeInLeft">Learn More</a>
					</div>
					<div class="spacer"></div>
				</div>
				<div class="swiper-slide">
					<img src="<?php echo get_template_directory_uri(); ?>/images/values_courage.png" alt="Materials">
					<div class="swiper-slide-content">
						<p class="animate__animated fs-100 margin-b-30 grey-text" data-animation="animate__rollIn">Even more values</p>
						<p class="animate__animated fs-600 fw-bold" data-animation="animate__rollIn">Courage 3</p>
						<p class="animate__animated fs-600 fw-medium grey-text margin-b-40" data-animation="animate__rollIn">The courage to take bold action to ensure that we succeed in tackling our challenges and opportunities.</p>
						<a href="#" class="animate__animated btn black outline" data-animation="animate__rollIn">Learn More</a>
					</div>
					<div class="spacer"></div>
				</div>
			</div>
		</div>
		
	</div>

	<script type="text/javascript">
		document.addEventListener('DOMContentLoaded', function () {
			// Carousel 3 - Split Carousel
			const swiperSplit = new Swiper('.swiper-split', {
				speed: 800,
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


			swiperSplit.on('slideChange', function (e) {
				
				const previousSlide = swiperSplit.slides[e.previousIndex];
				toggleAnimation(previousSlide, false);

				const currentSlide = swiperSplit.slides[e.activeIndex];
				toggleAnimation(currentSlide, true);
			});


			function toggleAnimation(parent, on) {
				let elements = parent.getElementsByClassName('animate__animated');
				let delay = 0.3;
				for (let i = 0; i < elements.length; i++) {

					const animation = elements[i].dataset.animation ?? 'animate__fadeInDown';

					if (on) {
						elements[i].classList.add('hidden');
						if (!elements[i].classList.contains(animation)) {
							elements[i].style.setProperty('--animation-delay', delay + 's');
							elements[i].style.setProperty('-webkit-animation-delay', delay + 's');
							elements[i].classList.add(animation);
						}
					} else {
						elements[i].classList.remove(animation);
					}

					delay += 0.7;
				}
			}

		});
	</script>

	<style type="text/css">
	/* 09 - Split carousel
	--------------------------------------------- */

	.swiper-carousel.split-carousel {
		position: relative;
	}

		.swiper-carousel.split-carousel .navigation-wrapper {
			position: absolute;	
			bottom: 0px;
			right: 0px;
			z-index: 2;
    		width: 57vw;
			margin-bottom: 30px;
		}

		.swiper-carousel.split-carousel .navigation-wrapper .carousel-navigation-inner img {
			width: 60px;
			height: 60px;
		}

		.swiper-carousel.split-carousel .navigation-wrapper .split-swiper-pagination {
			text-align: right;
			margin-right: 16vw;
			width: auto;
			margin-top: 20px;
			float: right;
		}

			.swiper-carousel.split-carousel .navigation-wrapper .split-swiper-pagination .swiper-pagination-bullet {
				background-color: #000;
				opacity: 1;
				margin-left: 2px;
				margin-right: 2px;
			}

			.swiper-carousel.split-carousel .navigation-wrapper .split-swiper-pagination .swiper-pagination-bullet-active {
				background-color: #3B82F6;
			}

	.swiper-split .swiper-slide {
		display: flex;
		align-items:center;
		justify-content: center;
	}

	.swiper-split .swiper-slide > * {
		flex: 3 1 0;
		width: 0;
	}

	.swiper-split .swiper-slide-content {
		flex-direction: column;
		/* max-width:600px */
		margin-bottom: 120px;
	}

	.swiper-split .spacer {
		flex: 1 0 0;
	}

	.calendar-carousel .swiper-slide-inner {
		border:1px solid #ccc
	}
	</style>



