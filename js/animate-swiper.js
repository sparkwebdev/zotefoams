document.addEventListener('DOMContentLoaded', function() {
    
    const sliders = document.getElementsByClassName('swiper');
    if (sliders) {
        for (let i = 0; i < sliders.length; i++) {

            const swiper = sliders[i].swiper;
            if (swiper) {

                swiper.on('slideChange', function (e) {
            
                     const previousSlide = swiper.slides[e.previousIndex];
                     toggleAnimation(previousSlide, false);

                     const currentSlide = swiper.slides[e.activeIndex];
                     toggleAnimation(currentSlide, true);
                });
            }
        }
    }


    function toggleAnimation(parent, on) {
        
        let delay = 0;
        let elements = parent.getElementsByClassName('animate__animated');

        for (let i = 0; i < elements.length; i++) {

            const animation = elements[i].dataset.animation ?? 'animate__fadeInDown';
            delay = elements[i].dataset.animationdelay ?? (delay === 0 ? 0.3 : delay + 0.7);

            if (on) {
                elements[i].classList.add('hidden');
                if (!elements[i].classList.contains(animation)) {
                    elements[i].style.setProperty('--animation-delay', delay + 's');
                    elements[i].style.setProperty('-webkit-animation-delay', delay + 's');
                    elements[i].classList.add(animation);
                }
            } else {
                elements[i].classList.remove(animation);
            }
        }
    }
});