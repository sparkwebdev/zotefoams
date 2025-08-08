<?php

/**
 * Hide admin bar.
 */
add_filter('show_admin_bar', '__return_false');

/**
 * Customize admin area styling and layout.
 * 
 * Applies custom CSS to improve the WordPress admin interface,
 * particularly for ACF field groups and post edit screens.
 * 
 * @return void
 */
function my_acf_admin_head()
{
?>
	<style type="text/css">
		#editor .postbox>.postbox-header:hover,
		.postbox-header,
		.acf-flexible-content .layout .acf-fc-layout-handle {
			background: #1d2327;
			color: #fff;
		}

		.postbox-header .hndle,
		.acf-flexible-content .layout .acf-fc-layout-handle {
			background: #1d2327;
			color: #fff;
		}
	</style>
<?php
}

add_action('acf/input/admin_head', 'my_acf_admin_head');

/**
 * Hide Gutenberg Block editor.
 */
// add_filter('use_block_editor_for_post', '__return_false');

/**
 * Disable Application Passwords.
 */
add_filter('wp_is_application_passwords_available', '__return_false');
