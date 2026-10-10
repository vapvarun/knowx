<?php
/**
 * Data for the Knowledge Base page templates.
 *
 * Every function returns plain arrays of scalars so the result can be cached, and
 * every query is bounded so the templates stay fast on sites with thousands of
 * posts and hundreds of categories.
 *
 * @package knowx
 */

/**
 * Category ids hidden from both Knowledge Base layouts.
 *
 * @return int[] Category ids.
 */
function knowx_kb_excluded_ids() {
	$raw = (string) knowx_mod( 'knowx_exclude' );

	return array_values( array_filter( array_map( 'absint', explode( ',', $raw ) ) ) );
}

/**
 * Version stamp of the docs data. It changes whenever a post, a category or category meta changes.
 *
 * Stored in an option on purpose: the "last changed" values of the object cache are rebuilt on every
 * request when the site has no persistent object cache, so a transient keyed on them never hits.
 *
 * @return string Version.
 */
function knowx_kb_version() {
	return (string) get_option( 'knowx_kb_version', '0' );
}

/**
 * Invalidates every cached docs result. Hooked to the post and term actions in the Docs component.
 */
function knowx_kb_bump_version() {
	update_option( 'knowx_kb_version', (string) microtime( true ) );
}

/**
 * Returns cached data and rebuilds it whenever any post or term changes.
 *
 * @param string   $key      Cache key, include every input that changes the result.
 * @param callable $callback Builds the data on a cache miss.
 * @return array Cached data.
 */
function knowx_kb_cached( $key, $callback ) {
	$transient = 'knowx_kb_' . md5( $key . knowx_kb_version() );
	$data      = get_transient( $transient );

	if ( ! is_array( $data ) ) {
		$data = (array) $callback();
		set_transient( $transient, $data, DAY_IN_SECONDS );
	}

	return $data;
}

/**
 * Maps category terms to the fields the templates print.
 *
 * @param WP_Term[]|WP_Error $terms Terms from get_terms().
 * @return array[] Items with id, name, url and description.
 */
function knowx_kb_map_terms( $terms ) {
	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		return array();
	}

	$items = array();
	foreach ( $terms as $term ) {
		$items[] = array(
			'id'          => (int) $term->term_id,
			'name'        => $term->name,
			'url'         => get_term_link( $term ),
			'description' => $term->description,
		);
	}

	return $items;
}

/**
 * Maps posts to the fields the templates print.
 *
 * @param WP_Post[] $posts Posts.
 * @return array[] Items with id, title and url.
 */
function knowx_kb_map_posts( $posts ) {
	$items = array();
	foreach ( $posts as $post_item ) {
		$items[] = array(
			'id'    => (int) $post_item->ID,
			'title' => get_the_title( $post_item ),
			'url'   => get_permalink( $post_item ),
		);
	}

	return $items;
}

/**
 * Upper limit for categories printed by a layout.
 *
 * @return int Maximum number of categories.
 */
function knowx_kb_max_categories() {
	/**
	 * Filters the maximum number of categories a Knowledge Base layout prints.
	 *
	 * @param int $max Default 100.
	 */
	return max( 1, (int) apply_filters( 'knowx_kb_max_categories', 100 ) );
}

/**
 * Top-level categories that contain posts (Layout One tiles).
 *
 * @return array[] Category items.
 */
function knowx_kb_top_categories() {
	$exclude = knowx_kb_excluded_ids();

	return knowx_kb_cached(
		'top-' . implode( ',', $exclude ),
		static function () use ( $exclude ) {
			return knowx_kb_map_terms(
				get_terms(
					array(
						'taxonomy'   => 'category',
						'parent'     => 0,
						'hide_empty' => true,
						'exclude'    => $exclude,
						'orderby'    => 'name',
						'order'      => 'ASC',
						'number'     => knowx_kb_max_categories(),
					)
				)
			);
		}
	);
}

/**
 * Newest posts outside the excluded categories.
 *
 * @param int $limit Number of posts.
 * @return array[] Post items.
 */
function knowx_kb_recent_posts( $limit = 5 ) {
	$exclude = knowx_kb_excluded_ids();

	return knowx_kb_cached(
		'recent-' . (int) $limit . '-' . implode( ',', $exclude ),
		static function () use ( $exclude, $limit ) {
			return knowx_kb_map_posts( get_posts( knowx_kb_post_query( $exclude, array( 'numberposts' => (int) $limit ) ) ) );
		}
	);
}

/**
 * Sticky posts outside the excluded categories, shown as featured articles.
 *
 * Owners mark an article sticky in the editor to feature it.
 *
 * @param int $limit Number of posts.
 * @return array[] Post items.
 */
function knowx_kb_featured_posts( $limit = 5 ) {
	$sticky = array_map( 'absint', (array) get_option( 'sticky_posts', array() ) );
	if ( empty( $sticky ) ) {
		return array();
	}

	$exclude = knowx_kb_excluded_ids();

	return knowx_kb_cached(
		'featured-' . (int) $limit . '-' . implode( ',', $exclude ),
		static function () use ( $exclude, $limit, $sticky ) {
			return knowx_kb_map_posts(
				get_posts(
					knowx_kb_post_query(
						$exclude,
						array(
							'numberposts'  => (int) $limit,
							'post__in'     => $sticky,
							'orderby'      => 'post__in',
						)
					)
				)
			);
		}
	);
}

/**
 * Builds the shared get_posts() arguments.
 *
 * @param int[] $exclude Category ids to skip, children included.
 * @param array $args    Extra arguments.
 * @return array Query arguments.
 */
function knowx_kb_post_query( $exclude, $args = array() ) {
	$query = array(
		'post_type'              => 'post',
		'ignore_sticky_posts'    => true,
		'no_found_rows'          => true,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => false,
	);

	if ( ! empty( $exclude ) ) {
		$query['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query -- bounded by 'numberposts' and cached.
			array(
				'taxonomy' => 'category',
				'field'    => 'term_id',
				'terms'    => $exclude,
				'operator' => 'NOT IN',
			),
		);
	}

	return array_merge( $query, $args );
}

/**
 * Categories with their latest posts (Layout Two).
 *
 * Reads the Posts per category (default 10) and Order posts settings.
 *
 * @return array[] Category items, each with a `posts` list.
 */
function knowx_kb_sections() {
	$exclude  = knowx_kb_excluded_ids();
	$per_cat  = max( 1, (int) knowx_mod( 'knowx_posts' ) );
	$by_name  = 'name' === knowx_mod( 'knowx_order' );
	$cache_id = 'sections-' . $per_cat . '-' . (int) $by_name . '-' . implode( ',', $exclude );

	return knowx_kb_cached(
		$cache_id,
		static function () use ( $exclude, $per_cat, $by_name ) {
			$sections = knowx_kb_map_terms(
				get_terms(
					array(
						'taxonomy'   => 'category',
						'hide_empty' => true,
						'exclude'    => $exclude,
						'orderby'    => 'name',
						'order'      => 'ASC',
						'number'     => knowx_kb_max_categories(),
					)
				)
			);

			foreach ( $sections as $index => $section ) {
				$sections[ $index ]['posts'] = knowx_kb_map_posts(
					get_posts(
						array(
							'post_type'              => 'post',
							'numberposts'            => $per_cat,
							'orderby'                => $by_name ? 'title' : 'date',
							'order'                  => $by_name ? 'ASC' : 'DESC',
							'ignore_sticky_posts'    => true,
							'no_found_rows'          => true,
							'update_post_meta_cache' => false,
							'update_post_term_cache' => false,
							'tax_query'              => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query -- one bounded, cached query per listed category.
								array(
									'taxonomy'         => 'category',
									'field'            => 'term_id',
									'terms'            => $section['id'],
									'include_children' => false,
								),
							),
						)
					)
				);
			}

			return $sections;
		}
	);
}
