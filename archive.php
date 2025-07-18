<?php

/**
 * The template for displaying archive pages
 *
 * @package Zotefoams
 */

get_header();

$title               = single_cat_title('', false);
$cat_more_link_label = zotefoams_map_cat_label($title);
$layout              = ($title === 'Case Studies' || $title === 'Videos') ? 'grid' : 'list';
$posts_page_id       = zotefoams_get_page_for_posts_id();
$category_description = category_description();

?>

<header class="text-banner padding-t-b-70">
	<div class="cont-m">
		<h1 class="uppercase grey-text fs-800 fw-extrabold">
			<?php echo esc_html(get_the_title($posts_page_id)); ?>
		</h1>
		<h2 class="uppercase black-text fs-800 fw-extrabold">
			<?php echo esc_html($title === 'News' ? 'Latest ' . $title : $title); ?>
		</h2>
	</div>
</header>

<?php
if (have_posts()) :
?>

	<div class="articles articles--<?php echo esc_attr($layout); ?> cont-m padding-t-b-70 padding-b-100">
		<?php
		while (have_posts()) :
			the_post();

			$cat_more_link   = esc_url(get_the_permalink());
			$thumbnail_url   = get_the_post_thumbnail_url(get_the_ID(), $layout === 'list' ? 'thumbnail-square' : 'thumbnail');

			if (! $thumbnail_url) {
				$thumb_type    = $layout === 'list' ? 'thumbnail-square' : 'thumbnail';
				$thumbnail_url = get_template_directory_uri() . '/images/placeholder-' . $thumb_type . '.png';
			}

			if ($layout === 'list') :
		?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<img src="<?php echo esc_url($thumbnail_url); ?>" alt="" class="thumbnail-square">
					<div class="articles__content">
						<?php if ($title !== 'Events' && $title !== 'Webinars') : ?>
							<div class="margin-b-20 grey-text"><?php zotefoams_posted_on(); ?></div>
							<?php the_title('<h3 class="fs-400 fw-semibold margin-b-20">', '</h3>'); ?>
						<?php else: ?>
							<?php
							if (function_exists('get_field')) {
								$start_date = get_field('event_start_date', get_the_ID());
								$end_date = get_field('event_end_date', get_the_ID());
								$event_name = get_field('event_name');
								if ($event_name) {
									echo '<h3 class="fs-400 fw-semibold">' . $event_name . '</h3>';
								} else {
									the_title('<h3 class="fs-400 fw-semibold">', '</h3>');
								}
								if ($start_date) {
									echo '<div class="margin-b-20">';
									get_template_part('template-parts/parts/events_details_short');
									echo '</div>';
								}
							} ?>
						<?php endif; ?>
						<div class="articles__footer">
							<?php
							if (function_exists('get_field')) {
								if (get_field('event_short_description')) :
									echo get_field('event_short_description');
								elseif (get_the_excerpt()) :
									the_excerpt();
								endif;
							}
							?>
							<p class="articles__cta">
								<a href="<?php echo esc_url($cat_more_link); ?>" class="btn black outline"><?php echo esc_html($cat_more_link_label); ?></a>
							</p>
						</div>
					</div>
				</article>
			<?php else : ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class($title === 'Case Studies' ? 'light-grey-bg' : ''); ?>>
					<?php
					if ($title === 'Videos') :
						$first_video_url = zotefoams_get_first_youtube_url(get_the_ID());
						$youtube_cover_image = $first_video_url
							? (get_the_post_thumbnail_url(get_the_ID(), 'thumbnail') ?: zotefoams_youtube_cover_image($first_video_url))
							: '';

						if ($first_video_url) :
					?>
							<div class="articles__block-embed-youtube" style="background-image:url(<?php echo esc_url($youtube_cover_image); ?>)">
								<button type="button" class="video-trigger" data-modal-trigger="video" data-video-url="<?php echo esc_url($first_video_url); ?>" aria-label="<?php esc_attr_e('Play Video', 'zotefoams'); ?>">
									<img src="<?php echo esc_url(get_template_directory_uri() . '/images/youtube-play.svg'); ?>" alt="" />
								</button>
							</div>
						<?php else : ?>
							<img src="<?php echo esc_url(get_template_directory_uri() . '/images/placeholder-thumbnail.png'); ?>" alt="" class="margin-b-20">
					<?php endif;
					endif;
					?>

					<div <?php post_class($title === 'Case Studies' ? 'articles__content padding-40' : 'articles__content'); ?>>
						<?php the_title('<h3 class="fs-400 fw-semibold margin-b-20">', '</h3>'); ?>

						<?php if (get_the_excerpt()) : ?>
							<div class="margin-b-20 grey-text"><?php the_excerpt(); ?></div>
						<?php endif; ?>

						<?php if ($title === 'Videos' && isset($first_video_url)) : ?>
							<p class="articles__cta margin-b-40">
								<button type="button" class="btn outline black" data-modal-trigger="video" data-video-url="<?php echo esc_url($first_video_url); ?>" aria-label="<?php echo esc_attr($cat_more_link_label); ?>">
									<?php echo esc_html($cat_more_link_label); ?>
								</button>
							</p>
						<?php elseif ($title === 'Videos') : ?>
							<p class="articles__cta margin-b-40">No video found.</p>
						<?php else : ?>
							<p class="articles__cta">
								<a href="<?php echo esc_url($cat_more_link); ?>" class="hl arrow read-more"><?php echo esc_html($cat_more_link_label); ?></a>
							</p>
						<?php endif; ?>
					</div>

					<?php if ($title === 'Case Studies') : ?>
						<img src="<?php echo esc_url($thumbnail_url); ?>" alt="">
					<?php endif; ?>
				</article>
		<?php endif;
		endwhile;
		?>
	</div>

	<?php get_template_part('template-parts/pagination'); ?>

<?php else : ?>

	<div class="text-block cont-m padding-t-b-100 theme-none">
		<div class="text-block__inner">
			<p>
				<?php esc_html_e('Coming soon', 'zotefoams'); ?>
			</p>
			<?php if (!empty($category_description)) : ?>
				<h3 class="fs-600 grey-text fw-semibold margin-t-20"><strong><?php echo wp_kses_post($category_description); ?></strong></h3>
			<?php endif; ?>
		</div>
	</div>
<?php endif; ?>

<?php
if (isset($title) && $title === 'Videos') {
	require_video_overlay();
}
get_footer();
