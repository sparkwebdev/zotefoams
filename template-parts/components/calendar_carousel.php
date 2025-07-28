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

        <div class="carousel-navigation black">
            <div class="carousel-navigation-inner">
                <div class="calendar-swiper-button-prev">
                    <img src="<?php echo esc_url($template_uri); ?>/images/left-arrow-black.svg" alt="Previous" />
                </div>
                <div class="calendar-swiper-button-next">
                    <img src="<?php echo esc_url($template_uri); ?>/images/right-arrow-black.svg" alt="Next" />
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($events)): ?>
        <div class="swiper calendar-carousel">
            <div class="swiper-wrapper">
                <?php foreach ($events as $event): 
                    $date        = $event['calendar_carousel_date'] ?? '';
                    $month_year  = $event['calendar_carousel_month_year'] ?? '';
                    $description = $event['calendar_carousel_description'] ?? '';
                ?>
                    <div class="swiper-slide calendar-carousel__slide">
                        <div class="calendar-carousel__slide-inner">
                            <div class="calendar-carousel__date">
                                <?php if ($date): ?>
                                    <label class="fs-700"><?php echo esc_html($date); ?></label>
                                <?php endif; ?>
                                <?php if ($month_year): ?>
                                    <label class="calendar-carousel__month-year fs-100"><?php echo esc_html($month_year); ?></label>
                                <?php endif; ?>
                            </div>
                            <?php if ($description): ?>
                                <p class="fs-100 grey-text"><?php echo esc_html($description); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($note): ?>
        <label class="calendar-carousel__note"><?php echo esc_html($note); ?></label>
    <?php endif; ?>
</div>
