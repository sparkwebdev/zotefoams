// Multi Item Carousel
document.addEventListener( 'DOMContentLoaded', function() {
	/*
   *
   *
   * Carousel 1 - Image Banner Swiper
   *
   *
	*/

	document.querySelectorAll( '.swiper-image' ).forEach( ( carousel ) => {
		const swiperImage = new Swiper( carousel, {
			direction: 'horizontal',
			loop: true,
			speed: 400,
			autoplay: {
				delay: 50000,
				disableOnInteraction: false,
			},
			navigation: {
				nextEl: '.swiper-button-next-image',
			},
			on: {
				init() {
					updateNextButtonTitle( this, '.swiper-button-next-image p span' );
				},
				slideChangeTransitionStart( e ) {
					updateNextButtonTitle( e, '.swiper-button-next-image p span' );
					resetProgressAnimation( '.circle-progress-image' );
				},
			},
		} );

		function updateNextButtonTitle( swiperInstance, selector ) {
			const nextSlide = getNextSlide( swiperInstance );
			if ( nextSlide ) {
				const nextTitle = nextSlide.getAttribute( 'data-title' );
				const nextButtonText = document.querySelector( selector );
				if ( nextButtonText && nextTitle ) {
					nextButtonText.textContent = nextTitle;
				}
			}
		}

		function getNextSlide( swiperInstance ) {
			for	( let i = 0; i < swiperInstance.slides.length; i++ ) {
				if ( swiperInstance.slides[ i ].classList.contains( 'swiper-slide-next' ) ) {
					return swiperInstance.slides[ i ];
				}
			}
			return null;
		}

		function resetProgressAnimation( circleSelector ) {
			const progressCircle = document.querySelector( circleSelector );
			if ( progressCircle ) {
				progressCircle.style.transition = 'none';
				progressCircle.style.strokeDashoffset = 144.513;
				setTimeout( () => {
					progressCircle.style.transition = 'stroke-dashoffset 5s linear';
					progressCircle.style.strokeDashoffset = 0;
				}, 50 );
			}
		}
	} );

	/*
   *
   *
   * Carousel 2 - Dual Carousel
   *
   *
	*/
	// // Initialize the text carousel
	// var swiper = new Swiper(".mySwiper", {
	//   loop: true,
	//   navigation: {
	//     nextEl: ".swiper-button-next-dual-carousel2",
	//     prevEl: ".swiper-button-prev-dual-carousel2",
	//   },
	// });

	// Initialize the text carousel
	document.querySelectorAll( '.swiper-dual-carousel' ).forEach( ( carousel ) => {
		const text = carousel.querySelector( '.swiper-dual-carousel-text' );
		const image = carousel.querySelector( '.swiper-dual-carousel-image' );

		const swiperText = new Swiper( text, {
			loop: true,
			effect: 'fade',
			fadeEffect: {
				crossFade: true,
			},
			speed: 1000,
			navigation: {
				nextEl: '.swiper-button-next-dual-carousel',
				prevEl: '.swiper-button-prev-dual-carousel',
			},
		} );

		// Initialize the image carousel (without fade effect)
		const swiperImage = new Swiper( image, {
			loop: true,
			spaceBetween: 10,
		} );

		// Sync the carousels so that they move together
		swiperText.controller.control = swiperImage;
		swiperImage.controller.control = swiperText;
	} );

	/*
   *
   *
   * Carousel 3 - Split Carousel
   *
   *
	*/
	// Carousel 3 - Split Carousel
	document.querySelectorAll( '.swiper-split' ).forEach( ( carousel ) => {
		const swiperSplit = new Swiper( carousel, {
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
		} );
	} );

	/*
   *
   *
   * Carousel 4 - Multi-Item Carousel
   *
   *
	*/
	document.querySelectorAll( '.multi-item-carousel' ).forEach( ( carousel ) => {
		const totalSlides = carousel.querySelectorAll( '.swiper-slide' ).length; // Count the slides
		const swiperMultiItem = new Swiper( carousel, {
			loop: false,
			slidesPerView: 2,
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
			breakpoints: {
				0: {
					slidesPerView: 1,
				},
				640: {
					slidesPerView: Math.max( 2, Math.min( 2, totalSlides ) ),
				},
				1024: {
					slidesPerView: Math.max( 2, Math.min( 2, totalSlides ) ),
				},
			},
			// autoHeight: false,
			// on: {
			//     init: function () {
			//         equalizeSwiperSlideHeights();
			//     },
			//     slideChange: function () {
			//         equalizeSwiperSlideHeights();
			//     }
			// }
		} );
	} );

	/*
   *
   *
   * Carousel 5 - Calendar Carousel
   *
   *
  */
	document.querySelectorAll( '.calendar-carousel' ).forEach( ( carousel ) => {
		const swiperCalendar = new Swiper( carousel, {
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
		} );
	} );
} );
