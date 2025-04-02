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
		.acf-postbox .postbox-header {
			background: #3B82F6;
		}
		.acf-postbox .postbox-header > * {
			color:#fff;
		}
		.acf-table > tbody > tr {
			border-top: 3px solid #3B82F6;
		}
    </style>
    <?php
}
 
add_action('acf/input/admin_head', 'my_acf_admin_head');

/**
 * Disable Application Passwords.
 */
add_filter( 'wp_is_application_passwords_available', '__return_false' );