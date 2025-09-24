<?php

/**
 * The header for our theme
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Zotefoams
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<!-- Google Tag Manager --> <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start': new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0], j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src= 'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f); })(window,document,'script','dataLayer','GTM-N8JK6JJF');</script> <!-- End Google Tag Manager -->
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<?php wp_head(); ?>
</head>

<body <?php body_class('has-sticky-header'); ?>>
	<!-- Google Tag Manager (noscript) --> <noscript><iframe src=https://www.googletagmanager.com/ns.html?id=GTM-N8JK6JJF height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript> <!-- End Google Tag Manager (noscript) -->
	<?php wp_body_open(); ?>
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'zotefoams'); ?></a>

	<header id="masthead" class="site-header" data-el-site-header>

		<div class="utility-menu">
			<?php
			wp_nav_menu(array(
				'theme_location' => 'utility_menu',
				'container'      => false,
				'depth'          => 2,
			));
			?>
		</div>

		<div class="site-header-wrapper">

			<div class="site-branding">
				<a href="<?php echo esc_url(home_url('/')); ?>">
					<?php
					if (function_exists('get_field')) :
						$brand_logo = get_field('brand_logo', 'option');
						if ($brand_logo) :
					?>
							<img src="<?php echo esc_url($brand_logo); ?>" alt="<?php esc_attr_e('Site Logo', 'zotefoams'); ?>" />
					<?php endif;
					endif; ?>
				</a>
			</div><!-- .site-branding -->

			<nav id="site-navigation" class="main-navigation">
				<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
					<div class="menu-line"></div>
					<div class="menu-line"></div>
					<div class="menu-line"></div>
					<span class="screen-reader-text"><?php esc_html_e('Toggle menu', 'zotefoams'); ?></span>
				</button>
				<?php
				wp_nav_menu(array(
					'theme_location' => 'primary_menu',
					'container'      => false,
					'depth'          => 3,
					'walker'         => new Mega_Menu_Walker(),
					'items_wrap'     => '%3$s',
				));
				?>
			</nav><!-- #site-navigation -->

		</div>
	</header><!-- #masthead -->
	<main id="page" class="site">