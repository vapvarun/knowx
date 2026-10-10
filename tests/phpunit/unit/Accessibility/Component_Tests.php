<?php
/**
 * KnowX\KnowX\Tests\Unit\Accessibility\Component_Tests class
 *
 * @package knowx
 */

namespace KnowX\KnowX\Tests\Unit\Accessibility;

use phpDocumentor\Reflection\Types\Object_;
use KnowX\KnowX\Tests\Framework\Unit_Test_Case;
use Brain\Monkey\Functions;
use Mockery;
use KnowX\KnowX\Accessibility\Component;

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
	 * WP_Post mock object.
	 *
	 * @var Object
	 */
	public $mock_post;

	/**
	 * Sets up the environment before each test.
	 */
	protected function setUp(): void {
		parent::setUp();

		$this->component = new Component();
		$this->mock_post = $this->getMockBuilder( \WP_Post::class )
								->disableOriginalConstructor()
								->getMock();
	}

	/**
	 * Tests that the slug of the component is correct.
	 *
	 * @covers Component::get_slug()
	 */
	public function test_get_slug() {
		$this->assertSame( 'accessibility', $this->component->get_slug() );
	}

	/**
	 * Tests that the component adds hooks correctly.
	 *
	 * @covers Component::initialize()
	 */
	public function test_initialize() {
		$this->component->initialize();
		$this->mock_post = $this->getMockBuilder( 'WP_Post' );

		$this->assertNotEquals( false, has_action( 'wp_enqueue_scripts', array( $this->component, 'action_enqueue_navigation_script' ) ) );
		$this->assertNotEquals( false, has_action( 'wp_head', array( $this->component, 'action_remove_no_js_class' ) ) );
	}

	/**
	 * Tests enqueueing the navigation script.
	 *
	 * @covers Component::action_enqueue_navigation_script()
	 */
	public function test_action_enqueue_navigation_script() {
		$template_tags = $this->mockTemplateTags( array( 'get_asset_version' ) );

		$template_tags->expects( $this->once() )
			->method( 'get_asset_version' )
			->will( $this->returnValue( '2.0.1' ) );

		Functions\when( 'get_theme_file_uri' )->returnArg();
		Functions\when( 'get_theme_file_path' )->returnArg();

		Functions\expect( 'wp_enqueue_script' )
			->with( 'knowx-navigation', Mockery::any(), Mockery::any(), Mockery::any(), Mockery::any() )
			->once();

		Functions\expect( 'wp_script_add_data' )
			->with( 'knowx-navigation', 'async', true )
			->twice();

		Functions\expect( 'wp_localize_script' )
			->with( 'knowx-navigation', Mockery::any(), Mockery::any() )
			->once();

		$this->component->action_enqueue_navigation_script();
	}


	/**
	 * Tests that the no-js class is removed by an inline script.
	 *
	 * @covers Component::action_remove_no_js_class()
	 */
	public function test_action_remove_no_js_class() {
		ob_start();
		$this->component->action_remove_no_js_class();
		$output = ob_get_clean();

		$this->assertTrue( false !== strpos( $output, "classList.remove( 'no-js' )" ) );
	}
}
