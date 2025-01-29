document.addEventListener('DOMContentLoaded', function () {
  const swiper = new Swiper('.swiper', {
    direction: 'horizontal',
    loop: true,
    speed: 400,
    autoplay: {
      delay: 3000, // Slide transition delay (3 seconds)
      disableOnInteraction: false,
    },
    navigation: {
      nextEl: '.swiper-button-next', // Only next button is enabled
    },
    on: {
      init: function () {
        // Ensure swiper is initialized before calling any functions
        updateNextButtonTitle(this); // `this` refers to the swiper instance
      },
      slideChange: function () {
        // Ensure swiper is initialized before calling any functions
        updateNextButtonTitle(this); // `this` refers to the swiper instance
        
        // Reset the circle animation
        const progressCircle = document.querySelector('.circle-progress');
        
        // Reset stroke-dashoffset on transition
        progressCircle.style.transition = 'none';
        progressCircle.style.strokeDashoffset = 144.513; // Reset to full circle
        
        // Trigger the animation again with a slight delay
        setTimeout(() => {
          progressCircle.style.transition = 'stroke-dashoffset 3s linear';
          progressCircle.style.strokeDashoffset = 0; // Animate the stroke to 0
        }, 50); // Small delay to allow for reset
      },
    },
  });

  // Function to update the next button title based on the next slide
  function updateNextButtonTitle(swiperInstance) {
    // Get the next slide index, considering the loop
    const nextSlide = swiperInstance.slides[(swiperInstance.realIndex + 1) % swiperInstance.slides.length];
    
    // Get the title from the data-title attribute
    const nextTitle = nextSlide.getAttribute('data-title');
    
    // Find the span inside the next button and update it
    const nextButtonText = document.querySelector('.swiper-button-next p span');
    
    if (nextButtonText && nextTitle) {
      nextButtonText.textContent = nextTitle;
    }
  }
});





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