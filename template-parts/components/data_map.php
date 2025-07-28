<?php
// Get field data using safe helper functions
$bg_image     = zotefoams_get_sub_field_safe('data_map_bg', [], 'image');
$map_image    = zotefoams_get_sub_field_safe('data_map_map_image', [], 'image');

$stats = [
    [
        'value' => zotefoams_get_sub_field_safe('data_map_stat_1_value', '', 'string'),
        'text'  => zotefoams_get_sub_field_safe('data_map_stat_1_text', '', 'string'),
        'has_map_image' => true,
    ],
    [
        'value' => zotefoams_get_sub_field_safe('data_map_stat_2_value', '', 'string'),
        'text'  => zotefoams_get_sub_field_safe('data_map_stat_2_text', '', 'string'),
    ],
    [
        'value' => zotefoams_get_sub_field_safe('data_map_stat_3_value', '', 'string'),
        'text'  => zotefoams_get_sub_field_safe('data_map_stat_3_text', '', 'string'),
    ]
];

// Image handling with Image Helper
$bg_image_url  = Zotefoams_Image_Helper::get_image_url($bg_image, 'large', 'data-map-bg') ?: get_template_directory_uri() . '/images/data-map-bg.jpg';
$map_image_url = Zotefoams_Image_Helper::get_image_url($map_image, 'large', 'data-map') ?: get_template_directory_uri() . '/images/data-map.png';

// Generate classes to match original structure exactly
$wrapper_classes = 'data-map padding-t-b-100 image-cover theme-dark';
?>

<div class="<?php echo $wrapper_classes; ?>" style="background-image:url('<?php echo esc_url($bg_image_url); ?>')">
    <div class="cont-m">
        <div class="data-map__stats">
            <?php foreach ($stats as $index => $stat): ?>
                <div class="data-map__stat data-map__stat--<?php echo absint($index + 1); ?> padding-30">
                    <?php if (!empty($stat['has_map_image'])): ?>
                        <img src="<?php echo esc_url($map_image_url); ?>" alt="Map" />
                    <?php endif; ?>
                    <?php if (!empty($stat['value'])): ?>
                        <p class="fs-700 fw-semibold margin-b-10"><?php echo esc_html($stat['value']); ?></p>
                    <?php endif; ?>
                    <?php if (!empty($stat['text'])): ?>
                        <p class="fs-200 fw-semibold"><?php echo esc_html($stat['text']); ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>