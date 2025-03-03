<?php 
// Allow for passed variables, as well as ACF values
$title = get_sub_field('locations_map_title');
$subtitle = get_sub_field('locations_map_subtitle');
$locations = get_sub_field('locations_map_locations');
$map_image = get_sub_field('locations_map_image');

// Extract 'large' size image URL from Image Array, with fallback
$map_image_url = $map_image ? $map_image['sizes']['large'] : get_template_directory_uri() . '/images/placeholder.png';
?>

<div class="locations-map half-half padding-t-b-100 theme-dark">
    <div class="cont-m">
        <div class="map-intro margin-b-40">
            <?php if ($title): ?>
                <p class="fw-semibold fs-600 white-text"><?php echo esc_html($title); ?></p>
            <?php endif; ?>
            <?php if ($subtitle): ?>
                <p class="fw-semibold fs-600 blue-text"><?php echo esc_html($subtitle); ?></p>
            <?php endif; ?>
        </div>

        <div class="map-container">
            <?php if ($locations): ?>
                <?php foreach ($locations as $location): ?>
                    <?php 
                        $description = $location['locations_map_description'];
						$from_top = $location['from_top'];
						$from_left = $location['from_left'];
                    ?>
                    <div class="location" onclick="locationClicked(this)" style="top:<?php echo esc_html($from_top); ?>%;left:<?php echo esc_html($from_left); ?>%;">
                        <?php if ($description): ?>
                            <div class="popup">
                                <p><?php echo wp_kses_post($description); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            <img class="map" src="<?php echo esc_url($map_image_url); ?>" />
        </div>
    </div>
</div>
