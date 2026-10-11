<?php
/**
 * KnowX\KnowX\Activation\Component class
 *
 * @package knowx
 */

namespace KnowX\KnowX\Activation;

use KnowX\KnowX\Component_Interface;
use KnowX\KnowX\Nav_Menus\Component as Nav_Menus_Component;

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
			// Reuse an existing Knowledge Base page instead of creating a duplicate.
			$existing = get_page_by_path( 'knowledge-base' );
			$page_id  = $existing ? $existing->ID : $this->create_home_page();

			if ( $page_id ) {
				// Only take over the front page when the site does not already use one.
				if ( ! (int) get_option( 'page_on_front' ) ) {
					update_option( 'show_on_front', 'page' );
					update_option( 'page_on_front', $page_id );
				}

				$this->create_primary_menu( (int) $page_id );

				// Mark theme as activated.
				update_option( 'knowx_theme_activated', true );
			}
		}
	}

	/**
	 * Gives a new site a main menu with the docs home page, unless a menu is already assigned.
	 *
	 * @param int $page_id Docs home page id.
	 */
	private function create_primary_menu( int $page_id ) {
		$locations = get_theme_mod( 'nav_menu_locations', array() );
		if ( ! empty( $locations[ Nav_Menus_Component::PRIMARY_NAV_MENU_SLUG ] ) ) {
			return;
		}

		$menu_id = wp_create_nav_menu( __( 'Main menu', 'knowx' ) );
		if ( is_wp_error( $menu_id ) ) {
			return;
		}

		wp_update_nav_menu_item(
			$menu_id,
			0,
			array(
				'menu-item-title'     => __( 'Docs', 'knowx' ),
				'menu-item-object'    => 'page',
				'menu-item-object-id' => $page_id,
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
			)
		);

		$locations[ Nav_Menus_Component::PRIMARY_NAV_MENU_SLUG ] = $menu_id;
		set_theme_mod( 'nav_menu_locations', $locations );
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
