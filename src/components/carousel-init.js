/**
 * Carousel Initialization Component
 * Initializes all Swiper carousels on the page
 */
import { ZotefoamsReadyUtils } from '../utils/dom-utilities.js';

const SLIDE_ANIMATION_STAGGER_MS = 200;
const SLIDE_INIT_DELAY_MS = 300;
const SLIDE_INIT_FIRST_STAGGER_MS = 100;

function initCarousels() {
	if ( typeof Swiper === 'undefined' ) {
		return;
	}

	// Image Banner Carousel (b1-Image-banner)
	const imageBannerCarousels = document.querySelectorAll( '.swiper-image' );
	imageBannerCarousels.forEach( ( carousel ) => {
		const swiper = new Swiper( carousel, {
			loop: true,
			autoplay: {
				delay: 5000,
				disableOnInteraction: false,
			},
			navigation: {
				nextEl: carousel.querySelector( '.swiper-button-next-image' ),
				prevEl: carousel.querySelector( '.swiper-button-prev-image' ),
			},
			pagination: {
				el: carousel.querySelector( '.swiper-pagination' ),
				clickable: true,
			},
			// Changed from 'fade' to default 'slide' effect
			speed: 600,
			on: {
				init() {
					// Cache animated elements per slide once on init
					this._animatedEls = this.slides.map( ( slide ) =>
						Array.from( slide.querySelectorAll( '.animate__animated:not(.value)' ) )
					);

					// Animate elements in the initial slide
					setTimeout( () => {
						const els = this._animatedEls[ this.activeIndex ] || [];
						els.forEach( ( el, index ) => {
							setTimeout( () => {
								el.classList.remove( 'is-anim-hidden' );
								el.classList.add( 'animate__fadeInDown' );
							}, index * SLIDE_ANIMATION_STAGGER_MS );
						} );
					}, SLIDE_INIT_DELAY_MS );
				},
				slideChangeTransitionStart() {
					// Hide animated elements in all slides using cached references
					this._animatedEls?.forEach( ( els ) => {
						els.forEach( ( el ) => {
							el.classList.add( 'is-anim-hidden' );
							el.classList.remove( 'animate__fadeInUp', 'animate__fadeInDown', 'animate__fadeInLeft', 'animate__fadeInRight' );
						} );
					} );
				},
				slideChangeTransitionEnd() {
					// Animate elements in the active slide using cached references
					const els = this._animatedEls?.[ this.activeIndex ] || [];
					els.forEach( ( el, index ) => {
						setTimeout( () => {
							el.classList.remove( 'is-anim-hidden' );
							el.classList.add( 'animate__fadeInDown' );
						}, index * SLIDE_ANIMATION_STAGGER_MS );
					} );
				},
			},
		} );
	} );

	// Dual Carousel
	const dualCarousels = document.querySelectorAll( '.swiper-dual-carousel' );
	dualCarousels.forEach( ( carousel ) => {
		const textCarousel = carousel.querySelector( '.swiper-dual-carousel-text' );
		const imageCarousel = carousel.querySelector( '.swiper-dual-carousel-image' );

		if ( ! textCarousel || ! imageCarousel ) {
			return;
		}

		// Hide all animated elements initially
		const allAnimatedElements = textCarousel.querySelectorAll( '.animate__animated:not(.value)' );
		allAnimatedElements.forEach( ( el ) => {
			el.classList.add( 'is-anim-hidden' );
		} );

		// Shared animation function for text carousel slides
		const animateTextSlide = ( swiper ) => {
			// Hide animated elements in non-active slides
			swiper.slides.forEach( ( slide, slideIndex ) => {
				if ( slideIndex !== swiper.activeIndex ) {
					const animatedElements = slide.querySelectorAll( '.animate__animated:not(.value)' );
					animatedElements.forEach( ( el ) => {
						el.classList.add( 'is-anim-hidden' );
						el.classList.remove( 'animate__fadeInUp', 'animate__fadeInDown', 'animate__fadeInLeft', 'animate__fadeInRight' );
					} );
				}
			} );

			// Immediately animate elements in the active slide
			const activeSlide = swiper.slides[ swiper.activeIndex ];
			if ( activeSlide ) {
				const animatedElements = activeSlide.querySelectorAll( '.animate__animated:not(.value)' );
				animatedElements.forEach( ( el, index ) => {
					setTimeout( () => {
						el.classList.remove( 'is-anim-hidden' );
						el.classList.add( 'animate__fadeInDown' );
					}, index * SLIDE_ANIMATION_STAGGER_MS );
				} );
			}
		};

		const textSwiper = new Swiper( textCarousel, {
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
			on: {
				slideChangeTransitionStart() {
					animateTextSlide( this );
				},
				init() {
					// Animate elements in the initial slide immediately
					const activeSlide = this.slides[ this.activeIndex ];
					if ( activeSlide ) {
						const animatedElements = activeSlide.querySelectorAll( '.animate__animated:not(.value)' );
						animatedElements.forEach( ( el, index ) => {
							setTimeout( () => {
								el.classList.remove( 'is-anim-hidden' );
								el.classList.add( 'animate__fadeInDown' );
							}, SLIDE_INIT_FIRST_STAGGER_MS + ( index * SLIDE_ANIMATION_STAGGER_MS ) );
						} );
					}
				},
			},
		} );

		const imageSwiper = new Swiper( imageCarousel, {
			loop: true,
			spaceBetween: 10,
			on: {
				slideChangeTransitionStart() {
					animateTextSlide( textSwiper );
				},
			},
		} );

		// Sync carousels
		textSwiper.controller.control = imageSwiper;
		imageSwiper.controller.control = textSwiper;
	} );

	// Multi Item Carousel
	const multiCarousels = document.querySelectorAll( '.multi-item-carousel' );
	multiCarousels.forEach( ( carousel ) => {
		const container = carousel.closest( '.multi-item-carousel-container' );
		new Swiper( carousel, {
			loop: false,
			slidesPerView: 2,
			spaceBetween: 20,
			navigation: {
				nextEl: container?.querySelector( '.multi-swiper-button-next' ),
				prevEl: container?.querySelector( '.multi-swiper-button-prev' ),
			},
			scrollbar: {
				el: carousel.querySelector( '.multi-swiper-scrollbar' ),
				hide: false,
				draggable: false,
			},
			breakpoints: {
				0: {
					slidesPerView: 1,
				},
				640: {
					slidesPerView: 2,
				},
				1024: {
					slidesPerView: 2,
				},
			},
		} );
	} );

	// Multi Item Gallery Carousel
	const galleryItemCarousels = document.querySelectorAll( '.multi-item-gallery-carousel' );
	galleryItemCarousels.forEach( ( carousel ) => {
		const container = carousel.closest( '.multi-item-gallery-carousel-container' );
		new Swiper( carousel, {
			loop: false,
			slidesPerView: 1,
			spaceBetween: 20,
			navigation: {
				nextEl: container?.querySelector( '.multi-gallery-swiper-button-next' ),
				prevEl: container?.querySelector( '.multi-gallery-swiper-button-prev' ),
			},
			scrollbar: {
				el: carousel.querySelector( '.multi-gallery-swiper-scrollbar' ),
				hide: false,
				draggable: false,
			},
		} );
	} );

	// Multi Item Gallery Carousel — preload non-default pill images
	document.querySelectorAll( '.multi-item-gallery-carousel__pill:not(.active)' ).forEach( ( pill ) => {
		if ( pill.dataset.imageUrl ) {
			const preload = new Image();
			preload.src = pill.dataset.imageUrl;
		}
	} );

	// Multi Item Gallery Carousel — pill image switcher with crossfade
	document.querySelectorAll( '.multi-item-gallery-carousel__pills' ).forEach( ( pillGroup ) => {
		pillGroup.addEventListener( 'click', ( e ) => {
			const pill = e.target.closest( '.multi-item-gallery-carousel__pill' );
			if ( ! pill ) {return;}

			pillGroup.querySelectorAll( '.multi-item-gallery-carousel__pill' ).forEach( ( p ) => {
				p.classList.remove( 'active' );
				p.setAttribute( 'aria-pressed', 'false' );
			} );
			pill.classList.add( 'active' );
			pill.setAttribute( 'aria-pressed', 'true' );

			const slide = pill.closest( '.swiper-slide' );
			const img = slide?.querySelector( '.multi-item-gallery-carousel__slide-image' );
			if ( ! img ) {return;}

			const newUrl = pill.dataset.imageUrl;
			const newAlt = pill.dataset.imageAlt;

			// Crossfade: overlay new image fades in over the current one
			const container = img.parentElement;
			const overlay = new Image();
			overlay.src = newUrl;
			overlay.alt = newAlt;
			overlay.className = 'multi-item-gallery-carousel__slide-image';
			Object.assign( overlay.style, {
				position: 'absolute',
				inset: '0',
				opacity: '0',
				transition: 'opacity 0.25s ease',
			} );
			container.appendChild( overlay );

			// Double rAF ensures opacity:0 is painted before transition starts
			requestAnimationFrame( () => {
				requestAnimationFrame( () => { overlay.style.opacity = '1'; } );
			} );

			overlay.addEventListener( 'transitionend', function onFadeIn( e ) {
				if ( e.propertyName !== 'opacity' ) {return;}
				overlay.removeEventListener( 'transitionend', onFadeIn );

				// Swap base while overlay is fully opaque — no pop
				img.src = newUrl;
				img.alt = newAlt;

				// Fade overlay out (both layers identical at this point — seamless)
				overlay.style.opacity = '0';
				overlay.addEventListener( 'transitionend', function onFadeOut( f ) {
					if ( f.propertyName !== 'opacity' ) {return;}
					overlay.removeEventListener( 'transitionend', onFadeOut );
					overlay.remove();
				} );
			} );

			// Fallback: covers both transitions (2 × 250ms) with margin
			setTimeout( () => {
				if ( overlay.parentNode ) {
					img.src = newUrl;
					img.alt = newAlt;
					overlay.remove();
				}
			}, 600 );
		} );
	} );

	// Split Carousel
	const splitCarousels = document.querySelectorAll( '.swiper-split' );
	splitCarousels.forEach( ( carousel ) => {
		const container = carousel.closest( '.split-carousel' );
		new Swiper( carousel, {
			speed: 800,
			loop: true,
			navigation: {
				nextEl: container?.querySelector( '.split-swiper-button-next' ),
				prevEl: container?.querySelector( '.split-swiper-button-prev' ),
			},
			pagination: {
				el: container?.querySelector( '.split-swiper-pagination' ),
				clickable: true,
			},
		} );
	} );

	// Calendar Carousel
	const calendarCarousels = document.querySelectorAll( '.calendar-carousel' );
	calendarCarousels.forEach( ( carousel ) => {
		const container = carousel.closest( '.calendar-carousel-wrapper' )?.parentElement;
		new Swiper( carousel, {
			slidesPerView: 1,
			spaceBetween: 20,
			navigation: {
				nextEl: container?.querySelector( '.calendar-swiper-button-next' ),
				prevEl: container?.querySelector( '.calendar-swiper-button-prev' ),
			},
			breakpoints: {
				768: {
					slidesPerView: 2,
				},
				1024: {
					slidesPerView: 3,
				},
			},
		} );
	} );

	// Step Slider (if exists)
	const stepSliders = document.querySelectorAll( '.step-slider .swiper' );
	stepSliders.forEach( ( slider ) => {
		new Swiper( slider, {
			slidesPerView: 1,
			spaceBetween: 30,
			navigation: {
				nextEl: '.step-swiper-button-next',
				prevEl: '.step-swiper-button-prev',
			},
			pagination: {
				el: '.step-swiper-pagination',
				clickable: true,
			},
		} );
	} );
}

// Initialize when DOM is ready
ZotefoamsReadyUtils.ready( initCarousels );

export { initCarousels };
