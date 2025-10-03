<?php
/**
 * Debug System for Better Developer Experience
 *
 * @package Zotefoams
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Debug system class for better developer experience
 */
class Zotefoams_Debug
{
    /**
     * Debug mode enabled flag
     *
     * @var bool
     */
    private static $debug_enabled = null;

    /**
     * Debug log entries
     *
     * @var array
     */
    private static $debug_log = [];

    /**
     * Check if debug mode is enabled
     *
     * @return bool
     */
    public static function is_enabled()
    {
        if (self::$debug_enabled === null) {
            // Enable debug mode if WP_DEBUG is on OR if debug query parameter is set
            self::$debug_enabled = (
                (defined('WP_DEBUG') && WP_DEBUG) ||
                (isset($_GET['zf_debug']) && current_user_can('administrator'))
            );
        }
        
        return self::$debug_enabled;
    }

    /**
     * Log debug information
     *
     * @param string $component Component name
     * @param string $message Debug message
     * @param mixed $context Additional context data
     * @param string $level Debug level (info, warning, error)
     */
    public static function log($component, $message, $context = null, $level = 'info')
    {
        if (!self::is_enabled()) {
            return;
        }

        $entry = [
            'timestamp' => microtime(true),
            'component' => $component,
            'message' => $message,
            'context' => $context,
            'level' => $level,
            'memory' => memory_get_usage(true),
            'backtrace' => self::get_compact_backtrace()
        ];

        self::$debug_log[] = $entry;

        // Also log to WordPress debug.log if WP_DEBUG_LOG is enabled
        if (defined('WP_DEBUG_LOG') && WP_DEBUG_LOG) {
            error_log(sprintf(
                '[Zotefoams Debug] %s: %s %s',
                strtoupper($level),
                $component,
                $message . ($context ? ' | Context: ' . json_encode($context) : '')
            ));
        }
    }

    /**
     * Log component error
     *
     * @param string $component Component name
     * @param string $message Error message
     * @param mixed $context Additional context
     */
    public static function error($component, $message, $context = null)
    {
        self::log($component, $message, $context, 'error');
    }

    /**
     * Log component warning
     *
     * @param string $component Component name
     * @param string $message Warning message
     * @param mixed $context Additional context
     */
    public static function warning($component, $message, $context = null)
    {
        self::log($component, $message, $context, 'warning');
    }

    /**
     * Log component info
     *
     * @param string $component Component name
     * @param string $message Info message
     * @param mixed $context Additional context
     */
    public static function info($component, $message, $context = null)
    {
        self::log($component, $message, $context, 'info');
    }

    /**
     * Get compact backtrace for debugging
     *
     * @return array
     */
    private static function get_compact_backtrace()
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5);
        $compact = [];
        
        foreach ($trace as $entry) {
            if (isset($entry['file'], $entry['line'])) {
                $compact[] = basename($entry['file']) . ':' . $entry['line'];
            }
        }
        
        return $compact;
    }

    /**
     * Get all debug log entries
     *
     * @return array
     */
    public static function get_log()
    {
        return self::$debug_log;
    }

    /**
     * Clear debug log
     */
    public static function clear_log()
    {
        self::$debug_log = [];
    }

    /**
     * Display debug panel in footer (for administrators only)
     */
    public static function display_debug_panel()
    {
        if (!self::is_enabled() || !current_user_can('administrator')) {
            return;
        }

        $log = self::get_log();
        if (empty($log)) {
            return;
        }

        echo '<div id="zotefoams-debug-panel" style="
            position: fixed; 
            bottom: 0; 
            left: 0; 
            right: 0; 
            background: #23282d; 
            color: #eee; 
            font-family: Consolas, monospace; 
            font-size: 12px; 
            max-height: 300px; 
            overflow-y: auto; 
            z-index: 99999; 
            border-top: 3px solid #0073aa;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.3);
        ">';
        
        echo '<div style="padding: 10px; background: #0073aa; color: white; font-weight: bold;">
            🐛 Zotefoams Debug Panel (' . count($log) . ' entries) 
            <button onclick="document.getElementById(\'zotefoams-debug-panel\').style.display=\'none\'" 
                    style="float: right; background: none; border: 1px solid white; color: white; padding: 2px 8px; cursor: pointer;">×</button>
        </div>';
        
        echo '<div style="padding: 10px;">';
        
        foreach ($log as $entry) {
            $level_color = [
                'error' => '#dc3232',
                'warning' => '#ffb900', 
                'info' => '#00a0d2'
            ][$entry['level']] ?? '#00a0d2';
            
            echo '<div style="margin-bottom: 8px; padding: 5px; border-left: 3px solid ' . $level_color . '; background: rgba(255,255,255,0.05);">';
            echo '<strong style="color: ' . $level_color . ';">[' . strtoupper($entry['level']) . ']</strong> ';
            echo '<span style="color: #46b450;">' . $entry['component'] . '</span>: ';
            echo $entry['message'];
            
            if ($entry['context']) {
                echo '<br><small style="color: #999;">Context: ' . esc_html(json_encode($entry['context'])) . '</small>';
            }
            
            echo '<br><small style="color: #666;">Memory: ' . size_format($entry['memory']) . ' | ';
            echo 'Trace: ' . implode(' → ', $entry['backtrace']) . '</small>';
            echo '</div>';
        }
        
        echo '</div></div>';
    }

    /**
     * Component validation helper
     *
     * @param string $component Component name
     * @param array $required_fields Required fields to validate
     * @param array $actual_data Actual data to validate against
     * @return bool True if validation passes
     */
    public static function validate_component_data($component, $required_fields, $actual_data)
    {
        $missing_fields = [];
        $empty_fields = [];
        
        foreach ($required_fields as $field => $config) {
            $field_name = is_string($config) ? $config : $field;
            $is_required = is_array($config) ? ($config['required'] ?? true) : true;
            
            if (!isset($actual_data[$field_name]) || $actual_data[$field_name] === null) {
                if ($is_required) {
                    $missing_fields[] = $field_name;
                }
            } elseif (empty($actual_data[$field_name]) && $actual_data[$field_name] !== 0 && $actual_data[$field_name] !== '0') {
                if ($is_required) {
                    $empty_fields[] = $field_name;
                }
            }
        }
        
        if (!empty($missing_fields)) {
            self::error($component, 'Missing required fields', ['missing' => $missing_fields]);
        }
        
        if (!empty($empty_fields)) {
            self::warning($component, 'Empty required fields', ['empty' => $empty_fields]);
        }
        
        if (!empty($missing_fields) || !empty($empty_fields)) {
            self::info($component, 'Component may not render correctly due to missing data');
            return false;
        }
        
        self::info($component, 'Component data validation passed');
        return true;
    }
}

// Hook debug panel display to footer
add_action('wp_footer', [Zotefoams_Debug::class, 'display_debug_panel'], 999);