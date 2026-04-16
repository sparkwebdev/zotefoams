<?php

/**
 * Hide admin bar.
 */
add_filter('show_admin_bar', '__return_false');


/**
 * Hide Gutenberg Block editor.
 */
// add_filter('use_block_editor_for_post', '__return_false');

/**
 * Disable Application Passwords.
 */
add_filter('wp_is_application_passwords_available', '__return_false');
