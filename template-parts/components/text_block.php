<?php
// Get field data using safe helper functions
$overline = zotefoams_get_sub_field_safe('text_block_overline', '', 'string');
$text     = zotefoams_get_sub_field_safe('text_block_text', '', 'string');

// Get theme-aware wrapper classes
$wrapper_classes = Zotefoams_Theme_Helper::get_wrapper_classes([
    'component' => 'text-block',
    'theme'     => 'none',
    'spacing'   => 'padding-t-b-100',
]);
?>

<div class="<?php echo $wrapper_classes; ?>">
	<div class="cont-m">
		<div class="text-block__inner">
			<?php if ($overline) : ?>
				<p class="margin-b-20"><?php echo esc_html($overline); ?></p>
			<?php endif; ?>

			<?php if ($text) : ?>
				<div class="grey-text fs-600 fw-semibold">
					<?php echo wp_kses_post($text); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>