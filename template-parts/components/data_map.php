<?php
$bg_image     = get_sub_field('data_map_bg');
$map_image    = get_sub_field('data_map_map_image');

$stats = [
    [
        'value' => get_sub_field('data_map_stat_1_value'),
        'text'  => get_sub_field('data_map_stat_1_text'),
        'has_map_image' => true,
    ],
    [
        'value' => get_sub_field('data_map_stat_2_value'),
        'text'  => get_sub_field('data_map_stat_2_text'),
    ],
    [
        'value' => get_sub_field('data_map_stat_3_value'),
        'text'  => get_sub_field('data_map_stat_3_text'),
    ]
];

// Image fallbacks
$bg_image_url  = $bg_image ? $bg_image['url'] : get_template_directory_uri() . '/images/data-map-bg.jpg';
$map_image_url = $map_image ? $map_image['url'] : get_template_directory_uri() . '/images/data-map.png';
?>

<div class="data-map padding-t-b-100 image-cover theme-dark" style="background-image:url('<?php echo esc_url($bg_image_url); ?>')">
    <div class="cont-m">
        <div class="stats">
            <?php foreach ($stats as $index => $stat): ?>
                <div class="stat stat-<?php echo $index + 1; ?> padding-30">
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