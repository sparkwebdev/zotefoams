<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Zotefoams
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'zotefoams' ); ?></a>

	<header id="masthead" class="site-header">
		<div class="utility-menu">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'menu-2',
					'menu_id'        => 'utility-menu',
    				'container' => false,
				)
			);
			?>
		</div>
		
		<div class="site-branding">
			<a href="/">
				<img src="<?php echo get_field('brand_logo', 'option'); ?>" />
			</a>
		</div><!-- .site-branding -->

		<nav id="site-navigation" class="main-navigation">
			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
				<div class="menu-line"></div>
				<div class="menu-line"></div>
				<div class="menu-line"></div>
			</button>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'menu-1',
					'menu_id'        => 'primary-menu',
    				'container' => false,
				)
			);
			?>
		</nav><!-- #site-navigation -->
	</header><!-- #masthead -->
