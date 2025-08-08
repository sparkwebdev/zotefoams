/**
 * Our History page scroll animations
 * Handles fade-in text animations, progress bar, and popups
 */

import { ZotefoamsReadyUtils } from '../utils/dom-utilities.js';

function initOurHistory() {
    // Check if we're on the Our History page
    if (!document.body.classList.contains('page-template-page-our-history')) {
        return;
    }

    const fadeElements = document.querySelectorAll('.fade-in');
    const scrollTargets = document.querySelectorAll('[data-js-el="scroll-target"]');
    const progressBar = document.getElementById('progress-bar');
    const popupMarkers = document.querySelectorAll('.zf-history__popup-marker');
    const timelineNav = document.querySelector('nav[data-js-el="timeline-nav"]');
    const timelineLinks = timelineNav ? timelineNav.querySelectorAll('a') : [];
    
    // Initialize timeline navigation smooth scrolling
    if (timelineLinks.length) {
        timelineLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const targetId = link.getAttribute('href').substring(1);
                const targetSection = document.getElementById(targetId);
                
                if (targetSection) {
                    const headerHeight = document.querySelector('header')?.offsetHeight || 0;
                    const targetPosition = targetSection.offsetTop - headerHeight;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    }
    
    // Initialize back to top smooth scrolling
    const backToTopLink = document.querySelector('a[href="#page"]');
    if (backToTopLink) {
        backToTopLink.addEventListener('click', (e) => {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
    
    // Initialize popups
    if (popupMarkers.length) {
        popupMarkers.forEach(marker => {
            const popup = marker.querySelector('.zf-history__popup');
            if (popup) {
                // Show popup on click/focus
                marker.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    popup.classList.toggle('is-visible');
                    popup.setAttribute('aria-hidden', popup.classList.contains('is-visible') ? 'false' : 'true');
                });
                
                // Hide popup when clicking outside
                document.addEventListener('click', (e) => {
                    if (!marker.contains(e.target) && popup.classList.contains('is-visible')) {
                        popup.classList.remove('is-visible');
                        popup.setAttribute('aria-hidden', 'true');
                    }
                });
                
                // Keyboard support
                marker.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && popup.classList.contains('is-visible')) {
                        popup.classList.remove('is-visible');
                        popup.setAttribute('aria-hidden', 'true');
                        marker.focus();
                    }
                });
            }
        });
    }
    
    if (!fadeElements.length || !scrollTargets.length) {
        return;
    }

    // Create Intersection Observer for visibility detection
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: [0, 0.25, 0.5, 0.75, 1]
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            const fadeIn = entry.target.querySelector('.fade-in');
            if (!fadeIn) return;

            if (entry.isIntersecting) {
                // Add is-visible class to parent
                entry.target.classList.add('is-visible');
                
                // Calculate scroll progress for smooth fade
                const scrollProgress = Math.min(1, Math.max(0, entry.intersectionRatio));
                entry.target.style.setProperty('--scroll-progress2', scrollProgress);
            }
        });
    }, observerOptions);

    // Observe all scroll targets
    scrollTargets.forEach(target => {
        observer.observe(target);
    });

    // Handle scroll for smooth opacity updates and progress bar
    let ticking = false;
    function updateScrollProgress() {
        if (!ticking) {
            window.requestAnimationFrame(() => {
                // Update progress bar
                if (progressBar) {
                    const scrollHeight = document.documentElement.scrollHeight - window.innerHeight;
                    const scrolled = window.pageYOffset || document.documentElement.scrollTop;
                    const progress = (scrolled / scrollHeight) * 100;
                    progressBar.style.width = `${Math.min(100, Math.max(0, progress))}%`;
                }
                
                // Update timeline navigation active state
                if (timelineLinks.length) {
                    const scrollPosition = window.pageYOffset + window.innerHeight / 2;
                    let activeSection = null;
                    
                    // Find which section we're in
                    timelineLinks.forEach(link => {
                        const targetId = link.getAttribute('href').substring(1);
                        const targetSection = document.getElementById(targetId);
                        
                        if (targetSection) {
                            const sectionTop = targetSection.offsetTop;
                            const sectionBottom = sectionTop + targetSection.offsetHeight;
                            
                            if (scrollPosition >= sectionTop && scrollPosition < sectionBottom) {
                                activeSection = link;
                            }
                        }
                    });
                    
                    // Update active classes
                    timelineLinks.forEach(link => {
                        link.classList.remove('is-active');
                    });
                    
                    if (activeSection) {
                        activeSection.classList.add('is-active');
                    }
                }
                
                scrollTargets.forEach(target => {
                    const rect = target.getBoundingClientRect();
                    const fadeIn = target.querySelector('.fade-in');
                    if (!fadeIn) return;

                    // Calculate visibility based on element position
                    const windowHeight = window.innerHeight;
                    const elementTop = rect.top;
                    const elementBottom = rect.bottom;
                    const elementHeight = rect.height;

                    // Element is in viewport
                    if (elementTop < windowHeight && elementBottom > 0) {
                        // Calculate progress (0 to 1)
                        let progress = 0;
                        
                        if (elementTop >= 0) {
                            // Element entering from bottom
                            progress = Math.min(1, (windowHeight - elementTop) / (windowHeight * 0.5));
                        } else if (elementBottom <= windowHeight) {
                            // Element exiting from top
                            progress = Math.max(0, elementBottom / (windowHeight * 0.5));
                        } else {
                            // Element fully in view
                            progress = 1;
                        }

                        target.style.setProperty('--scroll-progress2', progress);
                        
                        if (progress > 0) {
                            target.classList.add('is-visible');
                        }
                    }
                });
                ticking = false;
            });
            ticking = true;
        }
    }

    // Add scroll listener
    window.addEventListener('scroll', updateScrollProgress);
    
    // Initial check
    updateScrollProgress();
}

// Initialize on DOM ready
ZotefoamsReadyUtils.ready(initOurHistory);

export { initOurHistory };