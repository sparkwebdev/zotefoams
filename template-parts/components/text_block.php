<?php 
// Allow for passed variables, as well as ACF values
$overline = get_sub_field('text_block_overline') ;
$text = get_sub_field('text_block_text');
?>
<div class="text-block cont-m margin-b-100">
	<div class="text-block-inner">
		<?php if ($overline): ?>
			<p class="margin-b-20"><?php echo esc_html( $overline ); ?></p>
		<?php endif; ?>
		<?php if ($text): ?>
			<div class="grey-text fs-600 fw-semibold"><?php echo esc_html($text); ?></div>
		<?php endif; ?>
	</div>
</div>