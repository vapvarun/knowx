<?php
/**
 * Tests for the breadcrumb trail.
 *
 * @package knowx
 */

namespace KnowX\KnowX\Tests\Unit;

use Brain\Monkey\Functions;
use KnowX\KnowX\Tests\Framework\Unit_Test_Case;

require_once TESTS_THEME_DIR . '/inc/breadcrumbs.php';

/**
 * Breadcrumb tests.
 */
class Breadcrumbs_Tests extends Unit_Test_Case {

	/**
	 * Current conditional tag, e.g. 'is_404'.
	 *
	 * @var string
	 */
	private $context = '';

	/**
	 * Stubs every conditional tag so only the chosen one is true.
	 */
	protected function setUp(): void {
		parent::setUp();

		foreach ( array( 'is_front_page', 'is_home', 'is_singular', 'is_page', 'is_category', 'is_tag', 'is_tax', 'is_author', 'is_date', 'is_search', 'is_post_type_archive', 'is_404' ) as $tag ) {
			Functions\when( $tag )->alias(
				function () use ( $tag ) {
					return $this->context === $tag;
				}
			);
		}

		Functions\when( 'home_url' )->justReturn( 'https://example.test/' );
		Functions\when( 'get_search_query' )->justReturn( 'billing' );
	}

	/**
	 * The front page has no trail.
	 */
	public function test_front_page_has_no_trail() {
		$this->context = 'is_front_page';
		$this->assertSame( array(), knowx_get_breadcrumb_items() );
	}

	/**
	 * A 404 shows Home and the not-found label, the last item without a link.
	 */
	public function test_404_trail() {
		$this->context = 'is_404';
		$items         = knowx_get_breadcrumb_items();

		$this->assertCount( 2, $items );
		$this->assertSame( 'https://example.test/', $items[0]['url'] );
		$this->assertSame( '', $items[1]['url'] );
	}

	/**
	 * Search results include the escaped-at-output query as the label.
	 */
	public function test_search_trail() {
		$this->context = 'is_search';
		$items         = knowx_get_breadcrumb_items();

		$this->assertSame( 'Search results for "billing"', $items[1]['label'] );
	}

	/**
	 * Labels are escaped when the trail is printed.
	 */
	public function test_labels_are_escaped_on_output() {
		$this->context = 'is_search';
		Functions\when( 'get_search_query' )->justReturn( '<script>alert(1)</script>' );
		Functions\when( 'esc_url' )->returnArg();
		Functions\when( 'esc_attr__' )->returnArg();
		Functions\when( 'esc_html' )->alias(
			function ( $text ) {
				return htmlspecialchars( $text, ENT_QUOTES );
			}
		);

		ob_start();
		knowx_get_breadcrumb();
		$html = ob_get_clean();

		$this->assertStringNotContainsString( '<script>', $html );
		$this->assertStringContainsString( 'aria-current="page"', $html );
	}
}
