<?php
// Get ACF fields
$event_name            = get_field('event_name');
$short_description     = get_field('event_short_description');
$start_date            = get_field('event_start_date');
$end_date              = get_field('event_end_date');
$time                  = get_field('event_time');
$venue                 = get_field('event_venue');
$country               = get_field('event_country');
$stand_number          = get_field('event_stand_number');
$cost                  = get_field('event_cost');
$registration_url      = get_field('event_registration_url');
$playback_url          = get_field('event_playback_url');
$long_description      = get_field('event_long_description');
$agenda                = get_field('event_agenda');

if (has_post_thumbnail()) {
    $image_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
} else {
    $image_url = get_template_directory_uri() . '/images/placeholder.png';
}
?>

<?php if (
    $event_name || $short_description || $start_date || $end_date || $time || 
    $venue || $country || $stand_number || $cost || $registration_url || $playback_url
) : ?>

    </div><?php // close outer container to go full width ?>
    <div class="text-banner-split theme-dark">
        <div class="text-banner-split__image image-cover" style="background-image:url('<?php echo esc_url($image_url); ?>');"></div>

        <div class="black-bg white-text padding-100">
            <div class="text-banner-split__text">
                <?php if ($title) : ?>
                    <p class="fs-200 fw-regular margin-b-30"><?php echo esc_html($title); ?></p>
                <?php endif; ?>
                <?php if ($short_description) : ?>
                    <div class="fs-500 fw-regular margin-b-50"><?php echo wp_kses_post($short_description); ?></div>
                <?php endif; ?>

                <div class="event-details__description">

                    <?php if (zotefoams_is_past_date($end_date ?: $start_date)) : ?>
                        <h3 class="event-details__notice margin-b-20 fs-300">
                            <strong>Note:</strong> This event has already taken place.
                        </h3>
                    <?php endif; ?>

                    <div class="event-details__meta fs-300">
                        <?php
                        if ($start_date || $end_date) :
                            $formatted_date = zotefoams_format_event_date_range($start_date, $end_date);
                        ?>
                            <p class="margin-b-10 fs-500"><strong class="fs-300 blue-text uppercase">Date</strong><br /> <?php echo esc_html($formatted_date); ?></p>
                        <?php endif; ?>
                        <?php if ($time) : ?>
                            <p class="margin-b-10 fs-500"><strong class="fs-300 blue-text uppercase">Time</strong><br /> <?php echo esc_html($time); ?></p>
                        <?php endif; ?>
                        <?php if ($venue) : ?>
                            <p class="margin-b-10 fs-500"><strong class="fs-300 blue-text uppercase">Venue</strong><br /> <?php echo esc_html($venue); ?></p>
                        <?php endif; ?>
                        <?php if ($country) : ?>
                            <p class="margin-b-10 fs-500"><strong class="fs-300 blue-text uppercase">Country</strong><br /> <?php echo esc_html($country); ?></p>
                        <?php endif; ?>
                        <?php if ($stand_number) : ?>
                            <p class="margin-b-10 fs-500"><strong class="fs-300 blue-text uppercase">Stand Number</strong><br /> <?php echo esc_html($stand_number); ?></p>
                        <?php endif; ?>
                        <?php if ($cost) : ?>
                            <p class="margin-b-10 fs-500"><strong class="fs-300 blue-text uppercase">Cost</strong><br /> <?php echo esc_html($cost); ?></p>
                        <?php endif; ?>
                        <?php if ($playback_url) : ?>
                            <p class="margin-t-30"><a class="btn white outline" href="<?php echo esc_url($playback_url); ?>" target="_blank" rel="noopener">Watch video</a></p>
                        <?php elseif ($registration_url) : ?>
                            <p class="margin-t-30"><a class="btn white outline" href="#event-register" rel="noopener">Register below</a></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if ($long_description || $agenda) : ?>
        <div class="split-text cont-m padding-t-100 grey-text theme-none">
            <?php if ($short_description) : ?>
                <div class="split-text__title margin-b-20 fs-600 fw-semibold">
                    <?php echo wp_kses_post($long_description); ?>
                </div>
            <?php endif; ?>

            <?php if ($agenda) : ?>
                <div class="split-text__content grey-text fs-300">
                    <h3 class="fs-300 blue-text uppercase">Agenda</h3>
                    <?php echo do_shortcode($agenda);  ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="cont-xs padding-t-b-40">
<?php endif; ?>