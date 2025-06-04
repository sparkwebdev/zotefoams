<?php

/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Zotefoams
 */

if (! function_exists('zotefoams_posted_on')) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function zotefoams_posted_on()
	{
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if (get_the_time('U') !== get_the_modified_time('U')) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr(get_the_date(DATE_W3C)),
			esc_html(get_the_date()),
			esc_attr(get_the_modified_date(DATE_W3C)),
			esc_html(get_the_modified_date())
		);

		// $posted_on = sprintf(
		/* translators: %s: post date. */
		// esc_html_x( 'Posted on %s', 'post date', 'zotefoams' ),
		// '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		// );

		echo '<span class="posted-on">' . $time_string . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

if (! function_exists('zotefoams_posted_by')) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function zotefoams_posted_by()
	{
		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x('by %s', 'post author', 'zotefoams'),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
		);

		echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;


/**
 * Format an event date range smartly.
 *
 * @param string|null $start_date Date in 'Ymd' format (e.g., '20250501').
 * @param string|null $end_date   Date in 'Ymd' format (e.g., '20250503').
 * @return string Rendered date string.
 */
if (! function_exists('zotefoams_format_event_date_range')) :
	function zotefoams_format_event_date_range($start_date = null, $end_date = null)
	{
		if (!$start_date && !$end_date) {
			return '';
		}

		$start = $start_date ? DateTime::createFromFormat('Ymd', $start_date) : null;
		$end   = $end_date ? DateTime::createFromFormat('Ymd', $end_date) : null;
		$formatted_date = '';

		if ($start && $end) {
			if ($start->format('F Y') === $end->format('F Y')) {
				// Same month and year: "1 to 3 May 2025"
				$formatted_date = $start->format('j') . ' to ' . $end->format('j F Y');
			} else {
				// Different months: "1 May 2025 – 3 June 2025"
				$formatted_date = $start->format('j F Y') . ' – ' . $end->format('j F Y');
			}
		} elseif ($start) {
			$formatted_date = $start->format('j F Y');
		} elseif ($end) {
			$formatted_date = 'Until ' . $end->format('j F Y');
		}

		return $formatted_date;
	}
endif;

/**
 * Determine if a given date is in the past.
 *
 * @param string|null $date ACF date field in 'Ymd' format (e.g., '20250604').
 * @return bool True if the date is before today.
 */

if (! function_exists('zotefoams_is_past_date')) :
	function zotefoams_is_past_date($date = null)
	{
		if (!$date) {
			return false;
		}
		$date_obj = DateTime::createFromFormat('Ymd', $date);
		if (!$date_obj) {
			return false; // Failed to parse
		}
		$today = new DateTime('today');
		return $date_obj < $today;
	}
endif;


if (! function_exists('zotefoams_entry_footer')) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function zotefoams_entry_footer()
	{
		// Hide category and tag text for pages.
		if ('post' === get_post_type()) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list(esc_html__(', ', 'zotefoams'));
			if ($categories_list) {
				/* translators: 1: list of categories. */
				printf('<span class="cat-links">' . esc_html__('Posted in %1$s', 'zotefoams') . '</span>', $categories_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list('', esc_html_x(', ', 'list item separator', 'zotefoams'));
			if ($tags_list) {
				/* translators: 1: list of tags. */
				printf('<span class="tags-links">' . esc_html__('Tagged %1$s', 'zotefoams') . '</span>', $tags_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		if (! is_single() && ! post_password_required() && (comments_open() || get_comments_number())) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__('Leave a Comment<span class="screen-reader-text"> on %s</span>', 'zotefoams'),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post(get_the_title())
				)
			);
			echo '</span>';
		}

		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__('Edit <span class="screen-reader-text">%s</span>', 'zotefoams'),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post(get_the_title())
			),
			'<span class="edit-link"> | ',
			'</span>'
		);
	}
endif;

if (! function_exists('zotefoams_post_thumbnail')) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function zotefoams_post_thumbnail()
	{
		if (post_password_required() || is_attachment() || ! has_post_thumbnail()) {
			return;
		}

		if (is_singular()) :
?>

			<div class="post-thumbnail">
				<?php the_post_thumbnail(); ?>
			</div><!-- .post-thumbnail -->

		<?php else : ?>

			<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php
				the_post_thumbnail(
					'post-thumbnail',
					array(
						'alt' => the_title_attribute(
							array(
								'echo' => false,
							)
						),
					)
				);
				?>
			</a>

<?php
		endif; // End is_singular().
	}
endif;

if (! function_exists('wp_body_open')) :
	/**
	 * Shim for sites older than 5.2.
	 *
	 * @link https://core.trac.wordpress.org/ticket/12563
	 */
	function wp_body_open()
	{
		do_action('wp_body_open');
	}
endif;
