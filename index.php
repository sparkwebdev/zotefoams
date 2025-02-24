<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Zotefoams
 */

get_header();

	if ( have_posts() ) :

		if ( is_home() && ! is_front_page() ) :
			?>
			<header>
				
			</header>

			<header class="text-banner margin-t-70">
				<div class="cont-m margin-b-70">
					<h1><?php single_post_title(); ?></h1>
				</div>
			</header>

			<?php
		endif;

		/* Start the Loop */
		while ( have_posts() ) :
			the_post();

			/*
				* Include the Post-Type-specific template for the content.
				* If you want to override this in a child theme, then include a file
				* called content-___.php (where ___ is the Post Type name) and that will be used instead.
				*/
			get_template_part( 'template-parts/content', get_post_type() );

		endwhile;

		echo '<footer class="pagination cont-m margin-t-70 margin-b-70">';
			get_template_part( 'template-parts/pagination' );
		echo '</footer>';

	else :

		get_template_part( 'template-parts/content', 'none' );

	endif;

get_footer();
