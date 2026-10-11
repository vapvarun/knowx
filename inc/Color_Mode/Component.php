<?php
/**
 * KnowX\KnowX\Color_Mode\Component class
 *
 * @package knowx
 */

namespace KnowX\KnowX\Color_Mode;

use KnowX\KnowX\Component_Interface;
use KnowX\KnowX\Templating_Component_Interface;

use function add_action;
use function esc_attr;
use function esc_attr__;
use function esc_html__;
use function get_theme_file_path;
use function get_theme_file_uri;
use function KnowX\KnowX\knowx;
use function wp_enqueue_script;
use function wp_json_encode;
use function wp_script_add_data;

/**
 * Light, dark and automatic colour modes with an optional visitor toggle.
 *
 * The palettes live in CSS; this component only sets the `data-color-mode`
 * attribute before first paint (no flash) and prints the toggle button.
 */
class Component implements Component_Interface, Templating_Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'color_mode';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'wp_head', array( $this, 'action_print_mode_script' ), 1 );
		add_action( 'wp_enqueue_scripts', array( $this, 'action_enqueue_toggle_script' ) );
	}

	/**
	 * Gets template tags to expose as methods on the knowx() template tags object.
	 *
	 * @return array Associative array of method names and callables.
	 */
	public function template_tags(): array {
		return array(
			'color_mode_toggle' => array( $this, 'display_toggle' ),
		);
	}

	/**
	 * Default mode chosen in the Customizer.
	 *
	 * @return string One of light, dark or auto.
	 */
	protected function default_mode(): string {
		$mode = (string) knowx_mod( 'site_color_mode' );

		return in_array( $mode, array( 'light', 'dark', 'auto' ), true ) ? $mode : 'light';
	}

	/**
	 * Whether the visitor toggle is switched on.
	 *
	 * @return bool True when the toggle is shown.
	 */
	protected function toggle_enabled(): bool {
		return knowx_is_truthy( knowx_mod( 'site_color_mode_toggle_show' ) );
	}

	/**
	 * Sets the colour mode on the root element before the page paints.
	 */
	public function action_print_mode_script() {
		$default = $this->default_mode();

		if ( 'light' === $default && ! $this->toggle_enabled() ) {
			return;
		}

		$stored = $this->toggle_enabled()
			? "var m=localStorage.getItem('knowx-color-mode');if(m==='light'||m==='dark'||m==='auto'){mode=m;}"
			: '';

		printf(
			"<script>(function(){var mode=%s;try{%s}catch(e){}document.documentElement.setAttribute('data-color-mode',mode);})();</script>\n", // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON encoded constant and fixed script.
			wp_json_encode( $default ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			$stored // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);
	}

	/**
	 * Loads the toggle script only when the toggle is shown.
	 */
	public function action_enqueue_toggle_script() {
		if ( ! $this->toggle_enabled() ) {
			return;
		}

		wp_enqueue_script(
			'knowx-color-mode',
			get_theme_file_uri( '/assets/js/color-mode-toggle.min.js' ),
			array(),
			knowx()->get_asset_version( get_theme_file_path( '/assets/js/color-mode-toggle.min.js' ) ),
			true
		);
		wp_script_add_data( 'knowx-color-mode', 'strategy', 'defer' );
	}

	/**
	 * Prints the toggle button.
	 */
	public function display_toggle() {
		if ( ! $this->toggle_enabled() ) {
			return;
		}

		$labels = array(
			'light' => esc_html__( 'Light mode. Switch to dark mode.', 'knowx' ),
			'dark'  => esc_html__( 'Dark mode. Switch to automatic mode.', 'knowx' ),
			'auto'  => esc_html__( 'Automatic mode. Switch to light mode.', 'knowx' ),
		);
		?>
		<button type="button" class="color-mode-toggle" aria-label="<?php esc_attr_e( 'Switch color mode', 'knowx' ); ?>" data-labels="<?php echo esc_attr( wp_json_encode( $labels ) ); ?>">
			<svg class="color-mode-icon color-mode-icon--light" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
			<svg class="color-mode-icon color-mode-icon--dark" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/></svg>
			<svg class="color-mode-icon color-mode-icon--auto" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect x="3" y="4" width="18" height="12" rx="2"/><path d="M8 20h8M12 16v4"/></svg>
		</button>
		<?php
	}
}
