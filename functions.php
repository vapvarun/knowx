<?php
/**
 * KnowX functions and definitions.
 *
 * @see https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package knowx
 */

// Include Kirki
require get_template_directory() . '/external/require_plugins.php';
require_once get_template_directory() . '/external/kirki-utils.php';

// Setup autoloader (via Composer or custom).
if ( file_exists( get_template_directory() . '/vendor/autoload.php' ) ) {
	require get_template_directory() . '/vendor/autoload.php';
} else {
	/**
	 * Custom autoloader function for theme classes.
	 *
	 * @param string $class_name class name to load
	 *
	 * @return bool true if the class was loaded, false otherwise
	 */
	function _knowx_autoload( $class_name ) {
		$namespace = 'KnowX\KnowX';

		if ( strpos( $class_name, $namespace . '\\' ) !== 0 ) {
			return false;
		}

		$parts = explode( '\\', substr( $class_name, strlen( $namespace . '\\' ) ) );

		$path = get_template_directory() . '/inc';
		foreach ( $parts as $part ) {
			$path .= '/' . $part;
		}
		$path .= '.php';

		if ( ! file_exists( $path ) ) {
			return false;
		}

		require_once $path;

		return true;
	}
	spl_autoload_register( '_knowx_autoload' );
}

// Load the `knowx()` entry point function.
require get_template_directory() . '/inc/functions.php';

// Initialize the theme.
call_user_func( 'KnowX\KnowX\knowx' );

// Require plugin.php to use is_plugin_active() below.
if ( ! function_exists( 'is_plugin_active' ) ) {
	include_once ABSPATH . 'wp-admin/includes/plugin.php';
}

// Load webfont loader file.
require get_template_directory() . '/inc/Webfont/class-knowx-webfont-loader.php';

// Load theme extra function.
require get_template_directory() . '/inc/extra.php';

// Load theme category widget.
require get_template_directory() . '/inc/widgets/category-widget.php';

// Load theme breadcrubms function.
require get_template_directory() . '/inc/class-knowx-breadcrubms.php';

// Custom WP Login Form.
require get_template_directory() . '/inc/login.php';
