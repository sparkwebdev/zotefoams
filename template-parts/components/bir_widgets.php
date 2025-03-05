<?php 
$posts_page_id = zotefoams_get_page_for_posts_id();

// Retrieve the "Brighter IR Widget" field value
$brighter_ir_widget = get_sub_field('brighter_ir_widget');

// Conditional output based on the value
if ($brighter_ir_widget) {
    switch ($brighter_ir_widget) {
        case 'share-price-chart':
            get_template_part( 'template-parts/components/widgets/share_price_chart' ); 
            break;
        case 'share-price-widget':
            get_template_part( 'template-parts/components/widgets/share_price_widget' ); 
            break;
        case 'rns': // Assuming 'mo' corresponds to 'rns.php'
            get_template_part( 'template-parts/components/widgets/rns' ); 
            break;
        case 'alerts': // Assuming you have an 'alerts' option
            get_template_part( 'template-parts/components/widgets/alerts' ); 
            break;
        default:
            echo '<p>No specific widget selected.</p>';
            break;
    }
} else {
    echo '<p>Please select a widget.</p>';
}
?>