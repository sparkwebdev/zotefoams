<?php 
// Allow for passed variables, as well as ACF values
$title = get_sub_field('split_text_title');
$text = get_sub_field('split_text_text');
?>
<div class="split-text cont-m margin-t-100 margin-b-80 grey-text theme-none">
    <?php if ($title): ?>
        <div class="split-text-title margin-b-20 fs-600 fw-bold">
            <?php echo wp_kses_post( $title ); ?>
        </div>
    <?php endif; ?>
    <?php if ($text): ?>
        <div class="split-text-content grey-text fs-300">
            <?php echo wp_kses_post( $text ); ?>
        </div>
    <?php endif; ?>
</div>
