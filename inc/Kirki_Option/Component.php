<?php
/**
 * KnowX\KnowX\Typography_Options\Component class
 *
 * @package knowx
 */

namespace KnowX\KnowX\Kirki_Option;

use function add_action;
use function add_filter;
use KnowX\KnowX\Component_Interface;

/**
 * Class for adding custom background support.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'kirki_option';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_filter( 'body_class', [ $this, 'site_width_body_classes' ] );
		add_filter( 'body_class', [ $this, 'site_sticky_sidebar_body_classes' ] );

		/* Load required template */
		if ( class_exists( 'Kirki' ) ) {
			add_action( 'wp_head', [ $this, 'load_customizer_styles' ] );

			require_once __dir__ . '/Options.php';
			require_once __dir__ . '/Styles.php';
		}
	}

	/**
	 * Load Customizer Styles
	 */
	public function load_customizer_styles() {

		echo '<style id="knowx-customizer-inline-styles" type="text/css">';
		$output = knowx_get_customizer_style();
		echo knowx_css_compress( $output );
		echo '</style>' . "\n";

	}

	public function site_width_body_classes( array $classes ) : array {
		$classes[] = 'layout-' . get_theme_mod( 'site_layout', knowx_defaults( 'site-layout' ) );

		return $classes;
	}

	public function site_sticky_sidebar_body_classes( array $classes ) : array {

		$sticky_sidebar = get_theme_mod( 'sticky_sidebar_option', knowx_defaults( 'sticky-sidebar' ) );
		if ( $sticky_sidebar ) {
			$classes[] = 'sticky-sidebar-enable';
		}

		return $classes;
	}
}
