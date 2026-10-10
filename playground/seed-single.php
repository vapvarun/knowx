<?php
/**
 * Turns the sample site into a single-product site: only Orbit API is left.
 *
 * Runs after seed.php (tools/build-blueprint.mjs --single joins the two files).
 *
 * @package knowx
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$helpdesk = get_term_by( 'slug', 'orbit-helpdesk', 'category' );
if ( $helpdesk ) {
	$ids = array_merge( array( $helpdesk->term_id ), get_term_children( $helpdesk->term_id, 'category' ) );
	foreach ( get_posts(
		array(
			'category__in'   => $ids,
			'post_type'      => 'post',
			'posts_per_page' => -1,
			'fields'         => 'ids',
		)
	) as $post_id ) {
		wp_delete_post( $post_id, true );
	}
	foreach ( array_reverse( $ids ) as $term_id ) {
		wp_delete_term( $term_id, 'category' );
	}
}

// The changelog sample describes the helpdesk, so the single-product site goes without it.
$changelog = get_page_by_path( 'changelog' );
if ( $changelog ) {
	wp_delete_post( $changelog->ID, true );
}

update_option( 'blogdescription', 'Help for the Orbit API' );
foreach ( get_posts(
	array(
		'post_type'      => 'page',
		'posts_per_page' => -1,
	)
) as $page ) {
	if ( false !== strpos( $page->post_excerpt, 'Orbit Helpdesk and the Orbit API' ) ) {
		wp_update_post(
			array(
				'ID'           => $page->ID,
				'post_excerpt' => str_replace( 'Orbit Helpdesk and the Orbit API', 'the Orbit API', $page->post_excerpt ),
			)
		);
	}
}
