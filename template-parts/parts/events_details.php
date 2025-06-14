<?php
// Get ACF fields
$event_name            = get_field('event_name');
$short_description     = get_field('event_short_description');
$start_date            = get_field('event_start_date');
$end_date              = get_field('event_end_date');
$venue                 = get_field('event_venue');
$country               = get_field('event_country');
$stand_number          = get_field('event_stand_number');
$registration_url      = get_field('event_registration_url');
$playback_url          = get_field('event_playback_url');
?>

<?php if (
    $event_name || $short_description || $start_date || $end_date ||
    $venue || $country || $stand_number || $registration_url || $playback_url
) : ?>
    <div class="event-details padding-30 margin-t-b-40 light-grey-bg">
        <?php if ($event_name) : ?>
            <div class="event-details__title margin-b-20 fs-600 fw-semibold grey-text">
                <?php echo esc_html($event_name); ?>
            </div>
        <?php endif; ?>

        <?php if ($short_description) : ?>
            <div class="event-details__description grey-text fs-300 margin-b-20">
                <?php echo wp_kses_post($short_description); ?>
            </div>
        <?php endif; ?>

        <?php if (zotefoams_is_past_date($end_date ?: $start_date)) : ?>
            <h3 class="event-details__notice margin-b-20 fs-300 blue-text">
                <strong>Note:</strong> This event has already taken place.
            </h3>
        <?php endif; ?>

        <div class="event-details__meta fs-300">
            <?php
            if ($start_date || $end_date) :
                $formatted_date = zotefoams_format_event_date_range($start_date, $end_date);
            ?>
                <p><strong>Date:</strong> <?php echo esc_html($formatted_date); ?></p>
            <?php endif; ?>
            <?php if ($venue) : ?>
                <p><strong>Venue:</strong> <?php echo esc_html($venue); ?></p>
            <?php endif; ?>
            <?php if ($country) : ?>
                <p><strong>Country:</strong> <?php echo esc_html($country); ?></p>
            <?php endif; ?>
            <?php if ($stand_number) : ?>
                <p><strong>Stand Number:</strong> <?php echo esc_html($stand_number); ?></p>
            <?php endif; ?>
            <?php if ($playback_url) : ?>
                <p class="margin-t-20"><a class="btn black outline" href="<?php echo esc_url($playback_url); ?>" target="_blank" rel="noopener">Watch video</a></p>
            <?php elseif ($registration_url) : ?>
                <p class="margin-t-20"><a class="btn black outline" href="<?php echo esc_url($registration_url); ?>" target="_blank" rel="noopener">Register here</a></p>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>