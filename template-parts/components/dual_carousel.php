<?php 
// Allow for passed variables, as well as ACF values
$slides = get_sub_field('dual_carousel_slides');

$markets_page_id = zf_get_page_id_by_title('Markets');
$is_market_pages = $markets_page_id == get_the_ID() || $markets_page_id == $post->post_parent;
$theme_style = $is_market_pages ? "light-grey-bg theme-light" : "black-bg white-text theme-dark";
$theme_button_style = $is_market_pages ? "black" : "white";

?>

<!-- Carousel 2 - dual carousel -->
<div class="swiper-dual-carousel text-center <?php echo $theme_style; ?>">
    <div class="swiper swiper-dual-carousel-text">
        <div class="swiper-wrapper">
            <?php if ($slides): ?>
                <?php foreach ($slides as $slide): ?>
                    <?php 
                        $category = $slide['dual_carousel_category'];
                        $title = $slide['dual_carousel_title'];
                        $image = $slide['dual_carousel_image'];
                        $text = $slide['dual_carousel_text'];
                        $button = $slide['dual_carousel_button']; // ACF Link field
                        $bg_image = $slide['dual_carousel_bg_image'];
                        
                        // Extract 'large' size image URLs, with fallback to placeholder.png
                        $image_url = $image ? $image['sizes']['large'] : get_template_directory_uri() . '/images/placeholder.png';
                        $bg_image_url = $bg_image ? $bg_image['sizes']['large'] : get_template_directory_uri() . '/images/placeholder.png';
                    ?>
                    <div class="swiper-slide">
                        <div class="slide-inner">
                            <?php if ($category): ?>
                                <p class="animate__animated fs-200 fw-regular margin-b-30"><?php echo esc_html($category); ?></p>
                            <?php endif; ?>
                            <?php if ($title): ?>
                                <p class="animate__animated fs-600 fw-semibold margin-b-40"><?php echo esc_html($title); ?></p>
                            <?php endif; ?>
                            <div class="slide-sub-content">
                                <img class="animate__animated margin-b-30" src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>" />
                                <?php if ($text): ?>
                                    <div class="animate__animated margin-b-30"><?php echo wp_kses_post($text); ?></div>
                                <?php endif; ?>
                                <?php if ($button): ?>
                                    <a href="<?php echo esc_url($button['url']); ?>" class="animate__animated btn <?php echo $theme_button_style; ?> outline" target="<?php echo esc_attr($button['target']); ?>">
                                        <?php echo esc_html($button['title']); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Navigation -->
        <div class="carousel-navigation white">
            <div class="carousel-navigation-inner">
                <div class="swiper-button-next-dual-carousel">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/left-arrow-white.svg" />
                </div>
                <div class="swiper-button-prev-dual-carousel">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/right-arrow-white.svg" />
                </div>
            </div>
        </div>
    </div>

    <div class="swiper swiper-dual-carousel-image">
        <div class="swiper-wrapper">
            <?php if ($slides): ?>
                <?php foreach ($slides as $slide): ?>
                    <?php 
                        $bg_image = $slide['dual_carousel_bg_image'];
                        $bg_image_url = $bg_image ? $bg_image['sizes']['large'] : get_template_directory_uri() . '/images/placeholder.png';
                    ?>
                    <div class="swiper-slide">
                        <div class="swiper-inner white-text">
                            <img src="<?php echo esc_url($bg_image_url); ?>" alt="Carousel Background">
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>


	<script type="text/javascript">
		document.addEventListener('DOMContentLoaded', function () {
			// Carousel 2 - dual carousel

			// Initialize the text carousel
			var swiper = new Swiper(".mySwiper", {
				loop: true,
				navigation: {
					nextEl: '.swiper-button-next-dual-carousel2',
					prevEl: '.swiper-button-prev-dual-carousel2',
			  	},
			});

			// Initialize the text carousel
			const swiperText = new Swiper('.swiper-dual-carousel-text', {
			  loop: true,
			  effect: 'fade',
			  fadeEffect: {
				crossFade: true
			  },
			  speed: 1000,
			  navigation: {
				nextEl: '.swiper-button-next-dual-carousel',
				prevEl: '.swiper-button-prev-dual-carousel',
			  },
			});

			// Initialize the image carousel (without fade effect)
			const swiperImage = new Swiper('.swiper-dual-carousel-image', {
			  loop: true,
			  spaceBetween: 10
			});

			// Sync the carousels so that they move together
			swiperText.controller.control = swiperImage;
			swiperImage.controller.control = swiperText;
		});
	</script>

	<style type="text/css">
	/* 02 - Dual carousel
	--------------------------------------------- */
	.swiper-dual-carousel {
	  display: flex;
	  align-items: center;
	  justify-content: center;
	  align-items: stretch;  /* Ensure both child divs stretch to fill height */
	}
	.swiper-dual-carousel > div {
	  width: 50%;
	}

	.swiper-dual-carousel-image .swiper-wrapper {
	  align-items: center;
	}
	.swiper-dual-carousel-image .swiper-inner {
	  height: 100%;
	}
	.swiper-dual-carousel-image .swiper-inner img {
	  object-fit: cover;
	  height: 100%;
	}

	.swiper-dual-carousel-text {
	  display: flex;
	  align-items: center;
	  justify-content: center;
	  flex-direction: column;
	  position: relative;
	}

	.swiper-dual-carousel-text .swiper-slide {
	  padding: 70px 0 180px;
	}
	.swiper-dual-carousel-text .slide-sub-content {
	  max-width: 300px;
	  margin: 0 auto;
	}

	.swiper-dual-carousel .carousel-navigation {
	  display: flex;
	  flex-direction: column;
	  align-items: center;
	  justify-content: center;
	  position: absolute;
	  margin-left: auto;
	  margin-right: auto;
	  left: 0;
	  right: 0;
	  bottom: 50px;
	  text-align: center;
	  z-index: 1;
	}

	.swiper-pagination-bullet {
	  width: 9px;  /* Set width for the bullets */
	  height: 9px;  /* Set height for the bullets */
	  background-color: #fff;  /* Set default background color for the bullets */
	  border-radius: 50%;  /* Make the bullets round */
	  opacity: 0.6;  /* Optional: makes the bullets slightly transparent */
	  transition: opacity 0.3s;  /* Optional: add transition for better effect */
	  display: inline-block;
	  margin: 0 3px;
	}

	.swiper-pagination-bullet-active {
	  background-color: #3B82F6;  /* Set active bullet color */
	  opacity: 1;  /* Ensure the active bullet is fully opaque */
	}
	</style>