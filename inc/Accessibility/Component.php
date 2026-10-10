<?php
/**
 * KnowX\KnowX\Accessibility\Component class
 *
 * @package knowx
 */

namespace KnowX\KnowX\Accessibility;

use KnowX\KnowX\Component_Interface;
use WP_Post;

use function KnowX\KnowX\knowx;
use function add_action;
use function add_filter;
use function wp_enqueue_script;
use function get_theme_file_uri;
use function get_theme_file_path;
use function wp_script_add_data;
use function wp_localize_script;

/**
 * Class for improving accessibility among various core features.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'accessibility';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'wp_enqueue_scripts', array( $this, 'action_enqueue_navigation_script' ) );
		add_action( 'wp_head', array( $this, 'action_remove_no_js_class' ) );
	}

	/**
	 * Enqueues a script that improves navigation menu accessibility.
	 */
	public function action_enqueue_navigation_script() {
		// Enqueue the navigation script.
		wp_enqueue_script(
			'knowx-navigation',
			get_theme_file_uri( '/assets/js/navigation.min.js' ),
			array(),
			knowx()->get_asset_version( get_theme_file_path( '/assets/js/navigation.min.js' ) ),
			true
		);
		wp_script_add_data( 'knowx-navigation', 'async', true );
		wp_script_add_data( 'knowx-navigation', 'precache', true );
		wp_localize_script(
			'knowx-navigation',
			'knowxScreenReaderText',
			array(
				'expand'   => esc_attr__( 'Expand child menu', 'knowx' ),
				'collapse' => esc_attr__( 'Collapse child menu', 'knowx' ),
			)
		);
	}

	/**
	 * Replaces the `no-js` class on the root element once JavaScript is available.
	 */
	public function action_remove_no_js_class() {
		?>
		<script>document.documentElement.classList.remove( 'no-js' );</script>
		<?php
	}
}
