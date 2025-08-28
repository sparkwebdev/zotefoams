<?php

/**
 * Template Name: Biography
 */

get_header();

?>

<header class="text-banner padding-t-b-70">
	<div class="cont-m">
		<h1 class="uppercase grey-text fs-800 fw-extrabold">
			<?php echo esc_html(get_the_title(wp_get_post_parent_id())); ?>
		</h1>
		<h2 class="uppercase black-text fs-800 fw-extrabold">
			<?php echo esc_html(get_the_title()); ?>
		</h2>
	</div>
</header>

<?php

if (post_password_required()) :

	echo get_the_password_form();

else : ?>

	<div class="biography cont-m padding-t-b-100 theme-none">
		<div>
			<?php if (has_post_thumbnail()) : ?>
				<figure><?php zotefoams_post_thumbnail('large'); ?></figure>
			<?php endif; ?>
		</div>

		<div>
			<?php
			while (have_posts()) :
				the_post();
				the_content();
			endwhile;
			?>
		</div>
	</div>

	<?php 
	$nav_data = Zotefoams_Navigation_Helper::get_dynamic_back_link();
	if ($nav_data) : ?>
	<div class="cont-m padding-t-b-100 theme-none">
		<div class="nav-links">
			<?php Zotefoams_Navigation_Helper::render_dynamic_back_link(); ?>
		</div>
	</div>
	<?php endif; ?>

<?php
endif;

get_footer();

?>