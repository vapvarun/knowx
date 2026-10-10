<?php
/**
 * KnowX\KnowX\Tests\Unit\Base_Support\Component_Tests class
 *
 * @package knowx
 */

namespace KnowX\KnowX\Tests\Unit\Base_Support;

use KnowX\KnowX\Tests\Framework\Unit_Test_Case;
use Brain\Monkey\Functions;
use Mockery;
use KnowX\KnowX\Base_Support\Component;

/**
 * Class unit-testing the accessibility component.
 *
 * @group hooks
 */
class Component_Tests extends Unit_Test_Case {

	/**
	 * The accessibility component instance.
	 *
	 * @var Component
	 */
	private $component;

	/**
	 * Sets up the environment before each test.
	 */
	protected function setUp(): void {
		parent::setUp();

		$this->component = new Component();
	}

	/**
	 * Tests that the slug of the component is correct.
	 *
	 * @covers Component::get_slug()
	 */
	public function test_get_slug() {
		$this->assertSame( 'base_support', $this->component->get_slug() );
	}

	/**
	 * Tests that the component adds hooks correctly.
	 *
	 * @covers Component::initialize()
	 */
	public function test_initialize() {
		$this->component->initialize();

		$this->assertNotEquals( false, has_action( 'after_setup_theme', array( $this->component, 'action_essential_theme_support' ) ) );
		$this->assertNotEquals( false, has_action( 'wp_head', array( $this->component, 'action_add_pingback_header' ) ) );
		$this->assertNotEquals( false, has_filter( 'body_class', array( $this->component, 'filter_body_classes_add_hfeed' ) ) );
		$this->assertNotEquals( false, has_filter( 'embed_defaults', array( $this->component, 'filter_embed_dimensions' ) ) );
	}

	/**
	 * Tests that essential theme support is added.
	 *
	 * @covers Component::action_essential_theme_support()
	 */
	public function test_action_essential_theme_support() {
		$features = array();

		Functions\when( 'add_theme_support' )->alias(
			function ( $feature, ...$args ) use ( &$features ) {
				$features[ $feature ] = $args;
			}
		);

		$this->component->action_essential_theme_support();

		$this->assertEqualSets(
			array(
				'automatic-feed-links',
				'html5',
				'customize-selective-refresh-widgets',
				'responsive-embeds',
			),
			array_keys( $features )
		);
	}
}
