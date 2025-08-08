<?php
$posts_page_id = zotefoams_get_page_for_posts_id();

// Get field data using safe helper functions
$brighter_ir_widget = zotefoams_get_sub_field_safe('brighter_ir_widget', '', 'string');

// Only proceed if a widget value exists
if ($brighter_ir_widget) {
    $widget_key = sanitize_title($brighter_ir_widget); // Sanitize for safety

    switch ($widget_key) {
        case 'share-price-chart':
            get_template_part('template-parts/components/widgets/share_price_chart');
            break;

        case 'share-price-widget':
            get_template_part('template-parts/components/widgets/share_price_widget');
            break;

        case 'rns':
            get_template_part('template-parts/components/widgets/rns');
            break;

        case 'alerts':
            get_template_part('template-parts/components/widgets/alerts');
            break;

        default:
            echo '<p>No specific widget selected.</p>';
            break;
    }
} else {
    echo '<p>Please select a widget.</p>';
}
