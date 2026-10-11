<?php
/**
 * KnowX\KnowX\Custom_Header\Component class
 *
 * @package knowx
 */

namespace KnowX\KnowX\Custom_Header;

use KnowX\KnowX\Component_Interface;
use function add_action;
use function add_theme_support;
use function apply_filters;

/**
 * Class for adding custom header support.
 *
 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'custom_header';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'after_setup_theme', array( $this, 'action_add_custom_header_support' ) );
	}

	/**
	 * Adds support for the Custom Header feature.
	 */
	public function action_add_custom_header_support() {
		add_theme_support(
			'custom-header',
			/**
			 * Filters the arguments passed to add_theme_support( 'custom-header' ).
			 *
			 * @param array $args Theme support arguments.
			 */
			apply_filters(
				'knowx_custom_header_args',
				array(
					'default-image'      => '',
					// The site title colour is set with the Site Title typography, so there is no second control for it.
					'header-text'        => false,
					'width'              => 1600,
					'height'             => 250,
					'flex-height'        => true,
				)
			)
		);
	}
}
