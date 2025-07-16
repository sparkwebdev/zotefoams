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
</main><!-- #page -->

<footer id="colophon" class="site-footer padding-t-b-50">

	<div class="cont-m">

		<div class="site-branding margin-b-40">
			<a href="<?php echo esc_url(home_url('/')); ?>">
				<?php
				if (function_exists('get_field')) :
					$brand_logo = get_field('brand_logo', 'option');
					if ($brand_logo) :
				?>
						<img src="<?php echo esc_url($brand_logo); ?>" alt="Brand Logo" />
				<?php endif;
				endif; ?>
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
						<?php
						if (function_exists('have_rows')) :
							if (have_rows('social_media_links', 'option')) :
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
						endif;
						?>
					</ul>
				</div>
			</div>

			<div class="footer-newsletter">
				<?php
					get_template_part('template-parts/parts/mailchimp');
				?>
			</div>

		</div>

		<div class="footer-copyright">
			<p class="grey-text">
				Copyright © <?php echo esc_html(date("Y")); ?>
				<?php
				if (function_exists('get_field')) :
					echo ' | ' . esc_html(get_field('footer_copyright_text', 'option'));
				endif;
				?>
			</p>
		</div>

	</div>

</footer><!-- #colophon -->

<?php wp_footer(); ?>

<script type="text/javascript" src="//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js"></script><script type="text/javascript">(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';fnames[3]='ADDRESS';ftypes[3]='address';fnames[4]='PHONE';ftypes[4]='phone';fnames[5]='MMERGE5';ftypes[5]='text';fnames[6]='MMERGE6';ftypes[6]='text';fnames[7]='MMERGE7';ftypes[7]='text';fnames[8]='MMERGE8';ftypes[8]='number';fnames[9]='MMERGE9';ftypes[9]='text';fnames[10]='MMERGE10';ftypes[10]='text';fnames[11]='MMERGE11';ftypes[11]='text';fnames[12]='MMERGE12';ftypes[12]='text';}(jQuery));var $mcj = jQuery.noConflict(true);</script>


<script type="text/javascript">
_linkedin_partner_id = "1827026";
window._linkedin_data_partner_ids = window._linkedin_data_partner_ids || [];
window._linkedin_data_partner_ids.push(_linkedin_partner_id);
</script><script type="text/javascript">
(function(l) {
if (!l){window.lintrk = function(a,b){window.lintrk.q.push([a,b])};
window.lintrk.q=[]}
var s = document.getElementsByTagName("script")[0];
var b = document.createElement("script");
b.type = "text/javascript";b.async = true;
b.src = 'https://snap.licdn.com/li.lms-analytics/insight.min.js';
s.parentNode.insertBefore(b, s);})(window.lintrk);
</script>
<noscript>
<img height="1" width="1" style="display:none;" alt="" src="https://px.ads.linkedin.com/collect/?pid=1827026&fmt=gif" />
</body>

</html>