<?php
// Get field data using safe helper functions
$image       = zotefoams_get_sub_field_safe('split_video_one_image', [], 'image');
$video_url   = zotefoams_get_sub_field_safe('split_video_one_video_url', '', 'string');
$title       = zotefoams_get_sub_field_safe('split_video_one_title', '', 'string');
$text        = get_sub_field('split_video_one_text'); // Keep HTML intact
$extra_text  = get_sub_field('split_video_one_extra_text'); // Keep HTML intact
$link        = zotefoams_get_sub_field_safe('split_video_one_link', [], 'url');
$variant     = zotefoams_get_sub_field_safe('split_video_one_variant', false, 'boolean');

$image_url = Zotefoams_Image_Helper::get_image_url($image, 'large', 'split-video');
$wrapperClass = $variant ? 'split-video-one split-video-one--variant' : 'split-video-one';

// Generate classes to match original structure exactly
$wrapper_classes = $wrapperClass . ' cont-m theme-none padding-t-b-100';
?>

<div class="<?php echo $wrapper_classes; ?>">
	<div class="video-container image-cover" style="background-image:url('<?php echo esc_url($image_url); ?>');">
		<?php if ($video_url) : ?>
			<button type="button" class="video-trigger" data-modal-trigger="video" data-video-url="<?php echo esc_url($video_url); ?>" aria-label="<?php esc_attr_e('Play Video', 'zotefoams'); ?>">
				<img src="<?php echo esc_url(get_template_directory_uri() . '/images/youtube-play.svg'); ?>" alt="" />
			</button>
		<?php endif; ?>
	</div>

	<div <?php echo $variant ? 'class="light-grey-bg padding-50"' : ''; ?>>
		<?php if ($title) : ?>
			<p class="<?php echo $variant ? 'fs-400 fw-bold margin-b-30' : 'fs-200 fw-regular margin-b-30'; ?>"><?php echo esc_html($title); ?></p>
		<?php endif; ?>

		<?php if ($text) : ?>
			<div class="fs-500 fw-semibold margin-b-40"><?php echo wp_kses_post($text); ?></div>
		<?php endif; ?>

		<?php if ($extra_text) : ?>
			<div class="fs-300 text-margin margin-b-50">
				<?php echo wp_kses_post($extra_text); ?>
			</div>
		<?php endif; ?>

		<?php if ($link) : ?>
			<div class="margin-b-30">
				<a href="<?php echo esc_url($link['url']); ?>" class="hl arrow" target="<?php echo esc_attr($link['target']); ?>">
					<?php echo esc_html($link['title']); ?>
				</a>
			</div>
		<?php endif; ?>
	</div>
</div>



<!-- Video Overlay Structure -->
<?php require_video_overlay(); ?>