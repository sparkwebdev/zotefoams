(function () {
	'use strict';

	// =============================================================================
	// Zotefoams DOM Utilities - Core DOM manipulation utilities (ES Module)
	// =============================================================================

	/**
	 * DOM Selection Utilities - Centralized element selection patterns
	 */
	const ZotefoamsDOMUtils = {
		/**
		 * Safe querySelector with optional fallback
		 */
		select(selector, fallback = null) {
			const element = document.querySelector(selector);
			return element || fallback;
		},

		/**
		 * Safe querySelectorAll with guaranteed array return
		 */
		selectAll(selector) {
			return Array.from(document.querySelectorAll(selector));
		},

		/**
		 * Select elements with data attribute
		 */
		selectByData(attribute, value = null) {
			const selector = value ? `[data-${attribute}="${value}"]` : `[data-${attribute}]`;
			return this.selectAll(selector);
		},

		/**
		 * Safe closest selector with fallback
		 */
		closest(element, selector, fallback = null) {
			return element?.closest(selector) || fallback;
		},

		/**
		 * Check if element exists and is visible
		 */
		isVisible(element) {
			if (!element) return false;
			const rect = element.getBoundingClientRect();
			return rect.width > 0 && rect.height > 0;
		}
	};

	/**
	 * Event Handling Utilities - Centralized event management patterns
	 */
	const ZotefoamsEventUtils = {
		/**
		 * Add event listener with cleanup tracking
		 */
		on(element, event, handler, options = {}) {
			if (!element) return null;
			element.addEventListener(event, handler, options);
			return { element, event, handler };
		},

		/**
		 * Add event listener to multiple elements
		 */
		onAll(elements, event, handler, options = {}) {
			return elements.map(element => this.on(element, event, handler, options)).filter(Boolean);
		},

		/**
		 * Delegated event handling for dynamic content
		 */
		delegate(container, selector, event, handler) {
			return this.on(container, event, (e) => {
				const target = e.target.closest(selector);
				if (target && container.contains(target)) {
					handler.call(target, e);
				}
			});
		},

		/**
		 * One-time event listener
		 */
		once(element, event, handler) {
			return this.on(element, event, handler, { once: true });
		},

		/**
		 * Debounced event handler
		 */
		debounce(func, wait) {
			let timeout;
			return function executedFunction(...args) {
				const later = () => {
					clearTimeout(timeout);
					func(...args);
				};
				clearTimeout(timeout);
				timeout = setTimeout(later, wait);
			};
		}
	};

	/**
	 * CSS Class Utilities - Centralized class manipulation patterns
	 */
	const ZotefoamsClassUtils = {
		/**
		 * Toggle class on element safely
		 */
		toggle(element, className, force = undefined) {
			if (!element) return false;
			return element.classList.toggle(className, force);
		},

		/**
		 * Add class to element safely
		 */
		add(element, ...classNames) {
			if (!element) return;
			element.classList.add(...classNames);
		},

		/**
		 * Remove class from element safely
		 */
		remove(element, ...classNames) {
			if (!element) return;
			element.classList.remove(...classNames);
		},

		/**
		 * Toggle class on multiple elements
		 */
		toggleAll(elements, className, force = undefined) {
			return elements.map(element => this.toggle(element, className, force));
		},

		/**
		 * Add class to multiple elements
		 */
		addAll(elements, ...classNames) {
			elements.forEach(element => this.add(element, ...classNames));
		},

		/**
		 * Remove class from multiple elements
		 */
		removeAll(elements, ...classNames) {
			elements.forEach(element => this.remove(element, ...classNames));
		},

		/**
		 * Replace class on element
		 */
		replace(element, oldClass, newClass) {
			if (!element) return;
			this.remove(element, oldClass);
			this.add(element, newClass);
		}
	};

	/**
	 * Animation Utilities - Centralized animation patterns
	 */
	const ZotefoamsAnimationUtils = {
		/**
		 * Fade in element with class toggle
		 */
		fadeIn(element, className = 'fade-in') {
			if (!element) return;
			ZotefoamsClassUtils.add(element, className);
		},

		/**
		 * Fade out element with class toggle
		 */
		fadeOut(element, className = 'fade-in') {
			if (!element) return;
			ZotefoamsClassUtils.remove(element, className);
		},

		/**
		 * Show/hide element with smooth transition
		 */
		toggleVisibility(element, show) {
			if (!element) return;
			
			if (show) {
				element.style.display = 'block';
				element.style.opacity = '1';
				element.style.maxHeight = '1000px';
			} else {
				element.style.display = 'none';
				element.style.opacity = '0';
				element.style.maxHeight = '0';
			}
		},

		/**
		 * Smooth scroll to element
		 */
		scrollTo(element, offset = -80, behavior = 'smooth') {
			if (!element) return;
			const targetPosition = element.getBoundingClientRect().top + window.pageYOffset + offset;
			window.scrollTo({
				top: targetPosition,
				behavior: behavior
			});
		}
	};

	/**
	 * Touch/Device Detection Utilities
	 */
	const ZotefoamsDeviceUtils = {
		/**
		 * Detect touch device
		 */
		isTouchDevice() {
			return 'ontouchstart' in window || navigator.maxTouchPoints > 0 || navigator.msMaxTouchPoints > 0;
		},

		/**
		 * Get interaction event based on device
		 */
		getInteractionEvent() {
			return this.isTouchDevice() ? 'click' : 'hover';
		},

		/**
		 * Apply touch-specific classes
		 */
		initTouchSupport() {
			if (this.isTouchDevice()) {
				ZotefoamsClassUtils.add(document.body, 'touch-device');
			}
		}
	};

	/**
	 * Ready State Utilities - Centralized DOMContentLoaded patterns
	 */
	const ZotefoamsReadyUtils = {
		/**
		 * Execute function when DOM is ready
		 */
		ready(callback) {
			if (document.readyState === 'loading') {
				document.addEventListener('DOMContentLoaded', callback);
			} else {
				callback();
			}
		},

		/**
		 * Execute multiple functions when DOM is ready
		 */
		readyAll(callbacks) {
			this.ready(() => {
				callbacks.forEach(callback => {
					try {
						callback();
					} catch (error) {
						console.error('Ready callback error:', error);
					}
				});
			});
		}
	};

	// Initialize touch support on load
	ZotefoamsReadyUtils.ready(() => {
		ZotefoamsDeviceUtils.initTouchSupport();
	});

	/**
	 * Site Utilities
	 * Miscellaneous site-wide utility functions
	 */

	// Clickable URL elements
	function initClickableUrls() {
	  // Add a click event listener to all elements with the data-clickable-url attribute
	  document.querySelectorAll('[data-clickable-url]').forEach(function (article) {
	    const url = article.getAttribute('data-clickable-url');
	    if (url) {
	      const matchingChild = article.querySelector('[href="' + url + '"]');
	      if (matchingChild) {
	        article.addEventListener('click', function () {
	          matchingChild.click();
	        });
	      }
	    }
	  });
	}

	// Overlay fade-in effect
	function initOverlayFadeIn() {
	  if (document.querySelector('.overlay')) {
	    document.querySelector('.overlay').classList.add('fade-in');
	  }
	}

	// Dynamic iframe height adjustment
	function initIframeHeightAdjustment() {
	  window.addEventListener('message', function (event) {
	    var frames = document.getElementsByTagName('iframe');
	    for (var i = 0; i < frames.length; i++) {
	      if (frames[i].contentWindow === event.source) {
	        frames[i].style.height = event.data + 'px';
	        break;
	      }
	    }
	  });
	}

	// Header height CSS variable updater
	function initHeaderHeightUpdater() {
	  const updateHeaderHeight = () => {
	    const header = document.querySelector('[data-el-site-header]');
	    if (!header) return;

	    const headerHeight = header.offsetHeight;
	    document.documentElement.style.setProperty('--header-height', `${headerHeight}px`);
	  };

	  // Throttle resize events using requestAnimationFrame
	  let resizeTimeout = false;

	  const onResize = () => {
	    if (!resizeTimeout) {
	      resizeTimeout = true;
	      window.requestAnimationFrame(() => {
	        updateHeaderHeight();
	        resizeTimeout = false;
	      });
	    }
	  };

	  window.addEventListener('DOMContentLoaded', () => {
	    updateHeaderHeight(); // Initial run
	    document.body.classList.add('has-sticky-header');
	    window.addEventListener('resize', onResize); // Hook into resize once
	  });
	}

	// Initialize all site utilities when DOM is ready
	ZotefoamsReadyUtils.ready(() => {
	  initClickableUrls();
	  initOverlayFadeIn();
	  initIframeHeightAdjustment();
	  initHeaderHeightUpdater();
	});

	/**
	 * Navigation.js - Handles navigation menu functionality with accessibility support
	 * Original working implementation adapted for ES module system
	 */

	function initNavigation() {
	    // Detect touch devices, add a class to the body, and set megaNavMode accordingly
	    const isTouchDevice =
	        "ontouchstart" in window ||
	        navigator.maxTouchPoints > 0 ||
	        navigator.msMaxTouchPoints > 0;
	    if (isTouchDevice) {
	        document.body.classList.add("touch-device");
	    }

	    const siteNav = document.getElementById("site-navigation");
	    if (!siteNav) {
	        return;
	    }

	    const button = siteNav.querySelector("button");
	    const menu = siteNav.querySelector("ul");
	    const utilityMenu = document.querySelector(".utility-menu");
	    // Use "click" mode on touch devices, "hover" otherwise
	    const megaNavMode = isTouchDevice ? "click" : "hover";
	    const hoverDelay = 200; // ms

	    // Early returns for missing elements
	    if (!button || !menu) {
	        if (!menu && button) {
	            button.style.display = "none";
	        }
	        return;
	    }

	    menu.classList.add("nav-menu");

	    // Helper function: get focusable elements within a container
	    const getFocusable = (container) =>
	        Array.from(
	            container.querySelectorAll(
	                'a, button, input, textarea, select, [tabindex]:not([tabindex="-1"])'
	            )
	        );

	    const closeUtilityMenus = () => {
	        document
	            .querySelectorAll(".utility-menu .dropdown-active")
	            .forEach((el) => el.classList.remove("dropdown-active"));
	    };

	    // Global closeAll function
	    const closeAll = () => {
	        document
	            .querySelectorAll(".dropdown-active, .mega-menu.active")
	            .forEach((el) => el.classList.remove("dropdown-active", "active"));
	        const container = document.querySelector(".mega-menu-container");
	        if (container) {
	            container.classList.remove("active");
	        }
	        document
	            .querySelectorAll("[aria-controls]")
	            .forEach((link) => link.setAttribute("aria-expanded", "false"));
	    };

	    // Keyboard navigation within mega menus
	    const handleMegaKeyNav = (e) => {
	        const megaMenu = e.currentTarget;
	        if (e.key === "Escape") {
	            e.preventDefault();
	            megaMenu.classList.remove("active");
	            const container = document.querySelector(".mega-menu-container");
	            if (container && !container.querySelector(".mega-menu.active")) {
	                container.classList.remove("active");
	            }
	            if (megaMenu._topLink) {
	                megaMenu._topLink.focus();
	            }
	            megaMenu.removeEventListener("keydown", handleMegaKeyNav);
	        } else if (e.key === "Tab") {
	            const focusable = getFocusable(megaMenu);
	            const currentIndex = focusable.indexOf(document.activeElement);
	            if (
	                (e.shiftKey && currentIndex === 0) ||
	                (!e.shiftKey && currentIndex === focusable.length - 1)
	            ) {
	                e.preventDefault();
	                megaMenu.classList.remove("active");
	                const topLinks = Array.from(
	                    document.querySelectorAll("#menu-primary > li > a")
	                );
	                const index = topLinks.indexOf(megaMenu._topLink);
	                const targetLink = e.shiftKey
	                    ? index > 0
	                        ? topLinks[index - 1]
	                        : megaMenu._topLink
	                    : index < topLinks.length - 1
	                        ? topLinks[index + 1]
	                        : megaMenu._topLink;
	                targetLink.focus();
	                megaMenu.removeEventListener("keydown", handleMegaKeyNav);
	            }
	        }
	    };

	    // Set up dropdown/mega menu handlers
	    const setupDropdowns = (menuElement) => {
	        // Attach click event to .dropdown-toggle buttons to toggle submenus.
	        menuElement.querySelectorAll(".dropdown-toggle").forEach((toggle) => {
	            toggle.addEventListener("click", (e) => {
	                e.stopPropagation();
	                e.preventDefault();
	                const menuItem = toggle.parentNode;
	                menuItem.classList.toggle("dropdown-active");
	            });
	        });

	        // Process anchor links that have children.
	        menuElement
	            .querySelectorAll(".menu-item-has-children > a, [aria-controls]")
	            .forEach((link) => {
	                const controlId = link.getAttribute("aria-controls");
	                if (controlId) {
	                    // Mega menu branch
	                    const megaMenu = document.getElementById(controlId);
	                    if (!megaMenu) {
	                        return;
	                    }

	                    if (megaNavMode === "hover") {
	                        // For hover-enabled devices, attach hover and keyboard events.
	                        let hideTimer = null;
	                        const menuItem = link.parentNode;
	                        const clearTimer = () => {
	                            if (hideTimer) {
	                                clearTimeout(hideTimer);
	                                hideTimer = null;
	                            }
	                        };

	                        const showMenu = () => {
	                            clearTimer();
	                            closeUtilityMenus();
	                            megaMenu.classList.add("active");
	                            const container = document.querySelector(".mega-menu-container");
	                            if (container) {
	                                container.classList.add("active");
	                            }
	                            link.setAttribute("aria-expanded", "true");
	                            megaMenu._topLink = link;
	                            megaMenu.addEventListener("keydown", handleMegaKeyNav);
	                        };

	                        const hideMenu = () => {
	                            hideTimer = setTimeout(() => {
	                                megaMenu.classList.remove("active");
	                                const container = document.querySelector(".mega-menu-container");
	                                if (container && !container.querySelector(".mega-menu.active")) {
	                                    container.classList.remove("active");
	                                }
	                                link.setAttribute("aria-expanded", "false");
	                                megaMenu.removeEventListener("keydown", handleMegaKeyNav);
	                            }, hoverDelay);
	                        };

	                        menuItem.addEventListener("mouseenter", showMenu);
	                        menuItem.addEventListener("mouseleave", hideMenu);
	                        megaMenu.addEventListener("mouseenter", clearTimer);
	                        megaMenu.addEventListener("mouseleave", hideMenu);

	                        // Keyboard navigation for mega menus.
	                        link.addEventListener("keydown", (e) => {
	                            if (e.key === "Enter" || e.key === " " || e.key === "ArrowDown") {
	                                e.preventDefault();
	                                const isActive = megaMenu.classList.contains("active");
	                                if (isActive) {
	                                    if (e.key === "ArrowDown") {
	                                        const firstFocusable = getFocusable(megaMenu)[0];
	                                        if (firstFocusable) {
	                                            firstFocusable.focus();
	                                        }
	                                        return;
	                                    }
	                                    megaMenu.classList.remove("active");
	                                    const container = document.querySelector(".mega-menu-container");
	                                    if (container && !container.querySelector(".mega-menu.active")) {
	                                        container.classList.remove("active");
	                                    }
	                                    link.setAttribute("aria-expanded", "false");
	                                    megaMenu.removeEventListener("keydown", handleMegaKeyNav);
	                                } else {
	                                    closeUtilityMenus();
	                                    megaMenu.classList.add("active");
	                                    const container = document.querySelector(".mega-menu-container");
	                                    if (container) {
	                                        container.classList.add("active");
	                                    }
	                                    link.setAttribute("aria-expanded", "true");
	                                    megaMenu._topLink = link;
	                                    const heading = megaMenu.querySelector(".mega-menu-intro > h2");
	                                    if (heading) {
	                                        heading.setAttribute("tabindex", "-1");
	                                        setTimeout(() => heading.focus(), 200);
	                                    }
	                                    megaMenu.addEventListener("keydown", handleMegaKeyNav);
	                                }
	                            } else if (e.key === "Escape") {
	                                closeAll();
	                                link.focus();
	                            }
	                        });
	                    } else {
	                        // For touch devices in click mode, do not attach a click handler to the link,
	                        // so clicking the link will perform its default navigation.
	                        link.addEventListener("keydown", (e) => {
	                            if (e.key === "Enter" || e.key === " " || e.key === "ArrowDown") ; else if (e.key === "Escape") {
	                                closeAll();
	                                link.focus();
	                            }
	                        });
	                    }
	                } else {
	                    // Regular dropdown (typically utility menus).
	                    // If this menu item does NOT contain a .dropdown-toggle element, attach a click handler
	                    // to toggle the submenu. Otherwise, leave the link to act normally.
	                    if (!link.parentNode.querySelector('.dropdown-toggle')) {
	                        link.addEventListener("click", (e) => {
	                            e.preventDefault();
	                            const menuItem = link.parentNode;
	                            menuItem.classList.toggle("dropdown-active");
	                        });
	                    }
	                    link.addEventListener("keydown", (e) => {
	                        if (e.key === "Enter" || e.key === " " || e.key === "ArrowDown") {
	                            e.preventDefault();
	                            const menuItem = link.parentNode;
	                            menuItem.classList.toggle("dropdown-active");
	                            if (
	                                e.key === "ArrowDown" &&
	                                menuItem.classList.contains("dropdown-active")
	                            ) {
	                                const submenu = menuItem.querySelector("ul");
	                                if (submenu) {
	                                    const firstLink = submenu.querySelector("a");
	                                    if (firstLink) {
	                                        firstLink.focus();
	                                    }
	                                }
	                            }
	                        } else if (e.key === "Escape") {
	                            closeAll();
	                            link.focus();
	                        }
	                    });
	                }
	            });
	    };

	    // Set up event listeners for main navigation and utility menu
	    button.addEventListener("click", () => {
	        siteNav.classList.toggle("toggled");
	        const isExpanded = button.getAttribute("aria-expanded") === "true";
	        button.setAttribute("aria-expanded", (!isExpanded).toString());
	    });

	    setupDropdowns(siteNav);
	    if (utilityMenu) {
	        setupDropdowns(utilityMenu);
	    }

	    // Close menus when clicking outside
	    document.addEventListener("click", (e) => {
	        if (
	            !siteNav.contains(e.target) &&
	            !(utilityMenu && utilityMenu.contains(e.target))
	        ) {
	            closeAll();
	            siteNav.classList.remove("toggled");
	            button.setAttribute("aria-expanded", "false");
	        }
	    });

	    // Handle keyboard for standard links without aria-controls
	    document.querySelectorAll(".menu-item-has-children > a").forEach((link) => {
	        if (!link.getAttribute("aria-controls")) {
	            link.addEventListener("keydown", (e) => {
	                const menuItem = link.parentNode;
	                if (
	                    (e.key === "Enter" || e.key === " " || e.key === "ArrowDown") &&
	                    (menuItem.classList.contains("menu-item-has-children") ||
	                        menuItem.querySelector("ul"))
	                ) {
	                    e.preventDefault();
	                    menuItem.classList.toggle("dropdown-active");
	                    if (
	                        e.key === "ArrowDown" &&
	                        menuItem.classList.contains("dropdown-active")
	                    ) {
	                        const submenu = menuItem.querySelector("ul");
	                        if (submenu) {
	                            const firstLink = submenu.querySelector("a");
	                            if (firstLink) {
	                                firstLink.focus();
	                            }
	                        }
	                    }
	                } else if (e.key === "Escape") {
	                    closeAll();
	                    link.focus();
	                }
	            });
	        }
	    });
	}

	// Initialize when DOM is ready
	ZotefoamsReadyUtils.ready(initNavigation);

	/**
	 * Carousel Initialization Component
	 * Initializes all Swiper carousels on the page
	 */

	function initCarousels() {
	    // Image Banner Carousel (b1-Image-banner)
	    const imageBannerCarousels = document.querySelectorAll('.swiper-image');
	    imageBannerCarousels.forEach((carousel) => {
	        new Swiper(carousel, {
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
	            effect: 'fade',
	            fadeEffect: {
	                crossFade: true,
	            },
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
	    const calendarCarousels = document.querySelectorAll('.calendar-carousel .swiper');
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

	/**
	 * Tabbed Split Component
	 * Handles tab switching functionality with content panels
	 */

	function initTabbedSplit() {
		const tabs = ZotefoamsDOMUtils.selectAll('.tab');
		const tabContents = ZotefoamsDOMUtils.selectAll('.tab-content');

		ZotefoamsEventUtils.onAll(tabs, 'click', function() {
			// Remove active class from all tabs and contents
			ZotefoamsClassUtils.removeAll(tabs, 'active');
			ZotefoamsClassUtils.removeAll(tabContents, 'active');

			// Add active class to clicked tab and corresponding content
			ZotefoamsClassUtils.add(this, 'active');
			ZotefoamsClassUtils.add(ZotefoamsDOMUtils.select(`#${this.dataset.tab}`), 'active');
		});
	}

	// Initialize when DOM is ready
	ZotefoamsReadyUtils.ready(initTabbedSplit);

	/**
	 * Locations Map Component
	 * Handles interactive popups for location markers with touch/desktop support
	 */

	function showPopup(sender) {
		// Hide all other popups
		const allPopups = ZotefoamsDOMUtils.selectAll('.locations-map__popup');
		allPopups.forEach((popup) => {
			popup.style.display = 'none';
			ZotefoamsAnimationUtils.fadeOut(popup);
		});

		const popup = sender.querySelector('.locations-map__popup');
		if (popup) {
			if (CSS.supports('position', 'anchor')) {
				popup.style.position = 'anchor';
				sender.style.anchorName = '--popup-anchor';
			} else {
				popup.style.display = 'block';
				popup.style.position = 'absolute';
				popup.style.top = '100%';
				popup.style.left = '50%';
				popup.style.transform = 'translateX(-50%)';
			}
			ZotefoamsAnimationUtils.fadeIn(popup);
		}
	}

	function hideAllPopups() {
		const allPopups = ZotefoamsDOMUtils.selectAll('.locations-map__popup');
		allPopups.forEach((popup) => {
			popup.style.display = 'none';
			ZotefoamsAnimationUtils.fadeOut(popup);
		});
	}

	function initLocationsMap() {
		const locations = ZotefoamsDOMUtils.selectAll('.locations-map__location');

		locations.forEach((location) => {
			const popup = location.querySelector('.locations-map__popup');

			if (!popup) return;

			// 🖱 Desktop: Hover interaction
			if (!ZotefoamsDeviceUtils.isTouchDevice()) {
				ZotefoamsEventUtils.on(location, 'mouseenter', () => showPopup(location));
				ZotefoamsEventUtils.on(location, 'mouseleave', hideAllPopups);
			} 
			// 👆 Mobile: Tap interaction
			else {
				ZotefoamsEventUtils.on(location, 'click', (e) => {
					e.stopPropagation();
					showPopup(location);
				});
			}

			// Prevent popup click bubbling so it doesn't auto-close
			ZotefoamsEventUtils.on(popup, 'click', (e) => e.stopPropagation());
		});

		// 📲 Close popup when tapping outside (mobile)
		ZotefoamsEventUtils.on(document, 'click', (e) => {
			if (!e.target.closest('.locations-map__location')) {
				hideAllPopups();
			}
		});
	}

	// Initialize when DOM is ready
	ZotefoamsReadyUtils.ready(initLocationsMap);

	/**
	 * Data Points Component
	 * Animated counter values with intersection observer
	 */

	function animateValue(obj, start, end, duration, prefix, suffix, decimals) {
		let startTimestamp = null;
		const step = (timestamp) => {
			if (!startTimestamp) {
				startTimestamp = timestamp;
			}
			const progress = Math.min((timestamp - startTimestamp) / duration, 1);
			const value = (progress * (end - start) + start);

			// Format value with the correct decimals and remove trailing zeros
			let formattedValue = value.toFixed(decimals);
			if (decimals > 0 && !suffix.includes('%')) {
				formattedValue = formattedValue.replace(/\.?0+$/, '');
			}

			// Apply the formatted value
			obj.innerHTML = (prefix || '') + formattedValue + (suffix || '');

			if (progress < 1) {
				window.requestAnimationFrame(step);
			}
		};
		window.requestAnimationFrame(step);
	}

	function initDataPoints() {
		const observer = new IntersectionObserver((entries) => {
			entries.forEach((entry) => {
				if (entry.isIntersecting) {
					const values = entry.target.querySelectorAll('.value');

					values.forEach((valueElement) => {
						// Prevent re-animation
						if (valueElement.dataset.animated) {
							return;
						}

						const prefix = valueElement.dataset.prefix || '';
						const suffix = valueElement.dataset.suffix || '';
						const duration = parseInt(valueElement.dataset.duration) || 1100;
						const decimals = parseInt(valueElement.dataset.decimals) || 0;
						const to = parseFloat(valueElement.dataset.to) || 0;

						// Just animate the number, no additional CSS animation classes needed
						animateValue(valueElement, 0, to, duration, prefix, suffix, decimals);

						// Mark as animated
						valueElement.dataset.animated = 'true';
					});
				} else {
					// Allow re-animation if needed by removing flag
					const values = entry.target.querySelectorAll('.value');
					values.forEach((el) => {
						// Only reset animation flag when fully out of view for smoother experience
						if (entry.intersectionRatio <= 0) {
							el.removeAttribute('data-animated');
						}
					});
				}
			});
		}, { threshold: [0, 0.5] }); // Track both entering and fully visible states

		// Fix: Use the correct class name from your HTML
		const target = ZotefoamsDOMUtils.select('.data-points-items');
		if (target) {
			observer.observe(target);
		}
	}

	// Initialize when DOM is ready
	ZotefoamsReadyUtils.ready(initDataPoints);

	/**
	 * File List Component
	 * Enhanced file filtering with multiple filters, comma-separated values and keyboard support
	 */

	function initFileList() {
	  const fileListElements = document.querySelectorAll('[data-component="file-list"]');

	  if (fileListElements.length > 0) {
	    fileListElements.forEach(function (container) {
	      const filterButton = container.querySelector('#filter-toggle');
	      const filterOptions = container.querySelector('#filter-options');
	      const checkboxes = Array.from(container.querySelectorAll('.filter-options__checkbox'));
	      const showAllButton = container.querySelector('#file-list-show-all');
	      const fileItems = Array.from(container.querySelectorAll('.file-list__item'));

	      // Toggle the dropdown menu.
	      const toggleDropdown = (show) => {
	        filterOptions.classList.toggle('hidden', !show);
	        filterButton.classList.toggle('open', show);
	      };

	      // Keyboard support for the filter dropdown.
	      filterOptions.addEventListener('keydown', function (e) {
	        const KEY_UP = 38,
	          KEY_DOWN = 40,
	          KEY_ESCAPE = 27,
	          KEY_TAB = 9;
	        if (e.keyCode === KEY_DOWN) {
	          e.preventDefault();
	          let currentIndex = checkboxes.findIndex((cb) => cb === document.activeElement);
	          if (currentIndex === -1 || currentIndex === checkboxes.length - 1) {
	            checkboxes[0].focus();
	          } else {
	            checkboxes[currentIndex + 1].focus();
	          }
	        } else if (e.keyCode === KEY_UP) {
	          e.preventDefault();
	          let currentIndex = checkboxes.findIndex((cb) => cb === document.activeElement);
	          if (currentIndex <= 0) {
	            checkboxes[checkboxes.length - 1].focus();
	          } else {
	            checkboxes[currentIndex - 1].focus();
	          }
	        } else if (e.keyCode === KEY_ESCAPE) {
	          e.preventDefault();
	          toggleDropdown(false);
	          filterButton.focus();
	        } else if (e.keyCode === KEY_TAB) {
	          // Allow Tab to move focus, then close the dropdown if focus moves outside.
	          setTimeout(() => {
	            if (!filterOptions.contains(document.activeElement)) {
	              toggleDropdown(false);
	            }
	          }, 0);
	        }
	      });

	      // Gather active filters by filter type.
	      const getActiveFilters = () => {
	        const activeFilters = {};
	        checkboxes.forEach((cb) => {
	          const filterType = cb.dataset.filter;
	          if (cb.checked) {
	            if (!activeFilters[filterType]) activeFilters[filterType] = [];
	            activeFilters[filterType].push(cb.value);
	          }
	        });
	        return activeFilters;
	      };

	      // Filter file items: each item's data attribute may contain multiple values.
	      const filterFiles = () => {
	        // Get the tbody element inside the container.
	        const tbody = container.querySelector('tbody');

	        // Fade out the tbody.
	        tbody.style.transition = 'opacity 0.5s';
	        tbody.style.opacity = 0;

	        setTimeout(() => {
	          // Update the display of each file item based on the active filters.
	          const activeFilters = getActiveFilters();
	          fileItems.forEach((item) => {
	            let show = true;
	            for (const filterType in activeFilters) {
	              const dataValue = item.dataset[filterType] || '';
	              const itemValues = dataValue
	                .split(',')
	                .map((v) => v.trim())
	                .filter((v) => v !== '');
	              const intersection = activeFilters[filterType].filter((val) => itemValues.includes(val));
	              if (activeFilters[filterType].length && intersection.length === 0) {
	                show = false;
	                break;
	              }
	            }
	            if (show) {
	              item.classList.remove('filtered');
	            } else {
	              item.classList.add('filtered');
	            }
	          });
	          updateShowAllVisibility();

	          // Fade the tbody back in.
	          tbody.style.opacity = 1;
	        }, 200); // Wait 0.2 seconds for the fade-out transition.
	      };

	      // Update the URL query parameters with comma-separated filter values.
	      const updateURL = () => {
	        const activeFilters = getActiveFilters();
	        const params = new URLSearchParams();
	        for (const filterType in activeFilters) {
	          params.set(filterType, activeFilters[filterType].join(','));
	        }
	        let queryString = params.toString().replace(/%2C/gi, ',');
	        const newUrl = window.location.pathname + (queryString ? '?' + queryString : '');
	        window.history.replaceState({}, '', newUrl);
	      };

	      // Initialize filter checkboxes from the URL query parameters.
	      const initializeFiltersFromURL = () => {
	        const params = new URLSearchParams(window.location.search);
	        checkboxes.forEach((cb) => {
	          const filterType = cb.dataset.filter;
	          const valueParam = params.get(filterType);
	          if (valueParam) {
	            const values = valueParam
	              .split(',')
	              .map((v) => v.trim())
	              .filter((v) => v !== '');
	            if (values.includes(cb.value)) {
	              cb.checked = true;
	            }
	          }
	        });
	        filterFiles();
	      };

	      const updateShowAllVisibility = () => {
	        const totalSelected = checkboxes.filter((cb) => cb.checked).length;
	        if (showAllButton) {
	          showAllButton.classList.toggle('hidden', totalSelected === 0);
	        }
	        // Toggle the "filtered" class on the container (.file-list element)
	        container.classList.toggle('filtered', totalSelected > 0);
	      };

	      const resetFilters = () => {
	        checkboxes.forEach((cb) => (cb.checked = false));
	        toggleDropdown(false);
	        updateURL();
	        filterFiles();
	      };

	      if (filterButton) {
	        filterButton.addEventListener('click', (e) => {
	          e.stopPropagation();
	          const open = filterOptions.classList.contains('hidden');
	          toggleDropdown(open);
	          if (open && checkboxes.length > 0) {
	            // Set focus to the first checkbox when opening the dropdown.
	            checkboxes[0].focus();
	          }
	        });
	      }

	      checkboxes.forEach((cb) => {
	        cb.addEventListener('change', () => {
	          updateURL();
	          filterFiles();
	        });
	      });

	      showAllButton.addEventListener('click', resetFilters);

	      document.addEventListener('click', (e) => {
	        if (!container.contains(e.target)) {
	          toggleDropdown(false);
	        }
	      });

	      initializeFiltersFromURL();
	    });
	  }
	}

	// Initialize when DOM is ready
	ZotefoamsReadyUtils.ready(initFileList);

	/**
	 * Section List Component
	 * Gallery section filtering functionality
	 */

	function initSectionList() {
	  const sectionListElements = document.querySelectorAll('[data-component="section-list"]');

	  if (sectionListElements.length > 0) {
	    sectionListElements.forEach(function (article) {
	      const filterButton = article.querySelector('#filter-toggle');
	      const filterOptions = article.querySelector('#filter-options');
	      const checkboxes = [...article.querySelectorAll('.filter-options__checkbox')];
	      const showAllButton = article.querySelector('#section-list-show-all');
	      const sectionItems = [...article.querySelectorAll('.section-list__item')];
	      const toggleDropdown = (show) => {
	        filterOptions.classList.toggle('hidden', !show);
	        filterButton.classList.toggle('open', show);
	      };

	      const updateShowAllVisibility = () => {
	        const selectedCount = checkboxes.filter((cb) => cb.checked).length;
	        if (showAllButton) {
	          showAllButton.classList.toggle('hidden', selectedCount === 0 || selectedCount === checkboxes.length);
	        }
	      };

	      const filterSections = () => {
	        // Assume that sectionItems share the same parent container.
	        const sectionContainer = sectionItems.length > 0 ? sectionItems[0].parentNode : null;
	        if (!sectionContainer) return; // Exit if no container found.

	        // Fade out the container.
	        sectionContainer.style.transition = 'opacity 0.5s';
	        sectionContainer.style.opacity = 0;

	        setTimeout(() => {
	          // Get the selected labels from the checkboxes.
	          const selectedLabels = checkboxes.filter((cb) => cb.checked).map((cb) => cb.value);

	          // Update each section item's display based on the selected labels.
	          sectionItems.forEach((item) => {
	            item.style.display = selectedLabels.length === 0 || selectedLabels.includes(item.dataset.galleryLabel) ? 'block' : 'none';
	          });
	          updateShowAllVisibility();

	          // Fade the container back in.
	          sectionContainer.style.opacity = 1;
	        }, 200); // Wait 0.5 seconds to match the fade-out transition.
	      };

	      const resetFilters = () => {
	        sectionItems.forEach((item) => (item.style.display = 'block'));
	        checkboxes.forEach((cb) => (cb.checked = false));
	        toggleDropdown(false);
	        updateShowAllVisibility();
	      };

	      if (filterButton) {
	        filterButton.addEventListener('click', (e) => {
	          e.stopPropagation();
	          toggleDropdown(filterOptions.classList.contains('hidden'));
	        });
	      }

	      checkboxes.forEach((checkbox) => checkbox.addEventListener('change', filterSections));
	      if (showAllButton) {
	        showAllButton.addEventListener('click', resetFilters);
	      }

	      const dropdown = article.querySelector('.file-list__dropdown');
	      document.addEventListener('click', (e) => {
	        if (!dropdown.contains(e.target)) {
	          toggleDropdown(false);
	        }
	      });

	      updateShowAllVisibility();
	    });
	  }
	}

	// Initialize when DOM is ready
	ZotefoamsReadyUtils.ready(initSectionList);

	/**
	 * Video Modal Component
	 * YouTube video modal overlay with accessibility features
	 */

	function initVideoModal() {
	  const overlay = document.querySelector('[data-modal="video"]');
	  const iframe = document.querySelector('[data-video-iframe]');
	  const closeBtn = document.querySelector('[data-video-close]');
	  const triggers = document.querySelectorAll('[data-modal-trigger="video"]');
	  const mainPage = document.getElementById('page');

	  let lastFocusedElement = null;

	  function getYouTubeId(url) {
	    try {
	      const parsedUrl = new URL(url);
	      const videoId = new URLSearchParams(parsedUrl.search).get('v');
	      return videoId;
	    } catch (e) {
	      console.error('Invalid YouTube URL:', url);
	      return null;
	    }
	  }

	  function openOverlay(videoUrl) {
	    const videoId = getYouTubeId(videoUrl);
	    if (!videoId) return;

	    lastFocusedElement = document.activeElement;

	    iframe.src = `https://www.youtube.com/embed/${videoId}?autoplay=1`;
	    overlay.classList.add('is-visible');
	    overlay.setAttribute('aria-hidden', 'false');
	    mainPage?.setAttribute('aria-hidden', 'true');
	    document.body.classList.add('modal-open');
	    closeBtn?.focus();
	  }

	  function closeOverlay() {
	    overlay.classList.remove('is-visible');
	    overlay.setAttribute('aria-hidden', 'true');
	    iframe.src = '';
	    mainPage?.removeAttribute('aria-hidden');
	    document.body.classList.remove('modal-open');
	    if (lastFocusedElement) lastFocusedElement.focus();
	  }

	  // Bind open triggers
	  triggers.forEach(link => {
	    link.addEventListener('click', function (e) {
	      e.preventDefault();
	      const videoUrl = this.dataset.videoUrl;
	      openOverlay(videoUrl);
	    });
	  });

	  // Close on background click
	  overlay?.addEventListener('click', function (e) {
	    if (e.target === overlay) closeOverlay();
	  });

	  // Close on ESC key
	  document.addEventListener('keydown', function (e) {
	    if (e.key === 'Escape' && overlay.classList.contains('is-visible')) {
	      closeOverlay();
	    }
	  });

	  // Close on close button click
	  closeBtn?.addEventListener('click', closeOverlay);
	}

	// Initialize when DOM is ready
	ZotefoamsReadyUtils.ready(initVideoModal);

	/**
	 * Accordion Component
	 * Collapsible content sections with smooth animations and URL hash support
	 */

	function initAccordion() {
	  // Accordion
	  const headers = document.querySelectorAll('.accordion-header');

	  // Add click event listener to each header
	  headers.forEach((header) => {
	    header.addEventListener('click', function () {
	      const content = this.nextElementSibling; // The next sibling is the content
	      const icon = this.querySelector('.toggle-icon'); // Get the plus/minus icon

	      // Close all other accordion sections
	      headers.forEach((otherHeader) => {
	        if (otherHeader !== this) {
	          const otherContent = otherHeader.nextElementSibling;
	          const otherIcon = otherHeader.querySelector('.toggle-icon');
	          otherContent.style.display = 'none';
	          otherContent.style.opacity = '0';
	          otherContent.style.maxHeight = '0';
	          otherIcon.textContent = '+'; // Reset icon to plus
	          otherHeader.classList.remove('open'); // Remove 'open' class
	        }
	      });

	      // Toggle the display of the clicked content and icon
	      if (content.style.display === 'block') {
	        content.style.display = 'none';
	        content.style.opacity = '0';
	        content.style.maxHeight = '0';
	        icon.textContent = '+'; // Change the icon to plus
	        this.classList.remove('open');
	      } else {
	        content.style.display = 'block';
	        content.style.opacity = '1';
	        content.style.maxHeight = '1000px'; // Set a maximum height for the transition
	        icon.textContent = '-'; // Change the icon to minus
	        this.classList.add('open');

	        // Scroll the .accordion to the top of the page
	        const accordion = this.closest('.accordion'); // Get the .accordion container
	        accordion.scrollIntoView({
	          behavior: 'smooth',
	          block: 'start', // Scroll so the .accordion is at the top of the page
	        });
	      }
	    });
	  });

	  const hash = window.location.hash;
	  if (hash) {
	    const targetItem = document.querySelector(hash);
	    if (targetItem && targetItem.classList.contains("accordion-item")) {
	      const header = targetItem.querySelector(".accordion-header");
	      if (header) {
	        header.click(); // Simulate the user clicking the header
	        targetItem.scrollIntoView({ behavior: "smooth", block: "start" });

	      // === Remove hash from URL without refreshing the page
	        history.replaceState(null, document.title, window.location.pathname + window.location.search);

	      }
	    }
	  }
	}

	// Initialize when DOM is ready
	ZotefoamsReadyUtils.ready(initAccordion);

	/**
	 * Utility Search Component
	 * Enhanced search functionality in the utility menu with accessibility
	 */

	function initUtilitySearch() {
	  const menu = document.querySelector('#menu-utility');
	  const searchItem = menu?.querySelector('a[href="/search"]');

	  if (!menu || !searchItem) return;

	   // Add menu-item-has-children to the parent <li>
	  const searchItemParent = searchItem.parentElement;
	  searchItemParent?.classList.add('menu-item-has-children');

	  // Enhance searchItem for accessibility
	  searchItem.setAttribute('role', 'button');
	  searchItem.setAttribute('aria-expanded', 'false');
	  searchItem.setAttribute('aria-controls', 'utility-search-form');

	  // Create and inject the search container
	  const searchContainer = document.createElement('div');
	  searchContainer.className = 'utility-search';
	  searchContainer.id = 'utility-search-form';
	  searchContainer.setAttribute('hidden', '');

	  searchContainer.innerHTML = `
    <form role="search" aria-label="Site search form" action="/">
      <input type="text" name="s" placeholder="Search..." aria-label="Search input" required />
      <button type="submit" class="btn outline white">Go</button>
      <button type="button" class="btn outline white" aria-label="Close search form">✕</button>
    </form>
  `;

	  menu.after(searchContainer);

	  const form = searchContainer.querySelector('form');
	  const input = form.querySelector('input[type="text"]');
	  const closeButton = form.querySelector('button[type="button"]');
	  const nextMenuItem = searchItem.closest('li')?.nextElementSibling?.querySelector('a');

	  const openSearch = () => {
	    searchContainer.removeAttribute('hidden');
	    searchContainer.classList.add('is-visible');
	    searchItem.setAttribute('aria-expanded', 'true');
	  };

	  // Prevent scroll on input
	  input?.addEventListener("input", (e) => {
	    e.preventDefault();
	    window.scrollTo(window.scrollX, window.scrollY);
	  });

	  input?.addEventListener("keydown", (e) => {
	    // Store current scroll position
	    const currentScrollY = window.scrollY;
	    setTimeout(() => {
	      window.scrollTo(window.scrollX, currentScrollY);
	    }, 0);
	  });

	  const closeSearch = () => {
	    searchContainer.classList.remove('is-visible');
	    searchContainer.setAttribute('hidden', '');
	    searchItem.setAttribute('aria-expanded', 'false');
	  };

	  const toggleSearch = () => {
	    const isHidden = searchContainer.hasAttribute('hidden');
	    if (isHidden) {
	      openSearch();
	    } else {
	      closeSearch();
	    }
	  };

	  // Click to toggle
	  searchItem.addEventListener('click', (e) => {
	    e.preventDefault();
	    toggleSearch();
	  });

	  // Enter/Space key opens
	  searchItem.addEventListener('keydown', (e) => {
	    if (['Enter', ' '].includes(e.key)) {
	      e.preventDefault();
	      toggleSearch();
	    }
	  });

	  // Escape closes
	  window.addEventListener('keydown', (e) => {
	    if (e.key === 'Escape' && searchContainer.classList.contains('is-visible')) {
	      e.preventDefault();
	      closeSearch();
	      searchItem.focus();
	    }
	  });

	  // Close button click
	  closeButton?.addEventListener('click', () => {
	    closeSearch();
	    searchItem.focus();
	  });

	  // Focus trap exit logic
	  form.addEventListener('keydown', (e) => {
	    if (e.key !== 'Tab') return;

	    const focusable = Array.from(
	      form.querySelectorAll('input, button:not([disabled]), [tabindex]:not([tabindex="-1"])')
	    ).filter(el => !el.hasAttribute('hidden'));

	    const first = focusable[0];
	    const last = focusable[focusable.length - 1];

	    if (e.shiftKey && document.activeElement === first) {
	      // Shift+Tab on first element → close and focus "Search"
	      e.preventDefault();
	      closeSearch();
	      searchItem.focus();
	    } else if (!e.shiftKey && document.activeElement === last) {
	      // Tab on last element → close and focus next menu item
	      e.preventDefault();
	      closeSearch();
	      nextMenuItem?.focus();
	    }
	  });
	}

	// Initialize when DOM is ready
	ZotefoamsReadyUtils.ready(initUtilitySearch);

	// =============================================================================
	// Zotefoams Theme - Main Entry Point
	// Modern ES module system with unified build process
	// =============================================================================


	// Theme initialization
	console.log('Zotefoams Theme loaded - ES Module System with all components');

})();
//# sourceMappingURL=bundle.js.map
