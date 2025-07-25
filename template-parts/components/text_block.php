<?php
$overline = get_sub_field('text_block_overline');
$text     = get_sub_field('text_block_text');
?>

<div class="text-block cont-m padding-t-b-100 theme-none">
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