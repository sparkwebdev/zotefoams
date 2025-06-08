<?php

/**
 * Template part for displaying a message that posts cannot be found
 *
 * @package Zotefoams
 */
?>

<header class="text-banner padding-t-b-70">
	<div class="cont-m">
		<h1 class="uppercase grey-text fs-800 fw-extrabold">
			<?php echo is_search() ? esc_html__('Search', 'zotefoams') : esc_html__('404', 'zotefoams'); ?>
		</h1>
		<h2 class="uppercase black-text fs-800 fw-extrabold">
			<?php echo is_search() ? esc_html__('No results', 'zotefoams') : esc_html__('Nothing found', 'zotefoams'); ?>
		</h2>
	</div>
</header>

<div class="text-block cont-m padding-t-b-100 theme-none">
	<div class="text-block__inner">
		<?php if (is_search()) : ?>
			<p class="grey-text fs-600 fw-semibold">
				<?php
				$search_query = esc_html(get_search_query());
				printf(
					esc_html__('Sorry, but nothing matched your search term \'%s\'.', 'zotefoams'),
					$search_query
				);
				?>
			</p>
			<p class="margin-t-20">
				<?php esc_html_e('Please try again with some different keywords.', 'zotefoams'); ?>
			</p>
		<?php else : ?>
			<p>
				<?php esc_html_e('It seems we can’t find what you’re looking for. Perhaps searching can help.', 'zotefoams'); ?>
			</p>
		<?php endif; ?>

		<div class="margin-t-30">
			<?php get_search_form(); ?>
		</div>
	</div>
</div>