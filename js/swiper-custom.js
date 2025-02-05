document.addEventListener('DOMContentLoaded', function () {
    // First Swiper
    const swiperOne = new Swiper('.swiper-banner', {
        direction: 'horizontal',
        loop: true,
        speed: 400,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        navigation: {
            nextEl: '.swiper-button-next-banner',
        },
        on: {
            init: function () {
                updateNextButtonTitle(this, '.swiper-button-next-banner p span');
            },
            slideChange: function () {
                updateNextButtonTitle(this, '.swiper-button-next-banner p span');
                resetProgressAnimation('.circle-progress-one');
            },
        },
    });

    // Function to update the next button title based on the next slide
    function updateNextButtonTitle(swiperInstance, selector) {
        const nextSlide = swiperInstance.slides[(swiperInstance.realIndex + 1) % swiperInstance.slides.length];
        const nextTitle = nextSlide.getAttribute('data-title');
        const nextButtonText = document.querySelector(selector);
        if (nextButtonText && nextTitle) {
            nextButtonText.textContent = nextTitle;
        }
    }

    // Function to reset and animate progress circle
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


// Initialize the text carousel (with fade effect)
const swiperText = new Swiper('.swiper-carousel-text', {
  loop: true,
  effect: 'fade',
  fadeEffect: {
    crossFade: true
  },
  speed: 1000,
  navigation: {
    nextEl: '.swiper-button-next-carousel',
    prevEl: '.swiper-button-prev-carousel',
  },
});

// Initialize the image carousel (without fade effect)
const swiperImage = new Swiper('.swiper-carousel-image', {
  loop: true,
  spaceBetween: 10,
  navigation: {
    nextEl: '.swiper-button-next-carousel',
    prevEl: '.swiper-button-prev-carousel',
  },
});

// Sync the carousels so that they move together
swiperText.controller.control = swiperImage;
swiperImage.controller.control = swiperText;





// JavaScript to handle the overlay
document.addEventListener('DOMContentLoaded', function() {
	var overlay = document.getElementById('video-overlay');
	var iframe = document.getElementById('video-iframe');
	var closeBtn = document.getElementById('close-video');

	// Open overlay when the link is clicked
	var links = document.querySelectorAll('.open-video-overlay');
	links.forEach(function(link) {
		link.addEventListener('click', function(e) {
			e.preventDefault();
			var videoUrl = this.href;
			var videoId = videoUrl.split('v=')[1].split('&')[0]; // Get the video ID from the URL
			iframe.src = 'https://www.youtube.com/embed/' + videoId + '?autoplay=1'; // Embed the video
			overlay.style.display = 'flex'; // Show the overlay
		});
	});

	// Close the overlay
	closeBtn.addEventListener('click', function() {
		overlay.style.display = 'none';
		iframe.src = ''; // Stop the video
	});
});

document.querySelector('.overlay').classList.add('fade-in');



// Select all accordion headers
const headers = document.querySelectorAll('.accordion-header');

// Add click event listener to each header
headers.forEach(header => {
  header.addEventListener('click', function() {
    const content = this.nextElementSibling; // The next sibling is the content
    const icon = this.querySelector('.toggle-icon'); // Get the plus/minus icon

    // Close all other accordion sections
    headers.forEach(otherHeader => {
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
      content.style.maxHeight = '500px'; // Set a maximum height for the transition
      icon.textContent = '-'; // Change the icon to minus
      this.classList.add('open');
      content.scrollIntoView({
        behavior: 'smooth',
        block: 'start',
      });
    }
  });
});



