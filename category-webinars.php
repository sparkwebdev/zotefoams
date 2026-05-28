<?php

/**
 * The template for displaying the Webinars category archive
 *
 * @package Zotefoams
 */

get_header();

global $title; // Make category title global for template parts
$title = single_cat_title('', false); // This will be "Webinars"
$posts_page_id = function_exists('zotefoams_get_page_for_posts_id') ? zotefoams_get_page_for_posts_id() : get_option('page_for_posts');

?>

<header class="text-banner padding-t-b-70">
    <div class="cont-m">
        <h1 class="uppercase grey-text fs-800 fw-extrabold">
            <?php echo esc_html(get_the_title($posts_page_id)); ?>
        </h1>
        <h2 class="uppercase black-text fs-800 fw-extrabold">
            <?php echo esc_html($title); ?>
        </h2>
    </div>
</header>
<?php
$today = date('Ymd');
$current_category_id = get_queried_object_id(); // ID of the "Webinars" category
?>



<?php

$future_args = [
    'post_type'         => 'post', // Assuming webinars are standard posts
    'cat'               => $current_category_id,
    'posts_per_page'    => -1,
    'meta_key'          => 'event_start_date',
    'meta_value'        => $today,
    'meta_compare'      => '>=',
    'orderby'           => 'meta_value',
    'order'             => 'ASC',
    'meta_type'         => 'CHAR',
    'no_found_rows'     => true,
];
$future_webinars = new WP_Query($future_args);

if ($future_webinars->have_posts()) : ?>
    <div class="text-block cont-m padding-t-b-70 theme-none">
        <div class="text-block__inner">
            <div class="grey-text fs-600 fw-semibold">
                <p><strong>Upcoming Webinars</strong></p>
            </div>
        </div>
    </div>
    <div class="articles articles--list cont-m padding-t-b-70 padding-b-100 theme-none">
        <?php
        while ($future_webinars->have_posts()) : $future_webinars->the_post();
            $cat_more_link = get_permalink();

            $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail-square');
            if (!$thumbnail_url) {
                $thumbnail_url = get_template_directory_uri() . '/images/placeholder-thumbnail-square.png';
            }
            $cat_more_link_label = 'Learn More';

        ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <img src="<?php echo esc_url($thumbnail_url); ?>" alt="" class="thumbnail-square">
                <div class="articles__content">
                    <?php the_title('<h3 class="fs-400 fw-semibold">', '</h3>'); ?>
                    <?php
                    if (function_exists('get_field')) {
                        $start_date = get_field('event_start_date', get_the_ID());
                        $end_date = get_field('event_end_date', get_the_ID());
                        echo '<h3 class="fs-400 fw-semibold margin-b-20 grey-text">' . zotefoams_format_event_date_range($start_date, $end_date) . '</h3>';
                    } ?>
                    <div class="articles__footer">
                        <?php if (get_the_excerpt()) : the_excerpt();
                        endif; ?>
                        <p class="articles__cta">
                            <a href="<?php echo esc_url($cat_more_link); ?>" class="btn black outline"><?php echo esc_html($cat_more_link_label); ?></a>
                        </p>
                    </div>
                </div>
            </article>
        <?php
        endwhile;
        ?>
    </div>
<?php else : ?>

    <div class="text-block cont-m padding-t-b-100 theme-none">
        <div class="text-block__inner">
            <p class="margin-b-20">Upcoming Webinars</p>
            <div class="grey-text fs-600 fw-semibold">
                <p><strong>Zotefoams occasionally hosts free webinars</strong> about industry issues, new product launches, business plans and case studies. <strong>Register below for updates</strong>.</p>
            </div>
        </div>
    </div>
    <div class="cont-m padding-t-b-70 padding-b-100 theme-none">
        <p>No upcoming webinars currently scheduled. Please check back soon.</p>
    </div>
<?php
endif;
wp_reset_postdata();
?>


<div class="padding-t-b-100 theme-light light-grey-bg" id="register-updates">
    <div class="split-text cont-m">
        <div class="split-text__title margin-b-20 fs-600 fw-semibold">
            <p class="fw-bold margin-b-30">Register for updates</p>
            <p class="grey-text">If you would like to receive email notifications about forthcoming webinars, please complete the form.</p>
        </div>

        <div class="split-text__content fs-300">
            <?php echo do_shortcode('[wpforms id="3036" title="false"]'); ?>
        </div>
    </div>
</div>

<div class="text-block cont-m padding-t-b-100 theme-none">
    <div class="text-block__inner">
        <p class="margin-b-20">Past Webinars</p>
        <div class="grey-text fs-600 fw-semibold">
            <p><strong>Explore previous webinar recordings</strong> covering industry trends, product launches, and real-world case studies.</p>
        </div>
    </div>
</div>

<div class="articles articles--grid cont-m padding-t-b-70 padding-b-100 theme-none">
    <?php
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; // get_query_var for main query, for custom query, use its own paged var

    $past_args = [
        'post_type'         => 'post', // Assuming webinars are standard posts
        'cat'               => $current_category_id,
        'posts_per_page'    => -1,
        'paged'             => $paged, // Use paged for WP_Query pagination
        'meta_key'          => 'event_start_date',
        'meta_value'        => $today,
        'meta_compare'      => '<',
        'orderby'           => 'meta_value',
        'order'             => 'DESC',
        'meta_type'         => 'CHAR',
    ];
    $past_webinars = new WP_Query($past_args);

    if ($past_webinars->have_posts()) :
        $hasVideos = false;
        while ($past_webinars->have_posts()) : $past_webinars->the_post();
            // Prepare variables for the template part
            $cat_more_link = get_permalink();
            $cat_more_link_label = 'Watch Recording';

            $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');
            if (!$thumbnail_url) {
                $thumbnail_url = get_template_directory_uri() . '/images/placeholder-thumbnail.png';
            }

            if (function_exists('get_field')) {
                $first_video_url = get_field('event_playback_url');
            }

            if (!isset($first_video_url)) {
                $first_video_url = zotefoams_get_first_youtube_url(get_the_ID());
            }

            $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');

            if (!$thumbnail_url && $first_video_url) {
                $thumbnail_url = zotefoams_youtube_cover_image($first_video_url);
            }

            if (!$thumbnail_url) {
                $thumbnail_url = esc_url(get_template_directory_uri() . '/images/placeholder-thumbnail.png');
            }

            $youtube_cover_image = $thumbnail_url;

            if ($first_video_url) :
                $hasVideos = true;
    ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class($title === 'Case Studies' ? 'light-grey-bg' : ''); ?>>
                    <div class="articles__block-embed-youtube" style="background-image:url(<?php echo esc_url($youtube_cover_image); ?>)">
                        <button type="button" class="video-trigger" data-modal-trigger="video" data-video-url="<?php echo esc_url($first_video_url); ?>" aria-label="<?php esc_attr_e('Play Video', 'zotefoams'); ?>">
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/images/youtube-play.svg'); ?>" alt="" />
                        </button>
                    </div>

                    <div class="articles__content">
                        <?php the_title('<h3 class="fs-400 fw-semibold">', '</h3>'); ?>
                        <?php
                        if (function_exists('get_field')) {
                            $start_date = get_field('event_start_date', get_the_ID());
                            $end_date = get_field('event_end_date', get_the_ID());
                            echo '<h3 class="fs-400 fw-semibold margin-b-20 grey-text">' . zotefoams_format_event_date_range($start_date, $end_date) . '</h3>';
                        } ?>

                        <?php /* if (get_the_excerpt()) : ?>
                                <div class="margin-b-20 grey-text"><?php the_excerpt(); ?></div>
                            <?php endif;*/ ?>

                        <p class="articles__cta margin-b-40">
                            <button type="button" class="btn outline black" data-modal-trigger="video" data-video-url="<?php echo esc_url($first_video_url); ?>" aria-label="<?php echo esc_attr($cat_more_link_label); ?>">
                                <?php echo esc_html($cat_more_link_label); ?>
                            </button>
                        </p>
                    </div>
                </article>
        <?php endif;

        endwhile;
    endif;
    if (!$hasVideos) : ?>
        <p>There are no past webinars to display at this time.</p>
    <?php endif;
    wp_reset_postdata();
    ?>
</div>

<?php
require_video_overlay();
get_footer();
