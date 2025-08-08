<?php
// Get field data using safe helper functions
$title     = zotefoams_get_sub_field_safe('locations_map_title', '', 'string');
$subtitle  = zotefoams_get_sub_field_safe('locations_map_subtitle', '', 'string');
$locations = zotefoams_get_sub_field_safe('locations_map_locations', [], 'array');
$map_image = zotefoams_get_sub_field_safe('locations_map_image', [], 'image');

// Image handling with Image Helper
$map_image_url = Zotefoams_Image_Helper::get_image_url($map_image, 'large', 'locations-map');

// Generate classes to match original structure exactly
$wrapper_classes = 'locations-map padding-t-b-100 theme-dark';
?>

<div class="<?php echo $wrapper_classes; ?>">
    <div class="cont-m">
        <div class="locations-map__intro margin-b-40">
            <?php if ($title) : ?>
                <p class="fw-semibold fs-600 white-text"><?php echo esc_html($title); ?></p>
            <?php endif; ?>
            <?php if ($subtitle) : ?>
                <p class="fw-semibold fs-600 blue-text"><?php echo esc_html($subtitle); ?></p>
            <?php endif; ?>
        </div>

        <div class="locations-map__container">
            <?php if ($locations) : ?>
                <?php foreach ($locations as $location) :
                    $description = $location['locations_map_description'] ?? '';
                    $from_top    = $location['from_top'] ?? '0';
                    $from_left   = $location['from_left'] ?? '0';
                    $locationClass = $from_left < 40 ? "locations-map__location locations-map__location--left" : "locations-map__location"
                        
                ?>
                    <div class="<?php echo esc_attr($locationClass); ?>" style="top:<?php echo esc_attr($from_top); ?>%;left:<?php echo esc_attr($from_left); ?>%;">

                        <?php if ($description) : ?>
                            <div class="locations-map__popup">
                                <p><?php echo wp_kses_post($description); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <img class="locations-map__map" src="<?php echo esc_url($map_image_url); ?>" alt="<?php esc_attr_e('World map with locations', 'zotefoams'); ?>" />
        </div>
    </div>
</div>