<?php
/**
 * ACF Field Helper Functions
 *
 * Safe field retrieval with validation and fallback handling.
 *
 * @package Zotefoams
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Safely get ACF field with validation and fallback.
 *
 * @param string $field_name The ACF field name
 * @param mixed $post_id Post ID or 'option' for options page
 * @param mixed $default Default value if field is empty or doesn't exist
 * @param string $type Expected data type ('string', 'array', 'int', 'bool', 'url', 'email')
 * @return mixed Validated field value or default
 */
function zotefoams_get_field_safe($field_name, $post_id = false, $default = '', $type = 'string')
{
    if (!function_exists('get_field')) {
        return $default;
    }

    $value = get_field($field_name, $post_id);

    // Return default if field is empty or null
    if (empty($value) && $value !== '0' && $value !== 0) {
        return $default;
    }

    // Validate based on expected type
    switch ($type) {
        case 'string':
            return is_string($value) ? sanitize_text_field($value) : $default;
        
        case 'array':
            return is_array($value) ? $value : $default;
        
        case 'int':
            return is_numeric($value) ? intval($value) : $default;
        
        case 'bool':
            return is_bool($value) ? $value : (bool) $value;
        
        case 'url':
            if (is_array($value) && isset($value['url'])) {
                return filter_var($value['url'], FILTER_VALIDATE_URL) ? $value : $default;
            }
            return filter_var($value, FILTER_VALIDATE_URL) ? $value : $default;
        
        case 'email':
            return filter_var($value, FILTER_VALIDATE_EMAIL) ? $value : $default;
        
        case 'image':
            return (is_array($value) && isset($value['url'])) ? $value : $default;
        
        default:
            return $value;
    }
}

/**
 * Safely get ACF sub field with validation and fallback.
 *
 * @param string $field_name The ACF sub field name
 * @param mixed $default Default value if field is empty or doesn't exist
 * @param string $type Expected data type
 * @return mixed Validated field value or default
 */
function zotefoams_get_sub_field_safe($field_name, $default = '', $type = 'string')
{
    if (!function_exists('get_sub_field')) {
        return $default;
    }

    $value = get_sub_field($field_name);

    // Return default if field is empty or null
    if (empty($value) && $value !== '0' && $value !== 0) {
        return $default;
    }

    // Use same validation as main function
    return zotefoams_validate_field_value($value, $default, $type);
}

/**
 * Validate field value based on expected type.
 *
 * @param mixed $value The value to validate
 * @param mixed $default Default value if validation fails
 * @param string $type Expected data type
 * @return mixed Validated value or default
 */
function zotefoams_validate_field_value($value, $default, $type)
{
    switch ($type) {
        case 'string':
            return is_string($value) ? sanitize_text_field($value) : $default;
        
        case 'array':
            return is_array($value) ? $value : $default;
        
        case 'int':
            return is_numeric($value) ? intval($value) : $default;
        
        case 'bool':
            return is_bool($value) ? $value : (bool) $value;
        
        case 'url':
            if (is_array($value) && isset($value['url'])) {
                return filter_var($value['url'], FILTER_VALIDATE_URL) ? $value : $default;
            }
            return filter_var($value, FILTER_VALIDATE_URL) ? $value : $default;
        
        case 'email':
            return filter_var($value, FILTER_VALIDATE_EMAIL) ? $value : $default;
        
        case 'image':
            return (is_array($value) && isset($value['url'])) ? $value : $default;
        
        default:
            return $value;
    }
}