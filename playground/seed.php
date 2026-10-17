<?php
/**
 * Sample site for Playground, the QA Docker stack and the wordpress.org preview tests.
 *
 * The content itself is the theme's "Add sample docs" button (Starter_Content\Component).
 * This file adds what only a throwaway demo site should get: the site title, tags and comments,
 * pretty permalinks, and no WordPress sample post or page. It is not shipped in the theme zip.
 * Used by the Playground blueprint (runPHP) and by WP-CLI: `wp eval-file playground/seed.php`.
 * Running it twice does nothing the second time.
 *
 * @package knowx
 */

if ( ! defined( 'ABSPATH' ) ) {
	require_once '/wordpress/wp-load.php';
}

if ( get_term_by( 'slug', 'orbit-helpdesk', 'category' ) ) {
	return;
}

// Articles and pages get an author, like content an owner writes.
wp_set_current_user( 1 );

// Remove WordPress' own sample post and page.
wp_delete_post( 1, true );
wp_delete_post( 2, true );

$home = ( new KnowX\KnowX\Starter_Content\Component() )->create_sample_content();
update_option( 'knowx_setup', 'sample', false );

update_option( 'show_on_front', 'page' );
update_option( 'page_on_front', $home );
update_option( 'blogname', 'Orbit Docs' );
update_option( 'blogdescription', 'Help for Orbit Helpdesk and the Orbit API' );

// Tags and two comments, so the tag archive and the comment list have something to show.
$tagged = array(
	'Get an API key'          => array( 'authentication', 'api' ),
	'Make your first request' => array( 'api' ),
	'Create your account'     => array( 'authentication' ),
);
foreach ( $tagged as $title => $tags ) {
	$found = get_posts(
		array(
			'title'          => $title,
			'post_type'      => 'post',
			'posts_per_page' => 1,
			'no_found_rows'  => true,
		)
	);
	if ( $found ) {
		wp_set_post_tags( $found[0]->ID, $tags );
		if ( 'Get an API key' === $title ) {
			foreach ( array(
				array( 'Maya', 'Does the key work for the sandbox too?' ),
				array( 'Orbit Support', 'Yes. Create a separate key under Settings > API > Sandbox.' ),
			) as $comment ) {
				wp_insert_comment(
					array(
						'comment_post_ID'      => $found[0]->ID,
						'comment_author'       => $comment[0],
						'comment_author_email' => 'sample@example.com',
						'comment_content'      => $comment[1],
						'comment_approved'     => 1,
					)
				);
			}
		}
	}
}

global $wp_rewrite;
$wp_rewrite->set_permalink_structure( '/%postname%/' );
// Taxonomies registered their rewrite rules while permalinks were plain (WP-CLI), so register them again before flushing.
create_initial_taxonomies();
$wp_rewrite->flush_rules( true );
