<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Zotefoams
 */

?>

<section id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <!-- <header class="text-banner cont-m margin-t-70 margin-b-100">
        <?php the_title( '<h1 class="uppercase grey-text fs-800 fw-extrabold">', '</h1>' ); ?>
        <h2 class="uppercase black-text fs-800 fw-extrabold">Our Product Literature</h2>
    </header>


    <div class="text-banner-split half-half padding-b-100">
        <div class="half video-container image-cover" style="background-image:url(<?php echo get_template_directory_uri(); ?>/images/who-we-are.jpg)">
        </div>
        <div class="half black-bg white-text padding-100">
            <div class="text-banner-text">
                <p class="fs-200 fw-regular margin-b-30">Knowledge Powered By Zotefoams
</p>
                <p class="fs-600 fw-semibold margin-b-100">Welcome to the world of Zotefoams. Here you can find links our latest news articles, blogs, webinars and events.</p>
            </div>
        </div>
    </div>

    <div class="text-block cont-m margin-b-70">
        <div class="text-block-inner">
            <p class="margin-b-20">Everything Zotefoams</p>
            <p class="grey-text fs-600 fw-semibold"><b>Fusce eros dolor luctus et lobortis sit amet </b>vulputate ut lacus. Cras molestie est libero sed tempor leo porttitor vitae. Class aptent taciti sociosqu ad litora.</p>
        </div>
    </div> -->

	<section class="cont-m margin-t-70">
    <div class="articles articles--grid margin-b-70">

    <?php
        $parent_id = get_the_ID();
        $child_pages = get_children(array(
            'post_parent' => $parent_id, 
            'post_type'   => 'page',
            'post_status' => 'publish',
            'orderby' => 'menu_order',
            'order' => 'DESC',
        ));
        foreach ($child_pages as $child) {

            $thumbnail_url = get_the_post_thumbnail_url($child->ID, 'thumbnail');
            if (!$thumbnail_url) {
                $thumbnail_url = get_template_directory_uri() . '/images/placeholder-thumbnail.png';
            } ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('light-grey-bg'); ?>>
            <div class="articles__content">
            <?php

                echo '<h3 class="fs-400 fw-semibold margin-b-20">' . get_the_title($child->ID) . '</h3>';
                if (get_the_excerpt($child->ID)) {
                    echo '<div class="margin-b-20 grey-text">';
                    echo get_the_excerpt($child->ID);
                    echo '</div>';
                }
                echo '<p class="articles__cta"><a href="' . get_the_permalink($child->ID) . '" class="hl arrow">All ' . get_the_title($child->ID) . '</a></p>';
            ?>
            </div>
            <?php
                echo '<img src="' . esc_url($thumbnail_url) . '" alt="">';
            ?>
            </article><!-- #post-<?php the_ID(); ?> -->
        <?php } ?>
        </div>
    </section>

</section><!-- #post-<?php the_ID(); ?> -->
