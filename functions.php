<?php
/**
 * KnowX functions and definitions.
 *
 * @see https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package knowx
 */

// Include Kirki.
require get_template_directory() . '/external/require_plugins.php';
require_once get_template_directory() . '/external/kirki-utils.php';

// Setup autoloader (via Composer or custom).
if ( file_exists( get_template_directory() . '/vendor/autoload.php' ) ) {
	require get_template_directory() . '/vendor/autoload.php';
} else {
	/**
	 * Custom autoloader function for theme classes.
	 *
	 * @param string $class_name Class name to load.
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

// Load theme extra function.
require get_template_directory() . '/inc/extra.php';

// Load the Knowledge Base data helpers.
require get_template_directory() . '/inc/knowledge-base.php';

// Load the breadcrumb trail.
require get_template_directory() . '/inc/breadcrumbs.php';

// Custom WP Login Form.
require get_template_directory() . '/inc/login.php';
