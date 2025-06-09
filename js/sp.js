/* Component Init - File List Enhanced with Multiple Filters (Comma-Separated) and Keyboard Support */
document.addEventListener('DOMContentLoaded', function () {
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



  /* Component Init - Section List */
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
});

document.addEventListener('DOMContentLoaded', function () {
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
});


if (document.querySelector('.overlay')) {
  document.querySelector('.overlay').classList.add('fade-in');
}

document.addEventListener('DOMContentLoaded', function () {
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
});

window.addEventListener('message', function (event) {
  var frames = document.getElementsByTagName('iframe');
  for (var i = 0; i < frames.length; i++) {
    if (frames[i].contentWindow === event.source) {
      frames[i].style.height = event.data + 'px';
      break;
    }
  }
});

(function () {
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
})();


document.addEventListener('DOMContentLoaded', () => {
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


  

  // Our History
  document.querySelectorAll('.zf-history__popup-marker').forEach(function (button) {
    button.addEventListener('focus', function() {
      const tooltip = button.querySelector('.zf-history__popup');
      if (tooltip) {
        tooltip.setAttribute('aria-hidden', 'false');
      }
    });

    button.addEventListener('blur', function() {
      const tooltip = button.querySelector('.zf-history__popup');
      if (tooltip) {
        tooltip.setAttribute('aria-hidden', 'true');
      }
    });

    button.addEventListener('mouseenter', function() {
      const tooltip = button.querySelector('.zf-history__popup');
      if (tooltip) {
        tooltip.setAttribute('aria-hidden', 'false');
      }
    });

    button.addEventListener('mouseleave', function() {
      const tooltip = button.querySelector('.zf-history__popup');
      if (tooltip) {
        tooltip.setAttribute('aria-hidden', 'true');
      }
    });
  });

(() => {
  const SCROLL_PROPERTY = '--scroll-y';
  const ACTIVE_NAV_CLASS = 'is-active';
  const ARIA_CURRENT_ATTRIBUTE = 'aria-current';
  const ARIA_CURRENT_VALUE = 'page';

  let scrollTicking = false;
  const visibleSectionIds = new Set();
  let navLinks;
  let scrollTargetElements;
  let lastActiveId = null;
  // let dots = [];

  const updateScrollAnimations = (currentScrollY) => {
    if (!scrollTargetElements?.length) return;
    const viewportHeight = window.innerHeight;

    const htmlStyles = window.getComputedStyle(document.documentElement);
    const scrollPaddingTop = parseFloat(htmlStyles.scrollPaddingTop) || 0;
    
    const effectiveProgressRangeHeight = viewportHeight - scrollPaddingTop;

    scrollTargetElements.forEach(el => {
      const rect = el.getBoundingClientRect();
      const elementTopInDocument = rect.top + currentScrollY;
      const elementHeight = rect.height;
      const elementBottomInDocument = elementTopInDocument + elementHeight;

      let progressStart;
      if (effectiveProgressRangeHeight <= 0) {
        progressStart = (elementTopInDocument <= currentScrollY + scrollPaddingTop) ? 1 : 0;
      } else {
        const progressStartValue = (currentScrollY + viewportHeight - elementTopInDocument) / effectiveProgressRangeHeight;
        progressStart = Math.min(Math.max(progressStartValue, 0), 1);
      }
      el.style.setProperty('--scroll-progress', progressStart.toFixed(3));

      let progressEnd;
      if (effectiveProgressRangeHeight <= 0) {
        progressEnd = (elementBottomInDocument <= currentScrollY + scrollPaddingTop) ? 1 : 0;
      } else {
        const progressEndValue = (currentScrollY + viewportHeight - elementBottomInDocument) / effectiveProgressRangeHeight;
        progressEnd = Math.min(Math.max(progressEndValue, 0), 1);
      }
      el.style.setProperty('--scroll-progress-end', progressEnd.toFixed(3));
    });
  };

  const performScrollUpdates = () => {
    const currentScrollY = window.scrollY;
    document.documentElement.style.setProperty(SCROLL_PROPERTY, `${currentScrollY}px`);
    updateScrollAnimations(currentScrollY);
    scrollTicking = false;
    const docHeight = document.documentElement.scrollHeight - window.innerHeight;
    const scrollPercent = (currentScrollY / docHeight) * 100;

    const progressBar = document.getElementById('progress-bar');
    if (progressBar) {
      progressBar.style.width = `${scrollPercent}%`;
    }
  };

  const onScroll = () => {
    if (!scrollTicking) {
      requestAnimationFrame(performScrollUpdates);
      scrollTicking = true;
    }
  };

  const refreshActiveNavLinks = () => {
    if (!navLinks?.length) return;

    let bestCandidateId = null;
    let highestVisibleTop = Infinity;

    visibleSectionIds.forEach(id => {
      const section = document.getElementById(id);
      if (section) {
        const rect = section.getBoundingClientRect();
        if (rect.top < window.innerHeight && rect.bottom > 0 && rect.top < highestVisibleTop) {
          highestVisibleTop = rect.top;
          bestCandidateId = id;
        }
      }
    });

    navLinks.forEach(link => {
      const href = link.getAttribute('href');
      const linkTargetId = href?.startsWith('#') ? href.substring(1) : null;

      if (linkTargetId && linkTargetId === bestCandidateId) {
        link.classList.add(ACTIVE_NAV_CLASS);
        link.setAttribute(ARIA_CURRENT_ATTRIBUTE, ARIA_CURRENT_VALUE);
      } else {
        link.classList.remove(ACTIVE_NAV_CLASS);
        link.removeAttribute(ARIA_CURRENT_ATTRIBUTE);
      }
    });

    // 🆕 Update URL hash without adding to history
    if (bestCandidateId && bestCandidateId !== lastActiveId) {
      history.replaceState(null, '', `#${bestCandidateId}`);
      lastActiveId = bestCandidateId;
    }
  };

  const handleSectionIntersection = (entries) => {
    let changed = false;
    entries.forEach(entry => {
      const sectionId = entry.target.id;
      if (entry.isIntersecting) {
        if (!visibleSectionIds.has(sectionId)) {
          visibleSectionIds.add(sectionId);
          changed = true;
        }
      } else {
        if (visibleSectionIds.has(sectionId)) {
          visibleSectionIds.delete(sectionId);
          changed = true;
        }
      }
    });

    if (changed) refreshActiveNavLinks();
  };

  childElementVisibilityObserver = new IntersectionObserver(
  (entries) => {
    entries.forEach(entry => {
    entry.target.classList.toggle('is-visible', entry.isIntersecting);

    // // Find index of this entry.target in scrollTargetElements
    // const index = Array.from(scrollTargetElements).indexOf(entry.target);

    // if (index !== -1) {
    //   dots[index].classList.toggle('is-active', entry.isIntersecting);
    // }
  });

  },
  { threshold: 0.6 }
);

  const initializeApp = () => {
    navLinks = document.querySelectorAll('nav[aria-label="Timeline Navigation"] a');
    scrollTargetElements = document.querySelectorAll('[data-js-el="scroll-target"]');
    const mainSections = document.querySelectorAll('div.zf-history__years > section[id]');

    // const indicatorContainer = document.getElementById('panel-indicators');

    // // Create dots
    // Array.from(scrollTargetElements).forEach(() => {
    //   const dot = document.createElement('div');
    //   dot.classList.add('dot');
    //   indicatorContainer.appendChild(dot);
    //   dots.push(dot);
    // });

    if (scrollTargetElements.length > 0) {
      scrollTargetElements.forEach(el => childElementVisibilityObserver.observe(el));
    }

    const sectionIntersectionObserver = new IntersectionObserver(handleSectionIntersection, { threshold: 0.2 });
    if (mainSections.length > 0) {
      mainSections.forEach(section => sectionIntersectionObserver.observe(section));
    }

    performScrollUpdates();
    window.addEventListener('scroll', onScroll, { passive: true });

    // 🆕 Handle deep link scroll on load
    const hash = window.location.hash;
    if (hash && document.getElementById(hash.substring(1))) {
      requestAnimationFrame(() => {
        const targetElement = document.getElementById(hash.substring(1));
        if (targetElement) {
          targetElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      });
    }
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeApp);
  } else {
    initializeApp();
  }
})();

});
