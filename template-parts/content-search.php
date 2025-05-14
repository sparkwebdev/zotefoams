<?php

/**
 * Template part for displaying results in search pages
 *
 * @package Zotefoams
 */
?>

<div class="cont-m padding-t-50">
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<header class="entry-header">
			<div class="search-header">
				<div>
					<?php
					the_title(
						sprintf(
							'<h2 class="entry-title fs-500 margin-b-10"><a href="%s" rel="bookmark">',
							esc_url(get_permalink())
						),
						'</a></h2>'
					);
					?>

					<?php if ('post' === get_post_type()) : ?>
						<div class="entry-meta">
							<?php zotefoams_posted_on(); ?>
						</div>
					<?php endif; ?>
				</div>

				<div class="search-thumb">
					<?php zotefoams_post_thumbnail(); ?>
				</div>
			</div>
		</header>

		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div>
	</article>
</div>