<?php
/**
 * KnowX\KnowX\Custom_Background\Component class
 *
 * @package knowx
 */

namespace KnowX\KnowX\Custom_Background;

use KnowX\KnowX\Component_Interface;
use function add_action;
use function add_theme_support;
use function apply_filters;

/**
 * Class for adding custom background support.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'custom_background';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'after_setup_theme', array( $this, 'action_add_custom_background_support' ) );
		add_action( 'customize_register', array( $this, 'action_customize_register' ), 20 );
	}

	/**
	 * Puts the core Background Color control in Site Skin next to the other colours and drops the image controls.
	 *
	 * The colour itself is printed as the `--color-theme-body` token (see the Tokens component).
	 *
	 * @param \WP_Customize_Manager $wp_customize Customizer manager instance.
	 */
	public function action_customize_register( $wp_customize ) {
		$control = $wp_customize->get_control( 'background_color' );
		if ( $control ) {
			$control->section  = 'site_skin_section';
			$control->label    = __( 'Page Background Color', 'knowx' );
			$control->priority = 12;
		}
		$wp_customize->remove_section( 'background_image' );
	}

	/**
	 * Adds support for the Custom Background feature.
	 */
	public function action_add_custom_background_support() {
		add_theme_support(
			'custom-background',
			apply_filters(
				'knowx_custom_background_args',
				array(
					'default-color'    => 'f7f7f9',
					'default-image'    => '',
					// Core's body rule would ignore dark mode; the Tokens component prints the colour instead.
					'wp-head-callback' => '__return_false',
				)
			)
		);
	}
}
