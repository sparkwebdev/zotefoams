<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Zotefoams
 */

?>

<footer id="colophon" class="site-footer padding-t-b-50">

	<div class="cont-m">

		<div class="site-branding margin-b-40">
			<a href="<?php echo esc_url(home_url('/')); ?>">
				<?php
				$brand_logo = get_field('brand_logo', 'option');
				if ($brand_logo) :
				?>
					<img src="<?php echo esc_url($brand_logo); ?>" alt="Brand Logo" />
				<?php endif; ?>
			</a>
		</div><!-- .site-branding -->

		<div class="footer-content margin-b-50">

			<div class="footer-menus">
				<div class="footer-menu footer-menu-1">
					<p class="blue-text fw-bold margin-b-30">Legal</p>
					<?php
					wp_nav_menu(array(
						'theme_location' => 'legal_menu',
						'container' => false,
					));
					?>
				</div>
				<div class="footer-menu footer-menu-2">
					<p class="blue-text fw-bold margin-b-30">Quick Links</p>
					<?php
					wp_nav_menu(array(
						'theme_location' => 'quick_links_menu',
						'container' => false,
					));
					?>
				</div>
				<div class="footer-menu footer-menu-3">
					<p class="blue-text fw-bold margin-b-30">Social</p>
					<ul class="social-links">
						<?php if (have_rows('social_media_links', 'option')) :
							while (have_rows('social_media_links', 'option')) :
								the_row();
								$link = get_sub_field('social_media_link');
								if ($link) :
									$link_url = $link['url'];
									$link_title = $link['title'];
						?>
									<li>
										<a href="<?php echo esc_url($link_url); ?>" target="_blank" rel="noopener noreferrer">
											<?php echo esc_html($link_title); ?>
										</a>
									</li>
						<?php
								endif;
							endwhile;
						endif;
						?>
					</ul>
				</div>
			</div>

			<div class="footer-newsletter">
				<p class="blue-text fw-bold margin-b-30">Stay In Touch</p>
				<p>Subscribe to our email alerts to get notified with news and events.</p>
				<?php echo do_shortcode('[wpforms id="2048" title="false"]'); ?>
			</div>

		</div>

		<div class="footer-copyright">
			<p class="grey-text">
				Copyright © <?php echo esc_html(date("Y")); ?> |
				<?php echo esc_html(get_field('footer_copyright_text', 'option')); ?>
			</p>
		</div>

	</div>

</footer><!-- #colophon -->
</main><!-- #page -->

<?php wp_footer(); ?>

</body>

</html>