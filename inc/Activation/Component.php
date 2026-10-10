<?php
/**
 * KnowX\KnowX\Activation\Component class
 *
 * @package knowx
 */

namespace KnowX\KnowX\Activation;

use KnowX\KnowX\Component_Interface;

/**
 * Class for handling theme activation setup.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'activation';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'after_switch_theme', array( $this, 'setup_home_page' ) );
	}

	/**
	 * Sets up the home page on theme activation.
	 */
	public function setup_home_page() {
		// Check if this is the first activation by looking for a theme option.
		$theme_activated = get_option( 'knowx_theme_activated' );

		if ( ! $theme_activated ) {
			// Create the home page.
			$page_id = $this->create_home_page();

			if ( $page_id ) {
				// Set the page as front page.
				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $page_id );

				// Mark theme as activated.
				update_option( 'knowx_theme_activated', true );
			}
		}
	}

	/**
	 * Creates the home page with Knowledge Base Layout One template.
	 *
	 * @return int|false Page ID on success, false on failure.
	 */
	private function create_home_page() {
		$page_data = array(
			'post_title'   => __( 'Knowledge Base', 'knowx' ),
			'post_content' => __( 'Welcome to our Knowledge Base. Here you can find articles, guides, and resources to help you.', 'knowx' ),
			'post_status'  => 'publish',
			'post_type'    => 'page',
			'post_name'    => 'knowledge-base',
			'meta_input'   => array(
				'_wp_page_template' => 'page-knowledgebase-one.php',
			),
		);

		$page_id = wp_insert_post( $page_data );

		return $page_id ? $page_id : false;
	}
}
