<?php
/**
 * Hide admin bar.
 */
add_filter( 'show_admin_bar', '__return_false' );

/**
 * Edit Admin layout.
 */
function my_acf_admin_head()
{
    ?>
    <style type="text/css">
		.postbox-header {
			background:#1d2327;
		}
		.postbox-header > * {
			color:#fff;
		}
		.acf-table > tbody > tr {
			border-top: 3px solid #8c8f94;
		}
    </style>
    <?php
}
 
add_action('acf/input/admin_head', 'my_acf_admin_head');

/**
 * Disable Application Passwords.
 */
add_filter( 'wp_is_application_passwords_available', '__return_false' );