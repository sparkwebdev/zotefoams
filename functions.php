<?php

/**
 * Zotefoams functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Zotefoams
 */

if (! defined('_S_VERSION')) {
    // Replace the version number of the theme on each release.
    define('_S_VERSION', '2.1.5');
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function zotefoams_setup()
{
    /*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on Zotefoams, use a find and replace
		* to change 'zotefoams' to the name of your theme in all the template files.
		*/
    load_theme_textdomain('zotefoams', get_template_directory() . '/languages');

    // Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');

    /*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
    add_theme_support('title-tag');

    /*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
    add_theme_support('post-thumbnails');
    add_image_size('thumbnail-square', 350, 350, true); // Custom image size: cropped
    add_image_size('small', 700, 9999, false); // Custom image size: not cropped
    add_image_size('thumbnail-product', 690, 460, true); // Custom image size: cropped

    // This theme uses wp_nav_menu() in one location.
    register_nav_menus(
        array(
            'primary_menu' => esc_html__('Primary', 'zotefoams'),
            'utility_menu' => esc_html__('Utility', 'zotefoams'),
            'quick_links_menu' => esc_html__('Quick', 'zotefoams'),
            'legal_menu' => esc_html__('Legal', 'zotefoams')
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
add_action('after_setup_theme', 'zotefoams_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function zotefoams_content_width()
{
    $GLOBALS['content_width'] = apply_filters('zotefoams_content_width', 640);
}
add_action('after_setup_theme', 'zotefoams_content_width', 0);

/**
 * Enqueue scripts and styles.
 */
function zotefoams_enqueue_assets()
{
    // Main stylesheet
    wp_enqueue_style('zotefoams-style', get_stylesheet_uri(), array(), _S_VERSION);

    // Google Fonts
    wp_enqueue_style(
        'google-fonts',
        'https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap',
        array(),
        null
    );
    if (is_page_template('our-history-template.php')) {
        wp_enqueue_style(
            'google-fonts',
            'https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@1,400;1,600&display=swap',
            array(),
            null
        );
    }

    // Swiper CSS & JS
    wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@latest/swiper-bundle.min.css');
    wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), null, true);

    // Animate.css
    wp_enqueue_style('animate-css', 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css');
    //wp_enqueue_script('animate-css-swiper', get_template_directory_uri() . '/js/animate-swiper.js', array(), null, true);

    // Navigation Script
    wp_enqueue_script('zotefoams-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true);

    // Component CSS & JS
    wp_enqueue_script('zotefoams-components-js', get_template_directory_uri() . '/js/components.js', array(), _S_VERSION, true);
    wp_enqueue_script('zotefoams-components-carousels-js', get_template_directory_uri() . '/js/components-carousels.js', array(), _S_VERSION, true);

    // Stevens (WIP) Assets
    wp_enqueue_script('zotefoams-sp-js', get_template_directory_uri() . '/js/sp.js', array(), _S_VERSION, true);
    wp_enqueue_script('zotefoams-history-js', get_template_directory_uri() . '/js/our-history.js', array(), _S_VERSION, true);

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
 * Functions which hook into acf.
 */
// require get_template_directory() . '/inc/acf.php';

/**
 * Loads the custom walker class for rendering the mega menu.
 */
require_once get_template_directory() . '/inc/mega-menu-walker.php';

/**
 * Register our block's with WordPress's register_block_type();
 *
 * @link https://developer.wordpress.org/reference/functions/register_block_type/
 */
function zotefoams_register_acf_blocks()
{
    register_block_type(__DIR__ . '/blocks/quote-box');
    register_block_type(__DIR__ . '/blocks/highlight-box');
    register_block_type(__DIR__ . '/blocks/related-links-box');
}
add_action('init', 'zotefoams_register_acf_blocks');

/**
 * Customize the search form's input and submit button classes.
 *
 * Modifies the default WordPress search form to include custom CSS classes.
 *
 * @param string $form The original search form HTML.
 * @return string The modified search form HTML.
 */
function zotefoams_filter_search_form($form)
{
    $form = str_replace('class="search-submit"', 'class="search-submit btn blue"', $form);
    $form = str_replace('class="search-field"', 'class="search-field zf"', $form);
    return $form;
}
add_filter('get_search_form', 'zotefoams_filter_search_form');

/**
 * Add a custom rewrite rule for search URLs.
 *
 * Redirects "/search/" to the default WordPress search query structure.
 */
function zotefoams_custom_search_rewrite()
{
    add_rewrite_rule('^search/?$', 'index.php?s=', 'top');
}
add_action('init', 'zotefoams_custom_search_rewrite');


// Enable ACF local JSON feature
add_filter('acf/settings/save_json', 'zotefoams_acf_json_save_point');
function zotefoams_acf_json_save_point($path)
{
    return plugin_dir_path(__FILE__) . 'acf/acf-json';
}

add_filter('acf/settings/load_json', 'zotefoams_acf_json_load_point');
function zotefoams_acf_json_load_point($paths)
{
    unset($paths[0]);
    $paths[] = plugin_dir_path(__FILE__) . 'acf/acf-json';
    return $paths;
}

/**
 * Populate the ACF field with a list of WPForms.
 *
 * This function hooks into ACF's `acf/load_field` filter to dynamically 
 * populate the choices for the field named "show_hide_forms_form" 
 * with available WPForms.
 *
 * @link https://www.advancedcustomfields.com/resources/acf-load_field/
 */
add_filter('acf/load_field/name=show_hide_forms_form', 'zotefoams_populate_acf_with_wpforms');
function zotefoams_populate_acf_with_wpforms($field)
{
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


/**
 * Populate the ACF 'associated_brands' field with a list of Brands Child pages.
 *
 * @link https://www.advancedcustomfields.com/resources/acf-load_field/
 */

add_filter('acf/load_field/name=associated_brands', 'zotefoams_populate_acf_with_brands');
function zotefoams_populate_acf_with_brands($field)
{
    // Clear existing choices
    $field['choices'] = [];

    // Get the page ID for 'Our brands' (case-insensitive)
    $brands_page_id = zotefoams_get_page_id_by_title('Our brands');

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

/**
 * Add custom inline admin styles for the Knowledge Hub post type.
 *
 * This function injects inline CSS styles into the WordPress admin header when editing
 * a 'knowledge-hub' post type. The styles target the ACF gallery field (using a specific field ID)
 * to disable pointer events on gallery attachments and hide the remove and sort buttons.
 * This prevents unintended interactions within the Knowledge Hub's document attachments.
 *
 * @return void
 */
function zotefoams_add_knowledge_hub_admin_inline_styles()
{
    $screen = get_current_screen();
    if (isset($screen->post_type) && $screen->post_type === 'knowledge-hub') {
?>
        <style>
            #acf-field_67c58a842cccb .acf-gallery-attachments .acf-gallery-attachment {
                pointer-events: none;
            }

            #acf-field_67c58a842cccb .actions .acf-gallery-remove,
            #acf-field_67c58a842cccb .acf-gallery-sort {
                display: none;
            }
        </style>
        <?php
    }
}
add_action('admin_head', 'zotefoams_add_knowledge_hub_admin_inline_styles');

function add_preload_to_google_fonts($html, $handle, $href, $media)
{
    if ('google-fonts' === $handle) {
        $html  = '<link rel="preload" as="style" href="' . esc_url($href) . '" />';
        $html .= "\n" . '<link rel="stylesheet" id="' . esc_attr($handle) . '-css" href="' . esc_url($href) . '" media="' . esc_attr($media) . '">';
    }
    return $html;
}
add_filter('style_loader_tag', 'add_preload_to_google_fonts', 10, 4);



/**
 * Outputs the Google tag (gtag.js) script in the <head> section.
 *
 * The tag ID is pulled from an ACF options page field named 'google_tag_id'.
 * This allows administrators to manage the Google Tag Manager ID from the WordPress dashboard.
 *
 * Hooked into 'wp_head' to ensure it appears inside the <head> tag.
 */
function zotefoams_add_google_gtag_from_acf()
{

    // Check for Advanced Custom Fields plugin function
    if (function_exists('get_field')) {
        $tag_id = get_field('google_analytics_measurement_id', 'option');

        if ($tag_id) {
        ?>
            <!-- Google tag (gtag.js) -->
            <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr($tag_id); ?>"></script>
            <script>
                window.dataLayer = window.dataLayer || [];

                function gtag() {
                    dataLayer.push(arguments);
                }
                gtag('js', new Date());
                gtag('config', '<?php echo esc_js($tag_id); ?>');
            </script>
<?php
        }
    }
}
add_action('wp_head', 'zotefoams_add_google_gtag_from_acf');


/**
 * Modifies the main WordPress query to customize the display of upcoming events.
 *
 * This function hooks into the 'pre_get_posts' action to alter the query parameters
 * for event-related queries, such as filtering by date or ordering events.
 *
 * @param WP_Query $query The WP_Query instance (passed by reference).
 */
function zotefoams_upcoming_events_pre_get_posts($query)
{
    if (is_admin() || ! $query->is_main_query()) {
        return;
    }

    if (is_category('events')) {

        $today = date('Ymd');

        $meta_query = [
            'relation' => 'OR',
            [
                'key'     => 'event_start_date',
                'value'   => $today,
                'compare' => '>=',
                'type'    => 'CHAR',
            ],
            [
                'key'     => 'event_start_date',
                'compare' => 'NOT EXISTS',
            ],
            [
                'relation' => 'AND',
                [
                    'key'     => 'event_start_date',
                    'value'   => $today,
                    'compare' => '<',
                    'type'    => 'CHAR',
                ],
                [
                    'key'     => 'event_end_date',
                    'value'   => $today,
                    'compare' => '>=',
                    'type'    => 'CHAR',
                ],
            ],
        ];

        $query->set('meta_query', $meta_query);
        $query->set('orderby', 'meta_value');
        $query->set('meta_key', 'event_start_date');
        $query->set('order', 'ASC');
    }
}
add_action('pre_get_posts', 'zotefoams_upcoming_events_pre_get_posts');


/**
 * Add custom password_form output markup and some styling
 *
 * @return void
 */
function zotefoams_custom_password_form() {
    global $post;
    $label = 'pwbox-' . ( empty( $post->ID ) ? rand() : $post->ID );
    $output = '
    <div class="text-banner padding-t-b-100">
        <div class="cont-m">
            <form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post">
                <div class="custom-password-form">
                    <p class="margin-b-30"><strong>This content is password protected.</strong> To view it, please enter the password below:</p>
                    <div class="custom-password-form__inputs">
                        <label for="' . $label . '">Password:</label>
                        <input class="custom-password-form__password" name="post_password" id="' . $label . '" type="password" size="20" />
                        <input class="btn blue" type="submit" name="Submit" value="Submit" />
                    </div>
                </div>
            </form>
        </div>
    </div>';

    return $output;
}
add_filter( 'the_password_form', 'zotefoams_custom_password_form' );
