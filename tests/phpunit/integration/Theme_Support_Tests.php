<?php
/**
 * KnowX Theme_Support_Tests integration test class
 *
 * @package knowx
 */

namespace KnowX\KnowX\Tests\Integration;

use KnowX\KnowX\Tests\Framework\Integration_Test_Case;

/**
 * Class integration-testing the theme support features.
 *
 * @group hooks
 */
class Theme_Support_Tests extends Integration_Test_Case {

	/**
	 * Tests that the theme support features are present.
	 *
	 * @param string $feature Theme support feature.
	 *
	 * @dataProvider data_theme_support_features
	 */
	public function test_theme_support_features( string $feature ) {
		$this->assertTrue( current_theme_supports( $feature ) );
	}

	/**
	 * Gets the theme feature names to test for.
	 *
	 * @return array List of test datasets.
	 */
	public function data_theme_support_features(): array {
		return array(
			array(
				'automatic-feed-links',
			),
			array(
				'post-thumbnails',
			),
			array(
				'html5',
			),
			array(
				'custom-background',
			),
			array(
				'customize-selective-refresh-widgets',
			),
			array(
				'responsive-embeds',
			),
			array(
				'custom-logo',
			),
			array(
				'wp-block-styles',
			),
			array(
				'align-wide',
			),
			array(
				'custom-header',
			),
		);
	}

	/**
	 * Tests that the palette and font sizes come from theme.json.
	 */
	public function test_theme_json_supplies_palette_and_font_sizes() {
		$settings = wp_get_global_settings();

		$this->assertNotEmpty( $settings['color']['palette']['theme'] );
		$this->assertNotEmpty( $settings['typography']['fontSizes']['theme'] );
	}

	/**
	 * Tests that HTML5 theme support is added for all required areas.
	 */
	public function test_theme_support_html5() {
		$html5_support = get_theme_support( 'html5' );

		$this->assertIsArray( $html5_support );
		$this->assertEqualSets(
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			),
			array_unique( $html5_support[0] )
		);
	}
}
