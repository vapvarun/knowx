<?php
/**
 * KnowX Upgrade_Tests integration test class
 *
 * @package knowx
 */

namespace KnowX\KnowX\Tests\Integration;

use KnowX\KnowX\Customizer_Settings\Component as Customizer_Settings;
use KnowX\KnowX\Tokens\Component as Tokens;
use KnowX\KnowX\Tests\Framework\Integration_Test_Case;

/**
 * Settings saved by KnowX 1.7.4 (Kirki), read by 2.0.
 *
 * @group upgrade
 */
class Upgrade_Tests extends Integration_Test_Case {

	/**
	 * Clears the settings the tests set.
	 */
	public function tear_down() {
		foreach ( array( 'sticky_sidebar_option', 'site_sticky_header', 'background_setting', 'site_footer_content_color' ) as $mod ) {
			remove_theme_mod( $mod );
		}
		parent::tear_down();
	}

	/**
	 * 1.7.4 saved switches as 'on' and 'off'; 'off' must stay off.
	 */
	public function test_on_off_switches_from_1x() {
		$settings = new Customizer_Settings();

		set_theme_mod( 'sticky_sidebar_option', 'off' );
		set_theme_mod( 'site_sticky_header', 'off' );
		$this->assertNotContains( 'sticky-sidebar-enable', $settings->filter_sticky_sidebar_body_class( array() ) );
		$this->assertNotContains( 'sticky-header', $settings->filter_sticky_header_body_class( array() ) );

		set_theme_mod( 'sticky_sidebar_option', 'on' );
		set_theme_mod( 'site_sticky_header', true );
		$this->assertContains( 'sticky-sidebar-enable', $settings->filter_sticky_sidebar_body_class( array() ) );
		$this->assertContains( 'sticky-header', $settings->filter_sticky_header_body_class( array() ) );
	}

	/**
	 * Without the breadcrumb bar, the body says so, so pages keep the space under the header.
	 */
	public function test_no_sub_header_class_follows_breadcrumbs() {
		$settings = new Customizer_Settings();
		$this->go_to( get_permalink( self::factory()->post->create( array( 'post_type' => 'page' ) ) ) );

		set_theme_mod( 'site_breadcrumbs', '0' );
		$this->assertContains( 'no-sub-header', $settings->filter_sub_header_body_class( array() ) );

		set_theme_mod( 'site_breadcrumbs', '1' );
		$this->assertNotContains( 'no-sub-header', $settings->filter_sub_header_body_class( array() ) );
		remove_theme_mod( 'site_breadcrumbs' );
	}

	/**
	 * A dark footer background alone gives readable footer text, titles and links.
	 */
	public function test_dark_footer_background_gets_readable_titles() {
		set_theme_mod( 'background_setting', array( 'background-color' => '#1f2937' ) );

		$css = ( new Tokens() )->build_css();

		$this->assertStringContainsString( '--color-footer-title:#fff;', $css );
		$this->assertStringContainsString( '--color-footer-link:#fff;', $css );
	}

	/**
	 * The owner's footer text colour wins and also colours titles and links.
	 */
	public function test_footer_text_colour_drives_titles_and_links() {
		set_theme_mod( 'background_setting', array( 'background-color' => 'rgba(31, 41, 55, 1)' ) );
		set_theme_mod( 'site_footer_content_color', '#e5e7eb' );

		$css = ( new Tokens() )->build_css();

		$this->assertStringContainsString( '--color-footer-title:#e5e7eb;', $css );
		$this->assertStringNotContainsString( '--color-footer-content:#fff', $css );
	}
}
