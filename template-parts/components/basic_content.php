<?php 
// Allow for passed variables, as well as ACF values
$text = get_sub_field('basic_content_copy') ;
?>
<div class="generic-text cont-m margin-t-100 margin-b-100 theme-none">
		<?php if ($text): ?>
			<?php echo wp_kses_post( $text ); ?>
		<?php endif; ?>
</div>