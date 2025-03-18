<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Zotefoams
 */

 get_header();

$post_type_obj = get_post_type_object( get_post_type() );
$post_type_name = $post_type_obj->labels->singular_name;

 while ( have_posts() ) :
 ?>
	<header class="text-banner margin-t-70">
		<div class="cont-m margin-b-70">
			<h1 class="uppercase grey-text fs-800 fw-extrabold">
				<?php echo esc_html( $post_type_name ); ?>
			</h1>
			<h2 class="uppercase black-text fs-800 fw-extrabold">
			<?php echo get_the_title(); ?>
			</h2>
		</div>
	</header>

	<div class="text-block cont-m margin-t-100 margin-b-100 theme-none">
		<div class="text-block-inner">
			<p class="margin-b-20">Knowledge Powered By Zotefoams</p>
			<?php if (has_excerpt()):
				echo '<div class="fs-600 fw-semibold"><p class="lead-text">'.get_the_excerpt().'</p></div>';
			endif; ?>
		</div>
	</div>
 <?php
	 the_post();
	 // Check if the current page has any child pages.
	 $children = get_pages( array(
		 'child_of'   => get_the_ID(),
		 'post_type'  => 'knowledge-hub',
		 'post_status'=> 'publish'
	 ) );
	 
	 if ( ! empty( $children ) ) {
		 get_template_part( 'template-parts/content', 'knowledge-hub-section-technical' );
	 } else {
		 get_template_part( 'template-parts/content', 'knowledge-hub-section' );
	 }

 endwhile; // End of the loop.

get_footer();