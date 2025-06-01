<?php

/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#404-not-found
 *
 * @package Zotefoams
 */

get_header();
?>

<header class="text-banner padding-t-b-70">
	<div class="cont-m">
		<h1 class="uppercase grey-text fs-800 fw-extrabold">
			404
		</h1>
		<h2 class="uppercase black-text fs-800 fw-extrabold">
			<?php esc_html_e('Not found', 'zotefoams'); ?>
		</h2>
	</div>
</header>

<div class="text-block cont-m padding-t-b-100 theme-none">
	<div class="text-block__inner">
		<p class="grey-text fs-600 fw-semibold">
			<?php esc_html_e('It looks like nothing was found at this location.', 'zotefoams'); ?>
		</p>
		<p class="margin-t-20">
			<?php esc_html_e('Perhaps searching can help.', 'zotefoams'); ?>
		</p>

		<div class="margin-t-30">
			<?php get_search_form(); ?>
		</div>
	</div>
</div>

<?php
get_footer();
