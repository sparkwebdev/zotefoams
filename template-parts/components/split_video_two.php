<?php 
// Allow for passed variables, as well as ACF values
$image = isset($image) ? $image : get_sub_field('split_video_two_image');
$video_url = isset($video_url) ? $video_url : get_sub_field('split_video_two_video_url');
$title = isset($title) ? $title : get_sub_field('split_video_two_title');
$text = isset($text) ? $text : get_sub_field('split_video_two_text');

// Extract 'large' size image URL from Image Array
$image_url = $image ? $image['sizes']['large'] : '';
?>

<!-- Half & Half image text block -->
<div class="split-video-two black-bg white-text">
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
<div id="video-overlay" style="display:none;">
    <div id="overlay-content">
        <button id="close-video">Close</button>
        <iframe id="video-iframe" width="100%" height="100%" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>
</div>
