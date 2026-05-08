<?php
/**
 * KnowX\KnowX\Customizer\Component class
 *
 * @package knowx
 */

namespace KnowX\KnowX\Customizer;

use KnowX\KnowX\Component_Interface;
use WP_Customize_Manager;

use function KnowX\KnowX\knowx;
use function add_action;
use function bloginfo;
use function wp_add_inline_style;
use function wp_enqueue_style;
use function wp_enqueue_script;
use function wp_script_is;
use function get_theme_file_uri;
use function get_theme_file_path;

/**
 * Class for managing Customizer integration.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'customizer';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'customize_register', array( $this, 'action_customize_register' ) );
		add_action( 'customize_preview_init', array( $this, 'action_enqueue_customize_preview_js' ) );

		add_action(
			'customize_controls_enqueue_scripts',
			function () {
				$css_uri = get_theme_file_uri( '/assets/css/' );
				wp_enqueue_style( 'knowx-customizer', $css_uri . 'knowx-customizer.min.css', '', knowx()->get_asset_version( get_theme_file_path( '/assets/css/knowx-customizer.min.css' ) ) );

				if ( class_exists( 'Kirki' ) ) {
					$kirki_deps = array( 'customize-controls', 'jquery' );

					// Kirki 5.x uses 'kirki-customizer'; older versions used 'kirki_field_dependencies'.
					if ( wp_script_is( 'kirki-customizer', 'registered' ) ) {
						$kirki_deps[] = 'kirki-customizer';
					} elseif ( wp_script_is( 'kirki_field_dependencies', 'registered' ) ) {
						$kirki_deps[] = 'kirki_field_dependencies';
					}

					wp_enqueue_script(
						'knowx-customizer-controls',
						get_theme_file_uri( '/assets/js/customizer-controls.min.js' ),
						$kirki_deps,
						knowx()->get_asset_version( get_theme_file_path( '/assets/js/customizer-controls.min.js' ) ),
						true
					);

					wp_add_inline_style(
						'knowx-customizer',
						'.customize-control.knowx-force-hidden-by-mode{display:none !important;}'
					);
				}
			}
		);
	}

	/**
	 * Adds postMessage support for site title and description, plus a custom Theme Options section.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 */
	public function action_customize_register( WP_Customize_Manager $wp_customize ) {
		$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
		$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

		if ( isset( $wp_customize->selective_refresh ) ) {
			$wp_customize->selective_refresh->add_partial(
				'blogname',
				array(
					'selector'        => '.site-title a',
					'render_callback' => function () {
						bloginfo( 'name' );
					},
				)
			);
			$wp_customize->selective_refresh->add_partial(
				'blogdescription',
				array(
					'selector'        => '.site-description',
					'render_callback' => function () {
						bloginfo( 'description' );
					},
				)
			);
		}

		/**
		 * Theme options.
		 */
		$wp_customize->add_section(
			'theme_options',
			array(
				'title'    => __( 'Theme Options', 'knowx' ),
				'priority' => 130, // Before Additional CSS.
			)
		);
	}

	/**
	 * Enqueues JavaScript to make Customizer preview reload changes asynchronously.
	 */
	public function action_enqueue_customize_preview_js() {
		wp_enqueue_script(
			'knowx-customizer',
			get_theme_file_uri( '/assets/js/customizer.min.js' ),
			array( 'customize-preview' ),
			knowx()->get_asset_version( get_theme_file_path( '/assets/js/customizer.min.js' ) ),
			true
		);
	}
}
