<?php

/**
 * Template part for displaying results in search pages
 *
 * @package Zotefoams
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php
		$post_type = get_post_type();
		$title = get_the_title();

		// Add document info for attachments
		if ($post_type === 'attachment') {
			$file_url = wp_get_attachment_url(get_the_ID());
			$file_path = get_attached_file(get_the_ID());
			$file_type = strtoupper(pathinfo($file_url, PATHINFO_EXTENSION));

			// Only add file size if file exists
			if ($file_path && file_exists($file_path)) {
				$file_size = size_format(filesize($file_path), 1);
				$title .= ' <span class="fs-300 fw-100 nowrap">[' . $file_type . ', ' . $file_size . ']</span>';
			} else {
				$title .= ' <span class="fs-300 fw-100 nowrap">[' . $file_type . ']</span>';
			}
		}

		// Add target="_blank" for attachments
		$target_attr = ($post_type === 'attachment') ? ' target="_blank"' : '';

		printf(
			'<h2 class="entry-title fs-500"><a href="%s" rel="bookmark"%s>%s</a></h2>',
			esc_url(get_permalink()),
			$target_attr,
			$title
		);

		// Display breadcrumb: categories for posts, page titles for pages
		$breadcrumb = '';

		if (get_post_type() === 'post') {
			// For posts, show categories
			$categories = get_the_category();
			if (!empty($categories)) {
				$category_names = array_map(function($cat) {
					return $cat->name;
				}, $categories);
				$breadcrumb = '> ' . implode(' > ', $category_names);
			}
		} else {
			// For pages and other types, build breadcrumb from actual page titles
			$url_path = wp_parse_url(get_permalink(), PHP_URL_PATH);
			$segments = array_filter(explode('/', trim($url_path, '/'))); // Remove empty segments

			// Remove last segment (current page)
			array_pop($segments);

			// Build breadcrumb from page titles
			if (!empty($segments)) {
				$page_titles = array();
				$path_so_far = '';

				foreach ($segments as $slug) {
					$path_so_far .= '/' . $slug;

					// Try to find the page by path
					$page = get_page_by_path($path_so_far, OBJECT, array('page', 'knowledge-hub'));

					if ($page) {
						$page_titles[] = $page->post_title;
					} else {
						// Fallback to formatted slug if page not found
						$page_titles[] = ucwords(str_replace('-', ' ', $slug));
					}
				}

				if (!empty($page_titles)) {
					$breadcrumb = '> ' . implode(' > ', $page_titles);
				}
			}
		}

		// Display breadcrumb if exists
		if (!empty($breadcrumb)) {
			?>
			<p class="grey-text"><?php echo esc_html($breadcrumb); ?></p>
			<?php
		}
		?>
	</header>

	<?php
	// Don't show excerpt for attachments/files
	if (get_post_type() !== 'attachment') {
		$excerpt = get_the_excerpt();

		// If no excerpt and this is a page, try to extract from ACF page_content
		if (empty(trim(wp_strip_all_tags($excerpt))) && function_exists('zotefoams_get_acf_excerpt')) {
			$excerpt = zotefoams_get_acf_excerpt();
		}

		if (!empty(trim($excerpt))) : ?>
			<div class="entry-summary">
				<?php echo wp_kses_post(wpautop($excerpt)); ?>
			</div>
		<?php endif;
	}
	?>

	<?php if ('post' === get_post_type()) : ?>
		<div class="entry-meta margin-t-20 grey-text">
			<?php zotefoams_posted_on(); ?>
		</div>
	<?php endif; ?>
</article>