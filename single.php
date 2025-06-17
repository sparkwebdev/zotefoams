<?php

/**
 * The template for displaying all single posts
 *
 * @package Zotefoams
 */

get_header();
if (post_password_required()) :

	echo get_the_password_form();

else :
?>

<?php while (have_posts()) : the_post(); ?>
	<div class="cont-xs padding-t-b-70">
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="entry-header">
				<?php
				$categories = get_the_category();
				if (! empty($categories) && $categories[0]->name !== 'Uncategorised') {
					echo '<div class="margin-b-20 grey-text">';
					foreach ($categories as $category) {
						echo '<span class="tag">' . esc_html($category->name) . '</span> ';
					}
					echo '</div>';
				}

				if (function_exists('get_field')) {
					$event_name = get_field('event_name');
					if ($event_name) {
						echo '<h1 class="fs-600 fw-semibold margin-b-20">' . $event_name . '</h1>';
					} else {
						the_title('<h1 class="fs-600 fw-semibold margin-b-20">', '</h1>');
					}
				}

				if ('post' === get_post_type()) :
				?>
					<?php
					$has_events_or_webinars = false;
					foreach ($categories as $category) {
						if ($category->name === 'Events' || $category->name === 'Webinars') {
							$has_events_or_webinars = true;
							break;
						}
					}
					if ($category->name === 'Events') {
						get_template_part('template-parts/parts/events_details');
					} elseif ($category->name === 'Webinars') {
						get_template_part('template-parts/parts/webinars_details');
					} else {
						echo '<div class="margin-b-20 grey-text">';
						zotefoams_posted_on();
						echo '</div>';
					}
					?>
				<?php endif; ?>
			</header>

			<?php /* if (has_post_thumbnail()) : ?>
				<figure><?php zotefoams_post_thumbnail('large'); ?></figure>
			<?php endif; */ ?>

			<div class="entry-content">
				<?php if (has_excerpt()) : ?>
					<p class="lead-text"><strong><?php echo esc_html(get_the_excerpt()); ?></strong></p>
				<?php endif; ?>

				<?php
				the_content();

				wp_link_pages(array(
					'before' => '<div class="page-links">' . esc_html__('Pages:', 'zotefoams'),
					'after'  => '</div>',
				));
				?>
			</div>

			<?php
			if ($has_events_or_webinars) {
				get_template_part('template-parts/parts/events_speakers_registration');
			} 
			?>
		</article>

		<?php
		$categories         = get_the_category();
		$first_category_link = ! empty($categories) ? get_category_link($categories[0]->term_id) : '#';
		?>

		<div class="post-navigation post-navigation--single margin-t-70 margin-b-70">
			<div class="nav-back-to-list">
				<a href="<?php echo esc_url($first_category_link); ?>">
					<?php esc_html_e('« Back to List', 'zotefoams'); ?>
				</a>
			</div>

			<div class="nav-previous">
				<?php
				previous_post_link(
					'%link',
					'<span class="nav-subtitle">« ' . esc_html__('Previous Article', 'zotefoams') . '</span>',
					true,
					'',
					'category'
				);
				?>
			</div>

			<div class="nav-next">
				<?php
				next_post_link(
					'%link',
					'<span class="nav-subtitle">' . esc_html__('Next Article', 'zotefoams') . ' »</span>',
					true,
					'',
					'category'
				);
				?>
			</div>
		</div>
	</div>
<?php endwhile;
?>

<hr class="separator" />
<?php 
endif; ?>

<?php get_template_part('template-parts/latest-posts'); ?>

<?php get_footer(); ?>