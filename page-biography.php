<?php
/**
* Template Name: Biography
 */

get_header();

	?>

	<header class="text-banner margin-t-70">
		<div class="cont-m margin-b-70">
			<h1 class="uppercase grey-text fs-800 fw-extrabold">
				<?php echo get_the_title(wp_get_post_parent_id()); ?>
			</h1>
			<h2 class="uppercase black-text fs-800 fw-extrabold">
				<?php echo get_the_title(); ?>
			</h2>
		</div>
	</header>

	<div class="biography cont-m margin-t-100 margin-b-50">
		
		<div>
			<?php if ( has_post_thumbnail() ) { ?>
				<figure><?php zotefoams_post_thumbnail('large'); ?></figure>
			<?php } ?>
		</div>
		<div>
			<?php
			while ( have_posts() ) :
				the_post();
				the_content();
			endwhile; // End of the loop. ?>
		</div>
		
	</div>

	<div class="cont-m margin-b-100 post-navigation">
		<div class="nav-links">
			<a href="/directors">« Back to Directors</a>
		</div>
	</div>

<?php get_footer();