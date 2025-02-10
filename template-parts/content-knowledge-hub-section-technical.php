<section id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <header class="text-banner cont-m margin-t-70 margin-b-100">
        <h1 class="uppercase grey-text fs-800 fw-extrabold"><?php echo get_the_title(wp_get_post_parent_id(get_the_ID())); ?></h1>
        <h2 class="uppercase black-text fs-800 fw-extrabold"><?php echo get_the_title(); ?></h2>
    </header>

    <hr class="separator margin-b-70" />

    <div class="text-block cont-m margin-b-70">
        <div class="text-block-inner">
            <p class="margin-b-20 grey-text"><?php echo get_the_title(); ?></p>
            <p class="grey-text fs-600 fw-semibold">Aenean quis lorem tempus sodales ipsum ac maximus mauris.
                <b>Morbi elementum nec dolor id porttitor.</b></p>
        </div>
    </div>

    <?php if (get_the_content()) { ?>
    <div class="cont-m margin-b-70">
        <?php
        the_content();
        ?>
    </div><!-- .entry-content -->
    <?php } ?>


    </section><!-- #post-<?php the_ID(); ?> -->

