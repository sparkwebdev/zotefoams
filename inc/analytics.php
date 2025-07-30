<?php
/**
 * Analytics Integration (Google Analytics, LinkedIn, etc.)
 *
 * @package Zotefoams
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Add Google Analytics tracking code from ACF options.
 * 
 * Dynamically injects Google Analytics Global Site Tag (gtag.js)
 * based on the tracking ID configured in ACF theme options.
 * Only loads if google_analytics_tracking_id option is set.
 * 
 * @return void
 */
function zotefoams_add_google_gtag_from_acf()
{
    if (function_exists('get_field')) {
        $ga_tracking_id = get_field('google_analytics_tracking_id', 'option');
        
        if ($ga_tracking_id) {
            ?>
            <!-- Google Analytics -->
            <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr($ga_tracking_id); ?>"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());
                gtag('config', '<?php echo esc_js($ga_tracking_id); ?>');
            </script>
            <?php
        }
    }
}
add_action('wp_head', 'zotefoams_add_google_gtag_from_acf');

/**
 * Enqueue LinkedIn analytics scripts if configured.
 * 
 * Loads LinkedIn Insight Tag for conversion tracking and analytics
 * based on the partner ID configured in ACF theme options.
 * Only loads if linkedin_partner_id option is set.
 * 
 * @return void
 */
function zotefoams_enqueue_linkedin_analytics()
{
    // Default fallback ID
    $partner_id = '1827026';
    
    // Check if ACF is available and get partner ID from options
    if (function_exists('get_field')) {
        $acf_partner_id = get_field('linkedin_partner_id', 'option');
        if (!empty($acf_partner_id)) {
            $partner_id = sanitize_text_field($acf_partner_id);
        }
    }

    // LinkedIn tracking initialization
    $linkedin_init = "
        _linkedin_partner_id = '" . esc_js($partner_id) . "';
        window._linkedin_data_partner_ids = window._linkedin_data_partner_ids || [];
        window._linkedin_data_partner_ids.push(_linkedin_partner_id);
    ";

    // LinkedIn tracking script loader
    $linkedin_loader = "
        (function(l) {
            if (!l){
                window.lintrk = function(a,b){window.lintrk.q.push([a,b])};
                window.lintrk.q=[];
            }
            var s = document.getElementsByTagName('script')[0];
            var b = document.createElement('script');
            b.type = 'text/javascript';
            b.async = true;
            b.src = 'https://snap.licdn.com/li.lms-analytics/insight.min.js';
            s.parentNode.insertBefore(b, s);
        })(window.lintrk);
    ";

    // Add scripts inline to footer
    wp_add_inline_script('jquery', $linkedin_init . $linkedin_loader, 'after');
}

/**
 * Add LinkedIn noscript pixel for users without JavaScript.
 */
function zotefoams_linkedin_noscript_pixel()
{
    if (function_exists('get_field')) {
        $linkedin_partner_id = get_field('linkedin_partner_id', 'option');
        
        if ($linkedin_partner_id) {
            ?>
            <noscript>
                <img height="1" width="1" style="display:none;" alt="" src="https://px.ads.linkedin.com/collect/?pid=<?php echo esc_attr($linkedin_partner_id); ?>&fmt=gif" />
            </noscript>
            <?php
        }
    }
}