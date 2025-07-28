<?php
// Get field data using safe helper functions
$image      = zotefoams_get_sub_field_safe('split_video_two_image', [], 'image');
$video_url  = zotefoams_get_sub_field_safe('split_video_two_video_url', '', 'string');
$title      = zotefoams_get_sub_field_safe('split_video_two_title', '', 'string');
$text       = get_sub_field('split_video_two_text'); // Keep HTML intact

$image_url = Zotefoams_Image_Helper::get_image_url($image, 'large', 'split-video');

// Generate classes to match original structure exactly
$wrapper_classes = 'split-video-two black-bg white-text theme-dark';
?>

<div class="<?php echo $wrapper_classes; ?>">
    <div class="padding-t-b-100">
        <?php if ($title) : ?>
            <p class="fs-600 fw-regular margin-b-30"><?php echo esc_html($title); ?></p>
        <?php endif; ?>

        <?php if ($text) : ?>
            <span class="fs-300 text-margin">
                <?php echo wp_kses_post($text); ?>
            </span>
        <?php endif; ?>
    </div>

    <div class="video-container image-cover" style="background-image:url('<?php echo esc_url($image_url); ?>');">
        <?php if ($video_url) : ?>
            <button type="button" class="video-trigger" data-modal-trigger="video" data-video-url="<?php echo esc_url($video_url); ?>" aria-label="<?php esc_attr_e('Play Video', 'zotefoams'); ?>">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/images/youtube-play.svg'); ?>" alt="" />
            </button>
        <?php endif; ?>
    </div>
</div>

<?php
// Output video overlay container in footer
require_video_overlay();
