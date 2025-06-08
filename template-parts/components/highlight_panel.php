<?php
$image_id = get_sub_field('highlight_panel_background_image');
$lead_text = get_sub_field('highlight_panel_lead_text');
$sub_image_id = get_sub_field('highlight_panel_sub_image');
$sub_title = get_sub_field('highlight_panel_sub_title');
$sub_title_secondary = get_sub_field('highlight_panel_sub_title_secondary');
$image_url = wp_get_attachment_image_url($image_id, 'large') ?: get_template_directory_uri() . '/images/placeholder.png';
if ($sub_image_id) {
	$sub_image_url = wp_get_attachment_image_url($sub_image_id, 'thumbnail-square');
}
?>
<div class="highlight-panel image-cover padding-t-b-100 theme-dark" style="background-image:url(<?php echo esc_url($image_url); ?>);">
	<?php
	$container_class = ($lead_text && strlen(strip_tags($lead_text)) > 200) ? 'cont-s' : ($lead_text ? 'cont-xs' : '');
	?>
	<div class="highlight-panel__inner <?php echo esc_attr($container_class); ?> padding-t-b-70 text-center white-text">
		<?php if ($lead_text) : ?>
			<p class="fw-bold fs-600 margin-b-50">
				<?php echo wp_kses_post($lead_text); ?>
			</p>
		<?php endif; ?>
		<?php if ($sub_image_id) : ?>
			<img src="<?php echo esc_url($sub_image_url); ?>" alt="" class="highlight-panel__sub-image margin-b-20" />
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