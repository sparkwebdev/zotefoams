<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Zotefoams
 */

get_header();
?>

	<header class="text-banner margin-t-70">
		<div class="cont-m margin-b-70">
			<h1 class="uppercase grey-text fs-800 fw-extrabold">
				404
			</h1>
			<h2 class="uppercase black-text fs-800 fw-extrabold">
				<?php esc_html_e( 'Not found', 'zotefoams' ); ?>
			</h2>
		</div>
	</header>

	<div class="text-block cont-m margin-t-100 margin-b-100 theme-none">
		<div class="text-block-inner">
			<p class="grey-text fs-600 fw-semibold"><?php esc_html_e( 'It looks like nothing was found at this location.', 'zotefoams' ); ?></p>
		</div>
	</div>

<?php
get_footer();
