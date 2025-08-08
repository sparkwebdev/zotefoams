<?php
// Get field data using safe helper functions
$slides = zotefoams_get_sub_field_safe('split_carousel_slides', [], 'array');

// Generate classes to match original structure exactly
$wrapper_classes = 'swiper-carousel split-carousel light-grey-bg theme-light';
?>

<!-- Carousel 3 - Split Carousel -->
<div class="<?php echo $wrapper_classes; ?>">

    <div class="swiper swiper-split">
        <div class="swiper-wrapper">
            <?php if ($slides) : ?>
                <?php foreach ($slides as $slide) :
                    $category = $slide['split_carousel_category'];
                    $title    = $slide['split_carousel_title'];
                    $text     = $slide['split_carousel_text'];
                    $button   = $slide['split_carousel_button']; // ACF Link field
                    $image    = $slide['split_carousel_image'];

                    $image_url = Zotefoams_Image_Helper::get_image_url($image, 'large', 'split-carousel');
                ?>
                    <div class="swiper-slide">
                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>">
                        <div class="swiper-slide-content">
                            <div class="swiper-slide-content-inner">
                                <?php if ($category) : ?>
                                    <p class="animate__animated fs-100 margin-b-30 grey-text">
                                        <?php echo esc_html($category); ?>
                                    </p>
                                <?php endif; ?>

                                <?php if ($title) : ?>
                                    <p class="animate__animated fs-600 fw-bold">
                                        <?php echo esc_html($title); ?>
                                    </p>
                                <?php endif; ?>

                                <?php if ($text) : ?>
                                    <div class="animate__animated fs-600 margin-t-10 fw-medium grey-text margin-b-40">
                                        <?php echo wp_kses_post($text); ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ($button) : ?>
                                    <a href="<?php echo esc_url($button['url']); ?>"
                                        class="animate__animated btn black outline"
                                        target="<?php echo esc_attr($button['target']); ?>">
                                        <?php echo esc_html($button['title']); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Navigation -->
    <div class="navigation-wrapper">
        <div class="split-swiper-pagination"></div>
        <div class="carousel-navigation black" role="group" aria-label="Carousel navigation">
            <div class="carousel-navigation-inner">
                <button type="button" class="split-swiper-button-prev carousel-btn-reset" aria-label="<?php esc_attr_e('Previous slide', 'zotefoams'); ?>" tabindex="0">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/images/left-arrow-black.svg'); ?>" alt="" role="presentation" />
                </button>
                <button type="button" class="split-swiper-button-next carousel-btn-reset" aria-label="<?php esc_attr_e('Next slide', 'zotefoams'); ?>" tabindex="0">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/images/right-arrow-black.svg'); ?>" alt="" role="presentation" />
                </button>
            </div>
        </div>
    </div>
</div>