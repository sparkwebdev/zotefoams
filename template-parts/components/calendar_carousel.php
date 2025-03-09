<?php 
// Allow for passed variables, as well as ACF values
$title = get_sub_field('calendar_carousel_title');
$events = get_sub_field('calendar_carousel_events');
$note = get_sub_field('calendar_carousel_note');
?>

<!-- Carousel 5 - Calendar Carousel -->
<div class="cont-m padding-t-b-100 theme-none">
    
    <div class="title-strip margin-b-30">
        <?php if ($title): ?>
            <h3 class="fs-500 fw-600"><?php echo esc_html($title); ?></h3>
        <?php endif; ?>
        <!-- Navigation -->
        <div class="carousel-navigation black">
            <div class="carousel-navigation-inner">
                <div class="calendar-swiper-button-prev">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/left-arrow-black.svg" />
                </div>
                <div class="calendar-swiper-button-next">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/right-arrow-black.svg" />
                </div>
            </div>
        </div>
    </div>

    <div class="swiper calendar-carousel">
        <div class="swiper-wrapper">
            <?php if ($events): ?>
                <?php foreach ($events as $event): ?>
                    <?php 
                        $date = $event['calendar_carousel_date'];
                        $month_year = $event['calendar_carousel_month_year'];
                        $description = $event['calendar_carousel_description'];
                    ?>
                    <div class="swiper-slide">
                        <div class="swiper-slide-inner">
                            <div class="date-wrapper">
                                <?php if ($date): ?>
                                    <label class="date fs-700"><?php echo esc_html($date); ?></label>
                                <?php endif; ?>
                                <?php if ($month_year): ?>
                                    <label class="month-year fs-100"><?php echo esc_html($month_year); ?></label>
                                <?php endif; ?>
                            </div>
                            <?php if ($description): ?>
                                <p class="fs-100 grey-text"><?php echo esc_html($description); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($note): ?>
        <label class="calendar-carousel-note"><?php echo esc_html($note); ?></label>
    <?php endif; ?>
</div>