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
      slideChange: function () {
        // Reset the circle animation
        const progressCircle = document.querySelector('.circle-progress');
        
        // Reset stroke-dashoffset on transition
        progressCircle.style.transition = 'none';
        progressCircle.style.strokeDashoffset = 345; // Reset to full circle
        
        // Trigger the animation again
        setTimeout(() => {
          progressCircle.style.transition = 'stroke-dashoffset 3s linear';
          progressCircle.style.strokeDashoffset = 0; // Animate the stroke to 0
        }, 50); // Slight delay to allow for reset
      },
    },
  });
});
