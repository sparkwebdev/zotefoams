<?php
/**
 * Search Results Configuration
 *
 * Enhanced search functionality to include:
 * - Specific ACF flexible content field (page_content: field_67ace0a590515)
 * - Media attachments linked to Knowledge Hub posts via documents_list field
 * - Taxonomy terms
 *
 * @package Zotefoams
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Include specific post types in search results.
 *
 * Only searches:
 * - Posts
 * - Pages
 * - Knowledge Hub posts
 * - Document attachments (PDFs, DOCs, etc. - not images)
 */
function zotefoams_search_all_post_types($query)
{
    if (!is_admin() && $query->is_main_query() && $query->is_search()) {
        $query->set('post_type', array('post', 'page', 'knowledge-hub', 'attachment'));
        $query->set('post_status', array('publish', 'inherit')); // 'inherit' is the status for attachments
    }
    return $query;
}
add_action('pre_get_posts', 'zotefoams_search_all_post_types');

/**
 * Exclude image attachments from search results.
 * Only include document types (PDF, DOC, XLS, etc.).
 */
function zotefoams_exclude_images_from_search($where, $query)
{
    global $wpdb;

    if (!is_admin() && $query->is_main_query() && $query->is_search()) {
        // Add condition to exclude image MIME types for attachments
        $where .= " AND (
            {$wpdb->posts}.post_type != 'attachment'
            OR (
                {$wpdb->posts}.post_type = 'attachment'
                AND {$wpdb->posts}.post_mime_type NOT LIKE 'image/%'
                AND {$wpdb->posts}.post_mime_type NOT LIKE 'video/%'
                AND {$wpdb->posts}.post_mime_type NOT LIKE 'audio/%'
            )
        )";
    }

    return $where;
}
add_filter('posts_where', 'zotefoams_exclude_images_from_search', 10, 2);

/**
 * Enhanced search to include specific ACF fields and attachments.
 *
 * Searches in:
 * - Post title, content, and excerpt
 * - ACF flexible content field: page_content (field_67ace0a590515)
 * - Knowledge Hub documents (attachments via field_67c58d6ffce9f)
 * - Taxonomy terms
 *
 * @param string $search The SQL search clause.
 * @param WP_Query $wp_query The WP_Query instance.
 * @return string Modified SQL search clause.
 */
function zotefoams_enhanced_search($search, $wp_query)
{
    global $wpdb;

    if (!$wp_query->is_main_query() || !$wp_query->is_search()) {
        return $search;
    }

    $search_term = $wpdb->esc_like($wp_query->get('s'));

    if (empty($search_term)) {
        return $search;
    }

    $search = " AND (
        (
            {$wpdb->posts}.post_type != 'attachment'
            AND (
                {$wpdb->posts}.post_title LIKE '%{$search_term}%'
                OR {$wpdb->posts}.post_content LIKE '%{$search_term}%'
                OR {$wpdb->posts}.post_excerpt LIKE '%{$search_term}%'
                OR EXISTS (
                    SELECT 1 FROM {$wpdb->postmeta}
                    WHERE {$wpdb->postmeta}.post_id = {$wpdb->posts}.ID
                    AND {$wpdb->postmeta}.meta_key LIKE 'page\_content\_%'
                    AND {$wpdb->postmeta}.meta_value LIKE '%{$search_term}%'
                )
                OR {$wpdb->posts}.ID IN (
                    SELECT tr.object_id
                    FROM {$wpdb->term_relationships} AS tr
                    INNER JOIN {$wpdb->term_taxonomy} AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
                    INNER JOIN {$wpdb->terms} AS t ON tt.term_id = t.term_id
                    WHERE t.name LIKE '%{$search_term}%' OR t.slug LIKE '%{$search_term}%'
                )
            )
        )
        OR (
            {$wpdb->posts}.post_type = 'attachment'
            AND EXISTS (
                SELECT 1 FROM {$wpdb->postmeta} pm
                WHERE pm.meta_key LIKE 'documents\_list\_%'
                AND pm.meta_value = {$wpdb->posts}.ID
                AND EXISTS (
                    SELECT 1 FROM {$wpdb->posts} p
                    WHERE p.ID = pm.post_id
                    AND p.post_type = 'knowledge-hub'
                    AND p.post_status = 'publish'
                )
            )
            AND (
                {$wpdb->posts}.post_title LIKE '%{$search_term}%'
                OR {$wpdb->posts}.post_excerpt LIKE '%{$search_term}%'
                OR EXISTS (
                    SELECT 1 FROM {$wpdb->postmeta}
                    WHERE {$wpdb->postmeta}.post_id = {$wpdb->posts}.ID
                    AND {$wpdb->postmeta}.meta_key = '_wp_attachment_image_alt'
                    AND {$wpdb->postmeta}.meta_value LIKE '%{$search_term}%'
                )
            )
        )
    )";

    return $search;
}
add_filter('posts_search', 'zotefoams_enhanced_search', 500, 2);

/**
 * Prevent duplicate results in search.
 *
 * Because we're searching across multiple sources (content, custom fields,
 * taxonomies), the same post could match in multiple places. This ensures
 * each result appears only once.
 *
 * @param string $distinct The DISTINCT clause.
 * @param WP_Query $query The WP_Query instance.
 * @return string Modified DISTINCT clause.
 */
function zotefoams_search_distinct($distinct, $query)
{
    if ($query->is_search()) {
        return 'DISTINCT';
    }
    return $distinct;
}
add_filter('posts_distinct', 'zotefoams_search_distinct', 10, 2);

/**
 * Debug: Log the actual SQL query being executed.
 * Add ?search_debug=1 to your search URL to enable.
 */
function zotefoams_log_search_query($results, $query)
{
    if (isset($_GET['search_debug']) && $query->is_main_query() && $query->is_search()) {
        error_log('=== SEARCH SQL QUERY ===');
        error_log($query->request);
    }
    return $results;
}
add_filter('posts_results', 'zotefoams_log_search_query', 10, 2);

/**
 * Get excerpt from ACF page_content field if no manual excerpt exists.
 *
 * @param int $post_id The post ID to extract excerpt from.
 * @return string The excerpt text, or empty string if none found.
 */
function zotefoams_get_acf_excerpt($post_id = null)
{
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    // Only for pages with ACF
    if (get_post_type($post_id) !== 'page' || !function_exists('get_field')) {
        return '';
    }

    $page_content = get_field('page_content', $post_id);

    if (!$page_content || !is_array($page_content)) {
        return '';
    }

    // Loop through flexible content blocks to find first body text
    foreach ($page_content as $block) {
        if (!is_array($block)) {
            continue;
        }

        // Look for fields ending with _text, _extra_text, _description, _content
        // But skip anything with title, heading, overline, label
        foreach ($block as $key => $value) {
            // Skip non-text fields
            if ($key === 'acf_fc_layout' ||
                stripos($key, '_image') !== false ||
                stripos($key, '_link') !== false ||
                stripos($key, '_title') !== false ||
                stripos($key, '_heading') !== false ||
                stripos($key, '_overline') !== false ||
                stripos($key, '_label') !== false) {
                continue;
            }

            // Look for fields ending with _text or containing _extra_text, _description, _content
            if ((substr($key, -5) === '_text' ||
                 stripos($key, '_extra_text') !== false ||
                 stripos($key, '_content') !== false) &&
                is_string($value) &&
                !empty(trim(wp_strip_all_tags($value)))) {

                return wp_trim_words(wp_strip_all_tags($value), 30, '...');
            }
        }
    }

    return '';
}

/**
 * Debug function: Display meta fields containing search term.
 * Add ?search_debug=1 to your search URL.
 */
function zotefoams_debug_search_display()
{
    if (!isset($_GET['search_debug']) || !is_search()) {
        return;
    }

    global $wpdb, $wp_query;
    $search_term = get_search_query();

    // Get the actual SQL that was run
    $actual_sql = $wp_query->request;

    // Check if meta fields contain the search term in page_content fields
    $test_query = $wpdb->get_results($wpdb->prepare(
        "SELECT post_id, meta_key, LEFT(meta_value, 150) as meta_value
        FROM {$wpdb->postmeta}
        WHERE meta_value LIKE %s
        AND meta_key LIKE 'page\_content\_%'
        LIMIT 10",
        '%' . $wpdb->esc_like($search_term) . '%'
    ));

    // Check for Knowledge Hub attachments
    $attachment_query = $wpdb->get_results($wpdb->prepare(
        "SELECT p.ID as post_id, p.post_title, p.post_type
        FROM {$wpdb->posts} p
        WHERE p.post_type = 'attachment'
        AND p.post_status = 'inherit'
        AND (
            p.post_title LIKE %s
            OR p.post_excerpt LIKE %s
        )
        LIMIT 5",
        '%' . $wpdb->esc_like($search_term) . '%',
        '%' . $wpdb->esc_like($search_term) . '%'
    ));

    $has_results = $wp_query->found_posts > 0;
    $bg_color = $has_results ? '#d4edda' : '#f8d7da';
    $border_color = $has_results ? '#c3e6cb' : '#f5c6cb';

    echo '<div style="background: ' . $bg_color . '; padding: 20px; margin: 20px 0; border: 2px solid ' . $border_color . '; font-family: monospace; position: relative; z-index: 9999;">';
    echo '<strong style="font-size: 16px;">🔍 SEARCH DEBUG MODE</strong><br><br>';
    echo '<strong>Search Term:</strong> "' . esc_html($search_term) . '"<br>';
    echo '<strong>WordPress Results:</strong> ' . $wp_query->found_posts . '<br><br>';

    // Show the actual SQL query
    if ($actual_sql) {
        echo '<details style="margin-bottom: 15px;"><summary style="cursor: pointer; font-weight: bold; background: #e9ecef; padding: 5px;">📄 View SQL Query</summary>';
        echo '<pre style="background: white; padding: 10px; overflow-x: auto; font-size: 11px; border: 1px solid #ddd;">';
        echo esc_html($actual_sql);
        echo '</pre></details>';
    }

    if ($test_query) {
        if (!$has_results) {
            echo '<span style="color: #856404; background: #fff3cd; padding: 5px; display: inline-block; margin-bottom: 10px;">';
            echo '⚠️ PAGE_CONTENT FIELDS EXIST BUT NOT IN RESULTS!';
            echo '</span><br><br>';
        }

        echo '<strong>page_content ACF fields found:</strong><br><br>';
        echo '<div style="max-height: 300px; overflow-y: auto; background: white; padding: 10px; border: 1px solid #ddd;">';

        foreach ($test_query as $meta) {
            $post = get_post($meta->post_id);
            $post_title = $post ? $post->post_title : 'Unknown';
            $post_status = $post ? $post->post_status : 'unknown';
            $post_type = $post ? $post->post_type : 'unknown';

            echo '<div style="margin-bottom: 15px; padding: 10px; background: #f8f9fa; border-left: 3px solid #007bff;">';
            echo '<strong>Post:</strong> ' . esc_html($post_title) . '<br>';
            echo '<strong>ID:</strong> ' . $meta->post_id . ' | ';
            echo '<strong>Status:</strong> ' . $post_status . ' | ';
            echo '<strong>Type:</strong> ' . $post_type . '<br>';
            echo '<strong>Meta Key:</strong> <code>' . esc_html($meta->meta_key) . '</code><br>';
            echo '<strong>Value Preview:</strong> ' . esc_html($meta->meta_value) . '...<br>';
            echo '</div>';
        }

        echo '</div>';
    } else {
        echo '<span style="color: #666;">ℹ️ No page_content fields found with "' . esc_html($search_term) . '"</span><br>';
    }

    if ($attachment_query) {
        echo '<br><strong>Attachments found:</strong><br><br>';
        echo '<div style="max-height: 200px; overflow-y: auto; background: white; padding: 10px; border: 1px solid #ddd;">';

        foreach ($attachment_query as $attachment) {
            echo '<div style="margin-bottom: 10px; padding: 8px; background: #fff3cd; border-left: 3px solid #ffc107;">';
            echo '<strong>Title:</strong> ' . esc_html($attachment->post_title) . '<br>';
            echo '<strong>ID:</strong> ' . $attachment->post_id . '<br>';
            echo '</div>';
        }

        echo '</div>';
    }

    echo '</div>';
}
add_action('wp_footer', 'zotefoams_debug_search_display');
