/**
 * Carousel Initialization Component
 * Initializes all Swiper carousels on the page
 */
import { ZotefoamsReadyUtils } from '../utils/dom-utilities.js';

const SLIDE_ANIMATION_STAGGER_MS = 200;
const SLIDE_INIT_DELAY_MS = 300;
const SLIDE_INIT_FIRST_STAGGER_MS = 100;
const AUTOPLAY_DELAY_MS = 5000;

function initCarousels() {
	if ( typeof Swiper === 'undefined' ) {
		return;
	}

	// Image Banner Carousel (b1-Image-banner)
	const imageBannerCarousels = document.querySelectorAll( '.swiper-image' );
	imageBannerCarousels.forEach( ( carousel ) => {
		const ringDuration = `${ AUTOPLAY_DELAY_MS / 1000 }s linear`;
		const circleEl = carousel.querySelector( '.circle-progress-image' );
		const nextBtn = carousel.querySelector( '.swiper-button-next-image' );
		const nextLabel = nextBtn?.querySelector( 'span' );

		const resetCircle = () => {
			if ( ! circleEl ) { return; }
			circleEl.style.transition = 'none';
			circleEl.style.strokeDashoffset = '144.513';
			requestAnimationFrame( () => requestAnimationFrame( () => {
				circleEl.style.transition = `stroke-dashoffset ${ ringDuration }`;
				circleEl.style.strokeDashoffset = '0';
			} ) );
		};

		const pauseCircle = () => {
			if ( ! circleEl ) { return; }
			const current = getComputedStyle( circleEl ).strokeDashoffset;
			circleEl.style.transition = 'none';
			circleEl.style.strokeDashoffset = current;
		};

		const resumeCircle = () => {
			if ( ! circleEl ) { return; }
			requestAnimationFrame( () => {
				circleEl.style.transition = `stroke-dashoffset ${ ringDuration }`;
				circleEl.style.strokeDashoffset = '0';
			} );
		};

		const updateNextLabel = ( swiper ) => {
			if ( ! nextLabel ) { return; }
			const nextSlide = swiper.slides[ swiper.activeIndex + 1 ];
			const heading = nextSlide?.querySelector( 'h1, h2, h3' );
			nextLabel.textContent = heading ? heading.textContent.trim() : '';
		};

		const swiper = new Swiper( carousel, {
			loop: true,
			autoplay: {
				delay: AUTOPLAY_DELAY_MS,
				disableOnInteraction: false,
			},
			navigation: {
				nextEl: nextBtn,
				prevEl: carousel.querySelector( '.swiper-button-prev-image' ),
			},
			pagination: {
				el: carousel.querySelector( '.swiper-pagination' ),
				clickable: true,
			},
			speed: 600,
			on: {
				init() {
					setTimeout( () => {
						const activeSlide = this.slides[ this.activeIndex ];
						if ( ! activeSlide ) { return; }
						Array.from( activeSlide.querySelectorAll( '.animate__animated:not(.value)' ) )
							.forEach( ( el, index ) => {
								setTimeout( () => {
									el.classList.remove( 'is-anim-hidden' );
									el.classList.add( 'animate__fadeInDown' );
								}, index * SLIDE_ANIMATION_STAGGER_MS );
							} );
						resetCircle();
						updateNextLabel( this );
					}, SLIDE_INIT_DELAY_MS );
				},
				slideChangeTransitionStart() {
					this.slides.forEach( ( slide ) => {
						slide.querySelectorAll( '.animate__animated:not(.value)' ).forEach( ( el ) => {
							el.classList.add( 'is-anim-hidden' );
							el.classList.remove( 'animate__fadeInUp', 'animate__fadeInDown', 'animate__fadeInLeft', 'animate__fadeInRight' );
						} );
					} );
				},
				slideChangeTransitionEnd() {
					setTimeout( () => {
						const activeSlide = this.slides[ this.activeIndex ];
						if ( ! activeSlide ) { return; }
						Array.from( activeSlide.querySelectorAll( '.animate__animated:not(.value)' ) )
							.forEach( ( el, index ) => {
								setTimeout( () => {
									el.classList.remove( 'is-anim-hidden' );
									el.classList.add( 'animate__fadeInDown' );
								}, index * SLIDE_ANIMATION_STAGGER_MS );
							} );
						resetCircle();
						updateNextLabel( this );
					}, 50 );
				},
			},
		} );

		nextBtn?.addEventListener( 'mouseenter', () => {
			swiper.autoplay.stop();
			pauseCircle();
		} );
		nextBtn?.addEventListener( 'mouseleave', () => {
			swiper.autoplay.start();
			resumeCircle();
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
			const imageContainer = slide?.querySelector( '.multi-item-gallery-carousel__image' );
			const img = imageContainer?.querySelector( '.multi-item-gallery-carousel__slide-image' );

			if ( imageContainer ) {
				imageContainer.style.backgroundColor = pill.dataset.colour || '';
			}

			const newUrl = pill.dataset.imageUrl;
			const newAlt = pill.dataset.imageAlt;

			// No image for this pill — fade out whatever is currently showing
			if ( ! newUrl ) {
				if ( img ) {
					img.style.transition = 'opacity 0.25s ease';
					img.style.opacity = '0';
				}
				return;
			}

			// Switching to an image — no base img yet, create one and fade in directly
			if ( ! img ) {
				const newImg = new Image();
				newImg.src = newUrl;
				newImg.alt = newAlt;
				newImg.className = 'multi-item-gallery-carousel__slide-image';
				Object.assign( newImg.style, { opacity: '0', transition: 'opacity 0.25s ease' } );
				imageContainer.appendChild( newImg );
				requestAnimationFrame( () => {
					requestAnimationFrame( () => { newImg.style.opacity = '1'; } );
				} );
				newImg.addEventListener( 'transitionend', function onFadeIn( e ) {
					if ( e.propertyName !== 'opacity' ) {return;}
					newImg.removeEventListener( 'transitionend', onFadeIn );
					newImg.style.opacity = '';
					newImg.style.transition = '';
				} );
				return;
			}

			// Base img exists — ensure it is visible before crossfade
			img.style.transition = '';
			img.style.opacity = '1';

			// Crossfade: overlay new image fades in over the current one
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
			imageContainer.appendChild( overlay );

			// Double rAF ensures opacity:0 is painted before transition starts
			requestAnimationFrame( () => {
				requestAnimationFrame( () => { overlay.style.opacity = '1'; } );
			} );

			overlay.addEventListener( 'transitionend', function onFadeIn( transitionEvent ) {
				if ( transitionEvent.propertyName !== 'opacity' ) {return;}
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
