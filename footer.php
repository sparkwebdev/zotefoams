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
				<div id="mc_embed_shell">

      <link href="//cdn-images.mailchimp.com/embedcode/classic-061523.css" rel="stylesheet" type="text/css">

  <style type="text/css">
#mc_embed_signup  {
	margin: 0;
}
#mc_embed_signup form {
	margin: 0
}
#mc_embed_signup h2 {
	color: #3b82f6;
	font-size: 14px;
	margin-top: 0;
}
#mc_embed_signup .button {
	background-color: #066aab;
	padding: 15px;
	line-height: 1;
	height: auto;
	font-family: Manrope, sans-serif;
}

</style>

<div id="mc_embed_signup">

    <form action=https://zotefoams.us19.list-manage.com/subscribe/post?u=c37b5dea047401619cafe73b1&amp;id=d1585d2ae4&amp;f_id=000d36e7f0 method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank">

        <div id="mc_embed_signup_scroll"><h2>Stay In Touch</h2>
        <p class="margin-b-20">Subscribe to our email alerts to get notified with news and events.</p>

            <div class="indicates-required"><span class="asterisk">*</span> indicates required</div>

            <div class="mc-field-group"><label for="mce-EMAIL">Email Address <span class="asterisk">*</span></label><input type="email" name="EMAIL" class="required email" id="mce-EMAIL" required="" value=""></div>

<div hidden=""><input type="hidden" name="tags" value="24263797"></div>

        <div id="mce-responses" class="clear">

            <div class="response" id="mce-error-response" style="display: none;"></div>

            <div class="response" id="mce-success-response" style="display: none;"></div>

        </div><div aria-hidden="true" style="position: absolute; left: -5000px;"><input type="text" name="b_c37b5dea047401619cafe73b1_d1585d2ae4" tabindex="-1" value=""></div><div class="clear"><input type="submit" name="subscribe" id="mc-embedded-subscribe" class="button" value="Subscribe"></div>

    </div>

</form>

</div>

<script type="text/javascript" src="//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js"></script><script type="text/javascript">(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';fnames[3]='ADDRESS';ftypes[3]='address';fnames[4]='PHONE';ftypes[4]='phone';fnames[5]='MMERGE5';ftypes[5]='text';fnames[6]='MMERGE6';ftypes[6]='text';fnames[7]='MMERGE7';ftypes[7]='text';fnames[8]='MMERGE8';ftypes[8]='number';fnames[9]='MMERGE9';ftypes[9]='text';fnames[10]='MMERGE10';ftypes[10]='text';fnames[11]='MMERGE11';ftypes[11]='text';fnames[12]='MMERGE12';ftypes[12]='text';}(jQuery));var $mcj = jQuery.noConflict(true);</script></div>
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

<?php wp_footer(); ?>

</body>

</html>