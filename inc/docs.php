<?php
/**
 * Data for the docs layout: products, section tree and the per-article contents list.
 *
 * A product is a top-level category, its children are sections and posts are articles.
 * Every query is bounded and cached with knowx_kb_cached(), which rebuilds when any post
 * or term changes.
 *
 * @package knowx
 */

/**
 * Icons a product can choose, as slug => array( label, SVG path data on a 24px grid ).
 *
 * @return array[] Icon definitions.
 */
function knowx_product_icons() {
	return array(
		'book'   => array( __( 'Book', 'knowx' ), 'M4 19.5A2.5 2.5 0 0 1 6.5 17H20V3H6.5A2.5 2.5 0 0 0 4 5.5v14zM6.5 17H20v4H6.5' ),
		'rocket' => array( __( 'Rocket', 'knowx' ), 'M5 15c-1 1-2 4-2 6 2 0 5-1 6-2M9 15l-3-3 4-5c3-4 8-4 11-4 0 3 0 8-4 11l-5 4-3-3M15 9h.01' ),
		'gear'   => array( __( 'Gear', 'knowx' ), 'M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM19 12l2-1-2-4-2 1-2-1-1-2h-4l-1 2-2 1-2-1-2 4 2 1v2l-2 1 2 4 2-1 2 1 1 2h4l1-2 2-1 2 1 2-4-2-1z' ),
		'code'   => array( __( 'Code', 'knowx' ), 'M8 7l-5 5 5 5M16 7l5 5-5 5M14 4l-4 16' ),
		'shield' => array( __( 'Shield', 'knowx' ), 'M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6z' ),
		'chat'   => array( __( 'Chat', 'knowx' ), 'M4 5h16v11H9l-5 4z' ),
		'cart'   => array( __( 'Cart', 'knowx' ), 'M3 4h2l2 12h11l2-8H6M9 20h.01M17 20h.01' ),
		'user'   => array( __( 'User', 'knowx' ), 'M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM4 21a8 8 0 0 1 16 0' ),
		'puzzle' => array( __( 'Puzzle', 'knowx' ), 'M10 4a2 2 0 1 1 4 0v2h4v4h-2a2 2 0 1 0 0 4h2v6h-6v-2a2 2 0 1 0-4 0v2H4v-6h2a2 2 0 1 0 0-4H4V6h6z' ),
		'bolt'   => array( __( 'Bolt', 'knowx' ), 'M13 3L5 14h6l-1 7 8-11h-6z' ),
		'chart'  => array( __( 'Chart', 'knowx' ), 'M4 20V4M4 20h16M8 16v-5M12 16V8M16 16v-3' ),
		'cloud'  => array( __( 'Cloud', 'knowx' ), 'M7 18a4 4 0 0 1-.5-8A6 6 0 0 1 18 9a4.5 4.5 0 0 1-.5 9z' ),
	);
}

/**
 * Colours and icons a product gets when its owner has not picked any.
 *
 * Chosen by category id, so a product keeps the same look as products are added. Used only when the site
 * has two or more products; with one product the theme colour applies.
 *
 * @return array[] Two lists: 'accents' (AA contrast on white) and 'icons' (icon slugs).
 */
function knowx_default_product_looks() {
	return array(
		'accents' => array( '#2563eb', '#7c3aed', '#047857', '#b45309', '#be123c', '#0f766e', '#4338ca', '#c2410c' ),
		'icons'   => array( 'book', 'rocket', 'code', 'shield', 'chat', 'gear', 'bolt', 'puzzle' ),
	);
}

/**
 * Prints-ready inline SVG for a product icon.
 *
 * @param string $slug Icon slug; unknown slugs fall back to the book.
 * @param int    $size Width and height in pixels.
 * @return string SVG markup.
 */
function knowx_icon_svg( $slug, $size = 24 ) {
	$icons = knowx_product_icons();
	$path  = isset( $icons[ $slug ] ) ? $icons[ $slug ][1] : $icons['book'][1];

	return sprintf(
		'<svg viewBox="0 0 24 24" width="%1$d" height="%1$d" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="%2$s"/></svg>',
		(int) $size,
		esc_attr( $path )
	);
}

/**
 * Products: top-level categories that hold articles, with their icon, accent colour and article count.
 *
 * @return array[] Items with id, name, url, description, count, icon, accent and the Start here link.
 */
function knowx_products() {
	$exclude = knowx_kb_excluded_ids();

	return knowx_kb_cached(
		'products-' . implode( ',', $exclude ),
		static function () use ( $exclude ) {
			// Padded counts (articles in child sections too) only work on the full term list.
			$all   = get_terms(
				array(
					'taxonomy'   => 'category',
					'hide_empty' => true,
					'pad_counts' => true,
					'exclude'    => $exclude,
					'orderby'    => 'name',
					'order'      => 'ASC',
					'number'     => knowx_kb_max_categories() * 5,
				)
			);
			$terms = is_wp_error( $all ) ? array() : array_values(
				array_filter(
					$all,
					static function ( $term ) {
						return 0 === (int) $term->parent;
					}
				)
			);

			// WordPress' stock "Uncategorized" category is a placeholder, never a product. A renamed default category is.
			$default = (int) get_option( 'default_category' );
			$stock   = sanitize_title( _x( 'Uncategorized', 'Default category slug' ) ); // phpcs:ignore WordPress.WP.I18n.MissingArgDomain -- core string.
			$terms   = array_values(
				array_filter(
					$terms,
					static function ( $term ) use ( $default, $stock ) {
						return ! ( (int) $term->term_id === $default && $term->slug === $stock );
					}
				)
			);

			$terms = array_slice( $terms, 0, knowx_kb_max_categories() );
			if ( empty( $terms ) ) {
				return array();
			}

			// One query for every product's meta instead of one per product.
			update_termmeta_cache( wp_list_pluck( $terms, 'term_id' ) );

			// Lowest "Product order" first, then by name (the list is already by name and usort is stable).
			usort(
				$terms,
				static function ( $a, $b ) {
					return absint( get_term_meta( $a->term_id, 'knowx_order', true ) ) <=> absint( get_term_meta( $b->term_id, 'knowx_order', true ) );
				}
			);

			// Sections of each product come from the same padded term list, so the cards need no extra query.
			$sections = array();
			foreach ( $all as $term ) {
				if ( (int) $term->parent > 0 ) {
					$sections[ (int) $term->parent ][] = array(
						'name'  => $term->name,
						'url'   => get_term_link( $term ),
						'count' => (int) $term->count,
					);
				}
			}

			$items = knowx_kb_map_terms( $terms );
			foreach ( $items as $index => $item ) {
				$items[ $index ]['sections'] = array_slice( $sections[ $item['id'] ] ?? array(), 0, 4 );
				$items[ $index ]['count']    = (int) $terms[ $index ]->count;
				$icon                        = (string) get_term_meta( $item['id'], 'knowx_icon', true );
				$accent                      = (string) get_term_meta( $item['id'], 'knowx_accent', true );
				if ( count( $terms ) > 1 ) {
					$looks  = knowx_default_product_looks();
					$icon   = '' !== $icon ? $icon : $looks['icons'][ $item['id'] % count( $looks['icons'] ) ];
					$accent = '' !== $accent ? $accent : $looks['accents'][ $item['id'] % count( $looks['accents'] ) ];
				}
				$items[ $index ]['start']  = (string) get_term_meta( $item['id'], 'knowx_start', true );
				$items[ $index ]['icon']   = $icon;
				$items[ $index ]['accent'] = $accent;
			}

			return $items;
		}
	);
}

/**
 * Id of the product the current request belongs to: the top-level ancestor of the category or article.
 *
 * @return int Category id, or 0 outside a product.
 */
function knowx_current_product_id() {
	$term_id = 0;

	if ( is_category() ) {
		$term_id = (int) get_queried_object_id();
	} elseif ( is_singular( 'post' ) ) {
		$categories = get_the_category( get_queried_object_id() );
		$term_id    = $categories ? (int) $categories[0]->term_id : 0;
	}

	if ( ! $term_id ) {
		return 0;
	}

	$ancestors = get_ancestors( $term_id, 'category', 'taxonomy' );

	return $ancestors ? (int) end( $ancestors ) : $term_id;
}

/**
 * The current product's data from knowx_products().
 *
 * @return array|null Product item, or null outside a product.
 */
function knowx_current_product() {
	$id = knowx_current_product_id();
	foreach ( $id ? knowx_products() : array() as $product ) {
		if ( $product['id'] === $id ) {
			return $product;
		}
	}

	return null;
}

/**
 * Text for an article count, such as "12 articles".
 *
 * @param int $count Number of articles.
 * @return string Label.
 */
function knowx_article_count_label( $count ) {
	/* translators: %s: number of articles. */
	return sprintf( _n( '%s article', '%s articles', (int) $count, 'knowx' ), number_format_i18n( (int) $count ) );
}

/**
 * Upper limit for articles listed in one product's tree.
 *
 * @return int Maximum number of articles.
 */
function knowx_tree_max_posts() {
	/**
	 * Filters how many articles one product's section tree lists. Sections link to their full archive.
	 *
	 * @param int $max Default 500.
	 */
	return max( 1, (int) apply_filters( 'knowx_tree_max_posts', 500 ) );
}

/**
 * Section tree of a product: sections (child categories) with their articles.
 *
 * Two bounded queries (all descendant terms, all articles) however large the product is.
 * Articles are ordered by the editor's Order field, then by title.
 *
 * @param int $product_id Top-level category id.
 * @return array Tree: 'root' => array( 'posts' => items, 'children' => ids ) and 'nodes' => id => node.
 */
function knowx_product_tree( $product_id ) {
	$product_id = (int) $product_id;

	return knowx_kb_cached(
		'tree-' . $product_id,
		static function () use ( $product_id ) {
			$terms = get_terms(
				array(
					'taxonomy'   => 'category',
					'child_of'   => $product_id,
					'hide_empty' => true,
					'orderby'    => 'name',
					'order'      => 'ASC',
					'number'     => knowx_kb_max_categories() * 5,
				)
			);

			$tree = array(
				'root'  => array(
					'posts'    => array(),
					'children' => array(),
				),
				'nodes' => array(),
			);
			foreach ( is_wp_error( $terms ) ? array() : $terms as $term ) {
				$tree['nodes'][ $term->term_id ] = array(
					'id'          => (int) $term->term_id,
					'name'        => $term->name,
					'url'         => get_term_link( $term ),
					'parent'      => (int) $term->parent,
					'count'       => (int) $term->count,
					'description' => $term->description,
					'posts'       => array(),
					'children'    => array(),
				);
			}
			foreach ( $tree['nodes'] as $id => $node ) {
				if ( isset( $tree['nodes'][ $node['parent'] ] ) ) {
					$tree['nodes'][ $node['parent'] ]['children'][] = $id;
				} else {
					$tree['root']['children'][] = $id;
				}
			}

			$posts = get_posts(
				array(
					'post_type'              => 'post',
					'numberposts'            => knowx_tree_max_posts(),
					'orderby'                => 'menu_order title',
					'order'                  => 'ASC',
					'ignore_sticky_posts'    => true,
					'no_found_rows'          => true,
					'update_post_meta_cache' => false,
					'update_post_term_cache' => true,
					'tax_query'              => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query -- one bounded, cached query per product.
						array(
							'taxonomy' => 'category',
							'field'    => 'term_id',
							'terms'    => $product_id,
						),
					),
				)
			);
			foreach ( $posts as $post_item ) {
				$item   = array(
					'id'    => (int) $post_item->ID,
					'title' => get_the_title( $post_item ),
					'url'   => get_permalink( $post_item ),
				);
				$parent = 0;
				foreach ( (array) get_the_terms( $post_item, 'category' ) as $term ) {
					if ( $term instanceof WP_Term && isset( $tree['nodes'][ $term->term_id ] ) ) {
						$parent = (int) $term->term_id;
						break;
					}
				}
				if ( $parent ) {
					$tree['nodes'][ $parent ]['posts'][] = $item;
				} else {
					$tree['root']['posts'][] = $item;
				}
			}

			return $tree;
		}
	);
}

/**
 * Articles of a tree in reading order, for previous and next links.
 *
 * @param array $tree Tree from knowx_product_tree().
 * @return array[] Article items.
 */
function knowx_tree_flat( $tree ) {
	$flat  = $tree['root']['posts'];
	$visit = static function ( $ids ) use ( &$visit, &$flat, $tree ) {
		foreach ( $ids as $id ) {
			$flat = array_merge( $flat, $tree['nodes'][ $id ]['posts'] );
			$visit( $tree['nodes'][ $id ]['children'] );
		}
	};
	$visit( $tree['root']['children'] );

	return $flat;
}

/**
 * Section ids that must be open: the current section and everything above it.
 *
 * @param array $tree    Tree from knowx_product_tree().
 * @param int   $post_id Current article, or 0.
 * @param int   $term_id Current category, or 0.
 * @return int[] Section ids.
 */
function knowx_tree_open_ids( $tree, $post_id, $term_id ) {
	if ( $post_id ) {
		foreach ( $tree['nodes'] as $id => $node ) {
			if ( in_array( $post_id, array_column( $node['posts'], 'id' ), true ) ) {
				$term_id = $id;
				break;
			}
		}
	}

	$open = array();
	while ( $term_id && isset( $tree['nodes'][ $term_id ] ) ) {
		$open[]  = $term_id;
		$term_id = $tree['nodes'][ $term_id ]['parent'];
	}

	return $open;
}

/**
 * Prints the section tree: collapsible sections (no JavaScript needed) listing their articles.
 *
 * @param array $tree    Tree from knowx_product_tree().
 * @param int   $post_id Current article, or 0.
 * @param int   $term_id Current category, or 0.
 */
function knowx_render_tree( $tree, $post_id = 0, $term_id = 0 ) {
	$open = knowx_tree_open_ids( $tree, (int) $post_id, (int) $term_id );

	$articles = static function ( $items ) use ( $post_id ) {
		foreach ( $items as $item ) {
			printf(
				'<li><a href="%s"%s>%s</a></li>',
				esc_url( $item['url'] ),
				$item['id'] === (int) $post_id ? ' aria-current="page"' : '',
				esc_html( $item['title'] )
			);
		}
	};

	$sections = static function ( $ids ) use ( &$sections, $articles, $tree, $open, $term_id ) {
		foreach ( $ids as $id ) {
			$node = $tree['nodes'][ $id ];
			printf( '<li><details%s><summary>%s</summary><ul>', in_array( $id, $open, true ) ? ' open' : '', esc_html( $node['name'] ) );
			printf( '<li><a href="%s"%s>%s</a></li>', esc_url( $node['url'] ), $id === (int) $term_id ? ' aria-current="page"' : '', esc_html__( 'All articles', 'knowx' ) );
			$articles( $node['posts'] );
			$sections( $node['children'] );
			echo '</ul></details></li>';
		}
	};

	echo '<ul class="knowx-tree-list">';
	$articles( $tree['root']['posts'] );
	$sections( $tree['root']['children'] );
	echo '</ul>';
}

/**
 * Contents items collected by knowx_add_heading_anchors() for the current article.
 *
 * @param array|null $set Items to store, or null to read.
 * @return array[] Items with id, text and level.
 */
function knowx_toc_items( $set = null ) {
	static $items = array();
	if ( null !== $set ) {
		$items = $set;
	}

	return $items;
}

/**
 * Gives h2 and h3 headings an id and a link to themselves, and records them for "On this page".
 *
 * @param string $content Post content.
 * @return string Content with anchors.
 */
function knowx_add_heading_anchors( $content ) {
	if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}

	$items = array();
	$used  = array();

	$content = (string) preg_replace_callback(
		'#<h([23])([^>]*)>(.*?)</h\1>#is',
		static function ( $match ) use ( &$items, &$used ) {
			$text = trim( wp_strip_all_tags( $match[3] ) );
			if ( '' === $text ) {
				return $match[0];
			}

			if ( preg_match( '/\sid=(["\'])(.*?)\1/', $match[2], $existing ) ) {
				$id    = $existing[2];
				$attrs = $match[2];
			} else {
				$base = sanitize_title( $text );
				$id   = '' !== $base ? $base : 'section';
				for ( $n = 2; in_array( $id, $used, true ); $n++ ) {
					$id = $base . '-' . $n;
				}
				$attrs = $match[2] . ' id="' . esc_attr( $id ) . '"';
			}
			$used[]  = $id;
			$items[] = array(
				'id'    => $id,
				'text'  => $text,
				'level' => (int) $match[1],
			);

			/* translators: %s: section heading. */
			$label = sprintf( __( 'Link to section: %s', 'knowx' ), $text );

			return sprintf( '<h%1$s%2$s>%3$s <a class="knowx-anchor" href="#%4$s" aria-label="%5$s"></a></h%1$s>', $match[1], $attrs, $match[3], esc_attr( $id ), esc_attr( $label ) );
		},
		$content
	);

	knowx_toc_items( $items );

	return $content;
}

/**
 * Prints the "On this page" list.
 *
 * @param array[] $items Items from knowx_toc_items().
 */
function knowx_render_toc( $items ) {
	echo '<ul class="knowx-toc-list">';
	foreach ( $items as $item ) {
		printf( '<li class="knowx-toc-l%d"><a href="#%s">%s</a></li>', (int) $item['level'], esc_attr( $item['id'] ), esc_html( $item['text'] ) );
	}
	echo '</ul>';
}

/**
 * The "applies to version" label of an article.
 *
 * @param int $post_id Post id.
 * @return string Version text, or an empty string.
 */
function knowx_article_version( $post_id ) {
	return (string) get_post_meta( (int) $post_id, 'knowx_version', true );
}

/**
 * Plain text of a parsed block and everything inside it.
 *
 * @param array $block Parsed block.
 * @return string Text with collapsed whitespace.
 */
function knowx_block_text( $block ) {
	$html = '';
	foreach ( (array) ( $block['innerContent'] ?? array() ) as $chunk ) {
		$html .= is_string( $chunk ) ? $chunk : '';
	}
	foreach ( (array) ( $block['innerBlocks'] ?? array() ) as $inner ) {
		$html .= ' ' . knowx_block_text( $inner );
	}

	return trim( (string) preg_replace( '/\s+/', ' ', html_entity_decode( strip_tags( $html ), ENT_QUOTES, 'UTF-8' ) ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.strip_tags_strip_tags -- pure helper, kept free of WordPress functions so it can be unit tested.
}

/**
 * Question and answer pairs from the core Details blocks of a parsed post.
 *
 * @param array $blocks Parsed blocks from parse_blocks().
 * @return array[] Items with question and answer.
 */
function knowx_faq_from_blocks( $blocks ) {
	$faq = array();
	foreach ( $blocks as $block ) {
		if ( 'core/details' === ( $block['blockName'] ?? '' ) ) {
			$first = (string) ( $block['innerContent'][0] ?? '' );
			if ( preg_match( '#<summary[^>]*>(.*?)</summary>#is', $first, $found ) ) {
				$question = knowx_block_text( array( 'innerContent' => array( $found[1] ) ) );
				$answer   = knowx_block_text( array( 'innerBlocks' => $block['innerBlocks'] ?? array() ) );
				if ( '' !== $question && '' !== $answer ) {
					$faq[] = array(
						'question' => $question,
						'answer'   => $answer,
					);
				}
			}
		}
		if ( ! empty( $block['innerBlocks'] ) ) {
			$faq = array_merge( $faq, knowx_faq_from_blocks( $block['innerBlocks'] ) );
		}
	}

	return $faq;
}

/**
 * Structured data (schema.org) for the current page, as a JSON-LD graph.
 *
 * Home: WebSite with a search action. Article: TechArticle and BreadcrumbList. Any page with two or more
 * Details blocks: FAQPage.
 *
 * @return array[] Graph nodes; empty when there is nothing to describe.
 */
function knowx_schema_graph() {
	$graph = array();

	if ( is_front_page() ) {
		$graph[] = array(
			'@type'           => 'WebSite',
			'@id'             => home_url( '/#website' ),
			'url'             => home_url( '/' ),
			'name'            => get_bloginfo( 'name' ),
			'potentialAction' => array(
				'@type'       => 'SearchAction',
				'target'      => home_url( '/?s={search_term_string}' ),
				'query-input' => 'required name=search_term_string',
			),
		);
	}

	if ( is_singular() ) {
		$post_id = get_queried_object_id();
		$url     = get_permalink( $post_id );

		if ( is_singular( 'post' ) ) {
			$product = knowx_current_product();
			$article = array(
				'@type'            => 'TechArticle',
				'@id'              => $url . '#article',
				'mainEntityOfPage' => $url,
				'headline'         => get_the_title( $post_id ),
				'description'      => wp_strip_all_tags( get_the_excerpt( $post_id ) ),
				'datePublished'    => get_the_date( 'c', $post_id ),
				'dateModified'     => get_the_modified_date( 'c', $post_id ),
				'author'           => array(
					'@type' => 'Person',
					'name'  => get_the_author_meta( 'display_name', (int) get_post_field( 'post_author', $post_id ) ),
				),
			);
			if ( $product ) {
				$article['articleSection'] = $product['name'];
			}
			$graph[] = $article;
		}

		$crumbs   = array();
		$position = 0;
		foreach ( knowx_get_breadcrumb_items() as $item ) {
			$crumb    = array(
				'@type'    => 'ListItem',
				'position' => ++$position,
				'name'     => $item['label'],
			);
			$crumbs[] = $crumb + array( 'item' => ! empty( $item['url'] ) ? $item['url'] : $url );
		}
		if ( $crumbs ) {
			$graph[] = array(
				'@type'           => 'BreadcrumbList',
				'itemListElement' => $crumbs,
			);
		}

		$faq = knowx_faq_from_blocks( parse_blocks( (string) get_post_field( 'post_content', $post_id ) ) );
		if ( count( $faq ) >= 2 ) {
			$entities = array();
			foreach ( $faq as $pair ) {
				$entities[] = array(
					'@type'          => 'Question',
					'name'           => $pair['question'],
					'acceptedAnswer' => array(
						'@type' => 'Answer',
						'text'  => $pair['answer'],
					),
				);
			}
			$graph[] = array(
				'@type'      => 'FAQPage',
				'mainEntity' => $entities,
			);
		}
	}

	/**
	 * Filters the schema.org graph printed in the page head. Return an empty array to print nothing.
	 *
	 * @param array[] $graph Graph nodes.
	 */
	return (array) apply_filters( 'knowx_schema_graph', $graph );
}

/**
 * Text for the meta description of the current page.
 *
 * Article or page: its excerpt. Category: its description. Front page: the page excerpt or the site tagline.
 * Empty when the page has nothing to describe, or when an SEO plugin is active and prints its own.
 *
 * @return string Plain text of at most 160 characters.
 */
function knowx_meta_description() {
	if ( defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'AIOSEO_VERSION' ) || defined( 'SEOPRESS_VERSION' ) || defined( 'THE_SEO_FRAMEWORK_VERSION' ) ) {
		return '';
	}

	$text = '';
	if ( is_singular() ) {
		$text = get_the_excerpt( get_queried_object_id() );
	} elseif ( is_category() ) {
		$text = term_description();
	}
	if ( '' === trim( wp_strip_all_tags( $text ) ) && is_front_page() ) {
		$text = get_bloginfo( 'description', 'display' );
	}

	$text = trim( (string) preg_replace( '/\s+/', ' ', wp_strip_all_tags( $text ) ) );

	/**
	 * Filters the meta description. Return an empty string to print none.
	 *
	 * @param string $text Description.
	 */
	return (string) apply_filters( 'knowx_meta_description', wp_html_excerpt( $text, 160, '...' ) );
}
