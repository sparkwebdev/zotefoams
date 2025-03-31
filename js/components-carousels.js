document.addEventListener('DOMContentLoaded', function () {

	/**
	 * Initializes a Swiper instance with safe loop logic.
	 * Only disables loop if explicitly set to true and not enough slides.
	 */
	function initSwiperWithSafeLoop(carousel, config = {}) {
		const totalSlides = carousel.querySelectorAll('.swiper-slide').length;
		const slidesPerView = getSlidesPerView(config);

		if (config.loop === true && totalSlides <= slidesPerView) {
			config.loop = false;
		}

		return new Swiper(carousel, config);
	}

	function getSlidesPerView(config) {
		if (typeof config.slidesPerView === 'number') {
			return config.slidesPerView;
		}
		if (typeof config.breakpoints === 'object') {
			let maxSlides = 1;
			Object.values(config.breakpoints).forEach((bp) => {
				if (bp.slidesPerView && bp.slidesPerView > maxSlides) {
					maxSlides = bp.slidesPerView;
				}
			});
			return maxSlides;
		}
		return 1;
	}

	/*
	 *
	 * Carousel 1 - Image Banner Swiper
	 *
	 */
	document.querySelectorAll('.swiper-image').forEach((carousel) => {
		const config = {
			direction: 'horizontal',
			loop: true,
			speed: 400,
			autoplay: {
				delay: 5000,
				disableOnInteraction: false,
			},
			navigation: {
				nextEl: '.swiper-button-next-image',
			},
			on: {
				init() {
					updateNextButtonTitle(this, '.swiper-button-next-image p span');
					resetProgressAnimation('.circle-progress-image');
				},
				slideChangeTransitionStart(e) {
					updateNextButtonTitle(e, '.swiper-button-next-image p span');
					resetProgressAnimation('.circle-progress-image');
				},
			},
		};

		initSwiperWithSafeLoop(carousel, config);

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
			for (let i = 0; i < swiperInstance.slides.length; i++) {
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
					progressCircle.style.transition = 'stroke-dashoffset 5s linear';
					progressCircle.style.strokeDashoffset = 0;
				}, 50);
			}
		}
	});

	/*
	 *
	 * Carousel 2 - Dual Carousel
	 *
	 */
	document.querySelectorAll('.swiper-dual-carousel').forEach((carousel) => {
		const text = carousel.querySelector('.swiper-dual-carousel-text');
		const image = carousel.querySelector('.swiper-dual-carousel-image');

		const swiperText = initSwiperWithSafeLoop(text, {
			loop: true,
			effect: 'fade',
			fadeEffect: { crossFade: true },
			speed: 1000,
			navigation: {
				nextEl: '.swiper-button-next-dual-carousel',
				prevEl: '.swiper-button-prev-dual-carousel',
			},
		});

		const swiperImage = initSwiperWithSafeLoop(image, {
			loop: true,
		});

		swiperText.controller.control = swiperImage;
		swiperImage.controller.control = swiperText;
	});

	/*
	 *
	 * Carousel 3 - Split Carousel
	 *
	 */
	document.querySelectorAll('.swiper-split').forEach((carousel) => {
		const config = {
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
		};

		initSwiperWithSafeLoop(carousel, config);
	});

	/*
	 *
	 * Carousel 4 - Multi-Item Carousel (NO LOOP)
	 *
	 */
	document.querySelectorAll('.multi-item-carousel').forEach((carousel) => {
		const swiperMultiItem = new Swiper(carousel, {
			loop: false,
			slidesPerView: 2,
			spaceBetween: 20,
			navigation: {
				prevEl: '.multi-swiper-button-prev',
				nextEl: '.multi-swiper-button-next',
			},
			scrollbar: {
				el: '.multi-swiper-scrollbar',
				hide: false,
				draggable: false,
			},
			breakpoints: {
				0: { slidesPerView: 1 },
				640: { slidesPerView: 2 },
				1024: { slidesPerView: 2 },
			},
		});
	});

	/*
	 *
	 * Carousel 5 - Calendar Carousel (NO LOOP)
	 *
	 */
	document.querySelectorAll('.calendar-carousel').forEach((carousel) => {
		const swiperCalendar = new Swiper(carousel, {
			loop: false,
			pagination: {
				el: '.swiper-pagination',
				clickable: true,
			},
			navigation: {
				prevEl: '.calendar-swiper-button-prev',
				nextEl: '.calendar-swiper-button-next',
			},
			breakpoints: {
				1200: { slidesPerView: 4 },
				1023: { slidesPerView: 3 },
				767: { slidesPerView: 2 },
			},
		});
	});
});


// Animate on slide change
document.addEventListener('DOMContentLoaded', function () {
	const sliders = document.getElementsByClassName('swiper');
	if (sliders) {
		for (let i = 0; i < sliders.length; i++) {
			const swiper = sliders[i].swiper;
			if (swiper) {
				const currentSlide = swiper.slides[swiper.activeIndex];
				toggleAnimation(currentSlide, true);

				swiper.on('slideChange', function (e) {
					const previousSlide = swiper.slides[e.previousIndex];
					toggleAnimation(previousSlide, false);

					const currentSlide = swiper.slides[e.activeIndex];
					toggleAnimation(currentSlide, true);
				});
			}
		}
	}


    function toggleAnimation(parent, on) {
			let delay = 0;
			let elements = parent.getElementsByClassName('animate__animated');
		
			for (let i = 0; i < elements.length; i++) {
				let raw = elements[i].dataset.animation;
		
				// If attribute is present but empty, skip animation
				if (raw !== undefined && raw.trim() === '') {
					continue;
				}
		
				// If no data-animation attribute at all, fallback to default
				let animation = raw ?? 'animate__fadeInDown';
		
				delay = elements[i].dataset.animationdelay ?? (delay === 0 ? 0.2 : delay + 0.2);
		
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
			}
		}
		
		
		
});