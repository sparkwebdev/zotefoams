<?php
/**
 * Zotefoams functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Zotefoams
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function zotefoams_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on Zotefoams, use a find and replace
		* to change 'zotefoams' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'zotefoams', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'thumbnail-square', 350, 350, true ); // Custom image size: cropped
	add_image_size( 'small', 700, 9999, false ); // Custom image size: not cropped
	add_image_size( 'thumbnail-product', 690, 460, true ); // Custom image size: cropped

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'primary_menu' => esc_html__( 'Primary', 'zotefoams' ),
			'utility_menu' => esc_html__( 'Utility', 'zotefoams' ),
			'quick_links_menu' => esc_html__( 'Quick', 'zotefoams' )
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);
}
add_action( 'after_setup_theme', 'zotefoams_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function zotefoams_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'zotefoams_content_width', 640 );
}
add_action( 'after_setup_theme', 'zotefoams_content_width', 0 );

/**
 * Enqueue scripts and styles.
 */
function zotefoams_enqueue_assets() {
    // Main stylesheet
    wp_enqueue_style('zotefoams-style', get_stylesheet_uri(), array(), _S_VERSION);
    wp_style_add_data('zotefoams-style', 'rtl', 'replace');

    // Google Fonts
    wp_enqueue_style(
        'google-fonts',
        'https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap',
        array(),
        null
    );

    // Swiper CSS & JS
    wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@latest/swiper-bundle.min.css');
    wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), null, true);
    wp_enqueue_script('swiper-custom', get_template_directory_uri() . '/js/swiper-custom.js', array('swiper-js'), null, true);

    // Animate.css
    wp_enqueue_style('animate-css', 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css');
    wp_enqueue_script('animate-css-swiper', get_template_directory_uri() . '/js/animate-swiper.js', array(), null, true);

    // Navigation Script
    wp_enqueue_script('zotefoams-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true);

    // Components Assets
    wp_enqueue_style('zotefoams-components-style', get_template_directory_uri() . '/css/components.css', array(), _S_VERSION);
    wp_enqueue_script('zotefoams-components-js', get_template_directory_uri() . '/js/components.js', array(), _S_VERSION, true);
    wp_enqueue_style('zotefoams-components-carousels-style', get_template_directory_uri() . '/css/components-carousels.css', array(), _S_VERSION);
    wp_enqueue_script('zotefoams-components-carousels-js', get_template_directory_uri() . '/js/components-carousels.js', array(), _S_VERSION, true);

    // Stevens (Temp) Assets
    wp_enqueue_style('zotefoams-sp-style', get_template_directory_uri() . '/css/sp.css', array(), _S_VERSION);
    wp_enqueue_script('zotefoams-sp-js', get_template_directory_uri() . '/js/sp.js', array(), _S_VERSION, true);

    // Comment reply script (if applicable)
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'zotefoams_enqueue_assets');

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Functions which enhance the admin screens by hooking into WordPress.
 */
require get_template_directory() . '/inc/admin.php';

/**
 * Functions which enhance the admin editor screens by hooking into WordPress.
 */
require get_template_directory() . '/inc/admin-editor.php';

/**
 * Loads the custom walker class for rendering the mega menu.
 */
require_once get_template_directory() . '/inc/mega-menu-walker.php';

/**
 * Register our block's with WordPress's register_block_type();
 *
 * @link https://developer.wordpress.org/reference/functions/register_block_type/
 */
function zotefoams_register_acf_blocks() {
	register_block_type( __DIR__ . '/blocks/quote-box' );
	register_block_type( __DIR__ . '/blocks/highlight-box' );
	register_block_type( __DIR__ . '/blocks/related-links-box' );
}
add_action( 'init', 'zotefoams_register_acf_blocks' );

/**
 * Customize the search form's input and submit button classes.
 *
 * Modifies the default WordPress search form to include custom CSS classes.
 *
 * @param string $form The original search form HTML.
 * @return string The modified search form HTML.
 */
function zotefoams_filter_search_form( $form ) {
    $form = str_replace( 'class="search-submit"', 'class="search-submit btn blue"', $form );
    $form = str_replace( 'class="search-field"', 'class="search-field zf"', $form );
    return $form;
}
add_filter( 'get_search_form', 'zotefoams_filter_search_form' );

/**
 * Add a custom rewrite rule for search URLs.
 *
 * Redirects "/search/" to the default WordPress search query structure.
 */
function zotefoams_custom_search_rewrite() {
    add_rewrite_rule( '^search/?$', 'index.php?s=', 'top' );
}
add_action( 'init', 'zotefoams_custom_search_rewrite' );