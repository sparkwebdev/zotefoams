
<?php 
// Allow for passed variables, as well as ACF values
$slides = get_sub_field('split_carousel_slides');
?>

<!-- Carousel 3 - Split Carousel -->
<div class="swiper-carousel split-carousel light-grey-bg theme-light">
    
    <!-- Navigation -->
    <div class="navigation-wrapper">
        <div class="split-swiper-pagination"></div>
        <div class="carousel-navigation black">
            <div class="carousel-navigation-inner">
                <div class="split-swiper-button-prev">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/left-arrow-black.svg" />
                </div>
                <div class="split-swiper-button-next">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/right-arrow-black.svg" />
                </div>
            </div>
        </div>
    </div>

    <div class="swiper swiper-split">
        <div class="swiper-wrapper">
            <?php if ($slides): ?>
                <?php foreach ($slides as $slide): ?>
                    <?php 
                        $category = $slide['split_carousel_category'];
                        $title = $slide['split_carousel_title'];
                        $text = $slide['split_carousel_text'];
                        $button = $slide['split_carousel_button']; // ACF Link field
                        $image = $slide['split_carousel_image'];
                        
                        // Extract 'large' size image URL from Image Array with fallback
                        $image_url = $image ? $image['sizes']['large'] : get_template_directory_uri() . '/images/placeholder.png';
                    ?>
                    <div class="swiper-slide">
                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>">
                        <div class="swiper-slide-content">
                            <?php if ($category): ?>
                                <p class="animate__animated fs-100 margin-b-30 grey-text"><?php echo esc_html($category); ?></p>
                            <?php endif; ?>
                            <?php if ($title): ?>
                                <p class="animate__animated fs-600 fw-bold"><?php echo esc_html($title); ?></p>
                            <?php endif; ?>
                            <?php if ($text): ?>
                                <div class="animate__animated fs-600 fw-medium grey-text margin-b-40"><?php echo wp_kses_post($text); ?></div>
                            <?php endif; ?>
                            <?php if ($button): ?>
                                <a href="<?php echo esc_url($button['url']); ?>" class="animate__animated btn black outline" target="<?php echo esc_attr($button['target']); ?>">
                                    <?php echo esc_html($button['title']); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                        <div class="spacer"></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>


<script type="text/javascript">
		document.addEventListener('DOMContentLoaded', function () {
			// Carousel 3 - Split Carousel
			const swiperSplit = new Swiper('.swiper-split', {
				speed: 800,
				loop: true,
				navigation: {
					prevEl: '.split-swiper-button-prev',
					nextEl: '.split-swiper-button-next',
				},
				pagination: {
					el: '.split-swiper-pagination',
					clickable: true,
				},
			});
		});
	</script>

	<style type="text/css">
	/* 09 - Split carousel
	--------------------------------------------- */

	.swiper-carousel.split-carousel {
		position: relative;
	}

		.swiper-carousel.split-carousel .navigation-wrapper {
			position: absolute;	
			bottom: 0px;
			right: 0px;
			z-index: 2;
    		width: 50vw;
			margin-bottom: 30px;
		}

		.swiper-carousel.split-carousel .navigation-wrapper .carousel-navigation-inner img {
			width: 60px;
			height: 60px;
		}

		.swiper-carousel.split-carousel .navigation-wrapper .split-swiper-pagination {
			text-align: right;
			margin-right: 16vw;
			width: auto;
			margin-top: 20px;
			float: right;
		}

			.swiper-carousel.split-carousel .navigation-wrapper .split-swiper-pagination .swiper-pagination-bullet {
				background-color: #000;
				opacity: 1;
				margin-left: 2px;
				margin-right: 2px;
			}

			.swiper-carousel.split-carousel .navigation-wrapper .split-swiper-pagination .swiper-pagination-bullet-active {
				background-color: #3B82F6;
			}

	.swiper-split .swiper-slide {
		display: flex;
		align-items:center;
		justify-content: center;
	}

	.swiper-split .swiper-slide > * {
		flex: 3 1 0;
		width: 50%;
	}

	.swiper-split .swiper-slide-content {
		flex-direction: column;
		/* max-width:600px */
		margin-bottom: 120px;
	}

	.swiper-split .spacer {
		flex: 1 0 0;
	}

	.calendar-carousel .swiper-slide-inner {
		border:1px solid #ccc
	}
	</style>
