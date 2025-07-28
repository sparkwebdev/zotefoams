<?php
// Get field data using safe helper functions
$tab_overline = zotefoams_get_sub_field_safe('tabbed_split_overline', '', 'string');
$tab_text     = zotefoams_get_sub_field_safe('tabbed_split_text', '', 'string');
$tabs         = zotefoams_get_sub_field_safe('tabbed_split_tabs', [], 'array');

// Generate classes to match original structure exactly
$wrapper_classes = 'cont-xs tabs-container padding-t-b-100 theme-none';
$content_wrapper_classes = 'content-container light-grey-bg';
?>

<div class="<?php echo $wrapper_classes; ?>">
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
        <div class="tabs">
            <?php foreach ($tabs as $index => $tab) :
                $tab_id    = sanitize_title($tab['tabbed_split_tab_title']);
                $icon      = $tab['tabbed_split_tab_icon'];
                $is_active = $index === 0 ? 'active' : '';
            ?>
                <div class="tab <?php echo esc_attr($is_active); ?>" data-tab="<?php echo esc_attr($tab_id); ?>">
                    <?php if ($icon) : ?>
                        <img src="<?php echo esc_url($icon['url']); ?>" alt="<?php echo esc_attr($tab['tabbed_split_tab_title']); ?>" />
                    <?php endif; ?>
                    <p><?php echo esc_html($tab['tabbed_split_tab_title']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<div class="<?php echo $content_wrapper_classes; ?>">
    <?php if ($tabs) : ?>
        <?php foreach ($tabs as $index => $tab) :
            $tab_id     = sanitize_title($tab['tabbed_split_tab_title']);
            $title      = $tab['tabbed_split_content_title'];
            $text       = $tab['tabbed_split_content_text'];
            $button     = $tab['tabbed_split_button'];
            $image      = $tab['tabbed_split_image'];
            $image_url  = Zotefoams_Image_Helper::get_image_url($image, 'large', 'tabbed-split');
            $is_active  = $index === 0 ? 'active' : '';
        ?>
            <div class="tab-content <?php echo esc_attr($is_active); ?>" id="<?php echo esc_attr($tab_id); ?>">
                <div class="tab-content__inner">
                    <div>
                        <div class="all-content padding-t-b-100">
                            <div class="top-content">
                                <?php if ($title) : ?>
                                    <p class="fs-400 fw-bold margin-b-15"><?php echo esc_html($title); ?></p>
                                <?php endif; ?>

                                <?php if ($text) : ?>
                                    <div class="fs-300 grey-text margin-b-70"><?php echo wp_kses_post($text); ?></div>
                                <?php endif; ?>
                            </div>

                            <?php if ($button) : ?>
                                <a href="<?php echo esc_url($button['url']); ?>" class="hl arrow" target="<?php echo esc_attr($button['target']); ?>">
                                    <?php echo esc_html($button['title']); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="image-cover" style="background-image:url('<?php echo esc_url($image_url); ?>');"></div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>