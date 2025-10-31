<?php
$overline = get_sub_field('panel_switcher_overline');
$intro    = get_sub_field('panel_switcher_intro');
$panels   = zotefoams_get_sub_field_safe('panel_switcher_panels', [], 'array');

$wrapper_classes = Zotefoams_Theme_Helper::get_wrapper_classes([
    'component' => 'panel-switcher',
    'theme'     => 'none',
    'spacing'   => 'padding-t-b-100',
    'container' => '',
]);

// Generate unique ID for this component instance
$component_id = 'panel-switcher-' . uniqid();
$arrow_svg = get_template_directory_uri() . '/images/tab-arrow-right.svg';
?>

<div class="<?php echo $wrapper_classes; ?>">
    <?php if ($intro): ?>
        <?php
        echo zotefoams_render_content_block(
            '<h3 class="fs-600 grey-text fw-semibold">' . wp_kses_post($intro) . '</h3>',
            ['spacing' => 'margin-b-50']
        );
        ?>
    <?php endif; ?>
    <div class="cont-m">

        <?php if ($overline): ?>
            <p class="panel-switcher__overline margin-b-30 grey-text"><?php echo esc_html($overline); ?></p>
        <?php endif; ?>

        <?php if ($panels): ?>
            <!-- Desktop: Radio inputs for CSS-only switching -->
            <?php foreach ($panels as $index => $panel): ?>
                <input
                    type="radio"
                    name="<?php echo esc_attr($component_id); ?>"
                    id="<?php echo esc_attr($component_id . '-' . $index); ?>"
                    class="panel-switcher__radio"
                    role="tab"
                    aria-controls="<?php echo esc_attr($component_id . '-content-' . $index); ?>"
                    aria-selected="<?php echo ($index === 0) ? 'true' : 'false'; ?>"
                    <?php echo ($index === 0) ? 'checked' : ''; ?>
                >
            <?php endforeach; ?>

            <div class="panel-switcher__wrapper" data-panel-switcher="<?php echo esc_attr($component_id); ?>">
                <!-- Desktop navigation -->
                <div class="panel-switcher__nav" role="tablist" aria-label="<?php echo esc_attr($overline ?: __('Panel navigation', 'zotefoams')); ?>">
                    <?php foreach ($panels as $index => $panel): ?>
                        <label for="<?php echo esc_attr($component_id . '-' . $index); ?>" class="panel-switcher__nav-item">
                            <img src="<?php echo $arrow_svg; ?>" alt="" class="panel-switcher__arrow">
                            <span class="panel-switcher__title"><?php echo esc_html($panel['panel_switcher_title']); ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>

                <!-- Panel content -->
                <div class="panel-switcher__panels">
                    <?php foreach ($panels as $index => $panel): ?>
                        <div class="panel-switcher__panel" data-panel="<?php echo $index; ?>">
                            <!-- Mobile: h2 header (becomes button with JS) -->
                            <h2 class="panel-switcher__accordion-header" data-accordion-header>
                                <img src="<?php echo $arrow_svg; ?>" alt="" class="panel-switcher__accordion-arrow">
                                <span class="panel-switcher__accordion-title"><?php echo esc_html($panel['panel_switcher_title']); ?></span>
                            </h2>

                            <div class="panel-switcher__content" id="<?php echo esc_attr($component_id . '-content-' . $index); ?>" role="tabpanel" aria-labelledby="<?php echo esc_attr($component_id . '-' . $index); ?>">
                                <div class="panel-switcher__content-inner">
                                    <?php if ($panel['panel_switcher_icon']): ?>
                                        <div class="panel-switcher__icon">
                                            <?php
                                            echo Zotefoams_Image_Helper::render_image($panel['panel_switcher_icon'], [
                                                'alt' => '',
                                                'size' => 'thumbnail-square'
                                            ]);
                                            ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($panel['panel_switcher_text']): ?>
                                        <div class="panel-switcher__text">
                                            <?php echo wp_kses_post($panel['panel_switcher_text']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
