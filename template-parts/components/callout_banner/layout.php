<?php wp_enqueue_style('component-callout-banner', get_template_directory_uri() . '/template-parts/components/callout_banner/style.css', [], '1.0.0'); ?>

<div class="component-callout-banner component-callout-banner--<?php echo esc_attr(get_sub_field('theme')); ?> cont-m padding-t-b-70">
    <div class="component-callout-banner__box">
        <div class="component-callout-banner__text">
            <h2 class="component-callout-banner__title fs-500 fw-bold">
                <?php the_sub_field('headline'); ?>
            </h2>

            <?php if (get_sub_field('description')): ?>
                <div class="component-callout-banner__description">
                    <?php the_sub_field('description'); ?>
                </div>
            <?php endif; ?>

            <?php zotefoams_link(get_sub_field('cta'), 'component-callout-banner__cta'); ?>

            <?php if (get_sub_field('zoteiq_question')): ?>
                <?php zoteiq_link(get_sub_field('zoteiq_question'), 'component-callout-banner__zoteiq'); ?>
            <?php endif; ?>
        </div>

        <div class="component-callout-banner__img-wrapper">
            <?php echo wp_get_attachment_image(get_sub_field('image'), 'full', false, ['class' => 'component-callout-banner__img']) ?>
        </div>
    </div>
</div>