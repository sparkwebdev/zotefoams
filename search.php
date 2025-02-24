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

		<?php 
		
		if (!get_search_query()) : ?>

		<header class="text-banner margin-t-70">
			<div class="cont-m margin-b-70">
				<h1 class="uppercase grey-text fs-800 fw-extrabold">
					<?php esc_html_e( 'Search', 'zotefoams' ); ?>
				</h1>
				<div class="margin-t-30">
				<?php get_search_form(); ?>
				</div>
			</div>
		</header>
		
		<?php elseif ( have_posts() ) : ?>

		<header class="text-banner margin-t-70">
			<div class="cont-m margin-b-70">
				<h1 class="uppercase grey-text fs-800 fw-extrabold">Search</h1>
				<?php 
				echo '<div class="margin-t-30 margin-b-30">';
				get_search_form();
				echo '</div>';?>
				<h2 class="uppercase black-text fs-400 fw-extrabold  margin-t-70">
					<?php
					if (get_search_query()) {
						/* translators: %s: search query. */
						printf( esc_html__( 'Results for: \'%s\'', 'zotefoams' ), '<span>' . get_search_query() . '</span>' );
					}
					?></h2>
			</div>
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

			echo '<footer class="pagination cont-m margin-t-70 margin-b-70">';
				get_template_part( 'template-parts/pagination' );
			echo '</footer>';

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>

	</main><!-- #main -->

<?php
get_footer();
