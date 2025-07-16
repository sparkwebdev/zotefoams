<?php

/**
 * Template Name: Article
 */

get_header(); ?>

<?php

if (function_exists('get_field')) {

	$page_header_type = get_field('page_header_type');
	if ($page_header_type === 'text') {
		$pageHeaderText = get_field('page_header_text');
		if ($pageHeaderText):
			$title = !empty($pageHeaderText['title']) ? $pageHeaderText['title'] : get_the_title();
			$subtitle = $pageHeaderText['subtitle'] ?? '';
?>
			<div class="cont-xs padding-t-b-70 padding-b-30 theme-none">
				<header class="entry-header">
					<div class="margin-b-20 grey-text"><span class="tag"><?php echo $title; ?></span> </div>
					<h1 class="fs-600 fw-semibold margin-b-20"><?php echo $subtitle; ?></h1>
				</header>
			</div>
<?php
		endif;
	} elseif ($page_header_type === 'image') {
		include locate_template('/template-parts/components/b1-Image-banner.php', false, false);
	}
}

if (post_password_required()) :

	echo get_the_password_form();

else :
?>


	<div class="article-content cont-xs padding-t-b-70 theme-none">
		<div>
			<?php if (has_post_thumbnail()) : ?>
				<figure><?php zotefoams_post_thumbnail('large'); ?></figure>
			<?php endif; ?>
		</div>

		<?php
		while (have_posts()) :
			the_post();
			the_content();
		endwhile;
		?>
	</div>

	<div class="cont-xs padding-t-b-70 theme-none">
	<?php 
		include locate_template('/template-parts/pagination-sibling-articles.php', false, false);
	?>
	</div>

	<?php 
	if (function_exists('get_field')) {
		if (get_field('page_footer_contact_forms')) {
			include locate_template('/template-parts/components/show_hide_forms.php', false, false);
		}
	}

	while (have_posts()) :
		the_post();

		$page_title           = get_the_title();
		$parent_title         = $post->post_parent ? get_the_title($post->post_parent) : '';

		if ($page_title === 'Technical Literature' || strcasecmp($parent_title, 'Technical Literature') === 0) {
			get_template_part('template-parts/content', 'knowledge-hub-section-technical');
		} elseif ($post->post_parent && strcasecmp($parent_title, 'Knowledge Hub') === 0) {
			get_template_part('template-parts/content', 'knowledge-hub-section');
		}

	endwhile;

endif;

get_footer();
