<?php 
$bg_image = get_sub_field('data_map_bg');
$map_image = get_sub_field('data_map_map_image');

$stat_1_value = get_sub_field('data_map_stat_1_value');
$stat_1_text = get_sub_field('data_map_stat_1_text');

$stat_2_value = get_sub_field('data_map_stat_2_value');
$stat_2_text = get_sub_field('data_map_stat_2_text');

$stat_3_value = get_sub_field('data_map_stat_3_value');
$stat_3_text = get_sub_field('data_map_stat_3_text');

// Extract image URLs with fallbacks
$bg_image_url = $bg_image ? $bg_image['url'] : get_template_directory_uri() . '/images/data-map-bg.jpg';
$map_image_url = $map_image ? $map_image['url'] : get_template_directory_uri() . '/images/data-map.png';
?>

<div class="data-map padding-t-b-100 image-cover theme-dark" style="background-image:url('<?php echo esc_url($bg_image_url); ?>')">
    <div class="cont-m">
        <div class="stats">
            <div class="stat stat-1 padding-30">
                <img src="<?php echo esc_url($map_image_url); ?>" />
                <?php if ($stat_1_value): ?>
                    <p class="fs-700 fw-semibold margin-b-10"><?php echo esc_html($stat_1_value); ?></p>
                <?php endif; ?>
                <?php if ($stat_1_text): ?>
                    <p class="fs-200 fw-semibold"><?php echo esc_html($stat_1_text); ?></p>
                <?php endif; ?>
            </div>
            <div class="stat stat-2 padding-30">
                <?php if ($stat_2_value): ?>
                    <p class="fs-700 fw-semibold margin-b-10"><?php echo esc_html($stat_2_value); ?></p>
                <?php endif; ?>
                <?php if ($stat_2_text): ?>
                    <p class="fs-200 fw-semibold"><?php echo esc_html($stat_2_text); ?></p>
                <?php endif; ?>
            </div>
            <div class="stat stat-3 padding-30">
                <?php if ($stat_3_value): ?>
                    <p class="fs-700 fw-semibold margin-b-10"><?php echo esc_html($stat_3_value); ?></p>
                <?php endif; ?>
                <?php if ($stat_3_text): ?>
                    <p class="fs-200 fw-semibold"><?php echo esc_html($stat_3_text); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
