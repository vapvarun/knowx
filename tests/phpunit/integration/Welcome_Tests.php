<?php
/**
 * KnowX Welcome_Tests integration test class
 *
 * @package knowx
 */

namespace KnowX\KnowX\Tests\Integration;

use KnowX\KnowX\Starter_Content\Component as Starter_Content;
use KnowX\KnowX\Welcome\Component;
use KnowX\KnowX\Tests\Framework\Integration_Test_Case;

/**
 * Class integration-testing Appearance > KnowX.
 *
 * @group welcome
 */
class Welcome_Tests extends Integration_Test_Case {

	/**
	 * Renders the screen as the current user.
	 *
	 * @return string HTML.
	 */
	private function render(): string {
		ob_start();
		( new Component() )->render_page();
		return (string) ob_get_clean();
	}

	/**
	 * Without a docs home an administrator gets the setup buttons, even after dismissing the notice.
	 */
	public function test_setup_buttons_until_docs_home_exists() {
		wp_set_current_user( self::factory()->user->create( array( 'role' => 'administrator' ) ) );
		update_option( Starter_Content::STATE_OPTION, 'dismiss' );

		$html = $this->render();
		$this->assertStringContainsString( 'mode=sample', $html );
		$this->assertStringContainsString( 'mode=home', $html );

		$home = ( new Starter_Content() )->create_docs_home();
		$html = $this->render();
		$this->assertStringNotContainsString( 'mode=sample', $html );
		$this->assertStringContainsString( get_permalink( $home ), $html );
	}

	/**
	 * Only administrators can run setup, so an editor-like role sees no setup buttons.
	 */
	public function test_no_setup_buttons_without_manage_options() {
		$user = self::factory()->user->create( array( 'role' => 'editor' ) );
		get_user_by( 'id', $user )->add_cap( 'edit_theme_options' );
		wp_set_current_user( $user );

		$this->assertStringNotContainsString( 'mode=sample', $this->render() );
	}

	/**
	 * The guides URL and the cards can be changed by a child theme or plugin.
	 */
	public function test_filters_change_links_and_cards() {
		$docs  = static function () {
			return 'https://example.com/help/';
		};
		$cards = static function ( $sections ) {
			$sections['tasks'] = array( array( 'title' => 'Import from Zendesk' ) );
			return $sections;
		};
		add_filter( 'knowx_docs_url', $docs );
		add_filter( 'knowx_getting_started', $cards );
		wp_set_current_user( self::factory()->user->create( array( 'role' => 'administrator' ) ) );
		$html = $this->render();
		remove_filter( 'knowx_docs_url', $docs );
		remove_filter( 'knowx_getting_started', $cards );

		$this->assertStringContainsString( 'https://example.com/help/getting-started/01-overview/', $html );
		$this->assertStringContainsString( 'Import from Zendesk', $html );
		$this->assertStringNotContainsString( 'Write an article', $html );
	}
}
