
<?php 
// Allow for passed variables, as well as ACF values
$title = get_sub_field('multi_item_carousel_title');
$behaviour = get_sub_field('multi_item_carousel_behaviour'); // Pick / Children / Manual
$page_id = get_sub_field('multi_item_carousel_parent_id'); // Selected parent page
$manual_slides = get_sub_field('multi_item_carousel_slides'); // Manual items
?>

<!-- Carousel 4 - Multi-Item Carousel -->
<div class="swiper multi-item-carousel padding-t-100 theme-none">
    
    <div class="title-strip margin-b-30 cont-m">
        <?php if ($title): ?>
            <h3 class="fs-500 fw-600"><?php echo esc_html($title); ?></h3>
        <?php endif; ?>
        <!-- Navigation -->
        <div class="carousel-navigation black">
            <div class="carousel-navigation-inner">
                <div class="multi-swiper-button-prev">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/left-arrow-black.svg" />
                </div>
                <div class="multi-swiper-button-next">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/right-arrow-black.svg" />
                </div>
            </div>
        </div>
    </div>

    <div class="swiper-wrapper">
        <?php 
        if ($behaviour === 'pick') {
            $page_ids = get_sub_field('multi_item_carousel_page_ids');
            if ($page_ids) {
                foreach ($page_ids as $page_id) : 
                    $page_title = get_the_title($page_id);
                    $page_link = get_permalink($page_id);
                    $thumbnail_url = get_the_post_thumbnail_url($page_id, 'thumbnail');
                    if (!$thumbnail_url) {
                        $thumbnail_url = get_template_directory_uri() . '/images/placeholder-thumbnail.png';
                    }
                    ?>
                    <div class="swiper-slide">
                        <h3><?php echo esc_html($page_title); ?></h3>
                        <?php if (get_the_excerpt($page_id)): ?>
                            <p><?php echo esc_html(get_the_excerpt($page_id)); ?></p>
                        <?php endif; ?>
                        <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr($page_title); ?>">
                        <a href="<?php echo esc_url($page_link); ?>" class="btn black outline">Read More</a>
                    </div>
                <?php endforeach; 
            }
        } elseif ($behaviour === 'children') {
            // Fetch child pages
            $child_pages = get_pages([
                'parent' => $page_id,
                'sort_column' => 'menu_order',
                'sort_order' => 'ASC',
            ]);

            if ($child_pages):
                foreach ($child_pages as $child):
                    $child_id = $child->ID;
                    $child_title = get_the_title($child_id);
                    $child_link = get_permalink($child_id);
                    $thumbnail_url = get_the_post_thumbnail_url($child_id, 'thumbnail');
                    if (!$thumbnail_url) {
                        $thumbnail_url = get_template_directory_uri() . '/images/placeholder-thumbnail.png';
                    }
                    ?>
                    <div class="swiper-slide">
                        <h3><?php echo esc_html($child_title); ?></h3>
                        <?php if (get_the_excerpt($child_id)): ?>
                            <p><?php echo esc_html(get_the_excerpt($child_id)); ?></p>
                        <?php endif; ?>
                        <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr($child_title); ?>">
                        <a href="<?php echo esc_url($child_link); ?>" class="btn black outline">Read More</a>
                    </div>
                <?php endforeach;
            endif;

        } elseif ($behaviour === 'manual' && $manual_slides) {
            foreach ($manual_slides as $slide):
                $slide_title = $slide['multi_item_carousel_slide_title'] ?? '';
                $slide_text = $slide['multi_item_carousel_slide_text'] ?? '';
                $slide_button = $slide['multi_item_carousel_slide_button'] ?? '';
                $slide_image = $slide['multi_item_carousel_slide_image'] ?? null;

                $image_url = $slide_image ? $slide_image['sizes']['thumbnail'] : get_template_directory_uri() . '/images/placeholder.png';
                ?>
                <div class="swiper-slide">
                    <?php if ($slide_title): ?>
                        <h3><?php echo esc_html($slide_title); ?></h3>
                    <?php endif; ?>
                    <?php if ($slide_text): ?>
                        <p><?php echo wp_kses_post($slide_text); ?></p>
                    <?php endif; ?>
                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($slide_title); ?>">
                    <?php if ($slide_button): ?>
                        <a href="<?php echo esc_url($slide_button['url']); ?>" class="btn black outline" target="<?php echo esc_attr($slide_button['target']); ?>">
                            <?php echo esc_html($slide_button['title']); ?>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach;
        }
        ?>
    </div>

    <div class="multi-swiper-scrollbar cont-m"></div>

</div>



	<script type="text/javascript">
		document.addEventListener('DOMContentLoaded', function () {
			// Carousel 4 - Multi-Item Carousel
			const swiperMultiItem = new Swiper('.multi-item-carousel', {
				loop: false,
				slidesPerView: 3,
				spaceBetween: 20,
				navigation: {
					prevEl: '.multi-swiper-button-prev',
					nextEl: '.multi-swiper-button-next',
				},
				scrollbar: {
					el: '.multi-swiper-scrollbar', // Select your scrollbar container
					hide: false, // Optional: Hide scrollbar when not active
					draggable: false, // Optional: Make scrollbar draggable
				},
			});
		});
	</script>


	<style type="text/css">
	/* 10 - Multi item carousel
	--------------------------------------------- */

	.multi-item-carousel {
		padding: 50px;
	}

		.multi-item-carousel .swiper-slide {
			text-align: center;
			border: solid 1px #DDDDDD;	
		}

		.multi-swiper-button-prev img, .multi-swiper-button-next img {
			cursor: pointer;
		}

		.multi-item-carousel .swiper-button-disabled {
			opacity: 0.5;
		}
		.multi-item-carousel .swiper-button-disabled img {
			cursor: initial;
		}

			.multi-item-carousel .swiper-slide > * {
				display: block;
				margin-left: auto;
				margin-right: auto;
			}

			.multi-item-carousel .swiper-slide h3 {
				font-size: 2em;
				font-weight: normal;
				margin-top: 60px;
				margin-bottom: 30px;
			}

			.multi-item-carousel .swiper-slide p {
				margin-bottom: 70px;
				max-width: 400px;
				color: #707070;
			}

			.multi-item-carousel .swiper-slide img {
				margin-bottom: 60px;
				width: 100%;
			}

			.multi-item-carousel .swiper-slide a.btn {
				display: inline-block;
				margin-bottom: 60px;
				font-size: 0.8em;
			}

		.multi-item-carousel .multi-swiper-scrollbar {
			height: 5px;
			margin-top: 30px;
			background: rgba(0, 0, 0, 0.15); /* Background of the scrollbar */
		}

		.multi-item-carousel .swiper-scrollbar-drag {
			background: #707070;
			height: 5px;
		}
		
	</style>



