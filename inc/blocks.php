<?php
/**
 * Block Registration and Gutenberg Integration
 *
 * @package Zotefoams
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Register custom ACF blocks.
 */
function zotefoams_register_acf_blocks()
{
    // Register custom blocks
    register_block_type(__DIR__ . '/../blocks/quote-box');
    register_block_type(__DIR__ . '/../blocks/highlight-box');
    register_block_type(__DIR__ . '/../blocks/related-links-box');
}
add_action('init', 'zotefoams_register_acf_blocks');