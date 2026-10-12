<?php
/**
 * KnowX Output_Builder_Tests integration test class
 *
 * @package knowx
 */

namespace KnowX\KnowX\Tests\Integration;

use KnowX\KnowX\Customizer_Framework\Output_Builder;
use KnowX\KnowX\Tests\Framework\Integration_Test_Case;

/**
 * Saved Customizer values reach an inline style tag, so they must not be able to leave their rule.
 *
 * @group customizer
 */
class Output_Builder_Tests extends Integration_Test_Case {

	/**
	 * A typography value becomes one declaration block with a full font stack.
	 */
	public function test_typography_becomes_a_declaration_block() {
		set_theme_mod(
			'knowx_test_type',
			array(
				'font-family' => 'Open Sans',
				'font-size'   => '17px',
			)
		);

		$css = Output_Builder::collect(
			array(
				array(
					'settings' => 'knowx_test_type',
					'_type'    => 'typography',
					'output'   => array( array( 'element' => 'body' ) ),
				),
			)
		);

		remove_theme_mod( 'knowx_test_type' );

		$this->assertStringContainsString( 'body{', $css );
		$this->assertStringContainsString( 'font-family:"Open Sans"', $css );
		$this->assertStringContainsString( 'font-size:1.0625rem;', $css );
	}

	/**
	 * Angle brackets are removed, so a saved value cannot close the style tag.
	 */
	public function test_values_cannot_close_the_style_tag() {
		set_theme_mod( 'knowx_test_colour', '#fff</style><script>alert(1)</script>' );

		$css = Output_Builder::collect(
			array(
				array(
					'settings' => 'knowx_test_colour',
					'_type'    => 'color',
					'output'   => array(
						array(
							'element'  => '.x',
							'property' => 'color',
						),
					),
				),
			)
		);

		remove_theme_mod( 'knowx_test_colour' );

		$this->assertStringNotContainsString( '<', $css );
		$this->assertStringNotContainsString( '>', $css );
	}

	/**
	 * A setting with no saved value and no default prints nothing.
	 */
	public function test_an_empty_value_prints_nothing() {
		$css = Output_Builder::collect(
			array(
				array(
					'settings' => 'knowx_test_unset',
					'_type'    => 'color',
					'output'   => array(
						array(
							'element'  => '.x',
							'property' => 'color',
						),
					),
				),
			)
		);

		$this->assertSame( '', $css );
	}
}
