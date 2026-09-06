<?php 
// Get field data using safe helper functions
$title     = zotefoams_get_sub_field_safe('calendar_carousel_title', '', 'string');
$events    = zotefoams_get_sub_field_safe('calendar_carousel_events', [], 'array');
$note      = zotefoams_get_sub_field_safe('calendar_carousel_note', '', 'string');
$template_uri = get_template_directory_uri();

// Generate classes to match original structure exactly
$wrapper_classes = 'cont-m padding-t-b-100 theme-none';
?>

<div class="<?php echo $wrapper_classes; ?>">
    <div class="title-strip margin-b-30">
        <?php if ($title): ?>
            <h3 class="fs-500 fw-600"><?php echo esc_html($title); ?></h3>
        <?php endif; ?>
        <div class="carousel-navigation black" role="group" aria-label="Calendar navigation">
            <div class="carousel-navigation-inner">
                <button type="button" class="calendar-swiper-button-prev carousel-btn-reset" aria-label="Previous events" tabindex="0">
                    <img src="<?php echo esc_url($template_uri); ?>/images/left-arrow-black.svg" alt="" role="presentation" />
                </button>
                <button type="button" class="calendar-swiper-button-next carousel-btn-reset" aria-label="Next events" tabindex="0">
                    <img src="<?php echo esc_url($template_uri); ?>/images/right-arrow-black.svg" alt="" role="presentation" />
                </button>
            </div>
        </div>
    </div>

    <div class="calendar-carousel-wrapper">

        <?php if (!empty($events)): ?>
            <div class="swiper calendar-carousel">
                <div class="swiper-wrapper">
                <?php $event_index = 0; foreach ($events as $event):
                    $event_index++;
                    $day         = $event['calendar_carousel_date'] ?? '';
                    $month       = $event['calendar_carousel_month_year'] ?? '';
                    $year        = $event['calendar_carousel_year'] ?? '';
                    $description = $event['calendar_carousel_description'] ?? '';
                    $sr_date     = implode(' ', array_filter([$day, $month, $year]));
                    $heading_id  = 'cal-event-' . $event_index;
                    $desc_id     = $description ? 'cal-event-desc-' . $event_index : '';
                ?>
                    <div class="swiper-slide calendar-carousel__slide">
                        <div class="calendar-carousel__slide-inner">
                            <?php if ($sr_date): ?>
                                <h4 id="<?php echo esc_attr($heading_id); ?>"
                                    class="screen-reader-text"
                                    <?php if ($desc_id): ?>aria-describedby="<?php echo esc_attr($desc_id); ?>"<?php endif; ?>
                                ><?php echo esc_html($sr_date); ?></h4>
                            <?php endif; ?>
                            <div class="calendar-carousel__date" aria-hidden="true">
                                <?php if ($month): ?>
                                    <span class="calendar-carousel__month fs-600 fw-semibold"><?php echo esc_html($month); ?></span>
                                <?php endif; ?>
                                <?php if ($day || $year): ?>
                                    <div class="calendar-carousel__date-meta fs-200">
                                        <?php if ($day): ?>
                                            <span class="calendar-carousel__day"><?php echo esc_html($day); ?></span>
                                        <?php endif; ?>
                                        <?php if ($year): ?>
                                            <span class="calendar-carousel__year"><?php echo esc_html($year); ?></span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php if ($description): ?>
                            <div id="<?php echo esc_attr($desc_id); ?>" class="calendar-carousel__description">
                                <p class="fs-100 grey-text"><?php echo esc_html($description); ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
    </div><!-- .calendar-carousel-wrapper -->

    <?php if ($note): ?>
        <p class="calendar-carousel__note"><small class="fs-200"><?php echo esc_html($note); ?></small></p>
    <?php endif; ?>
</div>
