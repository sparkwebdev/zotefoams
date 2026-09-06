<?php

wp_enqueue_style('component-hero_banner', get_template_directory_uri() . '/template-parts/components/hero_banner/style.css', [], '1.0.0');

$content = get_field('page_header_hero');

if ($content):

    $title = !empty($content['title']) ? $content['title'] : get_the_title();
    $subtitle = $content['subtitle'] ?? '';
    $primary_cta = $content['primary_cta'];
    $secondary_cta = $content['secondary_cta'];

?>

    <header class="component-hero-banner cont-m padding-t-b-70" role="banner" aria-label="Page Header">
        <div class="component-hero-banner__top">
            <div class="component-hero-banner__left">
                <?php if (!empty($title)): ?>
                    <h1 class="component-hero-banner__title uppercase grey-text fs-800 fw-extrabold animate__animated animate__fadeInDown">
                        <?php echo esc_html($title); ?>
                    </h1>
                <?php endif; ?>

                <?php if (!empty($subtitle)): ?>
                    <p class="component-hero-banner__subtitle uppercase black-text fs-800 fw-extrabold animate__animated animate__fadeInDown">
                        <?php echo esc_html($subtitle); ?>
                    </p>
                <?php endif; ?>
            </div>

            <div class="component-hero-banner__right">

                <?php if (! empty($content['description'])): ?>

                    <div class="component-hero-banner__description grey-text">
                        <?php echo wp_kses_post($content['description']); ?>
                    </div>

                <?php endif; ?>

                <div class="component-hero-banner__ctas">
                    <?php zotefoams_link($content['primary_cta'], 'component-hero-banner__cta'); ?>

                    <?php zotefoams_link($content['secondary_cta'], 'component-hero-banner__cta'); ?>
                </div>
            </div>
        </div>

        <ul class="component-hero-banner__features">
            <?php foreach ($content['features'] ?? [] as $row): ?>

                <li class="component-hero-banner__feature">
                    <svg class="component-hero-banner__feature__icon" xmlns="http://www.w3.org/2000/svg" width="27" height="27" viewBox="0 0 27 27">
                        <path d="m11.61 19.71 9.518-9.517-1.89-1.89-7.628 7.627-3.847-3.847-1.89 1.89ZM13.5 27a13.15 13.15 0 0 1-5.265-1.063 13.6 13.6 0 0 1-4.286-2.886 13.6 13.6 0 0 1-2.886-4.286A13.15 13.15 0 0 1 0 13.5a13.15 13.15 0 0 1 1.063-5.265 13.6 13.6 0 0 1 2.886-4.286 13.6 13.6 0 0 1 4.286-2.886A13.15 13.15 0 0 1 13.5 0a13.15 13.15 0 0 1 5.265 1.063 13.6 13.6 0 0 1 4.286 2.886 13.6 13.6 0 0 1 2.886 4.286A13.15 13.15 0 0 1 27 13.5a13.15 13.15 0 0 1-1.063 5.265 13.6 13.6 0 0 1-2.886 4.286 13.6 13.6 0 0 1-4.286 2.886A13.15 13.15 0 0 1 13.5 27" />
                    </svg>

                    <span>
                        <?php echo wp_kses_post($row['label']); ?>
                    </span>
                </li>

            <?php endforeach; ?>
        </ul>

        <?php if (! empty($content['image'])): ?>
            <div class="component-hero-banner__img-wrapper">
                <?php echo wp_get_attachment_image($content['image'], 'full', false, ['class' => 'component-hero-banner__img', 'loading' => 'eager']); ?>

                <?php if (!empty($content['zoteiq_question'])): ?>

                    <?php zoteiq_link($content['zoteiq_question'], 'component-hero-banner__zoteiq'); ?>

                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (get_field('page_show_breadcrumbs')): ?>
            <div class="padding-t-50 theme-none">
                <?php get_template_part('template-parts/breadcrumbs'); ?>
            </div>
        <?php endif; ?>
    </header>

<?php endif; ?>