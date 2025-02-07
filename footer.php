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
				<a href="/">
					<img src="<?php echo get_field('brand_logo', 'option'); ?>" />
				</a>
			</div><!-- .site-branding -->
			
			<div class="footer-content margin-b-50">
				
				<div class="footer-menus">
					<div class="footer-menu footer-menu-1">
						<p class="blue-text fw-bold margin-b-30">Legal</p>
						<?php
						$legal_page_id = get_page_by_path('legal')->ID; // Get the ID of the "Legal" page
						$child_pages = get_pages(array('child_of' => $legal_page_id));

						if (!empty($child_pages)) {
							echo '<ul class="legal-links">';
							foreach ($child_pages as $page) {
								echo '<li><a href="' . get_permalink($page->ID) . '">' . esc_html($page->post_title) . '</a></li>';
							}
							echo '</ul>';
						}
						?>
					</div>
					<div class="footer-menu footer-menu-2">
						<p class="blue-text fw-bold margin-b-30">Quick Links</p>
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'quick_links_menu',
								'container' => false,
							)
						);
						?>
					</div>
					<div class="footer-menu footer-menu-3">
						<p class="blue-text fw-bold margin-b-30">Social</p>
						<ul class="social-links">
							<li><a href="">YouTube</a></li>
							<li><a href="">LinkedIn</a></li>
							<li><a href="">X</a></li></li>
						</ul>
					</div>
				</div>
				
				<div class="footer-newsletter">
					<p class="blue-text fw-bold margin-b-30">Stay In Touch</p>
					<p class="margin-b-20">Subscribe to our email alerts to get notified with news and events.</p>
					<div class="zf-form">
						<input class="zf" type="text" id="email" name="email" placeholder="Your Email Address" autocomplete="on">
						<a href="" class="btn blue">Submit</a>
					</div>
				</div>
			
			</div>
		
			<div class="footer-copyright">
				<p class="grey-text">Copyright © 2024   |  AZOTE®, ZOTEK®, T-FIT®, Plastazote®, Evazote®, Supazote®, ReZorce®, Refour® and Ecozote® are registered trademarks of Zotefoams plc. MuCell® is a registered trademark of Trexel Inc.</p>
			</div>
			
		</div>
		
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
