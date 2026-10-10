<?php
/**
 * KnowX\KnowX\Customizer_Settings\Component class
 *
 * @package knowx
 */

namespace KnowX\KnowX\Customizer_Settings;

use function add_action;
use function add_filter;
use function get_template_directory_uri;
use KnowX\KnowX\Component_Interface;

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
		return 'customizer_settings';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		\KnowX\KnowX\Customizer_Framework\Component::boot(
			array(
				'config_id'  => 'knowx_customizer',
				'assets_url' => get_template_directory_uri(),
			)
		);

		add_action( 'init', array( $this, 'add_panels_and_sections' ) );
		add_action( 'init', array( $this, 'add_fields' ) );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_palettes' ) );
		add_filter( 'body_class', array( $this, 'site_width_body_classes' ) );
		add_filter( 'body_class', array( $this, 'site_sticky_sidebar_body_classes' ) );
		add_filter( 'body_class', array( $this, 'site_sticky_header_classes' ) );
	}

	/**
	 * Site width body class.
	 */
	public function site_width_body_classes( array $classes ): array {
		$classes[] = 'layout-' . knowx_mod( 'site_layout' );

		return $classes;
	}

	/**
	 * Site sticky sidebar body class.
	 */
	public function site_sticky_sidebar_body_classes( array $classes ): array {

		$sticky_sidebar = knowx_mod( 'sticky_sidebar_option' );
		if ( $sticky_sidebar ) {
			$classes[] = 'sticky-sidebar-enable';
		}

		return $classes;
	}

	/**
	 * Site sticky header body class.
	 */
	public function site_sticky_header_classes( array $classes ): array {
		$sticky_header = knowx_mod( 'site_sticky_header' );
		if ( $sticky_header ) {
			$classes[] = 'sticky-header';
		}

		return $classes;
	}

	/**
	 * Add Customizer Section
	 */
	public function add_panels_and_sections() {
		// Site Layout.
		\KnowX\KnowX\Customizer_Framework\Section::add(
			'site_layout',
			array(
				'title'       => esc_html__( 'Layout', 'knowx' ),
				'priority'    => 30,
				'description' => '',
			)
		);

		// Color mode.
		\KnowX\KnowX\Customizer_Framework\Section::add(
			'site_color_mode_section',
			array(
				'title'       => esc_html__( 'Dark Mode', 'knowx' ),
				'priority'    => 23,
				'description' => '',
			)
		);

		// Site Loader.
		// Site Scroll Top.
		// Typography.
		\KnowX\KnowX\Customizer_Framework\Section::add(
			'site_title_typography_section',
			array(
				'title'       => esc_html__( 'Typography', 'knowx' ),
				'priority'    => 25,
				'description' => '',
			)
		);

		// Site Header.
		\KnowX\KnowX\Customizer_Framework\Section::add(
			'site_header_section',
			array(
				'title'       => esc_html__( 'Header and Breadcrumbs', 'knowx' ),
				'priority'    => 28,
				'description' => '',
			)
		);

		// Site Sub Header.
		// Site Skin.
		\KnowX\KnowX\Customizer_Framework\Section::add(
			'site_skin_section',
			array(
				'title'       => esc_html__( 'Colors', 'knowx' ),
				'priority'    => 22,
				'description' => '',
			)
		);

		// Site Blog Layout.
		// Site Sidebar Layout.
		\KnowX\KnowX\Customizer_Framework\Section::add(
			'site_sidebar_layout',
			array(
				'title'       => esc_html__( 'Sidebar', 'knowx' ),
				'priority'    => 32,
				'description' => '',
			)
		);

		// Site Knowledge Base.
		\KnowX\KnowX\Customizer_Framework\Panel::add(
			'knowledge_panel',
			array(
				'title'       => esc_html__( 'Docs', 'knowx' ),
				'priority'    => 31,
				'description' => '',
			)
		);

		\KnowX\KnowX\Customizer_Framework\Section::add(
			'site_knowledge_section_one',
			array(
				'title'       => esc_html__( 'Docs Home', 'knowx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'knowledge_panel',
			)
		);

		\KnowX\KnowX\Customizer_Framework\Section::add(
			'site_articles_section',
			array(
				'title'       => esc_html__( 'Articles', 'knowx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'knowledge_panel',
			)
		);

		\KnowX\KnowX\Customizer_Framework\Section::add(
			'site_knowledge_section_two',
			array(
				'title'       => esc_html__( 'Docs Index', 'knowx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'knowledge_panel',
			)
		);

		// Site Footer.
		\KnowX\KnowX\Customizer_Framework\Section::add(
			'site_footer_section',
			array(
				'title'       => esc_html__( 'Footer', 'knowx' ),
				'priority'    => 33,
				'description' => '',
			)
		);

		// Site Copyright.
	}

	/**
	 * Add Fields
	 */
	public function add_fields() {
		$fields_dir = __DIR__ . '/Fields/';

		require_once $fields_dir . 'General_Fields.php';
		require_once $fields_dir . 'Color_Mode_Fields.php';
		require_once $fields_dir . 'Typography_Fields.php';
		require_once $fields_dir . 'Header_Fields.php';
		require_once $fields_dir . 'Sub_Header_Fields.php';
		require_once $fields_dir . 'Skin_Fields.php';
		require_once $fields_dir . 'Sidebar_Fields.php';
		require_once $fields_dir . 'Knowledge_Base_Fields.php';
		require_once $fields_dir . 'Footer_Fields.php';
	}

	/**
	 * Loads the palette picker for the Colors section.
	 */
	public function enqueue_palettes() {
		$base = get_template_directory_uri() . '/inc/Customizer_Framework/assets/';
		$ver  = wp_get_theme()->get( 'Version' );

		wp_enqueue_style( 'knowx-customizer', $base . 'knowx-customizer.css', array(), $ver );
		wp_enqueue_script( 'knowx-customizer', $base . 'knowx-customizer.js', array( 'customize-controls' ), $ver, true );

		$palettes = \KnowX\KnowX\Tokens\Component::palettes();
		$first    = reset( $palettes );
		$ids      = $first ? array_keys( $first['colors'] ) : array();

		// What "Default" puts back: each colour's own default, and core's default page background.
		$defaults = array_fill_keys( $ids, '' );
		foreach ( \KnowX\KnowX\Customizer_Framework\Component::get_fields() as $field ) {
			if ( isset( $defaults[ $field['settings'] ?? '' ] ) ) {
				$defaults[ $field['settings'] ] = (string) ( $field['default'] ?? '' );
			}
		}
		$defaults['background_color'] = '#' . ltrim( (string) get_theme_support( 'custom-background', 'default-color' ), '#' );
		wp_localize_script(
			'knowx-customizer',
			'knowxPalettes',
			array(
				'palettes' => $palettes,
				'ids'      => $ids,
				'defaults' => $defaults,
				'strings'  => array(
					'title' => __( 'Palettes', 'knowx' ),
					'hint'  => __( 'Pick a starting point, then change any colour below.', 'knowx' ),
					'reset' => __( 'Default', 'knowx' ),
				),
			)
		);
	}
}
