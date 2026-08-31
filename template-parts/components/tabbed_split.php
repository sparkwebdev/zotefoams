<?php
// Get field data using safe helper functions
$tab_overline = zotefoams_get_sub_field_safe('tabbed_split_overline', '', 'string');
$tab_text     = zotefoams_get_sub_field_safe('tabbed_split_text', '', 'string');
$tabs         = zotefoams_get_sub_field_safe('tabbed_split_tabs', [], 'array');

// Generate classes to match original structure exactly
$wrapper_classes = 'cont-xs tabs-container padding-t-b-100 theme-none';
$content_wrapper_classes = 'content-container light-grey-bg';

// Unique per instance so tab/panel IDs can't collide if two tabbed_split
// components on the same page happen to share a tab title.
$component_id = 'tabbed-split-' . uniqid();
?>

<div class="<?php echo $wrapper_classes; ?>" data-js="tabs-container">
    <?php if ($tab_overline || $tab_text) : ?>
        <div class="tabs-intro text-center margin-b-30">
            <?php if ($tab_overline) : ?>
                <p class="margin-b-15"><?php echo esc_html($tab_overline); ?></p>
            <?php endif; ?>
            <?php if ($tab_text) : ?>
                <p class="fs-500 fw-bold"><?php echo esc_html($tab_text); ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if ($tabs) : ?>
        <div class="tabs" role="tablist">
            <?php foreach ($tabs as $index => $tab) :
                $tab_id    = $component_id . '-' . sanitize_title($tab['tabbed_split_tab_title']);
                $icon      = $tab['tabbed_split_tab_icon'];
                $is_active = $index === 0;
            ?>
                <button
                    type="button"
                    class="tab <?php echo $is_active ? 'active' : ''; ?>"
                    role="tab"
                    id="tab-btn-<?php echo esc_attr($tab_id); ?>"
                    aria-controls="<?php echo esc_attr($tab_id); ?>"
                    aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
                    tabindex="<?php echo $is_active ? '0' : '-1'; ?>"
                    data-tab="<?php echo esc_attr($tab_id); ?>"
                    data-js="tab"
                >
                    <?php if ($icon) : ?>
                        <?php echo Zotefoams_Image_Helper::render_image($icon, [
                            'alt' => $tab['tabbed_split_tab_title'],
                            'size' => 'large'
                        ]); ?>
                    <?php endif; ?>
                    <p><?php echo esc_html($tab['tabbed_split_tab_title']); ?></p>
                </button>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<div class="<?php echo $content_wrapper_classes; ?>" data-js="content-container">
    <?php if ($tabs) : ?>
        <?php foreach ($tabs as $index => $tab) :
            $tab_id     = $component_id . '-' . sanitize_title($tab['tabbed_split_tab_title']);
            $title      = $tab['tabbed_split_content_title'];
            $text       = $tab['tabbed_split_content_text'];
            $button     = $tab['tabbed_split_button'];
            $image      = $tab['tabbed_split_image'];
            $image_url  = Zotefoams_Image_Helper::get_image_url($image, 'large', 'tabbed-split');
            $is_active  = $index === 0 ? 'active' : '';
        ?>
            <div
                class="tab-content <?php echo $is_active ? 'active' : ''; ?>"
                id="<?php echo esc_attr($tab_id); ?>"
                role="tabpanel"
                aria-labelledby="tab-btn-<?php echo esc_attr($tab_id); ?>"
                aria-hidden="<?php echo $is_active ? 'false' : 'true'; ?>"
                data-js="tab-content"
            >
                <div class="tab-content__inner">
                    <div>
                        <div class="padding-t-b-100">
                            <div class="top-content">
                                <?php if ($title) : ?>
                                    <p class="fs-400 fw-bold margin-b-15"><?php echo esc_html($title); ?></p>
                                <?php endif; ?>

                                <?php if ($text) : ?>
                                    <div class="fs-300 grey-text margin-b-70"><?php echo wp_kses_post($text); ?></div>
                                <?php endif; ?>
                            </div>

                            <?php echo Zotefoams_Button_Helper::render($button, ['style' => 'arrow']); ?>
                        </div>
                    </div>

                    <div class="image-cover" style="background-image:url('<?php echo esc_url($image_url); ?>');"></div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>