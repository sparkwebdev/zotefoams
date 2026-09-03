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
                <?php $location_index = 0; ?>
                <?php foreach ($locations as $location) :
                    $location_index++;
                    $description = $location['locations_map_description'] ?? '';
                    $from_top    = $location['from_top'] ?? '0';
                    $from_left   = $location['from_left'] ?? '0';
                    $locationClass = $from_left < 40 ? "locations-map__location locations-map__location--left" : "locations-map__location";
                    $popup_id = 'locations-map-popup-' . $location_index;

                    // No discrete "name" field in ACF — derive from the description's first line.
                    $location_name = '';
                    if ($description) {
                        $description_lines = wp_strip_all_tags(preg_replace('/<\/(p|div|li)[^>]*>|<br[^>]*>/i', "\n", $description));
                        $location_name = trim(strtok($description_lines, "\n"));
                    }
                    if (!$location_name) {
                        $location_name = sprintf(__('Location %d', 'zotefoams'), $location_index);
                    }
                ?>
                    <?php if ($description) : ?>
                        <button type="button" class="<?php echo esc_attr($locationClass); ?>" style="top:<?php echo esc_attr($from_top); ?>%;left:<?php echo esc_attr($from_left); ?>%;" aria-expanded="false" aria-controls="<?php echo esc_attr($popup_id); ?>" aria-label="<?php echo esc_attr($location_name); ?>">
                            <span class="locations-map__dot" aria-hidden="true"></span>

                            <div class="locations-map__popup" id="<?php echo esc_attr($popup_id); ?>">
                                <p><?php echo wp_kses_post($description); ?></p>
                            </div>
                        </button>
                    <?php else : ?>
                        <span class="<?php echo esc_attr($locationClass); ?>" style="top:<?php echo esc_attr($from_top); ?>%;left:<?php echo esc_attr($from_left); ?>%;" aria-hidden="true">
                            <span class="locations-map__dot"></span>
                        </span>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>

            <img class="locations-map__map" src="<?php echo esc_url($map_image_url); ?>" alt="<?php esc_attr_e('World map with locations', 'zotefoams'); ?>" />
        </div>
    </div>
</div>