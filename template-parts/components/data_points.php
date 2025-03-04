<?php 
// Allow for passed variables, as well as ACF values
$title = get_sub_field('data_points_title');
$items = get_sub_field('data_points_items');

// Function to determine the number of decimal places
function getDecimalPlaces($number) {
		if (strpos($number, '.') !== false) {
				return strlen(substr(strrchr($number, "."), 1)); // Count decimals after the dot
		}
		return 0; // No decimal places if it's a whole number
}
?>

<div class="data-points half-half light-grey-bg padding-t-b-70 theme-light">
    
    <?php if ($title): ?>
        <h3 class="fw-bold margin-b-40"><?php echo esc_html($title); ?></h3>
    <?php endif; ?>
    
    <div class="data-points-items cont-m">
        <?php if ($items): ?>
            <?php foreach ($items as $item): ?>
                <?php 
                    $icon = $item['data_points_icon'];
                    $value = $item['data_points_value'];
                    $prefix = $item['data_points_prefix'];
                    $suffix = $item['data_points_suffix'];
                    $label = $item['data_points_label'];

                    // Default values for data attributes
                    $data_prefix = !empty($prefix) ? "data-prefix='{$prefix}'" : '';
                    $data_suffix = !empty($suffix) ? "data-suffix='{$suffix}'" : '';
                    $value = (string) $value; 
                    $data_decimals = !empty($value) ? "data-decimals='" . getDecimalPlaces($value) . "'" : '';
                    $data_to = !empty($value) ? "data-to='{$value}'" : '';

                ?>
                <div class="data-points-item">
                    <?php if ($icon): ?>
                        <img class="" src="<?php echo esc_url($icon['url']); ?>" alt="<?php echo esc_attr($label); ?>" />
                    <?php endif; ?>
                    <p class="animate__animated animate__delay-1s value fw-bold" <?php echo $data_to . ' ' . $data_prefix . ' ' . $data_suffix . ' ' . $data_decimals; ?>>0</p>
                    <?php if ($label): ?>
                        <label class="sub-title fs-100"><?php echo esc_html($label); ?></label>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>