<?php
/**
 * Query Modifications and Custom Post Handling
 *
 * @package Zotefoams
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Modify query for upcoming events to show only future events.
 * This function hooks into the 'pre_get_posts' action to alter the query parameters
 * for event-related queries, such as filtering by date or ordering events.
 *
 * @param WP_Query $query The WP_Query instance (passed by reference).
 */
function zotefoams_upcoming_events_pre_get_posts($query)
{
    if (is_admin() || ! $query->is_main_query()) {
        return;
    }

    // Filter events category to show upcoming and ongoing events
    if (is_category('events')) {
        $today = date('Ymd');

        $meta_query = [
            'relation' => 'OR',
            // Future events (start date >= today)
            [
                'key'     => 'event_start_date',
                'value'   => $today,
                'compare' => '>=',
                'type'    => 'CHAR',
            ],
            // Events with no start date
            [
                'key'     => 'event_start_date',
                'compare' => 'NOT EXISTS',
            ],
            // Ongoing events (started before today but end date >= today)
            [
                'relation' => 'AND',
                [
                    'key'     => 'event_start_date',
                    'value'   => $today,
                    'compare' => '<',
                    'type'    => 'CHAR',
                ],
                [
                    'key'     => 'event_end_date',
                    'value'   => $today,
                    'compare' => '>=',
                    'type'    => 'CHAR',
                ],
            ],
        ];

        $query->set('meta_query', $meta_query);
        $query->set('orderby', 'meta_value');
        $query->set('meta_key', 'event_start_date');
        $query->set('order', 'ASC');
    }
}
add_action('pre_get_posts', 'zotefoams_upcoming_events_pre_get_posts');