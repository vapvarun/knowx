<?php
/**
 * KnowX unit tests bootstrap script.
 *
 * @package knowx
 */

// Run common tests script.
require __DIR__ . '/bootstrap-common.php';

// WordPress time constants used by the cache helpers.
if ( ! defined( 'DAY_IN_SECONDS' ) ) {
	define( 'DAY_IN_SECONDS', 86400 );
}

// Theme files bail out when ABSPATH is missing; unit tests do not load WordPress.
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', TESTS_THEME_DIR . '/' );
}
