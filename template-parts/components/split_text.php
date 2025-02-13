<?php 
// Allow for passed variables, as well as ACF values
$title = get_sub_field('split_text_title');
$text = get_sub_field('split_text_text');
?>
<div class="split-text cont-m margin-b-100">
    <div class="split-text-inner">
        <?php if ($title): ?>
            <div class="split-text-title margin-b-20 fs-700 fw-bold">
                <?php echo wp_kses_post( $title ); ?>
            </div>
        <?php endif; ?>
        <?php if ($text): ?>
            <div class="split-text-content grey-text fs-600 fw-semibold">
                <?php echo wp_kses_post( $text ); ?>
            </div>
        <?php endif; ?>
    </div>
</div>
