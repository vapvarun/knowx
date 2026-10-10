<?php
/**
 * Breadcrumb trail.
 *
 * @package knowx
 */

/**
 * Builds the breadcrumb trail for the current request.
 *
 * @return array[] Items as array( 'label' => string, 'url' => string ); the last item has no url.
 */
function knowx_get_breadcrumb_items() {
	if ( is_front_page() ) {
		return array();
	}

	$items = array(
		array(
			'label' => __( 'Home', 'knowx' ),
			'url'   => home_url( '/' ),
		),
	);

	$term_trail = static function ( $term_id, $taxonomy ) {
		$trail = array();
		foreach ( array_reverse( get_ancestors( $term_id, $taxonomy, 'taxonomy' ) ) as $ancestor_id ) {
			$ancestor = get_term( $ancestor_id, $taxonomy );
			if ( $ancestor && ! is_wp_error( $ancestor ) ) {
				$trail[] = array(
					'label' => $ancestor->name,
					'url'   => get_term_link( $ancestor ),
				);
			}
		}
		return $trail;
	};

	if ( is_home() ) {
		$items[] = array(
			'label' => get_the_title( (int) get_option( 'page_for_posts' ) ) ? get_the_title( (int) get_option( 'page_for_posts' ) ) : __( 'Blog', 'knowx' ),
			'url'   => '',
		);
	} elseif ( is_singular() ) {
		if ( is_page() ) {
			foreach ( array_reverse( get_post_ancestors( get_the_ID() ) ) as $ancestor_id ) {
				$items[] = array(
					'label' => get_the_title( $ancestor_id ),
					'url'   => get_permalink( $ancestor_id ),
				);
			}
		} elseif ( 'post' === get_post_type() ) {
			$categories = get_the_category();
			if ( ! empty( $categories ) ) {
				$items = array_merge( $items, $term_trail( $categories[0]->term_id, 'category' ) );
				$items[] = array(
					'label' => $categories[0]->name,
					'url'   => get_category_link( $categories[0]->term_id ),
				);
			}
		}
		$items[] = array(
			'label' => get_the_title(),
			'url'   => '',
		);
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$term  = get_queried_object();
		$items = array_merge( $items, $term_trail( $term->term_id, $term->taxonomy ) );
		$items[] = array(
			'label' => $term->name,
			'url'   => '',
		);
	} elseif ( is_author() ) {
		$items[] = array(
			'label' => get_queried_object()->display_name,
			'url'   => '',
		);
	} elseif ( is_date() ) {
		$year = get_query_var( 'year' );
		if ( is_year() ) {
			$items[] = array(
				'label' => (string) $year,
				'url'   => '',
			);
		} else {
			$items[] = array(
				'label' => (string) $year,
				'url'   => get_year_link( $year ),
			);
			$items[] = array(
				'label' => get_the_date( is_month() ? 'F' : 'F j' ),
				'url'   => '',
			);
		}
	} elseif ( is_search() ) {
		$items[] = array(
			/* translators: %s: search term. */
			'label' => sprintf( __( 'Search results for "%s"', 'knowx' ), get_search_query() ),
			'url'   => '',
		);
	} elseif ( is_post_type_archive() ) {
		$items[] = array(
			'label' => post_type_archive_title( '', false ),
			'url'   => '',
		);
	} elseif ( is_404() ) {
		$items[] = array(
			'label' => __( 'Page not found', 'knowx' ),
			'url'   => '',
		);
	}

	return $items;
}

/**
 * Prints the breadcrumb trail.
 */
function knowx_get_breadcrumb() {
	$items = knowx_get_breadcrumb_items();
	if ( empty( $items ) ) {
		return;
	}

	$last = count( $items ) - 1;

	echo '<nav class="knowx-breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'knowx' ) . '"><ol class="knowx-trail-items">';
	foreach ( $items as $index => $item ) {
		echo '<li class="knowx-trail-item">';
		if ( $index < $last && ! empty( $item['url'] ) ) {
			printf( '<a href="%s">%s</a>', esc_url( $item['url'] ), esc_html( $item['label'] ) );
		} else {
			printf( '<span aria-current="page">%s</span>', esc_html( $item['label'] ) );
		}
		echo '</li>';
	}
	echo '</ol></nav>';
}
