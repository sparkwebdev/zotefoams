<?php
/**
 * Content Filters and Output Modifications
 *
 * @package Zotefoams
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Custom password form for protected posts.
 *
 * @return string Custom password form HTML.
 */
function zotefoams_custom_password_form()
{
    global $post;
    $label = 'pwbox-' . (empty($post->ID) ? rand() : $post->ID);
    
    $output = '<div class="text-banner padding-t-b-100">
        <div class="cont-m">
            <form action="' . esc_url(site_url('wp-login.php?action=postpass', 'login_post')) . '" method="post">
                <div class="password-form-container">
                    <p>' . __('This content is password protected. To view it please enter your password below:', 'zotefoams') . '</p>
                    <div class="password-form-fields margin-t-20">
                        <label for="' . $label . '">' . __('Password:', 'zotefoams') . '</label>
                        <input name="post_password" id="' . $label . '" type="password" size="20" />
                        <input type="submit" name="Submit" value="' . esc_attr__('Enter', 'zotefoams') . '" />
                    </div>
                </div>
            </form>
        </div>
    </div>';
    
    return $output;
}
add_filter('the_password_form', 'zotefoams_custom_password_form');