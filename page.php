<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Zotefoams
 */

get_header();

	$pageHeaderType = get_field( 'page_header_type' );

	if( $pageHeaderType == 'text' ): 

		get_template_part('template-parts/comp-07');

	elseif( $pageHeaderType == 'image' ): 

		get_template_part('template-parts/comp-01');

	endif;

	while ( have_posts() ) :
		the_post();
		if ( strcasecmp(get_the_title(), 'Knowledge Hub') === 0 ) {
			get_template_part( 'template-parts/content', 'knowledge-hub' );
		} else if ( get_the_title() === 'Technical Literature' || strcasecmp(get_the_title($post->post_parent), 'Technical Literature') === 0 ) {
				get_template_part( 'template-parts/content', 'knowledge-hub-section-technical' );
		} else if ( $post->post_parent && strcasecmp(get_the_title($post->post_parent), 'Knowledge Hub') === 0)  {
			get_template_part( 'template-parts/content', 'knowledge-hub-section' );
		} else if ( strcasecmp(get_the_title(), 'Markets') === 0)  {
			get_template_part( 'template-parts/content', 'markets' );
		} else {
				get_template_part( 'template-parts/content', 'page' );
		}

	endwhile; // End of the loop.
get_footer();