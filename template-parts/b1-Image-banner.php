	<!-- Carousel 1 - image banner -->
	<div class="image-banner swiper swiper-image">
		<div class="swiper-wrapper">
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
		</div>

		<!-- Navigation -->
		<div class="swiper-button-next swiper-button-next-image white-text">
			<p class="fw-regular fs-100 uppercase margin-r-15">NEXT: <span class="fw-bold"></span></p>
			<div class="circle-container">
				<svg class="circle-svg" viewBox="0 0 50 50" xmlns="http://www.w3.org/2000/svg">
					<circle class="circle-background" cx="25" cy="25" r="23"></circle>
					<circle class="circle-progress circle-progress-image" cx="25" cy="25" r="23"></circle>
				</svg>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		// Carousel 1 - image banner
		document.addEventListener('DOMContentLoaded', function () {
			// Image Banner Swiper
			const swiperImage = new Swiper('.swiper-image', {
				direction: 'horizontal',
				loop: true,
				speed: 400,
				autoplay: {
					delay: 3000,
					disableOnInteraction: false,
				},
				navigation: {
					nextEl: '.swiper-button-next-image',
				},
				on: {
					init: function () {
						updateNextButtonTitle(this, '.swiper-button-next-image p span');
					},
					slideChangeTransitionStart : function (e) {
						updateNextButtonTitle(e, '.swiper-button-next-image p span');
						resetProgressAnimation('.circle-progress-image');
					},
				},
			});

			function updateNextButtonTitle(swiperInstance, selector) {
				const nextSlide = getNextSlide(swiperInstance);
				if (nextSlide) {
					const nextTitle = nextSlide.getAttribute('data-title');
					const nextButtonText = document.querySelector(selector);
					if (nextButtonText && nextTitle) {
						nextButtonText.textContent = nextTitle;
					}
				}
			}

			function getNextSlide(swiperInstance) {
				for	(let i = 0; i < swiperInstance.slides.length; i++) {
					if (swiperInstance.slides[i].classList.contains('swiper-slide-next')) {
						return swiperInstance.slides[i];
					}
				}
				return null;
			}

			function resetProgressAnimation(circleSelector) {
				const progressCircle = document.querySelector(circleSelector);
				if (progressCircle) {
					progressCircle.style.transition = 'none';
					progressCircle.style.strokeDashoffset = 144.513;
					setTimeout(() => {
						progressCircle.style.transition = 'stroke-dashoffset 3s linear';
						progressCircle.style.strokeDashoffset = 0;
					}, 50);
				}
			}
		});
	</script>


	<style type="text/css">

		/* b1 - Image banner
		--------------------------------------------- */
		.swiper-image {
			width: 100%;
			height: 72vh;
			position: relative;
			max-height: 900px;
		}
		.swiper-image .swiper-slide {
			justify-content: center;
			align-items: center;
			background-color: #ddd;
		}
		.swiper-image .swiper-inner {
			display: flex;
			flex-direction: column;
			justify-content: space-between;
			height: calc(100% - 100px);
			position: relative;
			z-index: 1;
		}
		.swiper-image .swiper-inner h1 {
			max-width: 1100px;
		}
		.swiper-image .swiper-inner p {
			max-width: 750px;
		}
		.swiper-image .swiper-slide .overlay {
			background: linear-gradient(90deg, rgba(0,0,0,0.5) 0%, rgba(0,0,0,0) 100%);
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
		}

		/* Next Button Styles */
		.swiper-image .swiper-button-next-image {
			position: absolute;
			bottom: 50px;
			right: 50px;
			z-index: 2;
			display: flex;
			align-items: center;
			transition: opacity 0.3s;
			top: auto;
			width: auto;
			height: auto;
			margin: auto;
			cursor: auto;
			justify-content: unset;
			color: #fff;
		}

		.swiper-image .swiper-button-next:after, .swiper-rtl .swiper-button-prev:after {
			content: '';
		}

		.swiper-image .swiper-button-next-image:hover {
			cursor: pointer;
			opacity: 0.8;
		}

		/* Circle Container */
		.circle-container {
			width: 50px;
			height: 50px;
			display: flex;
			justify-content: center;
			align-items: center;
			pointer-events: none;
			z-index: 1;
		}
		.circle-svg {
			transform: rotate(-90deg);
			width: 100%;
			height: 100%;
		}
		.circle-background {
			fill: none;
			stroke: #ddd;
			stroke-width: 2;
		}
		.circle-progress {
			fill: none;
			stroke: #007bff;
			stroke-width: 2;
			stroke-dasharray: 144.513;
			stroke-dashoffset: 144.513;
			transition: stroke-dashoffset 3s linear;
		}
		.swiper-slide-active .circle-progress {
			stroke-dashoffset: 0;
		}
		.swiper-slide-next .circle-progress,
		.swiper-slide-prev .circle-progress {
			stroke-dashoffset: 144.513;
		}

	</style>




