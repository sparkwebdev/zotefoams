<?php
$position  = zotefoams_get_sub_field_safe('image_left_right_position', false, 'boolean');
$image     = zotefoams_get_sub_field_safe('image_left_right_image', [], 'array');
$heading   = zotefoams_get_sub_field_safe('image_left_right_heading', '', 'string');
$text      = get_sub_field('image_left_right_text');
$icon_list = zotefoams_get_sub_field_safe('image_left_right_icon_list', [], 'array');

$image_url = Zotefoams_Image_Helper::get_image_url($image, 'large', 'image-left-right');

$component_classes = 'image-left-right';
if ($position) {
    $component_classes .= ' image-left-right--reverse';
}

$wrapper_classes = Zotefoams_Theme_Helper::get_wrapper_classes([
    'component' => $component_classes,
    'theme'     => 'light',
    'spacing'   => '',
    'container' => '',
]);
?>

<div class="<?php echo $wrapper_classes; ?>">
    <?php if ($image_url): ?>
        <div class="image-left-right__image image-cover" style="background-image:url('<?php echo esc_url($image_url); ?>');"></div>
    <?php endif; ?>

    <div class="image-left-right__content">
        <div class="image-left-right__content-inner">
            <?php if ($heading): ?>
                <h2 class="fs-600 fw-semibold margin-b-30"><?php echo esc_html($heading); ?></h2>
            <?php endif; ?>

            <?php if ($text): ?>
                <div class="margin-b-50 grey-text">
                    <?php echo wp_kses_post($text); ?>
                </div>
            <?php endif; ?>

            <?php if ($icon_list): ?>
                <div class="image-left-right__icon-list">
                    <?php foreach ($icon_list as $item):
                        $icon = $item['image_left_right_icon'];
                        $icon_text = $item['image_left_right_icon_text'];
                    ?>
                        <div class="image-left-right__icon-item">
                            <?php if ($icon): ?>
                                <?php
                                echo Zotefoams_Image_Helper::render_image($icon, [
                                    'alt' => '',
                                    'size' => 'thumbnail'
                                ]);
                                ?>
                            <?php endif; ?>
                            <?php if ($icon_text): ?>
                                <div class="grey-text">
                                    <?php echo wp_kses_post($icon_text); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
