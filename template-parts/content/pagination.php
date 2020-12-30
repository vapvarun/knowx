<?php
/**
 * Template part for displaying a pagination
 *
 * @package helpdesk
 */

namespace Helpdesk\Helpdesk;

the_posts_pagination(
	[
		'mid_size'           => 2,
		'prev_text'          => _x( 'Previous', 'previous set of search results', 'helpdesk' ),
		'next_text'          => _x( 'Next', 'next set of search results', 'helpdesk' ),
		'screen_reader_text' => __( 'Page navigation', 'helpdesk' ),
	]
);
