<?php
$title           = zotefoams_get_sub_field_safe('material_swatches_title', '', 'string');
$items           = zotefoams_get_sub_field_safe('material_swatches_items', [], 'array');
$small_text      = zotefoams_get_sub_field_safe('material_swatches_small_text', '', 'string');
$use_default_cta = zotefoams_get_sub_field_safe('material_swatches_use_default_cta', true, 'bool');
$cta             = zotefoams_get_sub_field_safe('material_swatches_cta', [], 'url');
?>

<div class="material-swatches padding-t-b-100 light-grey-bg theme-light">
    <div class="cont-m">

        <?php if ($title) : ?>
        <div class="text-block">
            <div class="text-block__inner">
                <h2 class="fs-500 fw-semibold margin-b-40"><?php echo esc_html($title); ?></h2>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($items) : ?>
            <div class="material-swatches__items">
                <?php foreach ($items as $item) :
                    $item_title  = $item['material_swatches_item_title'] ?? '';
                    $colour      = $item['material_swatches_item_colour'] ?? '';
                    $image       = $item['material_swatches_item_image'] ?? [];
                    $white_text  = !$colour || zotefoams_hex_text_color($colour, 'loose') === 'light';
                ?>
                    <div class="material-swatches__item" style="<?php echo $colour ? 'background-color:' . esc_attr($colour) . ';' : ''; ?>">
                        <?php if ($item_title) : ?>
                            <span class="material-swatches__item-title fw-semibold<?php echo $white_text ? ' white-text' : ''; ?>"><?php echo esc_html($item_title); ?></span>
                        <?php endif; ?>
                        <?php if (!empty($image['ID'])) :
                            $image_src = wp_get_attachment_image_src($image['ID'], 'thumbnail-square');
                            $image_url = $image_src ? $image_src[0] : ($image['url'] ?? '');
                        ?>
                            <img
                                class="material-swatches__item-image"
                                src="<?php echo esc_url($image_url); ?>"
                                alt="<?php echo esc_attr($image['alt'] ?? $item_title); ?>"
                            >
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($small_text) : ?>
            <p class="fs-100 margin-t-40"><?php echo esc_html($small_text); ?></p>
        <?php endif; ?>

        <?php if ($use_default_cta) : ?>
            <div class="margin-t-40">
                <a href="#contact-forms-item-1" class="btn blue btn--chevron-down">
                    Request A Sample
                </a>
            </div>
        <?php elseif (!empty($cta['url'])) : ?>
            <div class="margin-t-40">
                <?php echo Zotefoams_Button_Helper::render($cta, ['style' => 'primary']); ?>
            </div>
        <?php endif; ?>

    </div>
</div>
