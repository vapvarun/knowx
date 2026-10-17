<?php
/**
 * KnowX Starter_Content_Tests integration test class
 *
 * @package knowx
 */

namespace KnowX\KnowX\Tests\Integration;

use KnowX\KnowX\Starter_Content\Component;
use KnowX\KnowX\Tests\Framework\Integration_Test_Case;

/**
 * Class integration-testing the docs setup: nothing on activation, content only on request.
 *
 * @group starter-content
 */
class Starter_Content_Tests extends Integration_Test_Case {

	/**
	 * Component under test.
	 *
	 * @var Component
	 */
	private $setup;

	/**
	 * Sets up an administrator and a site that shows its latest posts.
	 */
	public function set_up() {
		parent::set_up();
		wp_set_current_user( self::factory()->user->create( array( 'role' => 'administrator' ) ) );
		update_option( 'show_on_front', 'posts' );
		delete_option( 'page_on_front' );
		delete_option( Component::STATE_OPTION );
		delete_option( 'knowx_theme_activated' );
		remove_theme_mod( 'nav_menu_locations' );
		$this->setup = new Component();
	}

	/**
	 * Switching to the theme creates no page and changes no setting.
	 */
	public function test_activation_changes_nothing() {
		$pages = wp_count_posts( 'page' )->publish;

		do_action( 'after_switch_theme', 'twentytwentyfive', wp_get_theme( 'twentytwentyfive' ) );

		$this->assertSame( $pages, wp_count_posts( 'page' )->publish );
		$this->assertSame( 'posts', get_option( 'show_on_front' ) );
		$this->assertTrue( $this->setup->is_pending() );
	}

	/**
	 * Sites set up by 1.x and sites that dismissed the offer are not asked again.
	 */
	public function test_notice_respects_earlier_setup_and_filter() {
		update_option( 'knowx_theme_activated', true );
		$this->assertFalse( $this->setup->is_pending() );

		delete_option( 'knowx_theme_activated' );
		update_option( Component::STATE_OPTION, 'dismissed' );
		$this->assertFalse( $this->setup->is_pending() );

		delete_option( Component::STATE_OPTION );
		add_filter( 'knowx_show_setup_notice', '__return_false' );
		$this->assertFalse( $this->setup->is_pending() );
		remove_filter( 'knowx_show_setup_notice', '__return_false' );
	}

	/**
	 * An empty docs home becomes the front page of a latest-posts site, and is reused on a second run.
	 */
	public function test_docs_home_takes_front_page_only_from_latest_posts() {
		$home = $this->setup->create_docs_home();

		$this->assertSame( 'page-knowledgebase-one.php', get_page_template_slug( $home ) );
		$this->assertSame( $home, (int) get_option( 'page_on_front' ) );
		$this->assertSame( $home, $this->setup->create_docs_home() );

		$own = self::factory()->post->create( array( 'post_type' => 'page' ) );
		update_option( 'page_on_front', $own );
		wp_delete_post( $home, true );
		$this->setup->create_docs_home();

		$this->assertSame( $own, (int) get_option( 'page_on_front' ) );
	}

	/**
	 * Sample docs build products with sections and articles once, and keep an assigned menu.
	 */
	public function test_sample_content_is_complete_and_runs_once() {
		$menu = wp_create_nav_menu( 'Owner menu' );
		set_theme_mod( 'nav_menu_locations', array( 'primary' => $menu ) );

		$home     = $this->setup->create_sample_content();
		$product  = get_term_by( 'slug', 'orbit-api', 'category' );
		$sections = get_terms(
			array(
				'taxonomy'   => 'category',
				'parent'     => $product->term_id,
				'hide_empty' => false,
			)
		);
		$articles = wp_count_posts()->publish;

		$this->assertSame( 'code', get_term_meta( $product->term_id, 'knowx_icon', true ) );
		$this->assertCount( 3, $sections );
		$this->assertSame( 'page-knowledgebase-one.php', get_page_template_slug( $home ) );
		$this->assertSame( $menu, get_theme_mod( 'nav_menu_locations' )['primary'] );

		$this->assertSame( $home, $this->setup->create_sample_content() );
		$this->assertSame( $articles, wp_count_posts()->publish );
	}

	/**
	 * With no menu assigned, the sample menu links the docs home, FAQ and changelog.
	 */
	public function test_sample_content_menu_has_every_page() {
		$this->setup->create_sample_content();

		$items = wp_get_nav_menu_items( get_theme_mod( 'nav_menu_locations' )['primary'] );

		$this->assertSame( array( 'Docs', 'FAQ', 'Changelog' ), wp_list_pluck( $items, 'title' ) );
	}

	/**
	 * The knowx_sample_content filter can replace the sample products.
	 */
	public function test_sample_content_filter() {
		$filter = static function ( $content ) {
			$content['products'] = array(
				array(
					'name'     => 'Acme',
					'slug'     => 'acme',
					'sections' => array( 'Basics' => array( array( 'Hello', 'Lead.', array( 'Step.' ), 'Tip.' ) ) ),
				),
			);
			return $content;
		};
		add_filter( 'knowx_sample_content', $filter );
		$this->setup->create_sample_content();
		remove_filter( 'knowx_sample_content', $filter );

		$this->assertInstanceOf( \WP_Term::class, get_term_by( 'slug', 'acme', 'category' ) );
		$this->assertFalse( get_term_by( 'slug', 'orbit-api', 'category' ) );
	}
}
