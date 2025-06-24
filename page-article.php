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
			<div class="cont-xs padding-t-b-70">
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

	if (function_exists('get_field')) {
		if (have_rows('page_content')) {
			$i = 1;
			while (have_rows('page_content')) {
				the_row();
				$component = get_row_layout();

				if (is_page('Components')) {
					echo '<div class="blue-bg"><div class="white-text cont-m padding-t-b-30"><h2>' . esc_html(ucwords(str_replace('_', ' ', $component))) . ' ' . esc_html($i) . '</h2></div></div>';
				}

				include locate_template('/template-parts/components/' . $component . '.php', false, false);
				$i++;
			}
		}

		?>

	<div class="cont-xs padding-t-b-70">
	<?php 
		include locate_template('/template-parts/pagination_sibling_articles.php', false, false);
	?>
	</div>

	<?php 
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
