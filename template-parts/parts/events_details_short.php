<?php
// Get ACF fields
$start_date            = get_field('event_start_date');
$end_date              = get_field('event_end_date');
$venue                 = get_field('event_venue');
$country               = get_field('event_country');
$stand_number          = get_field('event_stand_number');
?>

<?php
if ($start_date) :
    $dateRange = zotefoams_format_event_date_range($start_date, $end_date);
    echo '<h3 class="fs-400 fw-semibold grey-text">' . $dateRange . '</h3>';
endif;
if ($venue || $country || $stand_number) :
    echo '<h4 class="fs-300 fw-regular grey-text">' . implode(', ', [$venue, $country, $stand_number]) . '</h4>';
endif;
?>