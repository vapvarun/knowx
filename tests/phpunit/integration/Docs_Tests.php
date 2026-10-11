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

	/**
	 * Resets the per-request debounce so each test starts clean.
	 */
	public function set_up() {
		parent::set_up();
		knowx_kb_invalidate( true );
	}

	/**
	 * Counts how many times the docs cache version is written while the callback runs.
	 *
	 * @param callable $callback Work to run.
	 * @return int Number of version writes.
	 */
	protected function count_bumps( callable $callback ): int {
		$count  = 0;
		$filter = static function ( $value ) use ( &$count ) {
			++$count;
			return $value;
		};
		add_filter( 'pre_update_option_knowx_kb_version', $filter );
		$callback();
		remove_filter( 'pre_update_option_knowx_kb_version', $filter );
		knowx_kb_invalidate( true );

		return $count;
	}

	/**
	 * A menu item is unrelated to the docs data and must not refresh it.
	 */
	public function test_menu_item_save_does_not_bump() {
		$bumps = $this->count_bumps(
			static function () {
				$menu = wp_create_nav_menu( 'KnowX test menu' );
				wp_update_nav_menu_item(
					$menu,
					0,
					array(
						'menu-item-title'  => 'Home',
						'menu-item-url'    => home_url( '/' ),
						'menu-item-status' => 'publish',
					)
				);
			}
		);

		$this->assertSame( 0, $bumps );
	}

	/**
	 * A draft is not shown to readers and must not refresh the docs data.
	 */
	public function test_draft_save_does_not_bump() {
		$bumps = $this->count_bumps(
			function () {
				self::factory()->post->create( array( 'post_status' => 'draft' ) );
			}
		);

		$this->assertSame( 0, $bumps );
	}

	/**
	 * Updating a published article refreshes the data once.
	 */
	public function test_article_update_bumps_once() {
		$post_id = self::factory()->post->create( array( 'post_status' => 'publish' ) );
		knowx_kb_invalidate( true );

		$bumps = $this->count_bumps(
			static function () use ( $post_id ) {
				wp_update_post(
					array(
						'ID'         => $post_id,
						'post_title' => 'Changed title',
					)
				);
			}
		);

		$this->assertSame( 1, $bumps );
	}

	/**
	 * A content change overwrites the cached row instead of leaving an old one behind.
	 */
	public function test_cache_rows_stay_flat() {
		global $wpdb;
		$count_rows = static function () use ( $wpdb ) {
			return (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->options} WHERE option_name LIKE '\_transient\_knowx\_kb\_%'" ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		};

		knowx_kb_cached(
			'flat-test',
			static function () {
				return array( 1 );
			}
		);
		$before = $count_rows();

		for ( $i = 0; $i < 5; $i++ ) {
			knowx_kb_bump_version();
			knowx_kb_cached(
				'flat-test',
				static function () use ( $i ) {
					return array( $i );
				}
			);
		}

		$this->assertSame( $before, $count_rows() );
	}

	/**
	 * An article gets TechArticle and BreadcrumbList data, and two Details blocks make an FAQPage.
	 */
	public function test_schema_graph_has_article_breadcrumbs_and_faq() {
		// An editor who may save unfiltered HTML, so the Details blocks are kept.
		wp_set_current_user( self::factory()->user->create( array( 'role' => 'administrator' ) ) );
		$category = self::factory()->category->create( array( 'name' => 'Schema product' ) );
		$content  = '<!-- wp:details --><details class="wp-block-details"><summary>One?</summary><!-- wp:paragraph --><p>Yes.</p><!-- /wp:paragraph --></details><!-- /wp:details -->'
			. '<!-- wp:details --><details class="wp-block-details"><summary>Two?</summary><!-- wp:paragraph --><p>Also yes.</p><!-- /wp:paragraph --></details><!-- /wp:details -->';
		$post_id  = self::factory()->post->create(
			array(
				'post_title'    => 'Schema article',
				'post_content'  => $content,
				'post_category' => array( $category ),
			)
		);
		$this->go_to( get_permalink( $post_id ) );

		$types = wp_list_pluck( knowx_schema_graph(), '@type' );

		$this->assertContains( 'TechArticle', $types );
		$this->assertContains( 'BreadcrumbList', $types );
		$this->assertContains( 'FAQPage', $types );
	}

	/**
	 * The category fields are saved for a user who can edit the term and ignored for one who cannot.
	 */
	public function test_category_fields_need_the_edit_capability() {
		$term_id = self::factory()->category->create();
		$docs    = new \KnowX\KnowX\Docs\Component();

		wp_set_current_user( self::factory()->user->create( array( 'role' => 'subscriber' ) ) );
		$_POST['knowx_order'] = '5';
		$docs->save_term_fields( $term_id );
		$this->assertSame( '', (string) get_term_meta( $term_id, 'knowx_order', true ) );

		wp_set_current_user( self::factory()->user->create( array( 'role' => 'administrator' ) ) );
		$docs->save_term_fields( $term_id );
		$this->assertSame( '5', (string) get_term_meta( $term_id, 'knowx_order', true ) );

		unset( $_POST['knowx_order'] );
	}

	/**
	 * An article names its publisher and has an image; FAQ data can be switched off for a page.
	 */
	public function test_article_schema_is_complete_and_faq_can_be_switched_off() {
		wp_set_current_user( self::factory()->user->create( array( 'role' => 'administrator' ) ) );
		$content = '<!-- wp:details --><details class="wp-block-details"><summary>One?</summary><!-- wp:paragraph --><p>Yes.</p><!-- /wp:paragraph --></details><!-- /wp:details -->'
			. '<!-- wp:details --><details class="wp-block-details"><summary>Two?</summary><!-- wp:paragraph --><p>Also yes.</p><!-- /wp:paragraph --></details><!-- /wp:details -->';
		$post_id = self::factory()->post->create( array( 'post_content' => $content ) );
		$this->go_to( get_permalink( $post_id ) );

		$by_type = array();
		foreach ( knowx_schema_graph() as $node ) {
			$by_type[ $node['@type'] ] = $node;
		}
		$this->assertSame( 'Organization', $by_type['TechArticle']['publisher']['@type'] );
		$this->assertArrayHasKey( 'FAQPage', $by_type );

		update_post_meta( $post_id, 'knowx_faq_schema', 'off' );
		$this->assertNotContains( 'FAQPage', wp_list_pluck( knowx_schema_graph(), '@type' ) );

		delete_post_meta( $post_id, 'knowx_faq_schema' );
		set_theme_mod( 'knowx_faq_schema', 0 );
		$this->assertNotContains( 'FAQPage', wp_list_pluck( knowx_schema_graph(), '@type' ) );
		remove_theme_mod( 'knowx_faq_schema' );
	}
}
