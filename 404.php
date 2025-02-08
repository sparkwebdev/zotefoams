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

	<main id="primary" class="site-main">

		<section class="error-404 not-found">

			<header class="text-banner cont-m margin-t-70">
				<h1 class="uppercase grey-text fs-800 fw-extrabold">404</h1>
				<h2 class="uppercase black-text fs-800 fw-extrabold"><?php esc_html_e( 'Not found', 'zotefoams' ); ?></h2>
			</header>

			<div class="cont-m margin-t-70 margin-b-70">
				<p class="grey-text fs-600 fw-semibold"><?php esc_html_e( 'It looks like nothing was found at this location.', 'zotefoams' ); ?></p>
			</div>

			</div><!-- .page-content -->
		</section><!-- .error-404 -->

	</main><!-- #main -->

<?php
get_footer();
