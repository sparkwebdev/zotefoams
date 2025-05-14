<?php
global $wp_query;

$big = 999999999; // unlikely integer to be replaced
$pagination = paginate_links(array(
	'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
	'format'    => '?paged=%#%',
	'current'   => max(1, get_query_var('paged')),
	'total'     => $wp_query->max_num_pages,
	'type'      => 'list', // outputs <ul><li>... structure (optional, for better markup)
	'prev_text' => esc_html__('« Prev', 'zotefoams'),
	'next_text' => esc_html__('Next »', 'zotefoams'),
));

if ($pagination) {
	echo wp_kses_post($pagination);
}
