<?php
$title = get_sub_field('split_text_title');
$text  = get_sub_field('split_text_text');
?>

<div class="split-text cont-m padding-t-100 padding-b-80 grey-text theme-none">
    <?php if ($title) : ?>
        <div class="split-text__title margin-b-20 fs-600 fw-semibold">
            <?php echo wp_kses_post($title); ?>
        </div>
    <?php endif; ?>

    <?php if ($text) : ?>
        <div class="split-text__content grey-text fs-300">
            <?php echo do_shortcode($text);  ?>
        </div>
    <?php endif; ?>
</div>