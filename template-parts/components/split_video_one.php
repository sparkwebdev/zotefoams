<?php 
// Allow for passed variables, as well as ACF values
$image = isset($image) ? $image : get_sub_field('split_video_one_image');
$video_url = isset($video_url) ? $video_url : get_sub_field('split_video_one_video_url');
$title = isset($title) ? $title : get_sub_field('split_video_one_title');
$text = isset($text) ? $text : get_sub_field('split_video_one_text');
$extra_text = isset($extra_text) ? $extra_text : get_sub_field('split_video_one_extra_text');

// Extract 'large' size image URL from Image Array, or use placeholder.png as fallback
$image_url = $image ? $image['sizes']['large'] : '';
?>

<div class="split-video-one half-half cont-m padding-t-b-100">
    <div class="half video-container image-cover" 
        style="background-image:url('<?php echo esc_url($image_url ? $image_url : get_template_directory_uri() . "/images/placeholder.png"); ?>');">
        <?php if ($video_url): ?>
            <a href="<?php echo esc_url($video_url); ?>" class="video-link open-video-overlay" rel="noopener noreferrer">
                <img src="<?php echo get_template_directory_uri(); ?>/images/youtube-play.svg" />
            </a>
        <?php endif; ?>
    </div>
    <div class="half">
        <?php if ($title): ?>
            <p class="fs-200 fw-regular margin-b-30"><?php echo esc_html($title); ?></p>
        <?php endif; ?>
        <?php if ($text): ?>
            <p class="fs-600 fw-semibold margin-b-40"><?php echo wp_kses_post($text); ?></p>
        <?php endif; ?>
        <?php if ($extra_text): ?>
            <span class="fs-300 text-margin margin-b-50">
                <p><?php echo wp_kses_post($extra_text); ?></p>
            </span>
        <?php endif; ?>
    </div>
</div>

<!-- Video Overlay Structure -->
<div id="video-overlay" style="display:none;">
    <div id="overlay-content">
        <button id="close-video">Close</button>
        <iframe id="video-iframe" width="100%" height="100%" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>
</div>
