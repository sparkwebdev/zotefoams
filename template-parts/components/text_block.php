<?php
// Get field data using safe helper functions
$overline = zotefoams_get_sub_field_safe('text_block_overline', '', 'string');
$text     = get_sub_field('text_block_text'); // Keep HTML content intact

// Generate classes to match original structure exactly
$wrapper_classes = 'text-block cont-m padding-t-b-100 theme-none';
?>

<div class="<?php echo $wrapper_classes; ?>">
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