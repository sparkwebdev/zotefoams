<?php

wp_enqueue_style('component-applications-slider', get_template_directory_uri() . '/template-parts/components/applications_slider/style.css', [], '1.0.0');
wp_enqueue_script('component-applications-slider', get_template_directory_uri() . '/template-parts/components/applications_slider/script.js', [], '1.0.0');

$id = uniqid('component-applications-slider__');
$categories = [];

foreach (get_sub_field('applications') as $application) {
    array_push($categories, ...array_map('trim', explode(',', $application['categories'])));
}

$categories = array_unique($categories);

?>

<div class="component-applications-slider cont-m padding-t-b-70">
    <h2 class="component-applications-slider__title fs-600 fw-bold">
        <?php the_sub_field('title'); ?>
    </h2>

    <ul class="component-applications-slider__chips" role="toolbar" aria-label="Applied filters" aria-controls="<?php echo esc_attr($id); ?>">
        <li class="component-applications-slider__chip">
            <button class="component-applications-slider__chip__toggle" aria-label="Clear filters" aria-pressed="true" data-applications-slider--chip="" type="button">
                <?php esc_html_e('All Applications', 'zotefoams'); ?>
            </button>
        </li>

        <?php foreach ($categories as $category): ?>
            <li class="component-applications-slider__chip">
                <button class="component-applications-slider__chip__toggle" aria-label="Toggle filter: <?php echo esc_attr($category); ?>" aria-pressed="false" data-applications-slider--chip="<?php echo esc_attr($category); ?>" type="button">
                    <?php echo esc_html($category); ?>
                </button>
            </li>
        <?php endforeach; ?>
    </ul>

    <ul class="component-applications-slider__slider" id="<?php echo esc_attr($id); ?>" aria-label="Filtered">
        <?php while (have_rows('applications')) : ?>
            <?php the_row(); ?>

            <li class="component-applications-slider__card" data-applications-slider--card="<?php echo esc_attr(get_sub_field('categories')); ?>">
                <div class="component-applications-slider__card__body">

                    <h3 class="component-applications-slider__card__title">
                        <?php the_sub_field('name') ?>
                    </h3>

                    <div class="component-applications-slider__card__description">
                        <?php the_sub_field('description'); ?>
                    </div>

                    <a class="component-applications-slider__card__link component-link" href="<?php echo get_sub_field('link') ?: "/contact-us/"; ?>">
                        More about <?php the_sub_field('name') ?>

                        <svg class="component-link__icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f">
                            <path d="M504-480 320-664l56-56 240 240-240 240-56-56 184-184Z" />
                        </svg>
                    </a>
                </div>

                <?php echo wp_get_attachment_image(get_sub_field('image'), 'large', false, ['class' => 'component-applications-slider__card__image']); ?>
            </li>

        <?php endwhile; ?>
    </ul>
</div>