<?php
$title   = zotefoams_get_sub_field_safe('split_accordion_image_title', '', 'string');
$items   = zotefoams_get_sub_field_safe('split_accordion_image_items', [], 'array');
$variant = zotefoams_get_sub_field_safe('split_accordion_image_variant', false, 'bool');

$first_item_image     = !empty($items) && !empty($items[0]['split_accordion_image_item_image']) ? $items[0]['split_accordion_image_item_image'] : [];
$default_image_url    = Zotefoams_Image_Helper::get_image_url($first_item_image, 'large', 'split-accordion-image', false);

$wrapper_classes = $variant
    ? 'split-accordion-image split-accordion-image--variant black-bg white-text padding-t-b-70 theme-dark'
    : 'split-accordion-image light-grey-bg padding-t-b-70 theme-light';
?>

<div class="<?php echo esc_attr($wrapper_classes); ?>">

    <?php if ($default_image_url): ?>
        <div class="split-accordion-image__image" data-default-image="<?php echo esc_url($default_image_url); ?>" data-current-image="<?php echo esc_url($default_image_url); ?>">
            <div class="split-accordion-image__image-layer split-accordion-image__image-layer--a image-cover" style="background-image:url('<?php echo esc_url($default_image_url); ?>');"></div>
            <div class="split-accordion-image__image-layer split-accordion-image__image-layer--b image-cover"></div>
        </div>
    <?php endif; ?>

    <div class="split-accordion-image__content<?php echo $default_image_url ? '' : ' cont-m'; ?> padding-t-b-70">
        <div class="split-accordion-image__content-inner accordion">

            <?php if ($title): ?>
                <h2 class="split-accordion-image__title fs-600 fw-semibold margin-b-40"><?php echo esc_html($title); ?></h2>
            <?php endif; ?>

            <?php if ($items): ?>
                <div class="accordion--compact">
                    <div class="accordion-items">
                        <?php foreach ($items as $item):
                            $item_title      = $item['split_accordion_image_item_title'] ?? '';
                            $item_content    = $item['split_accordion_image_item_content'] ?? '';
                            $item_link       = $item['split_accordion_image_item_link'] ?? [];
                            $item_image      = $item['split_accordion_image_item_image'] ?? [];
                            $item_image_url  = !empty($item_image) ? Zotefoams_Image_Helper::get_image_url($item_image, 'large', 'split-accordion-image', false) : '';
                        ?>
                            <?php if ($item_title): ?>
                            <div class="accordion-item">
                                <button class="accordion-header fs-200 fw-regular" data-js="accordion-header"<?php if ($item_image_url): ?> data-image="<?php echo esc_url($item_image_url); ?>"<?php endif; ?>>
                                    <?php echo esc_html($item_title); ?>
                                    <span class="toggle-icon" aria-hidden="true" data-js="toggle-icon">+</span>
                                </button>

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
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>

</div>
