<?php
/**
 * KnowX Docs_Tests integration test class
 *
 * @package knowx
 */

namespace KnowX\KnowX\Tests\Integration;

use KnowX\KnowX\Tests\Framework\Integration_Test_Case;

/**
 * Class integration-testing the docs lists and the article order field.
 *
 * @group docs
 */
class Docs_Tests extends Integration_Test_Case {

	/**
	 * Excluding a product on the Docs Index also hides its sections.
	 */
	public function test_excluding_a_parent_hides_its_sections() {
		$product = self::factory()->category->create( array( 'name' => 'Hidden product' ) );
		$section = self::factory()->category->create(
			array(
				'name'   => 'Hidden section',
				'parent' => $product,
			)
		);
		self::factory()->post->create( array( 'post_category' => array( $section ) ) );

		set_theme_mod( 'knowx_exclude', (string) $product );
		knowx_kb_bump_version();

		$names = wp_list_pluck( knowx_kb_sections(), 'name' );

		remove_theme_mod( 'knowx_exclude' );
		knowx_kb_bump_version();

		$this->assertNotContains( 'Hidden product', $names );
		$this->assertNotContains( 'Hidden section', $names );
	}

	/**
	 * The article order is readable and writable through the posts endpoint.
	 */
	public function test_menu_order_is_in_rest() {
		wp_set_current_user( self::factory()->user->create( array( 'role' => 'administrator' ) ) );
		$post_id = self::factory()->post->create();

		$request = new \WP_REST_Request( 'POST', '/wp/v2/posts/' . $post_id );
		$request->set_param( 'menu_order', 7 );
		$response = rest_do_request( $request );

		$this->assertSame( 200, $response->get_status() );
		$this->assertSame( 7, $response->get_data()['menu_order'] );
		$this->assertSame( 7, (int) get_post_field( 'menu_order', $post_id ) );
	}
}
