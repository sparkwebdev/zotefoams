<?php 
// Allow for passed variables, as well as ACF values
$image = get_sub_field('split_video_two_image');
$video_url = get_sub_field('split_video_two_video_url');
$title = get_sub_field('split_video_two_title');
$text = get_sub_field('split_video_two_text');

// Extract 'large' size image URL from Image Array
$image_url = $image ? $image['sizes']['large'] : '';
?>

<!-- Half & Half image text block -->
<div class="split-video-two black-bg white-text theme-none">
    <div class="split-video-two-inner half-half">
        <div class="half padding-t-b-100 text-content">
            <?php if ($title): ?>
                <p class="fs-600 fw-regular margin-b-30"><?php echo esc_html($title); ?></p>
            <?php endif; ?>
            <?php if ($text): ?>
                <span class="fs-300 text-margin">
                    <?php echo wp_kses_post($text); ?>
                </span>
            <?php endif; ?>
        </div>
        <div class="half video-container image-cover" 
            style="background-image:url('<?php echo esc_url($image_url ? $image_url : get_template_directory_uri() . "/images/placeholder.png"); ?>');">
            <?php if ($video_url): ?>
                <a href="<?php echo esc_url($video_url); ?>" class="video-link open-video-overlay" rel="noopener noreferrer">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/youtube-play.svg" />
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Video Overlay Structure -->
<?php
// This call sets the flag so the overlay is output in wp_footer.
require_video_overlay();
?>