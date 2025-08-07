<?php
/**
 * Third-party Integrations (Mailchimp, Forms, etc.)
 *
 * @package Zotefoams
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Enqueue Mailchimp scripts and initialize subscription forms.
 * 
 * Dynamically loads Mailchimp's JavaScript based on ACF option values.
 * Requires mailchimp_list_id and mailchimp_server to be configured
 * in the theme options.
 * 
 * @return void
 */
function zotefoams_enqueue_mailchimp_scripts()
{
    if (function_exists('get_field')) {
        $mailchimp_list_id = get_field('mailchimp_list_id', 'option');
        $mailchimp_server = get_field('mailchimp_server', 'option');
        
        if ($mailchimp_list_id && $mailchimp_server) {
            ?>
            <script id="mcjs">
            !function(c,h,i,m,p){
                m=c.createElement(h),p=c.getElementsByTagName(h)[0],m.async=1,m.src=i,p.parentNode.insertBefore(m,p)
            }(document,"script","https://chimpstatic.com/mcjs-connected/js/users/<?php echo esc_js($mailchimp_list_id); ?>/<?php echo esc_js($mailchimp_server); ?>.js");
            </script>
            <?php
        }
    }
}

/**
 * Register Mailchimp hooks only on frontend.
 * 
 * Ensures Mailchimp scripts are not loaded in WordPress admin area.
 */
if (!is_admin()) {
    add_action('wp_head', 'zotefoams_enqueue_mailchimp_scripts');
}