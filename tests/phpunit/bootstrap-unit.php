<?php
/**
 * KnowX unit tests bootstrap script.
 *
 * @package knowx
 */

// Run common tests script.
require __DIR__ . '/bootstrap-common.php';

// Theme files bail out when ABSPATH is missing; unit tests do not load WordPress.
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', TESTS_THEME_DIR . '/' );
}
