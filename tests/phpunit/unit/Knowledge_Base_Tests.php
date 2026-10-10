<?php
/**
 * Tests for the Knowledge Base data helpers.
 *
 * @package knowx
 */

namespace KnowX\KnowX\Tests\Unit;

use Brain\Monkey\Functions;
use KnowX\KnowX\Tests\Framework\Unit_Test_Case;

require_once TESTS_THEME_DIR . '/inc/helpers.php';
require_once TESTS_THEME_DIR . '/inc/knowledge-base.php';

/**
 * Knowledge Base helper tests.
 */
class Knowledge_Base_Tests extends Unit_Test_Case {

	/**
	 * Comma separated ids are parsed to unique-safe integers and junk is dropped.
	 */
	public function test_excluded_ids_are_parsed() {
		Functions\when( 'get_theme_mod' )->justReturn( ' 4, 7 ,abc,,0,12 ' );
		Functions\when( 'absint' )->alias(
			function ( $value ) {
				return abs( (int) $value );
			}
		);

		$this->assertSame( array( 4, 7, 12 ), knowx_kb_excluded_ids() );
	}

	/**
	 * An empty setting excludes nothing.
	 */
	public function test_no_exclusion_by_default() {
		Functions\when( 'get_theme_mod' )->justReturn( '' );
		Functions\when( 'absint' )->alias(
			function ( $value ) {
				return abs( (int) $value );
			}
		);

		$this->assertSame( array(), knowx_kb_excluded_ids() );
	}

	/**
	 * The category cap is at least one even when a filter returns nonsense.
	 */
	public function test_category_cap_is_never_below_one() {
		Functions\when( 'apply_filters' )->justReturn( -5 );

		$this->assertSame( 1, knowx_kb_max_categories() );
	}

	/**
	 * Cached data is returned without calling the builder again.
	 */
	public function test_cached_data_skips_the_builder() {
		Functions\when( 'wp_cache_get_last_changed' )->justReturn( '1' );
		Functions\when( 'get_transient' )->justReturn( array( 'cached' ) );

		$result = knowx_kb_cached(
			'k',
			function () {
				$this->fail( 'builder must not run on a cache hit' );
			}
		);

		$this->assertSame( array( 'cached' ), $result );
	}
}
