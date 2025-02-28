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
function zotefoams_scripts() {
	wp_enqueue_style( 'zotefoams-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'zotefoams-style', 'rtl', 'replace' );

	wp_enqueue_script( 'zotefoams-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'zotefoams_scripts' );

function enqueue_google_fonts() {
    wp_enqueue_style(
        'google-fonts',
        'https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap',
        array(),
        null
    );
}
add_action('wp_enqueue_scripts', 'enqueue_google_fonts');

/**
 * Enqueue swiper carousel assets.
 */
function enqueue_swiper_assets() {
    // Enqueue Swiper CSS
    wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@latest/swiper-bundle.min.css');

    // Enqueue Swiper JS
    wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), null, true);

    // Enqueue your custom script for Swiper initialization
    wp_enqueue_script('swiper-custom', get_template_directory_uri() . '/js/swiper-custom.js', array('swiper-js'), null, true);
}
add_action('wp_enqueue_scripts', 'enqueue_swiper_assets');


/**
 * Enqueue Animate.css
 */
function enqueue_animatecss_assets() {
    // Enqueue Animate.css
    wp_enqueue_style('animate-css', 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css');

	// Enqueue Animate.css Swiper Triggers
	wp_enqueue_script('animate-css-swiper', get_template_directory_uri() . '/js/animate-swiper.js', array(), null, true);
}
add_action('wp_enqueue_scripts', 'enqueue_animatecss_assets');



/**
 * Enqueue Stevens (temp) assets.
 */
function enqueue_steven_assets() {

	wp_enqueue_style( 'zotefoams-sp-style', get_template_directory_uri() . '/css/sp.css', array(), _S_VERSION );
	wp_enqueue_script( 'zotefoams-sp-js', get_template_directory_uri() . '/js/sp.js', array(), _S_VERSION, true );

}
add_action('wp_enqueue_scripts', 'enqueue_steven_assets');

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


// Enable ACF local JSON feature
add_filter('acf/settings/save_json', 'zotefoams_acf_json_save_point');
function zotefoams_acf_json_save_point($path) {
    return plugin_dir_path(__FILE__) . 'acf/acf-json';
}

add_filter('acf/settings/load_json', 'zotefoams_acf_json_load_point');
function zotefoams_acf_json_load_point($paths) {
    unset($paths[0]);
    $paths[] = plugin_dir_path(__FILE__) . 'acf/acf-json';
    return $paths;
}

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
 * Includes a template part and allows passing variables scoped to that instance.
 *
 * This function locates and includes a specified template file while extracting
 * an array of variables to be used within that file. This prevents global scope pollution
 * and ensures variables are only available within the included template.
 *
 * @param string $file The template file path (relative to the theme directory, without .php extension).
 * @param array $variables An associative array of variables to extract and make available in the template.
 */
function include_template_part($file, $variables = []) {
    extract($variables);
    include locate_template($file . '.php');
}



// function custom_tinymce_toolbar($init) {
//     // Set the first toolbar to only have Bold and Italic
//     $init['toolbar1'] = 'bold,italic';
//     // Remove the second toolbar
//     $init['toolbar2'] = '';
 
//     // Ensure block formats are limited to paragraph only
//     $init['block_formats'] = 'Paragraph=p';
 
//     // Disable extra plugins that might introduce other buttons
//     $init['plugins'] = 'wordpress';
 
//     return $init;
// }
// add_filter('tiny_mce_before_init', 'custom_tinymce_toolbar');



add_filter('acf/load_field/name=show_hide_forms_form', 'zf_populate_acf_with_wpforms');
function zf_populate_acf_with_wpforms($field) {
    // Clear existing choices
    $field['choices'] = [];

    // Get the list of WPForms
    if (class_exists('WPForms')) {
        $forms = wpforms()->form->get();
        if ($forms) {
            foreach ($forms as $form) {
                $form_data = wpforms()->form->get($form->ID); // Get full form data
                $form_name = $form_data->post_title; // Get the form's title
                $field['choices'][$form->ID] = $form_name;
            }
        }
    }

    return $field;
}



// function use_page_template_for_blog($template) {
//     if (is_home() && !is_front_page()) { // Ensure it's the posts page, not front page
//         $posts_page_id = get_option('page_for_posts'); // Get the assigned posts page ID
//         if ($posts_page_id) {
//             return locate_template('page.php'); // Force page.php
//         }
//     }
//     return $template;
// }
// add_filter('template_include', 'use_page_template_for_blog');


// Function to get page ID by title (replacement for get_page_by_title)
function zf_get_page_id_by_title($title) {
    global $wpdb;

    $page_id = $wpdb->get_var($wpdb->prepare(
        "SELECT ID FROM $wpdb->posts 
        WHERE post_type = 'page' 
        AND post_title COLLATE utf8mb4_general_ci = %s 
        LIMIT 1",
        $title
    ));

    return $page_id ?: null;
}


require_once get_template_directory() . '/template-parts/class-mega-menu-walker.php';



function zf_get_page_for_posts_id() {
	$page_for_posts = get_option('page_for_posts', true); // WordPress "Posts Page"
	$posts_page_id = !empty($page_for_posts) ? $page_for_posts : zf_get_page_id_by_title('News Centre');
	return $posts_page_id;
}

function my_custom_search_rewrite() {
    add_rewrite_rule( '^search/?$', 'index.php?s=', 'top' );
}
add_action( 'init', 'my_custom_search_rewrite' );


function zf_filter_search_form( $form ) {
    // Replace the default submit button's class with the new classes.
    $form = str_replace( 'class="search-submit"', 'class="search-submit btn blue"', $form );
    $form = str_replace( 'class="search-field"', 'class="search-field zf"', $form );
    return $form;
}
add_filter( 'get_search_form', 'zf_filter_search_form' );


/**
 * Populate the ACF 'associated_brands' field with a list of Brands Child pages.
 *
 * @link https://www.advancedcustomfields.com/resources/acf-load_field/
 */

add_filter('acf/load_field/name=associated_brands', 'zf_populate_acf_with_brands');
function zf_populate_acf_with_brands($field) {
    // Clear existing choices
    $field['choices'] = [];

    // Get the page ID for 'Our brands' (case-insensitive)
    $brands_page_id = zf_get_page_id_by_title('Our brands');

    if ($brands_page_id) {
        // Get child and grandchild pages
        $args = [
            'post_type'      => 'page',
            'post_parent'    => $brands_page_id,
            'posts_per_page' => -1,
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
        ];

        $child_pages = get_posts($args);

        if (!empty($child_pages)) {
            foreach ($child_pages as $page) {
                // Add child page
                $field['choices'][$page->ID] = $page->post_title;

                // Get grandchild pages
                $grandchild_args = [
                    'post_type'      => 'page',
                    'post_parent'    => $page->ID,
                    'posts_per_page' => -1,
                    'orderby'        => 'menu_order',
                    'order'          => 'ASC',
                ];

                $grandchild_pages = get_posts($grandchild_args);

                if (!empty($grandchild_pages)) {
                    foreach ($grandchild_pages as $grandchild) {
                        // Add grandchild page with indentation for clarity
                        $field['choices'][$grandchild->ID] = '— ' . $grandchild->post_title;
                    }
                }
            }
        }
    }

    return $field;
}
