<?php
$slides = get_sub_field('split_carousel_slides');
?>

<!-- Carousel 3 - Split Carousel -->
<div class="swiper-carousel split-carousel light-grey-bg theme-light">

    <div class="swiper swiper-split">
        <div class="swiper-wrapper">
            <?php if ($slides) : ?>
                <?php foreach ($slides as $slide) :
                    $category = $slide['split_carousel_category'];
                    $title    = $slide['split_carousel_title'];
                    $text     = $slide['split_carousel_text'];
                    $button   = $slide['split_carousel_button']; // ACF Link field
                    $image    = $slide['split_carousel_image'];

                    $image_url = $image ? $image['sizes']['large'] : get_template_directory_uri() . '/images/placeholder.png';
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
        <div class="carousel-navigation black">
            <div class="carousel-navigation-inner">
                <div class="split-swiper-button-prev">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/images/left-arrow-black.svg'); ?>" alt="<?php esc_attr_e('Previous slide', 'zotefoams'); ?>" />
                </div>
                <div class="split-swiper-button-next">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/images/right-arrow-black.svg'); ?>" alt="<?php esc_attr_e('Next slide', 'zotefoams'); ?>" />
                </div>
            </div>
        </div>
    </div>
</div>