<?php
// Get field data using safe helper functions
$image_id = zotefoams_get_sub_field_safe('highlight_panel_background_image', 0, 'int');
$lead_text = get_sub_field('highlight_panel_lead_text'); // Keep HTML intact
$sub_image_id = zotefoams_get_sub_field_safe('highlight_panel_sub_image', 0, 'int');
$sub_title = zotefoams_get_sub_field_safe('highlight_panel_sub_title', '', 'string');
$sub_title_secondary = get_sub_field('highlight_panel_sub_title_secondary'); // May contain HTML

// Use Image Helper for background image
$image_url = Zotefoams_Image_Helper::get_image_url($image_id, 'large', 'banner');

// Use Image Helper for sub image
$sub_image_url = '';
if ($sub_image_id) {
	$sub_image_url = Zotefoams_Image_Helper::get_image_url($sub_image_id, 'thumbnail-square', 'thumbnail-square');
}

// Get theme-aware wrapper classes
$wrapper_classes = Zotefoams_Theme_Helper::get_wrapper_classes([
    'component' => 'highlight-panel image-cover',
    'theme'     => 'dark',
    'spacing'   => 'padding-t-b-100',
    'container' => '', // No container needed at wrapper level
]);
?>
<div class="<?php echo esc_attr($wrapper_classes); ?>" style="background-image:url(<?php echo esc_url($image_url); ?>);">
	<?php
	$container_class = ($lead_text && strlen(strip_tags($lead_text)) > 200) ? 'cont-s' : ($lead_text ? 'cont-xs' : '');
	$text_color = Zotefoams_Theme_Helper::get_text_color('dark');
	?>
	<div class="highlight-panel__inner <?php echo esc_attr($container_class); ?> padding-t-b-70 text-center <?php echo esc_attr($text_color); ?>">
		<?php if ($lead_text) : ?>
			<p class="fw-bold fs-600 margin-b-50">
				<?php echo wp_kses_post($lead_text); ?>
			</p>
		<?php endif; ?>
		<?php if ($sub_image_id) : ?>
			<?php echo Zotefoams_Image_Helper::render_image($sub_image_id, [
				'size'    => 'thumbnail-square',
				'context' => 'thumbnail-square',
				'class'   => 'highlight-panel__sub-image margin-b-20',
				'alt'     => ''
			]); ?>
		<?php endif; ?>
		<?php if ($sub_title) : ?>
			<p class="uppercase fw-extrabold">
				<?php echo esc_html($sub_title); ?>
			</p>
		<?php endif; ?>
		<?php if ($sub_title_secondary) : ?>
			<p class="uppercase">
				<?php echo wp_kses_post($sub_title_secondary); ?>
			</p>
		<?php endif; ?>
	</div>
</div>