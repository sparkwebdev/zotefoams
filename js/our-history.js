// Our History
document.addEventListener("DOMContentLoaded", function () {
  (() => {
    const SCROLL_PROPERTY = "--scroll-y";
    const ACTIVE_NAV_CLASS = "is-active";
    const ARIA_CURRENT_ATTRIBUTE = "aria-current";
    const ARIA_CURRENT_VALUE = "page";

    let scrollTicking = false;
    const visibleSectionIds = new Set();
    let navLinks;
    let scrollTargetElements;
    let lastActiveId = null;
    // let dots = [];

    document.querySelectorAll(".zf-history__popup-marker").forEach(function (button) {
      button.addEventListener("focus", function () {
        const tooltip = button.querySelector(".zf-history__popup");
        if (tooltip) {
          tooltip.setAttribute("aria-hidden", "false");
        }
      });

      button.addEventListener("blur", function () {
        const tooltip = button.querySelector(".zf-history__popup");
        if (tooltip) {
          tooltip.setAttribute("aria-hidden", "true");
        }
      });

      button.addEventListener("mouseenter", function () {
        const tooltip = button.querySelector(".zf-history__popup");
        if (tooltip) {
          tooltip.setAttribute("aria-hidden", "false");
        }
      });

      button.addEventListener("mouseleave", function () {
        const tooltip = button.querySelector(".zf-history__popup");
        if (tooltip) {
          tooltip.setAttribute("aria-hidden", "true");
        }
      });
    });

    const updateScrollAnimations = (currentScrollY) => {
      if (!scrollTargetElements?.length) return;
      const viewportHeight = window.innerHeight;

      const htmlStyles = window.getComputedStyle(document.documentElement);
      const scrollPaddingTop = parseFloat(htmlStyles.scrollPaddingTop) || 0;

      const effectiveProgressRangeHeight = viewportHeight - scrollPaddingTop;

      scrollTargetElements.forEach((el) => {
        const rect = el.getBoundingClientRect();
        const elementTopInDocument = rect.top + currentScrollY;
        const elementHeight = rect.height;
        const elementBottomInDocument = elementTopInDocument + elementHeight;

        let progressStart;
        if (effectiveProgressRangeHeight <= 0) {
          progressStart = elementTopInDocument <= currentScrollY + scrollPaddingTop ? 1 : 0;
        } else {
          const progressStartValue = (currentScrollY + viewportHeight - elementTopInDocument) / effectiveProgressRangeHeight;
          progressStart = Math.min(Math.max(progressStartValue, 0), 1);
        }
        el.style.setProperty("--scroll-progress", progressStart.toFixed(3));

        let progressEnd;
        if (effectiveProgressRangeHeight <= 0) {
          progressEnd = elementBottomInDocument <= currentScrollY + scrollPaddingTop ? 1 : 0;
        } else {
          const progressEndValue = (currentScrollY + viewportHeight - elementBottomInDocument) / effectiveProgressRangeHeight;
          progressEnd = Math.min(Math.max(progressEndValue, 0), 1);
        }
        el.style.setProperty("--scroll-progress-end", progressEnd.toFixed(3));
      });
    };

    const performScrollUpdates = () => {
      const currentScrollY = window.scrollY;
      document.documentElement.style.setProperty(SCROLL_PROPERTY, `${currentScrollY}px`);
      updateScrollAnimations(currentScrollY);
      scrollTicking = false;
      const docHeight = document.documentElement.scrollHeight - window.innerHeight;
      const scrollPercent = (currentScrollY / docHeight) * 100;

      const progressBar = document.getElementById("progress-bar");
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

      visibleSectionIds.forEach((id) => {
        const section = document.getElementById(id);
        if (section) {
          const rect = section.getBoundingClientRect();
          if (rect.top < window.innerHeight && rect.bottom > 0 && rect.top < highestVisibleTop) {
            highestVisibleTop = rect.top;
            bestCandidateId = id;
          }
        }
      });

      navLinks.forEach((link) => {
        const href = link.getAttribute("href");
        const linkTargetId = href?.startsWith("#") ? href.substring(1) : null;

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
        history.replaceState(null, "", `#${bestCandidateId}`);
        lastActiveId = bestCandidateId;
      }
    };
    // 🆕 Custom smooth scrolling for in-page anchor links
    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
      anchor.addEventListener("click", function (e) {
        const targetId = this.getAttribute("href").substring(1);
        const targetElement = document.getElementById(targetId);
        if (!targetElement) return;

        e.preventDefault();

        // Temporarily disable scroll snap
        const scrollContainer = document.documentElement;
        scrollContainer.style.scrollSnapType = "none";

        // Adjust for any fixed/sticky headers if needed
        const yOffset = -80; // tweak this value as needed
        const y = targetElement.getBoundingClientRect().top + window.pageYOffset + yOffset;

        window.scrollTo({ top: y, behavior: "smooth" });

        // Re-enable scroll snap after a short delay
        setTimeout(() => {
          scrollContainer.style.scrollSnapType = ""; // fallback to stylesheet-defined value
        }, 500); // match your smooth scroll duration

        // Update URL without jump
        history.replaceState(null, "", `#${targetId}`);
      });
    });

    const handleSectionIntersection = (entries) => {
      let changed = false;
      entries.forEach((entry) => {
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
        entries.forEach((entry) => {
          entry.target.classList.toggle("is-visible", entry.isIntersecting);

          // // Find index of this entry.target in scrollTargetElements
          // const index = Array.from(scrollTargetElements).indexOf(entry.target);

          // if (index !== -1) {
          //   dots[index].classList.toggle('is-active', entry.isIntersecting);
          // }
        });
      },
      { threshold: 0.6 },
    );

    const initializeApp = () => {
      navLinks = document.querySelectorAll('nav[aria-label="Timeline Navigation"] a');
      scrollTargetElements = document.querySelectorAll('[data-js-el="scroll-target"]');
      const mainSections = document.querySelectorAll("div.zf-history__years > section[id]");

      // const indicatorContainer = document.getElementById('panel-indicators');

      // // Create dots
      // Array.from(scrollTargetElements).forEach(() => {
      //   const dot = document.createElement('div');
      //   dot.classList.add('dot');
      //   indicatorContainer.appendChild(dot);
      //   dots.push(dot);
      // });

      if (scrollTargetElements.length > 0) {
        scrollTargetElements.forEach((el) => childElementVisibilityObserver.observe(el));
      }

      const sectionIntersectionObserver = new IntersectionObserver(handleSectionIntersection, { threshold: 0.2 });
      if (mainSections.length > 0) {
        mainSections.forEach((section) => sectionIntersectionObserver.observe(section));
      }

      performScrollUpdates();
      window.addEventListener("scroll", onScroll, { passive: true });

      // 🆕 Handle deep link scroll on load
      const hash = window.location.hash;
      if (hash && document.getElementById(hash.substring(1))) {
        requestAnimationFrame(() => {
          const targetElement = document.getElementById(hash.substring(1));
          if (targetElement) {
            targetElement.scrollIntoView({ behavior: "smooth", block: "start" });
          }
        });
      }
    };

    if (document.readyState === "loading") {
      document.addEventListener("DOMContentLoaded", initializeApp);
    } else {
      initializeApp();
    }
  })();
});
