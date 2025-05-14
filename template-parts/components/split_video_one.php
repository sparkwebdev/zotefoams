<?php
$image       = get_sub_field('split_video_one_image');
$video_url   = get_sub_field('split_video_one_video_url');
$title       = get_sub_field('split_video_one_title');
$text        = get_sub_field('split_video_one_text');
$extra_text  = get_sub_field('split_video_one_extra_text');
$link        = get_sub_field('split_video_one_link');

$image_url = $image ? $image['sizes']['large'] : get_template_directory_uri() . '/images/placeholder.png';
?>

<?php if (is_page('Sustainability')) : ?>

	<div class="split-grey half-half cont-m padding-b-100 theme-none">
		<div class="half image-cover" style="background-image:url('<?php echo esc_url($image_url); ?>');"></div>

		<div class="half light-grey-bg padding-50">
			<div class="top">
				<?php if ($title) : ?>
					<p class="fs-200 fw-regular margin-b-10"><?php echo esc_html($title); ?></p>
				<?php endif; ?>

				<?php if ($text) : ?>
					<div class="fs-400 fw-semibold margin-b-10"><?php echo wp_kses_post($text); ?></div>
				<?php endif; ?>

				<?php if ($extra_text) : ?>
					<span class="fs-300 text-margin margin-b-30"><?php echo wp_kses_post($extra_text); ?></span>
				<?php endif; ?>
			</div>

			<div class="bottom">
				<?php if ($link) : ?>
					<a href="<?php echo esc_url($link['url']); ?>" class="hl arrow" target="<?php echo esc_attr($link['target']); ?>">
						<?php echo esc_html($link['title']); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</div>

<?php else : ?>

	<div class="split-video-one half-half cont-m padding-t-b-100 theme-none">
		<div class="half video-container image-cover" style="background-image:url('<?php echo esc_url($image_url); ?>');">
			<?php if ($video_url) : ?>
				<a href="<?php echo esc_url($video_url); ?>" class="video-link open-video-overlay" rel="noopener noreferrer">
					<img src="<?php echo esc_url(get_template_directory_uri() . '/images/youtube-play.svg'); ?>" alt="<?php esc_attr_e('Play Video', 'zotefoams'); ?>" />
				</a>
			<?php endif; ?>
		</div>

		<div class="half">
			<?php if ($title) : ?>
				<p class="fs-200 fw-regular margin-b-30"><?php echo esc_html($title); ?></p>
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

<?php endif; ?>

<!-- Video Overlay Structure -->
<?php require_video_overlay(); ?>