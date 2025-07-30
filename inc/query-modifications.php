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
 *
 * @param WP_Query $query The WordPress query object.
 */
function zotefoams_upcoming_events_pre_get_posts($query)
{
    // Only modify main query on front-end for 'upcoming-events' page
    if (!is_admin() && $query->is_main_query()) {
        // Check if we're on the upcoming events page
        if (is_page() && get_post_field('post_name', get_queried_object_id()) === 'upcoming-events') {
            // Set up meta query for future events
            $today = date('Y-m-d');
            
            $meta_query = array(
                'relation' => 'OR',
                array(
                    'key' => 'event_end_date',
                    'value' => $today,
                    'compare' => '>=',
                    'type' => 'DATE'
                ),
                array(
                    'key' => 'event_start_date',
                    'value' => $today,
                    'compare' => '>=',
                    'type' => 'DATE'
                )
            );
            
            $query->set('post_type', 'events');
            $query->set('posts_per_page', -1);
            $query->set('meta_query', $meta_query);
            $query->set('orderby', 'meta_value');
            $query->set('meta_key', 'event_start_date');
            $query->set('order', 'ASC');
        }
    }
}
add_action('pre_get_posts', 'zotefoams_upcoming_events_pre_get_posts');