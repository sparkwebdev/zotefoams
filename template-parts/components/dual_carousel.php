<?php 
// Allow for passed variables, as well as ACF values
$slides = get_sub_field('dual_carousel_slides');

$markets_page_id = zotefoams_get_page_id_by_title('Markets');
if (!$markets_page_id) {
    $markets_page_id = zotefoams_get_page_id_by_title('Industries');
}
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
		
		<?php if (wp_get_post_parent_id(get_the_ID()) == 11) : ?>
			<!-- Navigation -->
			<div class="carousel-navigation black">
				<div class="carousel-navigation-inner">
					<div class="swiper-button-prev-dual-carousel">
						<img src="<?php echo get_template_directory_uri(); ?>/images/left-arrow-black.svg" />
					</div>
					<div class="swiper-button-next-dual-carousel">
						<img src="<?php echo get_template_directory_uri(); ?>/images/right-arrow-black.svg" />
					</div>
				</div>
			</div>
		<?php else : ?>
			<!-- Navigation -->
			<div class="carousel-navigation white">
				<div class="carousel-navigation-inner">
					<div class="swiper-button-prev-dual-carousel">
						<img src="<?php echo get_template_directory_uri(); ?>/images/left-arrow-white.svg" />
					</div>
					<div class="swiper-button-next-dual-carousel">
						<img src="<?php echo get_template_directory_uri(); ?>/images/right-arrow-white.svg" />
					</div>
				</div>
			</div>
		<?php endif; ?>
		
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
							<?php if (wp_get_post_parent_id(get_the_ID()) == 11) : ?>
                            	<img src="<?php echo esc_url($bg_image_url); ?>" alt="Carousel Background" style="object-fit: contain !important;">
							<?php else : ?>
                            	<img src="<?php echo esc_url($bg_image_url); ?>" alt="Carousel Background">
							<?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>