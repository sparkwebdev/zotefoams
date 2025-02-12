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

	<main id="primary" class="site-main">

		<?php if ( have_posts() ) : ?>

			<header class="cont-m margin-t-70">
				<h1 class="uppercase grey-text fs-800 fw-extrabold">Search</h1>
				<h2 class="uppercase black-text fs-400 fw-extrabold  margin-t-70">
					<?php
					/* translators: %s: search query. */
					printf( esc_html__( 'Results for: \'%s\'', 'zotefoams' ), '<span>' . get_search_query() . '</span>' );
					?></h2>
			</header>

			<?php
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				/**
				 * Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called content-search.php and that will be used instead.
				 */
				get_template_part( 'template-parts/content', 'search' );

			endwhile;

			echo '<div class="pagination cont-m margin-t-70 margin-b-70">';
				get_template_part( 'template-parts/pagination' );
			echo '</div>';

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>

	</main><!-- #main -->

<?php
get_footer();
