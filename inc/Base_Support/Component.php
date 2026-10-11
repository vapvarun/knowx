<?php
/**
 * KnowX\KnowX\Base_Support\Component class
 *
 * @package knowx
 */

namespace KnowX\KnowX\Base_Support;

use KnowX\KnowX\Component_Interface;
use function add_action;
use function add_filter;
use function add_theme_support;
use function is_singular;
use function pings_open;
use function esc_url;
use function get_bloginfo;

/**
 * Class for adding basic theme support, most of which is mandatory to be implemented by all themes.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'base_support';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'init', array( $this, 'action_title_tag_support' ), -999 );
		add_action( 'after_setup_theme', array( $this, 'action_essential_theme_support' ), 1 );
		add_action( 'wp_head', array( $this, 'action_add_pingback_header' ) );
		add_filter( 'body_class', array( $this, 'filter_body_classes_add_hfeed' ) );
		add_filter( 'embed_defaults', array( $this, 'filter_embed_dimensions' ) );
		add_action( 'init', array( $this, 'action_register_block_styles' ) );
	}

	/**
	 * Adds title-tag theme support very early.
	 */
	public function action_title_tag_support() {
		add_theme_support( 'title-tag' );
	}

	/**
	 * Adds theme support for essential features.
	 */
	public function action_essential_theme_support() {
		// Add default RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// Ensure WordPress theme features render in HTML5 markup.
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		// Add support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Add support for responsive embedded content.
		add_theme_support( 'responsive-embeds' );
	}

	/**
	 * Adds a pingback url auto-discovery header for singularly identifiable articles.
	 */
	public function action_add_pingback_header() {
		if ( is_singular() && pings_open() ) {
			echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
		}
	}

	/**
	 * Adds a 'hfeed' class to the array of body classes for non-singular pages.
	 *
	 * @param array $classes Classes for the body element.
	 * @return array Filtered body classes.
	 */
	public function filter_body_classes_add_hfeed( array $classes ): array {
		if ( ! is_singular() ) {
			$classes[] = 'hfeed';
		}

		return $classes;
	}

	/**
	 * Sets the embed width in pixels, based on the theme's design and stylesheet.
	 *
	 * @param array $dimensions An array of embed width and height values in pixels (in that order).
	 * @return array Filtered dimensions array.
	 */
	public function filter_embed_dimensions( array $dimensions ): array {
		$dimensions['width'] = 720;
		return $dimensions;
	}

	/**
	 * Register block styles.
	 */
	public function action_register_block_styles() {
		$callouts = array(
			'knowx-note'    => __( 'Note', 'knowx' ),
			'knowx-tip'     => __( 'Tip', 'knowx' ),
			'knowx-warning' => __( 'Warning', 'knowx' ),
			'knowx-danger'  => __( 'Danger', 'knowx' ),
		);
		foreach ( $callouts as $name => $label ) {
			register_block_style(
				'core/group',
				array(
					'name'  => $name,
					'label' => $label,
				)
			);
		}

		register_block_style(
			'core/image',
			array(
				'name'  => 'knowx-border',
				'label' => esc_html__( 'Borders', 'knowx' ),
			)
		);
	}
}
