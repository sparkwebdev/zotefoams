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
 * Enqueue Mailchimp form validation scripts.
 * 
 * Loads Mailchimp's form validation JavaScript and sets up
 * field definitions for embedded subscription forms.
 * 
 * @return void
 */
function zotefoams_enqueue_mailchimp_scripts()
{
    ?>
    <script type="text/javascript" src="//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js"></script>
    <script type="text/javascript">
    (function($) {
        window.fnames = new Array(); 
        window.ftypes = new Array();
        fnames[0]='EMAIL';ftypes[0]='email';
        fnames[1]='FNAME';ftypes[1]='text';
        fnames[2]='LNAME';ftypes[2]='text';
        fnames[3]='ADDRESS';ftypes[3]='address';
        fnames[4]='PHONE';ftypes[4]='phone';
        fnames[5]='MMERGE5';ftypes[5]='text';
        fnames[6]='MMERGE6';ftypes[6]='text';
        fnames[7]='MMERGE7';ftypes[7]='text';
        fnames[8]='MMERGE8';ftypes[8]='number';
        fnames[9]='MMERGE9';ftypes[9]='text';
        fnames[10]='MMERGE10';ftypes[10]='text';
        fnames[11]='MMERGE11';ftypes[11]='text';
        fnames[12]='MMERGE12';ftypes[12]='text';
    }(jQuery));
    var $mcj = jQuery.noConflict(true);
    </script>
    <?php
}

/**
 * Register Mailchimp hooks only on frontend.
 * 
 * Ensures Mailchimp scripts are not loaded in WordPress admin area.
 * Scripts are added to footer to ensure jQuery is available.
 */
if (!is_admin()) {
    add_action('wp_footer', 'zotefoams_enqueue_mailchimp_scripts', 20);
}