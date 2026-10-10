<?php
/**
 * Tests for the theme setting accessor.
 *
 * @package knowx
 */

namespace KnowX\KnowX\Tests\Unit;

use Brain\Monkey\Functions;
use KnowX\KnowX\Customizer_Framework\Component;
use KnowX\KnowX\Tests\Framework\Unit_Test_Case;

require_once TESTS_THEME_DIR . '/inc/Customizer_Framework/Component.php';
require_once TESTS_THEME_DIR . '/inc/helpers.php';

/**
 * Setting accessor tests.
 */
class Settings_Tests extends Unit_Test_Case {

	/**
	 * A setting without a saved value returns the default declared on its field.
	 */
	public function test_field_default_is_the_fallback() {
		Component::register_field(
			'text',
			array(
				'settings' => 'knowx_test_setting',
				'default'  => 'declared-default',
			)
		);
		Functions\when( 'get_theme_mod' )->alias(
			function ( $id, $fallback = false ) {
				return $fallback;
			}
		);

		$this->assertSame( 'declared-default', knowx_mod( 'knowx_test_setting' ) );
	}

	/**
	 * Switch values saved in any historical shape read the same way.
	 *
	 * @dataProvider truthy_values
	 *
	 * @param mixed $value    Stored value.
	 * @param bool  $expected Expected result.
	 */
	public function test_truthiness( $value, $expected ) {
		$this->assertSame( $expected, knowx_is_truthy( $value ) );
	}

	/**
	 * Values for test_truthiness().
	 *
	 * @return array[] Value and expected result pairs.
	 */
	public function truthy_values() {
		return array(
			array( 1, true ),
			array( '1', true ),
			array( 'on', true ),
			array( 'Yes', true ),
			array( 0, false ),
			array( '0', false ),
			array( '', false ),
			array( 'off', false ),
			array( null, false ),
		);
	}
}
