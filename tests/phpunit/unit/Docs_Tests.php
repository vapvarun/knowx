<?php
/**
 * Tests for the docs tree helpers.
 *
 * @package knowx
 */

namespace KnowX\KnowX\Tests\Unit;

use KnowX\KnowX\Tests\Framework\Unit_Test_Case;

require_once TESTS_THEME_DIR . '/inc/docs.php';

/**
 * Docs tests.
 */
class Docs_Tests extends Unit_Test_Case {

	/**
	 * A two-section tree: root article 1; section 10 with article 2 and child 11 holding article 3.
	 *
	 * @return array Tree.
	 */
	protected function tree(): array {
		$item = static function ( $id ) {
			return array(
				'id'    => $id,
				'title' => 'A' . $id,
				'url'   => '/' . $id,
			);
		};

		return array(
			'root'  => array(
				'posts'    => array( $item( 1 ) ),
				'children' => array( 10 ),
			),
			'nodes' => array(
				10 => array(
					'parent'   => 0,
					'posts'    => array( $item( 2 ) ),
					'children' => array( 11 ),
				),
				11 => array(
					'parent'   => 10,
					'posts'    => array( $item( 3 ) ),
					'children' => array(),
				),
			),
		);
	}

	/**
	 * Previous and next follow the tree in reading order.
	 */
	public function test_tree_flat_reading_order() {
		$this->assertSame( array( 1, 2, 3 ), array_column( knowx_tree_flat( $this->tree() ), 'id' ) );
	}

	/**
	 * The current section and its parents are open.
	 */
	public function test_tree_open_ids() {
		$this->assertSame( array( 11, 10 ), knowx_tree_open_ids( $this->tree(), 3, 0 ) );
		$this->assertSame( array( 10 ), knowx_tree_open_ids( $this->tree(), 0, 10 ) );
		$this->assertSame( array(), knowx_tree_open_ids( $this->tree(), 1, 0 ) );
	}

	/**
	 * Details blocks become question and answer pairs, nested blocks included.
	 */
	public function test_faq_from_blocks() {
		$details = static function ( $question, $answer ) {
			return array(
				'blockName'    => 'core/details',
				'innerContent' => array( '<details class="wp-block-details"><summary>' . $question . '</summary>', null, '</details>' ),
				'innerBlocks'  => array(
					array(
						'blockName'    => 'core/paragraph',
						'innerContent' => array( '<p>' . $answer . '</p>' ),
						'innerBlocks'  => array(),
					),
				),
			);
		};
		$blocks  = array(
			$details( 'What is <strong>it</strong>?', 'A &amp; B.' ),
			array(
				'blockName'    => 'core/group',
				'innerContent' => array(),
				'innerBlocks'  => array( $details( 'Where?', 'Here.' ) ),
			),
			$details( '', 'No question' ),
		);

		$this->assertSame(
			array(
				array(
					'question' => 'What is it?',
					'answer'   => 'A & B.',
				),
				array(
					'question' => 'Where?',
					'answer'   => 'Here.',
				),
			),
			knowx_faq_from_blocks( $blocks )
		);
	}
}
