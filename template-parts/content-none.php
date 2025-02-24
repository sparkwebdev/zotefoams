<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Zotefoams
 */

?>

<header class="text-banner margin-t-70">
	<div class="cont-m margin-b-70">
		<h1 class="uppercase grey-text fs-800 fw-extrabold">
			<?php if (is_search()) :
			esc_html_e( 'Search', 'zotefoams' );
			else :
			esc_html_e( '404', 'zotefoams' );
			endif;
			?>
		</h1>
		<h2 class="uppercase black-text fs-800 fw-extrabold">
			<?php // echo ($title == "News" ? 'Latest ' : '') . $title; ?>
			Nothing found
		</h2>
	</div>
</header>

<div class="text-block cont-m margin-t-100 margin-b-100 theme-none">
	<div class="text-block-inner">

		<?php
		if ( is_search() ) :
			?>
			<p class="grey-text fs-600 fw-semibold margin-b-20"><?php esc_html_e( 'It looks like nothing was found at this location.', 'zotefoams' ); ?></p>
			<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'zotefoams' ); ?></p>
		<?php else : ?>
			<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'zotefoams' ); ?></p>
		<?php
		endif;

		echo '<div class="margin-t-30 margin-b-70">';
			get_search_form();
		echo '</div>';
		?>

	</div>
</div>