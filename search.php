<?php

/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Zotefoams
 */

get_header();
$search_query = get_search_query();
?>

<?php if (! $search_query || have_posts()) : ?>

	<header class="text-banner padding-t-b-70">
		<div class="cont-m">
			<h1 class="uppercase grey-text fs-800 fw-extrabold">
				<?php esc_html_e('Search', 'zotefoams'); ?>
			</h1>
			<div class="margin-t-30">
				<?php get_search_form(); ?>
			</div>
			<?php if (have_posts() && $search_query) : ?>
				<h2 class="uppercase black-text fs-400 fw-extrabold margin-t-70">
					<?php
					if ($search_query) :

						printf(
							esc_html__('Results for: \'%s\'', 'zotefoams'),
							'<span>' . esc_html($search_query) . '</span>'
						);
					endif;
					?>
				</h2>
			<?php else : ?>
				<p class=" margin-t-20">
					<?php esc_html_e('Please enter some search keywords above.', 'zotefoams'); ?>
				</p>
			<?php endif; ?>
		</div>
	</header>

<?php endif; ?>

<?php 
if ($search_query && have_posts()) : 
	while (have_posts()) :
		the_post();
		get_template_part('template-parts/content', 'search');
	endwhile;

	get_template_part('template-parts/pagination');
?>

<?php elseif ($search_query) : ?>

	<?php get_template_part('template-parts/content', 'none'); ?>

<?php endif; ?>

</main>

<?php
get_footer();
