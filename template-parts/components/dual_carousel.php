<?php
$slides = get_sub_field('dual_carousel_slides');

$markets_page_id = zotefoams_get_page_id_by_title('Markets') ?: zotefoams_get_page_id_by_title('Industries');
$is_market_pages = $markets_page_id && (get_the_ID() == $markets_page_id || $post->post_parent == $markets_page_id);

$theme_style = $is_market_pages ? 'light-grey-bg theme-light' : 'black-bg white-text theme-dark';
$theme_button_style = $is_market_pages ? 'black' : 'white';

$parent_id = wp_get_post_parent_id(get_the_ID());
$use_black_arrows = ($parent_id == 11); // Hardcoded logic
$arrow_color = $use_black_arrows ? 'black' : 'white';
?>

<div class="swiper-dual-carousel text-center <?php echo esc_attr($theme_style); ?>">

    <div class="swiper swiper-dual-carousel-text">
        <div class="swiper-wrapper">
            <?php if ($slides): ?>
                <?php foreach ($slides as $slide):
                    $category   = $slide['dual_carousel_category'] ?? '';
                    $title      = $slide['dual_carousel_title'] ?? '';
                    $image      = $slide['dual_carousel_image']['sizes']['large'] ?? '';
                    $text       = $slide['dual_carousel_text'] ?? '';
                    $button     = $slide['dual_carousel_button'] ?? null;
                    $image_url  = $image ?: get_template_directory_uri() . '/images/placeholder.png';
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
                                    <a href="<?php echo esc_url($button['url']); ?>" class="animate__animated btn <?php echo esc_attr($theme_button_style); ?> outline" target="<?php echo esc_attr($button['target']); ?>">
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
        <div class="carousel-navigation <?php echo esc_attr($arrow_color); ?>">
            <div class="carousel-navigation-inner">
                <div class="swiper-button-prev-dual-carousel">
                    <img src="<?php echo esc_url(get_template_directory_uri() . "/images/left-arrow-{$arrow_color}.svg"); ?>" alt="Previous">
                </div>
                <div class="swiper-button-next-dual-carousel">
                    <img src="<?php echo esc_url(get_template_directory_uri() . "/images/right-arrow-{$arrow_color}.svg"); ?>" alt="Next">
                </div>
            </div>
        </div>
    </div>

    <div class="swiper swiper-dual-carousel-image">
        <div class="swiper-wrapper">
            <?php if ($slides): ?>
                <?php foreach ($slides as $slide):
                    $bg_image_url = $slide['dual_carousel_bg_image']['sizes']['large'] ?? get_template_directory_uri() . '/images/placeholder.png';
                    $bg_alt = $slide['dual_carousel_title'] ?? 'Carousel Background';
                ?>
                    <div class="swiper-slide">
                        <div class="swiper-inner white-text">
                            <img
                                src="<?php echo esc_url($bg_image_url); ?>"
                                alt="<?php echo esc_attr($bg_alt); ?>"
                                <?php echo $use_black_arrows ? 'style="object-fit: contain !important;"' : ''; ?> />
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>