<?php
/**
 * Tests for the colour tokens.
 *
 * @package knowx
 */

namespace KnowX\KnowX\Tests\Unit;

use KnowX\KnowX\Component_Interface;
use KnowX\KnowX\Tests\Framework\Unit_Test_Case;
use KnowX\KnowX\Tokens\Component;

require_once TESTS_THEME_DIR . '/inc/Component_Interface.php';
require_once TESTS_THEME_DIR . '/inc/Tokens/Component.php';

/**
 * Token tests.
 */
class Tokens_Tests extends Unit_Test_Case {

	/**
	 * Labels on light brand colours are dark and on dark brand colours are white.
	 */
	public function test_on_color_picks_a_readable_label() {
		$this->assertSame( '#111', Component::on_color( '#03a9f4' ) );
		$this->assertSame( '#111', Component::on_color( '#f1c40f' ) );
		$this->assertSame( '#fff', Component::on_color( '#7b2cbf' ) );
		$this->assertSame( '#fff', Component::on_color( 'rgb(20, 20, 20)' ) );
		$this->assertSame( '#fff', Component::on_color( 'not-a-color' ) );
	}

	/**
	 * Link colours reach 4.5:1 on the light page background.
	 */
	public function test_link_colors_meet_contrast() {
		foreach ( array( '#03a9f4', '#f1c40f', '#ffffff', '#7b2cbf' ) as $brand ) {
			$link = Component::link_colors( $brand );
			$this->assertCount( 2, $link, $brand );

			preg_match( '/rgb\((\d+),(\d+),(\d+)\)/', $link[0], $m );
			$channels = array_map(
				static function ( $c ) {
					$c = $c / 255;
					return $c <= 0.03928 ? $c / 12.92 : pow( ( $c + 0.055 ) / 1.055, 2.4 );
				},
				array( (int) $m[1], (int) $m[2], (int) $m[3] )
			);
			$luminance = 0.2126 * $channels[0] + 0.7152 * $channels[1] + 0.0722 * $channels[2];
			$page      = 0.9302; // Luminance of #f7f7f9.

			$this->assertGreaterThanOrEqual( 4.5, ( $page + 0.05 ) / ( $luminance + 0.05 ), $brand );
		}
	}

	/**
	 * Only safe colour syntax is printed into CSS.
	 */
	public function test_sanitize_color() {
		$this->assertSame( '#03a9f4', Component::sanitize_color( ' #03a9f4 ' ) );
		$this->assertSame( 'rgba(0, 0, 0, .5)', Component::sanitize_color( 'rgba(0, 0, 0, .5)' ) );
		$this->assertSame( '', Component::sanitize_color( 'red;}body{display:none' ) );
		$this->assertSame( '', Component::sanitize_color( 'url(javascript:alert(1))' ) );
	}

	/**
	 * A scheme slug is read from styles/<slug>.json and nothing else.
	 */
	public function test_scheme_palette() {
		\Brain\Monkey\Functions\when( 'get_template_directory' )->justReturn( TESTS_THEME_DIR );
		\Brain\Monkey\Functions\when( 'wp_json_file_decode' )->alias(
			static function ( $file ) {
				return json_decode( file_get_contents( $file ), true );
			}
		);

		$palette = Component::scheme_palette( 'warm' );
		foreach ( array( 'base', 'contrast', 'primary', 'secondary', 'tertiary' ) as $slug ) {
			$this->assertArrayHasKey( $slug, $palette );
		}
		$this->assertSame( array(), Component::scheme_palette( '../theme' ) );
		$this->assertSame( array(), Component::scheme_palette( 'missing' ) );
		$this->assertSame( array(), Component::scheme_palette( '' ) );
	}
}
