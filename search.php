<?php

/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Zotefoams
 */

get_header();
?>

<?php if (! get_search_query()) : ?>

	<header class="text-banner margin-t-70">
		<div class="cont-m margin-b-70">
			<h1 class="uppercase grey-text fs-800 fw-extrabold">
				<?php esc_html_e('Search', 'zotefoams'); ?>
			</h1>
			<div class="margin-t-30">
				<?php get_search_form(); ?>
			</div>
		</div>
	</header>

<?php elseif (have_posts()) : ?>

	<header class="text-banner margin-t-70">
		<div class="cont-m margin-b-70">
			<h1 class="uppercase grey-text fs-800 fw-extrabold">
				<?php esc_html_e('Search', 'zotefoams'); ?>
			</h1>
			<div class="margin-t-30 margin-b-30">
				<?php get_search_form(); ?>
			</div>
			<h2 class="uppercase black-text fs-400 fw-extrabold margin-t-70">
				<?php
				printf(
					esc_html__('Results for: \'%s\'', 'zotefoams'),
					'<span>' . esc_html(get_search_query()) . '</span>'
				);
				?>
			</h2>
		</div>
	</header>

	<?php
	while (have_posts()) :
		the_post();
		get_template_part('template-parts/content', 'search');
	endwhile;
	?>

	<?php get_template_part('template-parts/pagination'); ?>

<?php else : ?>

	<?php get_template_part('template-parts/content', 'none'); ?>

<?php endif; ?>

</main><!-- #main -->

<?php
get_footer();
