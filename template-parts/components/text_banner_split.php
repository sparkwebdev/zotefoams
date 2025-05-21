<?php
$image = get_sub_field('text_banner_split_image');
$title = get_sub_field('text_banner_split_title');
$text  = get_sub_field('text_banner_split_text');
$link  = get_sub_field('text_banner_split_link'); // ACF Link field (array)

if ($image) {
    $image_url = $image['sizes']['large'];
} elseif (has_post_thumbnail()) {
    $image_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
} else {
    $image_url = get_template_directory_uri() . '/images/placeholder.png';
}
?>

<div class="text-banner-split half-half theme-dark">
    <div class="half text-banner-split__image image-cover" style="background-image:url('<?php echo esc_url($image_url); ?>');"></div>

    <div class="half black-bg white-text padding-100">
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
                <a href="<?php echo esc_url($link['url']); ?>" class="btn white outline fw-regular" target="<?php echo esc_attr($link['target']); ?>">
                    <?php echo esc_html($link['title']); ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>