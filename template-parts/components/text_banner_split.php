<?php 
// Allow for passed variables, as well as ACF values
$image = get_sub_field('text_banner_split_image');
$title = get_sub_field('text_banner_split_title');
$text = get_sub_field('text_banner_split_text');
$link = get_sub_field('text_banner_split_link'); // ACF Link field returns an array

// Extract 'large' size image URL from Image Array, with fallback to placeholder.png
$image_url = $image ? $image['sizes']['large'] : '';
?>

<div class="text-banner-split half-half theme-dark">
    <div class="half video-container image-cover" style="background-image:url('<?php echo esc_url($image_url ? $image_url : get_template_directory_uri() . "/images/placeholder.png"); ?>');">
    </div>
    <div class="half black-bg white-text padding-100">
        <div class="text-banner-text">
            <?php if ($title): ?>
                <p class="fs-200 fw-regular margin-b-30"><?php echo esc_html($title); ?></p>
            <?php endif; ?>
            <?php if ($text): ?>
                <div class="fs-600 fw-semibold margin-b-100"><?php echo wp_kses_post($text); ?></div>
            <?php endif; ?>
            <?php if ($link): ?>
                <a href="<?php echo esc_url($link['url']); ?>" class="btn white outline" target="<?php echo esc_attr($link['target']); ?>">
                    <?php echo esc_html($link['title']); ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>
