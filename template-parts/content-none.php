<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Zotefoams
 */

?>

<section class="cont-m margin-t-70 no-results not-found section">
	<header>
		<h1 class="uppercase grey-text fs-800 fw-extrabold"><?php esc_html_e( 'Search', 'zotefoams' ); ?></h1>
		<h2 class="uppercase black-text fs-800 fw-extrabold">Nothing found</h2>
		<h3 class="uppercase black-text fs-400 fw-extrabold  margin-t-70">
			<?php
			/* translators: %s: search query. */
			printf( esc_html__( 'Results for: \'%s\'', 'zotefoams' ), '<span>' . get_search_query() . '</span>' );
			?></h3>
	</header>

	<div class="page-content">
		<?php
		if ( is_search() ) :
			?>
			<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'zotefoams' ); ?></p>
		<?php else : ?>
			<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'zotefoams' ); ?></p>
		<?php
		endif;

		echo '<div class="margin-t-30 margin-b-70">';
			get_search_form();
		echo '</div>';
		?>
	</div><!-- .page-content -->
</section><!-- .no-results -->
