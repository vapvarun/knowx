<?php
/**
 * Tests for the Customizer sanitisers and the active callback compiler.
 *
 * @package knowx
 */

namespace KnowX\KnowX\Tests\Unit;

use Brain\Monkey\Functions;
use KnowX\KnowX\Customizer_Framework\Active_Callback;
use KnowX\KnowX\Customizer_Framework\Field;
use KnowX\KnowX\Customizer_Framework\Output_Builder;
use KnowX\KnowX\Tests\Framework\Unit_Test_Case;

require_once TESTS_THEME_DIR . '/inc/Customizer_Framework/Component.php';
require_once TESTS_THEME_DIR . '/inc/Customizer_Framework/Field.php';
require_once TESTS_THEME_DIR . '/inc/Customizer_Framework/Active_Callback.php';
require_once TESTS_THEME_DIR . '/inc/Customizer_Framework/Output_Builder.php';
require_once TESTS_THEME_DIR . '/inc/helpers.php';

/**
 * Sanitiser and condition tests: these turn user input into saved settings and CSS.
 */
class Customizer_Framework_Tests extends Unit_Test_Case {

	/**
	 * Every shape of a switch value becomes 0 or 1.
	 */
	public function test_switch_values_become_zero_or_one() {
		foreach ( array( true, 1, '1', 'on', 'yes', 'true', 'ON' ) as $on ) {
			$this->assertSame( 1, Field::sanitize_bool_int( $on ), var_export( $on, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_var_export -- test code, not shipped.
		}
		foreach ( array( false, 0, '0', '', null, 'off', 'no', 'junk' ) as $off ) {
			$this->assertSame( 0, Field::sanitize_bool_int( $off ), var_export( $off, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_var_export -- test code, not shipped.
		}
	}

	/**
	 * Colours keep valid hex, rgb and hsl values and drop anything that could break out of a CSS rule.
	 */
	public function test_colours_accept_only_colour_syntax() {
		$this->assertSame( '#aa2255', Field::sanitize_color_alpha( ' #aa2255 ' ) );
		$this->assertSame( 'rgba(10, 20, 30, 0.5)', Field::sanitize_color_alpha( 'rgba(10, 20, 30, 0.5)' ) );
		$this->assertSame( 'hsl(120, 50%, 40%)', Field::sanitize_color_alpha( 'hsl(120, 50%, 40%)' ) );
		$this->assertSame( '', Field::sanitize_color_alpha( 'red;}body{display:none' ) );
		$this->assertSame( '', Field::sanitize_color_alpha( '#12' ) );
		$this->assertSame( '', Field::sanitize_color_alpha( 'url(javascript:alert(1))' ) );
	}

	/**
	 * Dimensions are a number with an optional known unit.
	 */
	public function test_dimensions_accept_a_number_and_a_unit() {
		$this->assertSame( '1170px', Field::sanitize_dimension( '1170px' ) );
		$this->assertSame( '1.5rem', Field::sanitize_dimension( '1.5rem' ) );
		$this->assertSame( '', Field::sanitize_dimension( '12px;color:red' ) );
		$this->assertSame( '', Field::sanitize_dimension( 'calc(1px)' ) );
	}

	/**
	 * Typography keeps only the whitelisted keys.
	 */
	public function test_typography_keeps_only_known_keys() {
		Functions\when( 'sanitize_text_field' )->returnArg();

		$clean = Field::sanitize_typography(
			array(
				'font-family' => 'Open Sans',
				'font-size'   => '16px',
				'evil'        => 'x',
			)
		);

		$this->assertSame(
			array(
				'font-family' => 'Open Sans',
				'font-size'   => '16px',
			),
			$clean
		);
		$this->assertSame( array(), Field::sanitize_typography( 'not an array' ) );
	}

	/**
	 * Content Width stays between 1000 and 1920 pixels, and an empty value is the default.
	 */
	public function test_content_width_stays_in_range() {
		$this->assertSame( '1170px', knowx_sanitize_container_width( '1170px' ) );
		$this->assertSame( '1000px', knowx_sanitize_container_width( '700px' ) );
		$this->assertSame( '1920px', knowx_sanitize_container_width( '5000' ) );
		$this->assertSame( '1170px', knowx_sanitize_container_width( '' ) );
		$this->assertSame( '1170px', knowx_sanitize_container_width( 'wide' ) );
	}

	/**
	 * Typography CSS: sizes follow the browser font size, a font the theme cannot load and a 1.x colour are left out.
	 */
	public function test_typography_css_skips_what_the_theme_cannot_deliver() {
		Functions\when( 'wp_get_global_settings' )->justReturn(
			array( 'theme' => array( array( 'name' => 'Open Sans' ) ) )
		);
		$field = static function ( array $value ) {
			Functions\when( 'get_theme_mod' )->justReturn( $value );

			return Output_Builder::collect(
				array(
					array(
						'_type'    => 'typography',
						'settings' => 'typography_option',
						'output'   => array( array( 'element' => 'body' ) ),
					),
				)
			);
		};

		$this->assertSame(
			'body{font-family:"Open Sans", system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;font-size:1.125rem;}',
			$field(
				array(
					'font-family' => 'Open Sans',
					'font-size'   => '18px',
				)
			)
		);
		$this->assertSame(
			'body{line-height:2.1;}',
			$field(
				array(
					'font-family' => 'Playfair Display',
					'line-height' => '2.1',
					'color'       => '#ff0000',
				)
			)
		);
	}

	/**
	 * A saved "on" matches a condition written as true, and the other way round.
	 */
	public function test_conditions_compare_switches_by_meaning() {
		Functions\when( 'get_theme_mod' )->alias(
			static function ( $id ) {
				return array(
					'legacy_on' => 'on',
					'zero'      => 0,
					'layout'    => 'wide',
				)[ $id ] ?? null;
			}
		);

		$this->assertTrue(
			Active_Callback::compile(
				array(
					array(
						'setting'  => 'legacy_on',
						'operator' => '==',
						'value'    => true,
					),
				)
			)()
		); // phpcs:ignore Universal.Arrays.MixedKeyedUnkeyedArray -- test code, not shipped.
		$this->assertTrue(
			Active_Callback::compile(
				array(
					array(
						'setting'  => 'zero',
						'operator' => '==',
						'value'    => false,
					),
				)
			)()
		);
		$this->assertFalse(
			Active_Callback::compile(
				array(
					array(
						'setting'  => 'zero',
						'operator' => '==',
						'value'    => true,
					),
				)
			)()
		);
		$this->assertTrue(
			Active_Callback::compile(
				array(
					array(
						'setting'  => 'layout',
						'operator' => '!=',
						'value'    => 'boxed',
					),
				)
			)()
		);
		$this->assertTrue( Active_Callback::compile( array() )() );
	}
}
