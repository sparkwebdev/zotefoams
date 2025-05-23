<?php
$title     = get_sub_field('locations_map_title');
$subtitle  = get_sub_field('locations_map_subtitle');
$locations = get_sub_field('locations_map_locations');
$map_image = get_sub_field('locations_map_image');

// Extract image URL or fallback
$map_image_url = $map_image['sizes']['large'] ?? get_template_directory_uri() . '/images/placeholder.png';
?>

<div class="locations-map padding-t-b-100 theme-dark">
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
                ?>
                    <div
                        class="locations-map__location"
                        onclick="locationClicked(this)"
                        style="top:<?php echo esc_attr($from_top); ?>%;left:<?php echo esc_attr($from_left); ?>%;">

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