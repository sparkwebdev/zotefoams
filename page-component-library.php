<?php
/**
 * Template: Component Library
 *
 * Front-end showcase page for browsing and previewing ACF flexible
 * content components with realistic dummy data.
 *
 * @package Zotefoams
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once get_template_directory() . '/inc/component-library-data.php';

$registry = zotefoams_component_library_registry();

// Support up to 3 selections via ?c1=slug&c2=slug&c3=slug
$slots = [
    1 => isset($_GET['c1']) ? sanitize_key($_GET['c1']) : '',
    2 => isset($_GET['c2']) ? sanitize_key($_GET['c2']) : '',
    3 => isset($_GET['c3']) ? sanitize_key($_GET['c3']) : '',
];

// Option toggles via URL params (preserved across dropdown navigation)
$testmode       = isset($_GET['testmode']) && $_GET['testmode'] === '1';
$opt_containers = !isset($_GET['containers']) || $_GET['containers'] !== '0';
$opt_variants   = !isset($_GET['variants']) || $_GET['variants'] !== '0';

// Base args for all links (preserves toggle state)
$toggle_args = [];
if (!$opt_containers) $toggle_args['containers'] = '0';
if (!$opt_variants)   $toggle_args['variants'] = '0';
if ($testmode)        $toggle_args['testmode'] = '1';

/**
 * Stress-test a data array at a given level (1–3).
 * Level 1 = mild, Level 2 = moderate, Level 3 = extreme.
 * Recursively walks the array and extends string values.
 */
function zotefoams_cl_stress_data($data, $level)
{
    if (!is_array($data)) {
        return $data;
    }

    $suffixes = [
        1 => ' — extended content for testing',
        2 => ' — this is moderately longer content designed to stress-test layout boundaries and wrapping behaviour',
        3 => ' — this is an extremely long piece of content that pushes the absolute limits of what this component was designed to handle, testing overflow, wrapping, truncation, and general layout resilience under heavy content load conditions',
    ];

    $title_suffixes = [
        1 => ': Extended Title',
        2 => ': A Moderately Longer Title to Test Wrapping',
        3 => ': An Extremely Long Title That Pushes Layout Boundaries and Tests How Components Handle Overflow',
    ];

    foreach ($data as $key => &$value) {
        if (is_array($value)) {
            // Skip ACF image/link arrays (have 'url' + 'sizes' or 'url' + 'target')
            if (isset($value['url']) && (isset($value['sizes']) || isset($value['target']))) {
                continue;
            }
            // Check if this is a repeater (sequential array of arrays)
            if (isset($value[0]) && is_array($value[0])) {
                // Skip duplication for year-keyed repeaters (years must be unique)
                if (!isset($value[0]['year'])) {
                    $extra_items = array_slice($value, 0, $level);
                    foreach ($extra_items as $ei => &$item) {
                        $item = zotefoams_cl_stress_data($item, $level);
                        // Offset position values so duplicated map points don't stack
                        if (isset($item['from_top'])) {
                            $item['from_top'] = (string) min(90, floatval($item['from_top']) + ($ei + 1) * 8);
                        }
                        if (isset($item['from_left'])) {
                            $item['from_left'] = (string) min(90, floatval($item['from_left']) + ($ei + 1) * 6);
                        }
                    }
                    unset($item);
                    $value = array_merge($value, $extra_items);
                }
            }
            // Recurse into sub-arrays
            $value = zotefoams_cl_stress_data($value, $level);
            continue;
        }
        if (!is_string($value)) {
            continue;
        }
        // Numeric value fields — scale up for stress testing
        if (preg_match('/^[\d.]+$/', $value) && preg_match('/_value/', $key)) {
            $multipliers = [1 => 2, 2 => 10, 3 => 100];
            $value = (string)(floatval($value) * ($multipliers[$level] ?? 1));
            continue;
        }
        // Skip URLs, booleans-as-strings, numeric values, very short strings (prefixes/suffixes)
        if (preg_match('/^(https?:|#$|true|false)/', $value) || preg_match('/^[\d.]+$/', $value) || strlen($value) <= 2) {
            continue;
        }
        // Skip fields that are behaviour/mode selectors (short values used in conditionals)
        if (preg_match('/(behaviour|variant|position|theme|mode|type|layout|style|target|prefix|suffix|from_top|from_left)/', $key)) {
            continue;
        }
        // HTML content (paragraphs)
        if (strpos($value, '<p>') !== false) {
            $extra = str_repeat('<p>Additional paragraph for stress testing. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>', $level);
            $value .= $extra;
        }
        // Inline HTML content (e.g. <br> separated text in tooltips/points)
        elseif (strpos($value, '<br') !== false) {
            $value .= str_repeat('<br>Extra line of content for stress testing this element', $level);
        }
        // Titles / headings (short strings without HTML tags, or with only <strong>)
        elseif (strlen(strip_tags($value)) < 120) {
            $value .= ($title_suffixes[$level] ?? '');
        }
    }
    unset($value);
    return $data;
}

// Group the registry for the dropdown
$groups = [];
foreach ($registry as $slug => $config) {
    $groups[$config['group']][$slug] = $config;
}

/**
 * SVG wireframe map — a tiny layout sketch per component.
 * Each value is the inner <svg> content drawn on an 80x52 viewBox.
 */
function zotefoams_cl_wireframes()
{
    $w = []; // wireframes

    // --- Text ---
    $w['text_block'] = '
        <rect x="16" y="8" width="30" height="3" rx="1" fill="#bbb"/>
        <rect x="8" y="16" width="64" height="2" rx="1" fill="#ccc"/>
        <rect x="8" y="21" width="64" height="2" rx="1" fill="#ccc"/>
        <rect x="8" y="26" width="52" height="2" rx="1" fill="#ccc"/>
        <rect x="8" y="31" width="64" height="2" rx="1" fill="#ccc"/>
        <rect x="8" y="36" width="38" height="2" rx="1" fill="#ccc"/>';

    $w['split_text'] = '
        <rect x="4" y="8" width="34" height="3" rx="1" fill="#bbb"/>
        <rect x="4" y="14" width="34" height="2" rx="1" fill="#ddd"/>
        <rect x="4" y="19" width="28" height="2" rx="1" fill="#ddd"/>
        <line x1="41" y1="6" x2="41" y2="46" stroke="#e5e5e5" stroke-width=".5"/>
        <rect x="45" y="8" width="32" height="2" rx="1" fill="#ccc"/>
        <rect x="45" y="13" width="32" height="2" rx="1" fill="#ccc"/>
        <rect x="45" y="18" width="32" height="2" rx="1" fill="#ccc"/>
        <rect x="45" y="23" width="24" height="2" rx="1" fill="#ccc"/>
        <rect x="45" y="30" width="32" height="2" rx="1" fill="#ccc"/>
        <rect x="45" y="35" width="20" height="2" rx="1" fill="#ccc"/>';

    $w['columns_content'] = '
        <rect x="16" y="6" width="30" height="3" rx="1" fill="#bbb"/>
        <rect x="4" y="14" width="35" height="2" rx="1" fill="#ccc"/>
        <rect x="4" y="19" width="35" height="2" rx="1" fill="#ccc"/>
        <rect x="4" y="24" width="28" height="2" rx="1" fill="#ccc"/>
        <rect x="42" y="14" width="35" height="2" rx="1" fill="#ccc"/>
        <rect x="42" y="19" width="35" height="2" rx="1" fill="#ccc"/>
        <rect x="42" y="24" width="28" height="2" rx="1" fill="#ccc"/>
        <rect x="4" y="32" width="73" height="14" rx="2" fill="#eee"/>';

    $w['related_links_box'] = '
        <rect x="20" y="8" width="40" height="3" rx="1" fill="#bbb"/>
        <rect x="16" y="16" width="48" height="6" rx="2" fill="none" stroke="#ccc" stroke-width=".8"/>
        <rect x="16" y="25" width="48" height="6" rx="2" fill="none" stroke="#ccc" stroke-width=".8"/>
        <rect x="16" y="34" width="48" height="6" rx="2" fill="none" stroke="#ccc" stroke-width=".8"/>';

    // --- Media ---
    $w['image_left_right'] = '
        <rect x="4" y="6" width="36" height="40" rx="2" fill="#e5e5e5"/>
        <polygon points="14,30 22,20 30,30" fill="#ccc"/>
        <circle cx="14" cy="16" r="4" fill="#ccc"/>
        <rect x="44" y="10" width="32" height="3" rx="1" fill="#bbb"/>
        <rect x="44" y="17" width="32" height="2" rx="1" fill="#ccc"/>
        <rect x="44" y="22" width="32" height="2" rx="1" fill="#ccc"/>
        <rect x="44" y="27" width="24" height="2" rx="1" fill="#ccc"/>';

    $w['split_video_one'] = '
        <rect x="4" y="6" width="36" height="40" rx="2" fill="#e5e5e5"/>
        <polygon points="18,22 18,32 28,27" fill="#bbb"/>
        <rect x="44" y="10" width="28" height="2" rx="1" fill="#bbb"/>
        <rect x="44" y="16" width="32" height="3" rx="1" fill="#aaa"/>
        <rect x="44" y="23" width="32" height="2" rx="1" fill="#ccc"/>
        <rect x="44" y="28" width="24" height="2" rx="1" fill="#ccc"/>
        <rect x="44" y="36" width="20" height="5" rx="2" fill="none" stroke="#bbb" stroke-width=".8"/>';

    $w['split_video_two'] = '
        <rect x="4" y="6" width="36" height="18" rx="0" fill="#333"/>
        <rect x="8" y="10" width="24" height="2" rx="1" fill="#666"/>
        <rect x="8" y="15" width="20" height="2" rx="1" fill="#555"/>
        <rect x="4" y="26" width="36" height="20" rx="0" fill="#e5e5e5"/>
        <polygon points="16,32 16,42 26,37" fill="#bbb"/>
        <rect x="44" y="6" width="0" height="0"/>';

    $w['highlight_panel'] = '
        <rect x="4" y="4" width="72" height="44" rx="2" fill="#e5e5e5"/>
        <rect x="16" y="14" width="48" height="4" rx="1" fill="#bbb"/>
        <rect x="24" y="22" width="32" height="4" rx="1" fill="#bbb"/>
        <circle cx="40" cy="34" r="5" fill="#ddd"/>
        <rect x="30" y="42" width="20" height="2" rx="1" fill="#ccc"/>';

    $w['text_banner_split'] = '
        <rect x="4" y="6" width="36" height="40" rx="2" fill="#e5e5e5"/>
        <polygon points="14,30 22,20 30,30" fill="#ccc"/>
        <rect x="42" y="6" width="36" height="40" rx="0" fill="#333"/>
        <rect x="46" y="14" width="24" height="2" rx="1" fill="#666"/>
        <rect x="46" y="20" width="28" height="3" rx="1" fill="#888"/>
        <rect x="46" y="32" width="16" height="5" rx="2" fill="none" stroke="#777" stroke-width=".8"/>';

    $w['interactive_image'] = '
        <rect x="4" y="4" width="72" height="44" rx="2" fill="#e5e5e5"/>
        <circle cx="24" cy="18" r="4" fill="#0073aa" opacity=".6"/>
        <circle cx="50" cy="28" r="4" fill="#0073aa" opacity=".6"/>
        <circle cx="36" cy="38" r="4" fill="#0073aa" opacity=".6"/>
        <text x="24" y="20" text-anchor="middle" font-size="5" fill="#fff" font-weight="bold">1</text>
        <text x="50" y="30" text-anchor="middle" font-size="5" fill="#fff" font-weight="bold">2</text>
        <text x="36" y="40" text-anchor="middle" font-size="5" fill="#fff" font-weight="bold">3</text>';

    // --- Columns & Grids ---
    $w['icon_columns'] = '
        <rect x="16" y="4" width="30" height="3" rx="1" fill="#bbb"/>
        <circle cx="16" cy="22" r="6" fill="#e5e5e5"/>
        <rect x="10" y="31" width="12" height="2" rx="1" fill="#bbb"/>
        <rect x="8" y="35" width="16" height="2" rx="1" fill="#ccc"/>
        <circle cx="40" cy="22" r="6" fill="#e5e5e5"/>
        <rect x="34" y="31" width="12" height="2" rx="1" fill="#bbb"/>
        <rect x="32" y="35" width="16" height="2" rx="1" fill="#ccc"/>
        <circle cx="64" cy="22" r="6" fill="#e5e5e5"/>
        <rect x="58" y="31" width="12" height="2" rx="1" fill="#bbb"/>
        <rect x="56" y="35" width="16" height="2" rx="1" fill="#ccc"/>';

    $w['box_columns'] = '
        <rect x="4" y="4" width="23" height="44" rx="2" fill="#f0f0f0"/>
        <rect x="6" y="28" width="14" height="2" rx="1" fill="#bbb"/>
        <rect x="6" y="33" width="18" height="2" rx="1" fill="#ccc"/>
        <rect x="29" y="4" width="23" height="44" rx="2" fill="#f0f0f0"/>
        <rect x="31" y="28" width="14" height="2" rx="1" fill="#bbb"/>
        <rect x="31" y="33" width="18" height="2" rx="1" fill="#ccc"/>
        <rect x="54" y="4" width="23" height="44" rx="2" fill="#f0f0f0"/>
        <rect x="56" y="28" width="14" height="2" rx="1" fill="#bbb"/>
        <rect x="56" y="33" width="18" height="2" rx="1" fill="#ccc"/>';

    $w['small_box_columns'] = '
        <rect x="4" y="6" width="22" height="22" rx="2" fill="#e5e5e5"/>
        <rect x="6" y="32" width="16" height="2" rx="1" fill="#bbb"/>
        <rect x="6" y="37" width="12" height="2" rx="1" fill="#ccc"/>
        <rect x="30" y="6" width="22" height="22" rx="2" fill="#e5e5e5"/>
        <rect x="32" y="32" width="16" height="2" rx="1" fill="#bbb"/>
        <rect x="32" y="37" width="12" height="2" rx="1" fill="#ccc"/>
        <rect x="56" y="6" width="22" height="22" rx="2" fill="#e5e5e5"/>
        <rect x="58" y="32" width="16" height="2" rx="1" fill="#bbb"/>
        <rect x="58" y="37" width="12" height="2" rx="1" fill="#ccc"/>';

    $w['icons_grid'] = '
        <rect x="4" y="4" width="35" height="20" rx="2" fill="#f0f0f0"/>
        <circle cx="12" cy="10" r="3" fill="#ddd"/>
        <rect x="18" y="9" width="16" height="2" rx="1" fill="#bbb"/>
        <rect x="18" y="14" width="12" height="2" rx="1" fill="#ccc"/>
        <rect x="42" y="4" width="35" height="20" rx="2" fill="#f0f0f0"/>
        <circle cx="50" cy="10" r="3" fill="#ddd"/>
        <rect x="56" y="9" width="16" height="2" rx="1" fill="#bbb"/>
        <rect x="56" y="14" width="12" height="2" rx="1" fill="#ccc"/>
        <rect x="4" y="28" width="35" height="20" rx="2" fill="#f0f0f0"/>
        <circle cx="12" cy="34" r="3" fill="#ddd"/>
        <rect x="18" y="33" width="16" height="2" rx="1" fill="#bbb"/>
        <rect x="42" y="28" width="35" height="20" rx="2" fill="#f0f0f0"/>
        <circle cx="50" cy="34" r="3" fill="#ddd"/>
        <rect x="56" y="33" width="16" height="2" rx="1" fill="#bbb"/>';

    $w['data_points'] = '
        <rect x="4" y="6" width="20" height="3" rx="1" fill="#bbb"/>
        <circle cx="16" cy="24" r="6" fill="#e5e5e5"/>
        <rect x="8" y="34" width="16" height="4" rx="1" fill="#0073aa" opacity=".3"/>
        <rect x="10" y="40" width="12" height="2" rx="1" fill="#ccc"/>
        <circle cx="40" cy="24" r="6" fill="#e5e5e5"/>
        <rect x="32" y="34" width="16" height="4" rx="1" fill="#0073aa" opacity=".3"/>
        <rect x="34" y="40" width="12" height="2" rx="1" fill="#ccc"/>
        <circle cx="64" cy="24" r="6" fill="#e5e5e5"/>
        <rect x="56" y="34" width="16" height="4" rx="1" fill="#0073aa" opacity=".3"/>
        <rect x="58" y="40" width="12" height="2" rx="1" fill="#ccc"/>';

    $w['data_map'] = '
        <rect x="4" y="4" width="72" height="44" rx="2" fill="#333"/>
        <ellipse cx="40" cy="24" rx="26" ry="14" fill="none" stroke="#555" stroke-width=".5"/>
        <rect x="8" y="8" width="14" height="4" rx="1" fill="#0073aa" opacity=".5"/>
        <rect x="8" y="14" width="10" height="2" rx="1" fill="#666"/>
        <rect x="8" y="36" width="14" height="4" rx="1" fill="#0073aa" opacity=".5"/>
        <rect x="8" y="42" width="10" height="2" rx="1" fill="#666"/>';

    // --- Carousels ---
    $w['dual_carousel'] = '
        <rect x="4" y="4" width="36" height="44" rx="0" fill="#333"/>
        <rect x="8" y="10" width="20" height="2" rx="1" fill="#666"/>
        <rect x="8" y="16" width="28" height="3" rx="1" fill="#888"/>
        <rect x="8" y="24" width="28" height="14" rx="1" fill="#555"/>
        <rect x="42" y="4" width="36" height="44" rx="0" fill="#e5e5e5"/>
        <polygon points="52,22 52,32 62,27" fill="#ccc"/>
        <circle cx="6" cy="26" r="3" fill="none" stroke="#666" stroke-width=".5"/>
        <circle cx="76" cy="26" r="3" fill="none" stroke="#aaa" stroke-width=".5"/>
        <polyline points="5,25 7,26 5,27" fill="none" stroke="#666" stroke-width=".5"/>
        <polyline points="75,25 77,26 75,27" fill="none" stroke="#aaa" stroke-width=".5"/>';

    $w['split_carousel'] = '
        <rect x="4" y="4" width="44" height="44" rx="2" fill="#e5e5e5"/>
        <polygon points="18,22 18,32 28,27" fill="#ccc"/>
        <rect x="52" y="10" width="24" height="2" rx="1" fill="#ccc"/>
        <rect x="52" y="16" width="24" height="3" rx="1" fill="#bbb"/>
        <rect x="52" y="24" width="24" height="2" rx="1" fill="#ccc"/>
        <rect x="52" y="29" width="20" height="2" rx="1" fill="#ccc"/>
        <rect x="52" y="36" width="16" height="5" rx="2" fill="none" stroke="#bbb" stroke-width=".8"/>
        <circle cx="58" cy="46" r="2" fill="#0073aa"/>
        <circle cx="64" cy="46" r="2" fill="#ddd"/>
        <circle cx="70" cy="46" r="2" fill="#ddd"/>';

    $w['multi_item_carousel'] = '
        <rect x="4" y="6" width="24" height="3" rx="1" fill="#bbb"/>
        <rect x="4" y="14" width="18" height="32" rx="2" fill="#f0f0f0"/>
        <rect x="6" y="26" width="12" height="2" rx="1" fill="#bbb"/>
        <rect x="6" y="31" width="10" height="2" rx="1" fill="#ccc"/>
        <rect x="24" y="14" width="18" height="32" rx="2" fill="#f0f0f0"/>
        <rect x="26" y="26" width="12" height="2" rx="1" fill="#bbb"/>
        <rect x="26" y="31" width="10" height="2" rx="1" fill="#ccc"/>
        <rect x="44" y="14" width="18" height="32" rx="2" fill="#f0f0f0"/>
        <rect x="46" y="26" width="12" height="2" rx="1" fill="#bbb"/>
        <rect x="46" y="31" width="10" height="2" rx="1" fill="#ccc"/>
        <rect x="64" y="14" width="14" height="32" rx="2" fill="#f0f0f0" opacity=".5"/>';

    $w['calendar_carousel'] = '
        <rect x="4" y="6" width="24" height="3" rx="1" fill="#bbb"/>
        <rect x="4" y="14" width="18" height="30" rx="2" fill="none" stroke="#ddd" stroke-width=".8"/>
        <rect x="8" y="18" width="10" height="6" rx="1" fill="#0073aa" opacity=".2"/>
        <rect x="8" y="27" width="10" height="2" rx="1" fill="#ccc"/>
        <rect x="24" y="14" width="18" height="30" rx="2" fill="none" stroke="#ddd" stroke-width=".8"/>
        <rect x="28" y="18" width="10" height="6" rx="1" fill="#0073aa" opacity=".2"/>
        <rect x="28" y="27" width="10" height="2" rx="1" fill="#ccc"/>
        <rect x="44" y="14" width="18" height="30" rx="2" fill="none" stroke="#ddd" stroke-width=".8"/>
        <rect x="48" y="18" width="10" height="6" rx="1" fill="#0073aa" opacity=".2"/>
        <rect x="48" y="27" width="10" height="2" rx="1" fill="#ccc"/>
        <rect x="64" y="14" width="14" height="30" rx="2" fill="none" stroke="#eee" stroke-width=".5"/>';

    $w['step_slider'] = '
        <rect x="4" y="4" width="72" height="44" rx="0" fill="#333"/>
        <rect x="8" y="10" width="32" height="28" rx="2" fill="#555"/>
        <rect x="44" y="12" width="8" height="2" rx="1" fill="#666"/>
        <rect x="44" y="18" width="24" height="3" rx="1" fill="#888"/>
        <rect x="44" y="26" width="28" height="2" rx="1" fill="#666"/>
        <rect x="44" y="31" width="28" height="2" rx="1" fill="#666"/>
        <rect x="44" y="36" width="20" height="2" rx="1" fill="#666"/>';

    // --- Navigation ---
    $w['panel_switcher'] = '
        <rect x="4" y="6" width="20" height="38" rx="0" fill="#f5f5f5"/>
        <rect x="6" y="10" width="16" height="4" rx="1" fill="#0073aa" opacity=".3"/>
        <rect x="6" y="18" width="16" height="4" rx="1" fill="#eee"/>
        <rect x="6" y="26" width="16" height="4" rx="1" fill="#eee"/>
        <rect x="28" y="8" width="46" height="34" rx="2" fill="#fff" stroke="#e5e5e5" stroke-width=".5"/>
        <circle cx="36" cy="18" r="4" fill="#e5e5e5"/>
        <rect x="32" y="26" width="30" height="2" rx="1" fill="#ccc"/>
        <rect x="32" y="31" width="30" height="2" rx="1" fill="#ccc"/>
        <rect x="32" y="36" width="20" height="2" rx="1" fill="#ccc"/>';

    $w['tabbed_split'] = '
        <rect x="14" y="6" width="16" height="8" rx="2" fill="#0073aa" opacity=".3"/>
        <rect x="33" y="6" width="16" height="8" rx="2" fill="#eee"/>
        <rect x="52" y="6" width="16" height="8" rx="2" fill="#eee"/>
        <rect x="4" y="18" width="36" height="28" rx="0" fill="#f5f5f5"/>
        <rect x="8" y="22" width="20" height="2" rx="1" fill="#bbb"/>
        <rect x="8" y="28" width="28" height="2" rx="1" fill="#ccc"/>
        <rect x="8" y="33" width="28" height="2" rx="1" fill="#ccc"/>
        <rect x="42" y="18" width="36" height="28" rx="0" fill="#e5e5e5"/>';

    $w['show_hide'] = '
        <rect x="4" y="6" width="24" height="3" rx="1" fill="#bbb"/>
        <rect x="4" y="14" width="72" height="8" rx="2" fill="none" stroke="#ddd" stroke-width=".8"/>
        <rect x="8" y="17" width="40" height="2" rx="1" fill="#bbb"/>
        <text x="72" y="20" text-anchor="middle" font-size="6" fill="#bbb">+</text>
        <rect x="4" y="26" width="72" height="8" rx="2" fill="none" stroke="#ddd" stroke-width=".8"/>
        <rect x="8" y="29" width="36" height="2" rx="1" fill="#bbb"/>
        <text x="72" y="32" text-anchor="middle" font-size="6" fill="#bbb">+</text>
        <rect x="4" y="38" width="72" height="8" rx="2" fill="none" stroke="#ddd" stroke-width=".8"/>
        <rect x="8" y="41" width="32" height="2" rx="1" fill="#bbb"/>
        <text x="72" y="44" text-anchor="middle" font-size="6" fill="#bbb">+</text>';

    // --- Data ---
    $w['news_feed'] = '
        <rect x="4" y="6" width="16" height="3" rx="1" fill="#bbb"/>
        <rect x="4" y="14" width="23" height="16" rx="2" fill="#e5e5e5"/>
        <rect x="6" y="33" width="14" height="2" rx="1" fill="#ccc"/>
        <rect x="6" y="38" width="18" height="2" rx="1" fill="#bbb"/>
        <rect x="30" y="14" width="23" height="16" rx="2" fill="#e5e5e5"/>
        <rect x="32" y="33" width="14" height="2" rx="1" fill="#ccc"/>
        <rect x="32" y="38" width="18" height="2" rx="1" fill="#bbb"/>
        <rect x="56" y="14" width="23" height="16" rx="2" fill="#e5e5e5"/>
        <rect x="58" y="33" width="14" height="2" rx="1" fill="#ccc"/>
        <rect x="58" y="38" width="18" height="2" rx="1" fill="#bbb"/>';

    $w['document_list'] = '
        <rect x="4" y="6" width="24" height="3" rx="1" fill="#bbb"/>
        <rect x="4" y="14" width="72" height="6" rx="1" fill="#f8f8f8"/>
        <circle cx="10" cy="17" r="2" fill="#ddd"/>
        <rect x="16" y="16" width="30" height="2" rx="1" fill="#ccc"/>
        <rect x="66" y="16" width="8" height="2" rx="1" fill="#0073aa" opacity=".4"/>
        <rect x="4" y="22" width="72" height="6" rx="1" fill="#fff"/>
        <circle cx="10" cy="25" r="2" fill="#ddd"/>
        <rect x="16" y="24" width="28" height="2" rx="1" fill="#ccc"/>
        <rect x="66" y="24" width="8" height="2" rx="1" fill="#0073aa" opacity=".4"/>
        <rect x="4" y="30" width="72" height="6" rx="1" fill="#f8f8f8"/>
        <circle cx="10" cy="33" r="2" fill="#ddd"/>
        <rect x="16" y="32" width="32" height="2" rx="1" fill="#ccc"/>
        <rect x="66" y="32" width="8" height="2" rx="1" fill="#0073aa" opacity=".4"/>';

    $w['financial_documents_picker'] = '
        <rect x="4" y="6" width="24" height="3" rx="1" fill="#bbb"/>
        <rect x="4" y="14" width="30" height="7" rx="2" fill="none" stroke="#ddd" stroke-width=".8"/>
        <rect x="8" y="16" width="16" height="3" rx="1" fill="#ccc"/>
        <polyline points="26,17 28,19 26,21" fill="none" stroke="#aaa" stroke-width=".6"/>
        <rect x="4" y="26" width="72" height="5" rx="1" fill="#f8f8f8"/>
        <rect x="8" y="28" width="30" height="2" rx="1" fill="#ccc"/>
        <rect x="4" y="33" width="72" height="5" rx="1" fill="#fff"/>
        <rect x="8" y="35" width="26" height="2" rx="1" fill="#ccc"/>
        <rect x="4" y="40" width="72" height="5" rx="1" fill="#f8f8f8"/>
        <rect x="8" y="42" width="28" height="2" rx="1" fill="#ccc"/>';

    $w['markets_list'] = '
        <rect x="4" y="4" width="24" height="44" rx="2" fill="#fff" stroke="#eee" stroke-width=".5"/>
        <rect x="8" y="10" width="16" height="3" rx="1" fill="#bbb"/>
        <rect x="8" y="16" width="12" height="2" rx="1" fill="#ccc"/>
        <rect x="8" y="32" width="16" height="8" rx="1" fill="#e5e5e5"/>
        <rect x="30" y="4" width="24" height="44" rx="2" fill="#fff" stroke="#eee" stroke-width=".5"/>
        <rect x="34" y="10" width="16" height="3" rx="1" fill="#bbb"/>
        <rect x="34" y="16" width="12" height="2" rx="1" fill="#ccc"/>
        <rect x="34" y="32" width="16" height="8" rx="1" fill="#e5e5e5"/>
        <rect x="56" y="4" width="22" height="44" rx="2" fill="#fff" stroke="#eee" stroke-width=".5"/>
        <rect x="60" y="10" width="14" height="3" rx="1" fill="#bbb"/>
        <rect x="60" y="16" width="10" height="2" rx="1" fill="#ccc"/>
        <rect x="60" y="32" width="14" height="8" rx="1" fill="#e5e5e5"/>';

    $w['locations_map'] = '
        <rect x="4" y="4" width="72" height="44" rx="2" fill="#333"/>
        <ellipse cx="40" cy="26" rx="28" ry="14" fill="none" stroke="#555" stroke-width=".5" stroke-dasharray="2,2"/>
        <circle cx="36" cy="20" r="3" fill="#0073aa" opacity=".6"/>
        <circle cx="22" cy="28" r="3" fill="#0073aa" opacity=".6"/>
        <circle cx="52" cy="22" r="3" fill="#0073aa" opacity=".6"/>
        <rect x="4" y="8" width="20" height="3" rx="1" fill="#888"/>
        <rect x="4" y="13" width="14" height="2" rx="1" fill="#0073aa" opacity=".5"/>';

    // --- Misc ---
    $w['waste-hierarchy'] = '
        <polygon points="40,6 60,44 20,44" fill="none" stroke="#ccc" stroke-width=".8"/>
        <rect x="30" y="16" width="20" height="4" rx="1" fill="#4caf50" opacity=".3"/>
        <rect x="27" y="24" width="26" height="4" rx="1" fill="#ffc107" opacity=".3"/>
        <rect x="24" y="32" width="32" height="4" rx="1" fill="#f44336" opacity=".3"/>';

    $w['bir_widgets'] = '
        <rect x="4" y="4" width="72" height="44" rx="2" fill="#f5f5f5"/>
        <polyline points="8,40 20,28 32,34 44,16 56,22 68,12" fill="none" stroke="#0073aa" stroke-width="1.2"/>
        <rect x="8" y="8" width="20" height="3" rx="1" fill="#bbb"/>';

    $w['show_hide_forms'] = '
        <rect x="4" y="6" width="24" height="3" rx="1" fill="#bbb"/>
        <rect x="4" y="14" width="72" height="8" rx="2" fill="none" stroke="#ddd" stroke-width=".8"/>
        <rect x="8" y="17" width="30" height="2" rx="1" fill="#bbb"/>
        <text x="72" y="20" text-anchor="middle" font-size="6" fill="#bbb">+</text>
        <rect x="4" y="26" width="72" height="16" rx="2" fill="#f8f8f8"/>
        <rect x="8" y="29" width="28" height="2" rx="1" fill="#bbb"/>
        <rect x="8" y="34" width="60" height="2" rx="1" fill="#ddd"/>
        <rect x="8" y="38" width="20" height="3" rx="1" fill="#0073aa" opacity=".3"/>';

    $w['cta_picker'] = '
        <rect x="4" y="6" width="20" height="3" rx="1" fill="#bbb"/>
        <rect x="4" y="14" width="23" height="30" rx="2" fill="#f0f0f0"/>
        <rect x="6" y="30" width="14" height="2" rx="1" fill="#bbb"/>
        <rect x="6" y="35" width="10" height="2" rx="1" fill="#ccc"/>
        <rect x="30" y="14" width="23" height="30" rx="2" fill="#f0f0f0"/>
        <rect x="32" y="30" width="14" height="2" rx="1" fill="#bbb"/>
        <rect x="32" y="35" width="10" height="2" rx="1" fill="#ccc"/>
        <rect x="56" y="14" width="22" height="30" rx="2" fill="#f0f0f0"/>
        <rect x="58" y="30" width="14" height="2" rx="1" fill="#bbb"/>
        <rect x="58" y="35" width="10" height="2" rx="1" fill="#ccc"/>';

    return $w;
}

$wireframes = zotefoams_cl_wireframes();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<?php wp_head(); ?>
<style>
body.component-library{background:#f5f5f5}

/* ---- Top bar ---- */
.cl-topbar{background:#111;padding:14px 24px;display:flex;align-items:center;gap:16px}
.cl-topbar a{color:#fff;text-decoration:none;font-size:.85rem;opacity:.75;transition:opacity .15s}
.cl-topbar a:hover{opacity:1}
.cl-topbar__title{color:#fff;font-size:.85rem;font-weight:600;margin:0}

/* ---- Component Library UI ---- */
.cl-selector{background:#f5f5f5;padding-bottom:32px;border-bottom:1px solid #e0e0e0}
.cl-header{max-width:1400px;margin:0 auto;padding:48px 20px 0}
.cl-header h1{font-size:1.75rem;font-weight:700;margin:0 0 6px;letter-spacing:-.02em}
.cl-header p{color:#777;font-size:.9rem;margin:0}

/* Dropdown row */
.cl-picker{max-width:1400px;margin:24px auto 0;padding:0 20px;position:relative;z-index:100}
.cl-picker-row{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;align-items:stretch}
.cl-dropdown__trigger{height:100%;box-sizing:border-box;min-height:78px}
@media(max-width:768px){.cl-picker-row{grid-template-columns:1fr}}
.cl-dropdown{position:relative}
.cl-dropdown__trigger{
    display:flex;align-items:center;gap:12px;width:100%;
    padding:12px 16px;border:1px solid #d0d0d0;border-radius:8px;
    background:#fff;cursor:pointer;font-size:.95rem;
    transition:border-color .15s,box-shadow .15s;
}
.cl-dropdown__trigger:hover{border-color:#0073aa}
.cl-dropdown__trigger:focus{outline:none;border-color:#0073aa;box-shadow:0 0 0 2px rgba(0,115,170,.15)}
.cl-dropdown__trigger svg.cl-wireframe{flex-shrink:0;border:1px solid #eee;border-radius:4px;background:#fafafa}
.cl-dropdown__trigger-label{flex:1;text-align:left}
.cl-dropdown__trigger-label .cl-trigger-title{font-weight:600;color:#222}
.cl-dropdown__trigger-label .cl-trigger-file{font-size:.8rem;color:#999;margin-top:1px}
.cl-dropdown__trigger-placeholder{color:#999;flex:1;text-align:left}
.cl-dropdown__chevron{
    width:20px;height:20px;flex-shrink:0;transition:transform .2s;
    color:#999;
}
.cl-dropdown.is-open .cl-dropdown__chevron{transform:rotate(180deg)}

.cl-dropdown__menu{
    display:none;position:absolute;top:calc(100% + 4px);left:0;right:0;z-index:200;
    background:#fff;border:1px solid #d0d0d0;border-radius:8px;
    box-shadow:0 8px 24px rgba(0,0,0,.1);max-height:480px;overflow-y:auto;
    padding:6px 0;
}
.cl-dropdown.is-open .cl-dropdown__menu{display:block}

.cl-dropdown__group-label{
    padding:10px 16px 4px;font-size:.7rem;font-weight:700;
    text-transform:uppercase;letter-spacing:.08em;color:#999;
}
.cl-dropdown__group-label:not(:first-child){
    border-top:1px solid #eee;margin-top:4px;padding-top:12px;
}

.cl-dropdown__option{
    display:flex;align-items:center;gap:12px;padding:8px 16px;
    cursor:pointer;transition:background .1s;text-decoration:none;color:inherit;
}
.cl-dropdown__option:hover{background:#f0f7fc}
.cl-dropdown__option.is-active{background:#e8f4fb}
.cl-dropdown__option svg.cl-wireframe{flex-shrink:0;border:1px solid #eee;border-radius:4px;background:#fafafa}
.cl-dropdown__option-text{flex:1}
.cl-dropdown__option-text .cl-opt-title{font-size:.9rem;font-weight:500;color:#222}
.cl-opt-badge{display:inline-block;font-size:.65rem;color:#888;background:#f0f0f0;padding:1px 6px;border-radius:3px;margin-left:6px;vertical-align:middle;font-weight:500}

/* Toolbar (clear + options) */
.cl-toolbar{display:flex;align-items:center;margin-top:16px;flex-wrap:wrap;gap:12px}
.cl-clear{font-size:.85rem;color:#0073aa;text-decoration:none}
.cl-clear:hover{text-decoration:underline}
.cl-options{display:flex;align-items:center;gap:20px;margin-left:auto;flex-wrap:wrap}
.cl-toggle{display:flex;align-items:center;gap:8px;cursor:pointer;font-size:.85rem;color:#555;user-select:none}
.cl-toggle input{display:none}
.cl-toggle__track{
    position:relative;width:36px;height:20px;background:#ccc;border-radius:10px;
    transition:background .2s;flex-shrink:0;
}
.cl-toggle__track::after{
    content:'';position:absolute;top:2px;left:2px;width:16px;height:16px;
    background:#fff;border-radius:50%;transition:transform .2s;
}
.cl-toggle input:checked + .cl-toggle__track{background:#0073aa}
.cl-toggle input:checked + .cl-toggle__track::after{transform:translateX(16px)}

/* Preview container */
.cl-preview{margin:48px 20px;border:1px solid #e0e0e0;background:#fff}
body.cl-no-containers .cl-preview{border:none;background:transparent;margin:0}
body.cl-no-containers{background:#fff}
body.cl-no-variants .cl-preview[data-variant]{display:none}
.cl-stress-label{margin:0;padding:8px 20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#c62828;background:#fce4ec;border:1px solid #f8bbd0;border-bottom:none}
.cl-tooltip{position:relative;display:inline-flex;align-items:center;justify-content:center;width:20px;height:20px;border-radius:50%;background:#ddd;color:#555;font-size:.75rem;font-weight:700;cursor:help;margin-left:-12px;font-style:normal}
.cl-tooltip:hover::after{content:attr(data-tip);position:absolute;bottom:calc(100% + 6px);right:0;width:220px;background:#333;color:#fff;font-size:.75rem;font-weight:400;line-height:1.4;padding:8px 10px;border-radius:4px;z-index:300}
body.cl-testmode .cl-dropdown:nth-child(n+2){display:none}
</style>
</head>
<?php
$body_classes = 'component-library';
if ($testmode) $body_classes .= ' cl-testmode';
if (!$opt_containers) $body_classes .= ' cl-no-containers';
if (!$opt_variants) $body_classes .= ' cl-no-variants';
?>
<body <?php body_class($body_classes); ?>>

<div class="cl-topbar">
    <a href="<?php echo esc_url(home_url('/')); ?>">&larr; Back to site</a>
    <span class="cl-topbar__title">Component Library</span>
</div>

<div class="cl-selector">
<div class="cl-header">
    <h1>Component Library</h1>
    <p>Select up to three components to preview with dummy content, rendered using the real templates and styles.</p>
</div>

<div class="cl-picker">
    <div class="cl-picker-row">
        <?php for ($slot = 1; $slot <= 3; $slot++) :
            $slot_selected = $slots[$slot];
            $slot_key      = 'c' . $slot;
            // Build base args preserving the other slots
            $base_args = $toggle_args;
            foreach ($slots as $s => $v) {
                if ($s !== $slot && $v) {
                    $base_args['c' . $s] = $v;
                }
            }
        ?>
        <div class="cl-dropdown" data-cl-dropdown>
            <button type="button" class="cl-dropdown__trigger" aria-haspopup="listbox" aria-expanded="false">
                <?php if ($slot_selected && isset($registry[$slot_selected])) : ?>
                    <?php if (isset($wireframes[$slot_selected])) : ?>
                        <svg class="cl-wireframe" width="80" height="52" viewBox="0 0 80 52" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <?php echo $wireframes[$slot_selected]; ?>
                        </svg>
                    <?php endif; ?>
                    <span class="cl-dropdown__trigger-label">
                        <span class="cl-trigger-title"><?php echo esc_html($registry[$slot_selected]['label']); ?></span>
                    </span>
                <?php else : ?>
                    <span class="cl-dropdown__trigger-placeholder">Component <?php echo $slot; ?>&hellip;</span>
                <?php endif; ?>
                <svg class="cl-dropdown__chevron" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
            </button>

            <div class="cl-dropdown__menu" role="listbox">
                <?php if ($slot_selected) :
                    $clear_href = esc_url(add_query_arg($base_args, home_url('/component-library')));
                ?>
                    <a class="cl-dropdown__option cl-dropdown__option--clear" href="<?php echo $clear_href; ?>" role="option">
                        <span class="cl-dropdown__option-text"><span class="cl-opt-title" style="color:#0073aa">Clear selection</span></span>
                    </a>
                <?php endif; ?>
                <?php foreach ($groups as $group_name => $components) : ?>
                    <div class="cl-dropdown__group-label"><?php echo esc_html($group_name); ?></div>
                    <?php foreach ($components as $slug => $config) :
                        $is_active = ($slug === $slot_selected);
                        $option_args = array_merge($base_args, [$slot_key => $slug]);
                        $href = esc_url(add_query_arg($option_args, home_url('/component-library')));
                    ?>
                        <a class="cl-dropdown__option<?php echo $is_active ? ' is-active' : ''; ?>"
                           href="<?php echo $href; ?>"
                           role="option"
                           <?php echo $is_active ? 'aria-selected="true"' : ''; ?>>
                            <?php if (isset($wireframes[$slug])) : ?>
                                <svg class="cl-wireframe" width="80" height="52" viewBox="0 0 80 52" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <?php echo $wireframes[$slug]; ?>
                                </svg>
                            <?php endif; ?>
                            <span class="cl-dropdown__option-text">
                                <span class="cl-opt-title"><?php echo esc_html($config['label']); ?></span>
                                <?php if (!empty($config['variants'])) : ?><span class="cl-opt-badge">has variant</span><?php endif; ?>
                            </span>
                        </a>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endfor; ?>
    </div>

    <div class="cl-toolbar">
        <?php if ($slots[1] || $slots[2] || $slots[3]) : ?>
            <a class="cl-clear" href="<?php echo esc_url(add_query_arg($toggle_args, home_url('/component-library'))); ?>">Clear all</a>
        <?php endif; ?>
        <div class="cl-options">
            <label class="cl-toggle"><input type="checkbox" <?php echo $opt_containers ? 'checked' : ''; ?> data-cl-toggle="containers"><span class="cl-toggle__track"></span> Show containers</label>
            <label class="cl-toggle"><input type="checkbox" <?php echo $opt_variants ? 'checked' : ''; ?> data-cl-toggle="variants"><span class="cl-toggle__track"></span> Show variants</label>
            <label class="cl-toggle"><input type="checkbox" <?php echo $testmode ? 'checked' : ''; ?> data-cl-toggle="testmode"><span class="cl-toggle__track"></span> Enable test mode</label><i class="cl-tooltip" data-tip="Renders 4 versions at increasing stress levels: longer titles, extra paragraphs, and duplicated repeater items">?</i>
        </div>
    </div>
</div>
</div>

<?php
// ---------------------------------------------------------------------------
// Render previews for all selected slots
// ---------------------------------------------------------------------------
$render_slots = $testmode ? [1 => $slots[1]] : $slots;

foreach ($render_slots as $slot_num => $slot_slug) :
    if (!$slot_slug || !isset($registry[$slot_slug])) {
        continue;
    }

    $config   = $registry[$slot_slug];
    $callback = $config['data'] ?? null;
    $template = locate_template('template-parts/components/' . $slot_slug . '.php');

    if (!$template) {
        continue;
    }

    // Determine how many iterations to render
    $iterations = $testmode ? [0, 1, 2, 3] : [0]; // 0 = normal, 1-3 = stress levels

    foreach ($iterations as $stress_level) :
        if ($stress_level === 1) {
            echo '<div class="cl-stress-label">Stress test — 3 iterations with increasing content length</div>';
        }
        $base_data = is_callable($callback) ? call_user_func($callback) : [];
        $fields = $stress_level > 0 ? zotefoams_cl_stress_data($base_data, $stress_level) : $base_data;

        $label_attr = $stress_level > 0 ? ' data-stress="' . $stress_level . '"' : '';
        echo '<div class="cl-preview"' . $label_attr . '>';
        $GLOBALS['zotefoams_preview_fields'] = $fields;
        include $template;
        unset($GLOBALS['zotefoams_preview_fields']);
        echo '</div>';
    endforeach;

    // --- Variant renders ---
    if (!empty($config['variants'])) {
        foreach ($config['variants'] as $variant_label => $variant_callback) {
            if (!is_callable($variant_callback)) {
                continue;
            }
            $var_iterations = $testmode ? [0, 1, 2, 3] : [0];
            foreach ($var_iterations as $stress_level) :
                $base_data = call_user_func($variant_callback);
                $fields = $stress_level > 0 ? zotefoams_cl_stress_data($base_data, $stress_level) : $base_data;

                $label_attr = $stress_level > 0 ? ' data-stress="' . $stress_level . '"' : '';
                echo '<div class="cl-preview" data-variant="' . esc_attr($variant_label) . '"' . $label_attr . '>';
                if ($stress_level > 0) {
        
                }
                $GLOBALS['zotefoams_preview_fields'] = $fields;
                include $template;
                unset($GLOBALS['zotefoams_preview_fields']);
                echo '</div>';
            endforeach;
        }
    }
endforeach;
?>

<script>
(function() {
    var dropdowns = document.querySelectorAll('[data-cl-dropdown]');

    dropdowns.forEach(function(dropdown) {
        var trigger = dropdown.querySelector('.cl-dropdown__trigger');
        var menu    = dropdown.querySelector('.cl-dropdown__menu');

        trigger.addEventListener('click', function() {
            // Close other open dropdowns
            dropdowns.forEach(function(other) {
                if (other !== dropdown) {
                    other.classList.remove('is-open');
                    other.querySelector('.cl-dropdown__trigger').setAttribute('aria-expanded', 'false');
                }
            });
            var open = dropdown.classList.toggle('is-open');
            trigger.setAttribute('aria-expanded', open);
            if (open) {
                var active = menu.querySelector('.is-active');
                if (active) active.scrollIntoView({ block: 'nearest' });
            }
        });

        trigger.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                dropdown.classList.remove('is-open');
                trigger.setAttribute('aria-expanded', 'false');
            }
        });
    });

    // Close all on outside click
    document.addEventListener('click', function(e) {
        dropdowns.forEach(function(dropdown) {
            if (!dropdown.contains(e.target)) {
                dropdown.classList.remove('is-open');
                dropdown.querySelector('.cl-dropdown__trigger').setAttribute('aria-expanded', 'false');
            }
        });
    });

    // Option toggles — all update URL params and reload
    var toggleDefaults = { containers: '1', variants: '1', testmode: '0' };
    document.querySelectorAll('[data-cl-toggle]').forEach(function(input) {
        var key = input.getAttribute('data-cl-toggle');
        var onVal = (key === 'testmode') ? '1' : '0';
        input.addEventListener('change', function() {
            var url = new URL(window.location);
            var isDefault = input.checked === (toggleDefaults[key] === '1');
            if (isDefault) {
                url.searchParams.delete(key);
            } else {
                url.searchParams.set(key, input.checked ? '1' : '0');
            }
            window.location.href = url.toString();
        });
    });
})();
</script>

<?php wp_footer(); ?>
</body>
</html>
