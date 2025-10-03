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
 * 
 * Registers the theme's custom Gutenberg blocks that use ACF for data:
 * - quote-box: Styled quote containers
 * - highlight-box: Promotional content blocks  
 * - related-links-box: Link collection blocks
 * 
 * @return void
 */
function zotefoams_register_acf_blocks()
{
    // Register custom blocks
    register_block_type(__DIR__ . '/../blocks/quote-box');
    register_block_type(__DIR__ . '/../blocks/highlight-box');
    register_block_type(__DIR__ . '/../blocks/related-links-box');
}
add_action('init', 'zotefoams_register_acf_blocks');