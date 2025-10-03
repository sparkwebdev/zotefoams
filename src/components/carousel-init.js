/**
 * Carousel Initialization Component
 * Initializes all Swiper carousels on the page
 */
import { ZotefoamsReadyUtils } from '../utils/dom-utilities.js';

function initCarousels() {
    // Image Banner Carousel (b1-Image-banner)
    const imageBannerCarousels = document.querySelectorAll('.swiper-image');
    imageBannerCarousels.forEach((carousel) => {
        const swiper = new Swiper(carousel, {
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: '.swiper-button-next-image',
                prevEl: '.swiper-button-prev-image',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            // Changed from 'fade' to default 'slide' effect
            speed: 600,
            on: {
                slideChangeTransitionStart: function() {
                    // Hide animated elements only in THIS carousel's slides
                    this.slides.forEach(slide => {
                        const animatedElements = slide.querySelectorAll('.animate__animated:not(.value)');
                        animatedElements.forEach(el => {
                            el.style.opacity = '0';
                            el.classList.remove('animate__fadeInUp', 'animate__fadeInDown', 'animate__fadeInLeft', 'animate__fadeInRight');
                        });
                    });
                },
                slideChangeTransitionEnd: function() {
                    // Animate elements in the active slide
                    const activeSlide = this.slides[this.activeIndex];
                    if (activeSlide) {
                        const animatedElements = activeSlide.querySelectorAll('.animate__animated:not(.value)');
                        animatedElements.forEach((el, index) => {
                            setTimeout(() => {
                                el.style.opacity = '1';
                                el.classList.add('animate__fadeInDown');
                            }, index * 200); // Stagger animations
                        });
                    }
                },
                init: function() {
                    // Animate elements in the initial slide
                    setTimeout(() => {
                        const activeSlide = this.slides[this.activeIndex];
                        if (activeSlide) {
                            const animatedElements = activeSlide.querySelectorAll('.animate__animated:not(.value)');
                            animatedElements.forEach((el, index) => {
                                setTimeout(() => {
                                    el.style.opacity = '1';
                                    el.classList.add('animate__fadeInDown');
                                }, index * 200);
                            });
                        }
                    }, 300); // Small delay for initialization
                }
            }
        });
    });

    // Dual Carousel
    const dualCarousels = document.querySelectorAll('.swiper-dual-carousel');
    dualCarousels.forEach((carousel) => {
        const textCarousel = carousel.querySelector('.swiper-dual-carousel-text');
        const imageCarousel = carousel.querySelector('.swiper-dual-carousel-image');
        
        if (!textCarousel || !imageCarousel) return;

        // Hide all animated elements initially
        const allAnimatedElements = textCarousel.querySelectorAll('.animate__animated:not(.value)');
        allAnimatedElements.forEach(el => {
            el.style.opacity = '0';
        });

        const textSwiper = new Swiper(textCarousel, {
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
                slideChangeTransitionStart: function() {
                    // Hide animated elements in non-active slides
                    this.slides.forEach((slide, slideIndex) => {
                        if (slideIndex !== this.activeIndex) {
                            const animatedElements = slide.querySelectorAll('.animate__animated:not(.value)');
                            animatedElements.forEach(el => {
                                el.style.opacity = '0';
                                el.classList.remove('animate__fadeInUp', 'animate__fadeInDown', 'animate__fadeInLeft', 'animate__fadeInRight');
                            });
                        }
                    });
                    
                    // Immediately animate elements in the active slide
                    const activeSlide = this.slides[this.activeIndex];
                    if (activeSlide) {
                        const animatedElements = activeSlide.querySelectorAll('.animate__animated:not(.value)');
                        animatedElements.forEach((el, index) => {
                            setTimeout(() => {
                                el.style.opacity = '1';
                                el.classList.add('animate__fadeInDown');
                            }, index * 200); // Stagger animations
                        });
                    }
                },
                slideChangeTransitionEnd: function() {
                    // Animation now happens on start, not end
                },
                init: function() {
                    // Animate elements in the initial slide immediately
                    const activeSlide = this.slides[this.activeIndex];
                    if (activeSlide) {
                        const animatedElements = activeSlide.querySelectorAll('.animate__animated:not(.value)');
                        animatedElements.forEach((el, index) => {
                            setTimeout(() => {
                                el.style.opacity = '1';
                                el.classList.add('animate__fadeInDown');
                            }, 100 + (index * 200)); // Small initial delay then stagger
                        });
                    }
                }
            }
        });

        const imageSwiper = new Swiper(imageCarousel, {
            loop: true,
            spaceBetween: 10,
        });

        // Sync carousels
        textSwiper.controller.control = imageSwiper;
        imageSwiper.controller.control = textSwiper;
    });

    // Multi Item Carousel
    const multiCarousels = document.querySelectorAll('.multi-item-carousel');
    multiCarousels.forEach((carousel) => {
        const totalSlides = carousel.querySelectorAll('.swiper-slide').length;
        new Swiper(carousel, {
            loop: false,
            slidesPerView: 2,
            spaceBetween: 20,
            navigation: {
                nextEl: '.multi-swiper-button-next',
                prevEl: '.multi-swiper-button-prev',
            },
            scrollbar: {
                el: '.multi-swiper-scrollbar',
                hide: false,
                draggable: false,
            },
            breakpoints: {
                0: {
                    slidesPerView: 1,
                },
                640: {
                    slidesPerView: Math.max(2, Math.min(2, totalSlides)),
                },
                1024: {
                    slidesPerView: Math.max(2, Math.min(2, totalSlides)),
                },
            },
        });
    });

    // Split Carousel
    const splitCarousels = document.querySelectorAll('.swiper-split');
    splitCarousels.forEach((carousel) => {
        new Swiper(carousel, {
            speed: 800,
            loop: true,
            navigation: {
                nextEl: '.split-swiper-button-next',
                prevEl: '.split-swiper-button-prev',
            },
            pagination: {
                el: '.split-swiper-pagination',
                clickable: true,
            },
        });
    });

    // Calendar Carousel
    const calendarCarousels = document.querySelectorAll('.calendar-carousel');
    calendarCarousels.forEach((carousel) => {
        new Swiper(carousel, {
            slidesPerView: 1,
            spaceBetween: 20,
            navigation: {
                nextEl: '.calendar-swiper-button-next',
                prevEl: '.calendar-swiper-button-prev',
            },
            breakpoints: {
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },
            },
        });
    });

    // Step Slider (if exists)
    const stepSliders = document.querySelectorAll('.step-slider .swiper');
    stepSliders.forEach((slider) => {
        new Swiper(slider, {
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
        });
    });
}

// Initialize when DOM is ready
ZotefoamsReadyUtils.ready(initCarousels);

export { initCarousels };