<?php

/**
 * Hide admin bar.
 */
add_filter('show_admin_bar', '__return_false');

/**
 * Edit Admin layout.
 */
function my_acf_admin_head()
{
?>
	<style type="text/css">
		.acf-postbox>.inside {
			padding: 10px 10px 10px 10px !important
		}

		.edit-post-meta-boxes-area #poststuff .stuffbox>h3,
		.edit-post-meta-boxes-area #poststuff h2.hndle,
		.edit-post-meta-boxes-area #poststuff h3.hndle {
			border-bottom: 0;
			font-size: 21px;
			font-family: arial;
		}

		.edit-post-meta-boxes-area .postbox>.inside {
			background: #f2f2f2;
		}

		.hndle:hover {
			background: #fff !important;
		}

		.postbox-container .meta-box-sortables {
			margin-bottom: 100px;
		}

		.postbox.acf-postbox {
			margin: 2%;
			border: 1px solid #ccc;
		}

		.postbox-header {
			background: #1d2327;
		}

		.hndle:hover {
			background: #1d2327 !important;
		}

		#poststuff h2,
		.acf-admin-page #poststuff .postbox-header h2,
		.acf-admin-page #poststuff .postbox-header h3 {
			color: #fff !important;
		}

		.acf-table {
			border-collapse: collapse;
		}

		.acf-table>tbody>tr {
			border-top: 2px solid black;
		}

		.acf-admin-page .postbox .postbox-header,
		.acf-admin-page .postbox .title,
		.acf-admin-page .acf-box .postbox-header,
		.acf-admin-page .acf-box .title {
			min-height: 3rem;
		}
	</style>

	<script type="text/javascript">
		(function($) {

			/* ... */

		})(jQuery);
	</script>
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
