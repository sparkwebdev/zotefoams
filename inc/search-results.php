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

    if (is_admin() || !$wp_query->is_main_query() || !$wp_query->is_search()) {
        return $search;
    }

    $s = $wp_query->get('s');

    if (empty($s)) {
        return $search;
    }

    $like = '%' . $wpdb->esc_like($s) . '%';

    $search = $wpdb->prepare(
        " AND (
            (
                {$wpdb->posts}.post_type != 'attachment'
                AND (
                    {$wpdb->posts}.post_title LIKE %s
                    OR {$wpdb->posts}.post_content LIKE %s
                    OR {$wpdb->posts}.post_excerpt LIKE %s
                    OR EXISTS (
                        SELECT 1 FROM {$wpdb->postmeta}
                        WHERE {$wpdb->postmeta}.post_id = {$wpdb->posts}.ID
                        AND {$wpdb->postmeta}.meta_key LIKE 'page\_content\_%'
                        AND {$wpdb->postmeta}.meta_value LIKE %s
                    )
                    OR {$wpdb->posts}.ID IN (
                        SELECT tr.object_id
                        FROM {$wpdb->term_relationships} AS tr
                        INNER JOIN {$wpdb->term_taxonomy} AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
                        INNER JOIN {$wpdb->terms} AS t ON tt.term_id = t.term_id
                        WHERE t.name LIKE %s OR t.slug LIKE %s
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
                    {$wpdb->posts}.post_title LIKE %s
                    OR {$wpdb->posts}.post_excerpt LIKE %s
                    OR EXISTS (
                        SELECT 1 FROM {$wpdb->postmeta}
                        WHERE {$wpdb->postmeta}.post_id = {$wpdb->posts}.ID
                        AND {$wpdb->postmeta}.meta_key = '_wp_attachment_image_alt'
                        AND {$wpdb->postmeta}.meta_value LIKE %s
                    )
                )
            )
        )",
        $like, $like, $like, $like, $like, $like, $like, $like, $like
    );

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

