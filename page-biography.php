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

<div class="cont-m padding-t-b-100 theme-none">
	<div class="nav-links">
		<a href="<?php echo esc_url(home_url('/directors')); ?>">« <?php esc_html_e('Back to Directors', 'zotefoams'); ?></a>
	</div>
</div>

<?php get_footer(); ?>