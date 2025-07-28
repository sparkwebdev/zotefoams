<?php
// Get field data using safe helper functions
$title = zotefoams_get_sub_field_safe('data_points_title', '', 'string');
$items = zotefoams_get_sub_field_safe('data_points_items', [], 'array');

// Function to determine the number of decimal places
function getDecimalPlaces($number)
{
    if (strpos((string)$number, '.') !== false) {
        return strlen(substr(strrchr((string)$number, '.'), 1));
    }
    return 0;
}

// Get theme-aware wrapper classes
$wrapper_classes = Zotefoams_Theme_Helper::get_wrapper_classes([
    'component' => 'data-points',
    'theme'     => 'light',
    'spacing'   => 'padding-t-b-70',
    'container' => '',
]);
?>

<div class="<?php echo $wrapper_classes; ?>">

    <div class="cont-m">
        <?php if ($title): ?>
            <h3 class="fw-bold margin-b-40"><?php echo esc_html($title); ?></h3>
        <?php endif; ?>
    </div>

    <div class="data-points-items cont-m">
        <?php if ($items): ?>
            <?php foreach ($items as $item):
                $icon   = $item['data_points_icon'];
                $value  = $item['data_points_value'];
                $prefix = $item['data_points_prefix'];
                $suffix = $item['data_points_suffix'];
                $label  = $item['data_points_label'];

                // Prepare data attributes
                $attributes = [];
                if (!empty($value)) {
                    $attributes[] = 'data-to="' . esc_attr($value) . '"';
                    $attributes[] = 'data-decimals="' . esc_attr(getDecimalPlaces($value)) . '"';
                }
                if (!empty($prefix)) {
                    $attributes[] = 'data-prefix="' . esc_attr($prefix) . '"';
                }
                if (!empty($suffix)) {
                    $attributes[] = 'data-suffix="' . esc_attr($suffix) . '"';
                }
                $attr_string = implode(' ', $attributes);
            ?>
                <div class="data-points-item">
                    <?php if ($icon): ?>
                        <?php echo Zotefoams_Image_Helper::render_image($icon, [
                            'alt' => $label ?: 'Data point',
                            'size' => 'large'
                        ]); ?>
                    <?php endif; ?>

                    <p class="animate__animated animate__delay-1s value fw-bold fs-700 margin-b-10" <?php echo $attr_string; ?>>0</p>

                    <?php if ($label): ?>
                        <label class="sub-title fs-100"><?php echo esc_html($label); ?></label>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>