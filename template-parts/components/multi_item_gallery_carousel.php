<?php
$title  = zotefoams_get_sub_field_safe('multi_item_gallery_carousel_title', '', 'string');
$slides = zotefoams_get_sub_field_safe('multi_item_gallery_carousel_slides', [], 'array');
?>

<div class="multi-item-gallery-carousel-container padding-t-b-100 theme-none">
    <div class="cont-m">

        <div class="title-strip margin-b-30">
            <?php if ($title) : ?>
                <h3 class="fs-500 fw-600"><?php echo esc_html($title); ?></h3>
            <?php endif; ?>
            <div class="carousel-navigation black" role="group" aria-label="Carousel navigation">
                <div class="carousel-navigation-inner">
                    <button type="button" class="multi-gallery-swiper-button-prev carousel-btn-reset" aria-label="Previous slide" tabindex="0">
                        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/images/left-arrow-black.svg" alt="" role="presentation" />
                    </button>
                    <button type="button" class="multi-gallery-swiper-button-next carousel-btn-reset" aria-label="Next slide" tabindex="0">
                        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/images/right-arrow-black.svg" alt="" role="presentation" />
                    </button>
                </div>
            </div>
        </div>

        <?php if ($slides) : ?>
            <div class="swiper multi-item-gallery-carousel">
                <div class="swiper-wrapper">
                    <?php foreach ($slides as $slide) :
                        $slide_title = $slide['multi_item_gallery_carousel_slide_title'] ?? '';
                        $text        = $slide['multi_item_gallery_carousel_slide_text'] ?? '';
                        $images      = $slide['multi_item_gallery_carousel_slide_image'] ?? [];
                        $button      = $slide['multi_item_gallery_carousel_slide_button'] ?? [];

                        // First image is shown by default
                        $first_image     = !empty($images[0]) ? $images[0] : [];
                        $first_image_url = Zotefoams_Image_Helper::get_image_url($first_image, 'large', 'large', false);
                        $first_image_alt = $first_image['alt'] ?? $slide_title;

                        if (!$slide_title && !$text && !$first_image_url && empty($button)) continue;
                    ?>
                        <div class="swiper-slide">
                            <?php if ($first_image_url) : ?>
                                <div class="multi-item-gallery-carousel__image">
                                    <img class="multi-item-gallery-carousel__slide-image"
                                         src="<?php echo esc_url($first_image_url); ?>"
                                         alt="<?php echo esc_attr($first_image_alt); ?>">
                                </div>
                            <?php endif; ?>
                            <div class="multi-item-gallery-carousel__content light-grey-bg theme-light">

                                <?php if (count($images) > 1) : ?>
                                    <div class="multi-item-gallery-carousel__pills">
                                        <?php foreach ($images as $i => $img) :
                                            $label = !empty($img['caption'])
                                                ? $img['caption']
                                                : (!empty($img['title'])
                                                    ? $img['title']
                                                    : ucfirst((new NumberFormatter('en', NumberFormatter::SPELLOUT))->format($i + 1)));
                                            $img_url = Zotefoams_Image_Helper::get_image_url($img, 'large', 'large', false);
                                            $img_alt = $img['alt'] ?? $label;
                                        ?>
                                            <button
                                                class="multi-item-gallery-carousel__pill<?php echo $i === 0 ? ' active' : ''; ?> fs-100"
                                                aria-pressed="<?php echo $i === 0 ? 'true' : 'false'; ?>"
                                                data-image-url="<?php echo esc_url($img_url); ?>"
                                                data-image-alt="<?php echo esc_attr($img_alt); ?>"
                                            ><?php echo esc_html($label); ?></button>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ($slide_title) : ?>
                                    <h3 class="fs-400 fw-bold margin-t-30"><?php echo esc_html($slide_title); ?></h3>
                                <?php endif; ?>
                                <?php if ($text) : ?>
                                    <div class="multi-item-gallery-carousel__text grey-text margin-t-20 margin-b-40"><?php echo wp_kses_post($text); ?></div>
                                <?php endif; ?>
                                <?php if (!empty($button['url'])) : ?>
                                    <?php echo zotefoams_render_link($button, ['class' => 'btn black outline']); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="multi-gallery-swiper-scrollbar"></div>
            </div>
        <?php endif; ?>

    </div>
</div>
