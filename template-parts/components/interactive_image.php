<?php
// Get field data using safe helper functions
$title          = zotefoams_get_sub_field_safe('interactive_image_title', '', 'string');
$subtitle       = zotefoams_get_sub_field_safe('interactive_image_subtitle', '', 'string');
$output_numbers = zotefoams_get_sub_field_safe('interactive_image_output_numbers', false, 'bool');
$light_theme    = zotefoams_get_sub_field_safe('interactive_image_light_theme', false, 'bool');
$points         = zotefoams_get_sub_field_safe('interactive_image_points', [], 'array');
$bg_image       = zotefoams_get_sub_field_safe('interactive_image_bg', [], 'image');

// Image handling with Image Helper
$bg_image_url = Zotefoams_Image_Helper::get_image_url($bg_image, 'large', 'interactive-image');

// Generate classes based on theme setting
$theme_class = $light_theme ? 'theme-light' : 'theme-dark';
$wrapper_classes = 'interactive-image padding-t-b-100 ' . $theme_class;
?>

<div class="<?php echo $wrapper_classes; ?>">
    <div class="cont-m">
        <?php if ($title || $subtitle) : ?>
            <div class="interactive-image__intro margin-b-40">
                <?php if ($title) : ?>
                    <p class="fw-semibold fs-600<?php echo !$light_theme ? ' white-text' : ''; ?>"><?php echo esc_html($title); ?></p>
                <?php endif; ?>
                <?php if ($subtitle) : ?>
                    <p class="fw-semibold fs-600 blue-text"><?php echo esc_html($subtitle); ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="interactive-image__container">
            <?php if ($points) : ?>
                <?php 
                $point_counter = 1;
                foreach ($points as $point) :
                    $point_content = $point['point_content'] ?? '';
                    $from_top      = $point['from_top'] ?? '0';
                    $from_left     = $point['from_left'] ?? '0';
                    
                    // Determine point classes
                    $pointClass = 'interactive-image__point';
                    $pointClass .= $output_numbers ? ' interactive-image__point--numbered' : ' interactive-image__point--circle';
                    if ($from_left < 40) {
                        $pointClass .= ' interactive-image__point--left';
                    }
                ?>
                    <div class="<?php echo esc_attr($pointClass); ?>" 
                         style="top:<?php echo esc_attr($from_top); ?>%;left:<?php echo esc_attr($from_left); ?>%;"
                         <?php if ($output_numbers) : ?>
                         data-point-number="<?php echo esc_attr($point_counter); ?>"
                         <?php endif; ?>>

                        <?php if ($point_content) : ?>
                            <div class="interactive-image__popup">
                                <?php 
                                // Check if content contains <br> tags
                                if (preg_match('/<br\s*\/?>/i', $point_content)) {
                                    // Split on first <br> tag
                                    $parts = preg_split('/<br\s*\/?>/i', $point_content, 2);
                                    // Output first line in strong, then the rest
                                    echo '<p><strong>' . wp_kses($parts[0], array()) . '</strong>';
                                    if (isset($parts[1])) {
                                        echo '<br>' . wp_kses($parts[1], array('br' => array()));
                                    }
                                    echo '</p>';
                                } else {
                                    // No <br> tags, output as normal
                                    echo '<p>' . wp_kses($point_content, array('br' => array())) . '</p>';
                                }
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php 
                    $point_counter++;
                endforeach; 
                ?>
            <?php endif; ?>

            <?php if ($bg_image_url) : ?>
                <img class="interactive-image__bg" 
                     src="<?php echo esc_url($bg_image_url); ?>" 
                     alt="<?php echo esc_attr($title ?: __('Interactive image', 'zotefoams')); ?>" />
            <?php endif; ?>
        </div>
    </div>
</div>