<?php
$overline = get_sub_field('icons_grid_overline');
$intro = get_sub_field('icons_grid_intro');
$items = zotefoams_get_sub_field_safe('icons_grid_items', [], 'array');

$wrapper_classes = Zotefoams_Theme_Helper::get_wrapper_classes([
    'component' => 'icons-grid',
    'theme'     => 'none',
    'spacing'   => 'padding-t-b-100',
    'container' => '',
]);
?>

<div class="<?php echo $wrapper_classes; ?>">

    <?php if ($overline || $intro): ?>
        <?php
        $content = '';
        if ($overline) {
            $content .= '<p class="margin-b-20">' . wp_kses_post($overline) . '</p>';
        }
        if ($intro) {
            $content .= '<h3 class="fs-600 grey-text fw-semibold">' . wp_kses_post($intro) . '</h3>';
        }
        echo zotefoams_render_content_block($content, [
            'spacing' => 'margin-b-70'
        ]);
        ?>
    <?php endif; ?>

    <?php if ($items): ?>
        <div class="icons-grid__wrapper cont-m">
            <div class="icons-grid__grid">
                <?php foreach ($items as $item): ?>
                    <div class="icons-grid__item">
                        <?php if ($item['icon_image']): ?>
                            <?php
                            echo Zotefoams_Image_Helper::render_image($item['icon_image'], [
                                'alt' => $item['icon_image']['alt'] ?? '',
                                'size' => 'thumbnail-square',
                                'class' => 'icons-grid__icon'
                            ]);
                            ?>
                        <?php endif; ?>

                        <div class="icons-grid__content">

                            <?php if ($item['title']): ?>
                                <h4 class="icons-grid__title"><?php echo esc_html($item['title']); ?></h4>
                            <?php endif; ?>

                            <?php if ($item['text']): ?>
                                <div class="icons-grid__text">
                                    <?php echo wp_kses_post(wpautop($item['text'])); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if ($item['background_image']): ?>
                            <div class="icons-grid__background">
                                <?php
                                echo Zotefoams_Image_Helper::render_image($item['background_image'], [
                                    'alt' => '',
                                    'size' => 'medium'
                                ]);
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

</div>