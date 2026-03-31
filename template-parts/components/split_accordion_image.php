<?php
$title   = zotefoams_get_sub_field_safe('split_accordion_image_title', '', 'string');
$image   = zotefoams_get_sub_field_safe('split_accordion_image_image', [], 'image');
$items   = zotefoams_get_sub_field_safe('split_accordion_image_items', [], 'array');
$variant = zotefoams_get_sub_field_safe('split_accordion_image_variant', false, 'boolean');

$image_url = Zotefoams_Image_Helper::get_image_url($image, 'large', 'split-accordion-image');

$wrapper_classes = $variant
    ? 'split-accordion-image split-accordion-image--variant black-bg white-text padding-t-b-70 theme-dark'
    : 'split-accordion-image light-grey-bg padding-t-b-70 theme-light';
?>

<div class="<?php echo esc_attr($wrapper_classes); ?>">

    <?php if ($image_url): ?>
        <div class="split-accordion-image__image image-cover" style="background-image:url('<?php echo esc_url($image_url); ?>');"></div>
    <?php endif; ?>

    <div class="split-accordion-image__content padding-t-b-70">
        <div class="split-accordion-image__content-inner">

            <?php if ($title): ?>
                <h2 class="split-accordion-image__title fs-600 fw-semibold margin-b-40"><?php echo esc_html($title); ?></h2>
            <?php endif; ?>

            <?php if ($items): ?>
                <div class="accordion accordion--compact">
                    <div class="accordion-items">
                        <?php foreach ($items as $item):
                            $item_title   = $item['split_accordion_image_item_title'] ?? '';
                            $item_content = $item['split_accordion_image_item_content'] ?? '';
                            $item_link    = $item['split_accordion_image_item_link'] ?? [];
                        ?>
                            <div class="accordion-item">
                                <?php if ($item_title): ?>
                                    <button class="accordion-header fs-200 fw-regular">
                                        <?php echo esc_html($item_title); ?>
                                        <span class="toggle-icon" aria-hidden="true">+</span>
                                    </button>
                                <?php endif; ?>

                                <div class="accordion-content<?php echo $variant ? ' white-text' : ' grey-text'; ?>">
                                    <?php if ($item_content): ?>
                                        <div class="margin-b-20">
                                            <?php echo wp_kses_post($item_content); ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($item_link['url'])): ?>
                                        <?php echo zotefoams_render_link($item_link, [
                                            'class'   => $variant ? 'btn outline white' : 'btn black',
                                            'wrapper' => 'margin-b-20',
                                        ]); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>

</div>
