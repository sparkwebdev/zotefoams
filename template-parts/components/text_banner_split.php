<?php
// Get field data using safe helper functions
$image = zotefoams_get_sub_field_safe('text_banner_split_image', [], 'image');
$title = zotefoams_get_sub_field_safe('text_banner_split_title', '', 'string');
$text  = get_sub_field('text_banner_split_text'); // Keep HTML intact
$link  = zotefoams_get_sub_field_safe('text_banner_split_link', [], 'url');

$image_url = Zotefoams_Image_Helper::get_image_url($image, 'large', 'text-banner-split');
if (!$image_url && has_post_thumbnail()) {
    $image_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
}

// Generate classes to match original structure exactly
$wrapper_classes = 'text-banner-split theme-dark';
?>

<div class="<?php echo $wrapper_classes; ?>">
    <div class="text-banner-split__image image-cover" style="background-image:url('<?php echo esc_url($image_url); ?>');"></div>

    <div class="black-bg white-text padding-100">
        <div class="text-banner-split__text">
            <?php if ($title) : ?>
                <p class="fs-200 fw-regular margin-b-30"><?php echo esc_html($title); ?></p>
            <?php endif; ?>

            <?php if (is_page('Investors') || is_page('Share Price')) : ?>
                <?php get_template_part('template-parts/components/widgets/share_price_widget'); ?>
            <?php endif; ?>

            <?php if ($text) : ?>
                <div class="fs-600 fw-regular margin-b-100"><?php echo wp_kses_post($text); ?></div>
            <?php endif; ?>

            <?php if ($link) : ?>
                <?php echo Zotefoams_Button_Helper::render($link, [
                    'style' => 'white',
                    'class' => 'fw-regular'
                ]); ?>
            <?php endif; ?>
        </div>
    </div>
</div>