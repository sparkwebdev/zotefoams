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
					slideChange: function () {
						updateNextButtonTitle(this, '.swiper-button-next-image p span');
						resetProgressAnimation('.circle-progress-image');
					},
				},
			});

			function updateNextButtonTitle(swiperInstance, selector) {
				const nextSlide = swiperInstance.slides[(swiperInstance.realIndex + 1) % swiperInstance.slides.length];
				const nextTitle = nextSlide.getAttribute('data-title');
				const nextButtonText = document.querySelector(selector);
				if (nextButtonText && nextTitle) {
					nextButtonText.textContent = nextTitle;
				}
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







