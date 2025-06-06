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

	// Check for Advanced Custom Fields plugin function
	if( function_exists('get_field') ) {

		if ( get_field( 'page_header_type' ) ):

			$pageHeaderType = get_field( 'page_header_type' );

			if( $pageHeaderType == 'text' ):

				include( locate_template( '/template-parts/components/b2-text-banner.php', false, false ) );

			elseif( $pageHeaderType == 'image' ): 

				include( locate_template( '/template-parts/components/b1-Image-banner.php', false, false ) );

			endif;

		endif;

		// check if the flexible content field has rows of data
		if( have_rows('page_content') ) {
			 // loop through the rows of data
			$i = 1;
			while ( have_rows('page_content') ) {
			  the_row();
				$component = get_row_layout();
				if (is_page('Components')) {
					echo '<div class="blue-bg"><div class="white-text cont-m padding-t-b-30"><h2>'.ucwords(str_replace("_", " ", $component)).' '.$i.'</h2></div></div>';
				}
				include( locate_template( '/template-parts/components/'.$component.'.php', false, false ) );
				$i++;
			}
		}
		
		if ( get_field( 'page_footer_contact_forms' ) ):

			include( locate_template( '/template-parts/components/show_hide_forms.php', false, false ) );

		endif;
	}


	while ( have_posts() ) :
		the_post();
		if ( get_the_title() === 'Technical Literature' || strcasecmp(get_the_title($post->post_parent), 'Technical Literature') === 0 ) {
				get_template_part( 'template-parts/content', 'knowledge-hub-section-technical' );
		} else if ( $post->post_parent && strcasecmp(get_the_title($post->post_parent), 'Knowledge Hub') === 0)  {
			get_template_part( 'template-parts/content', 'knowledge-hub-section' );
		} else {
				// get_template_part( 'template-parts/content', 'page' );
		}

	endwhile; // End of the loop.

get_footer();