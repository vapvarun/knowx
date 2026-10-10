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
		\KnowX\KnowX\Customizer_Framework\Panel::add(
			'site_layout_panel',
			array(
				'title'       => esc_html__( 'General', 'knowx' ),
				'priority'    => 30,
				'description' => '',
			)
		);

		\KnowX\KnowX\Customizer_Framework\Section::add(
			'site_layout',
			array(
				'title'       => esc_html__( 'Site Layout', 'knowx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'site_layout_panel',
			)
		);

		// Color mode.
		\KnowX\KnowX\Customizer_Framework\Section::add(
			'site_color_mode_section',
			array(
				'title'       => esc_html__( 'Color Mode', 'knowx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'site_layout_panel',
			)
		);

		// Site Loader.
		\KnowX\KnowX\Customizer_Framework\Section::add(
			'site_loader',
			array(
				'title'       => esc_html__( 'Site Loader', 'knowx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'site_layout_panel',
			)
		);

		// Site Scroll Top.
		\KnowX\KnowX\Customizer_Framework\Section::add(
			'site_scroll_top',
			array(
				'title'       => esc_html__( 'Scroll Top', 'knowx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'site_layout_panel',
			)
		);

		// Typography.
		\KnowX\KnowX\Customizer_Framework\Panel::add(
			'typography_panel',
			array(
				'title'       => esc_html__( 'Typography', 'knowx' ),
				'priority'    => 30,
				'description' => '',
			)
		);

		\KnowX\KnowX\Customizer_Framework\Section::add(
			'site_title_typography_section',
			array(
				'title'       => esc_html__( 'Site Title', 'knowx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'typography_panel',
			)
		);

		\KnowX\KnowX\Customizer_Framework\Section::add(
			'headings_typography_section',
			array(
				'title'       => esc_html__( 'Headings', 'knowx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'typography_panel',
			)
		);

		\KnowX\KnowX\Customizer_Framework\Section::add(
			'menu_typography_section',
			array(
				'title'       => esc_html__( 'Menu', 'knowx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'typography_panel',
			)
		);

		\KnowX\KnowX\Customizer_Framework\Section::add(
			'body_typography_section',
			array(
				'title'       => esc_html__( 'Body', 'knowx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'typography_panel',
			)
		);

		// Site Header.
		\KnowX\KnowX\Customizer_Framework\Section::add(
			'site_header_section',
			array(
				'title'       => esc_html__( 'Site Header', 'knowx' ),
				'priority'    => 30,
				'description' => '',
			)
		);

		// Site Sub Header.
		\KnowX\KnowX\Customizer_Framework\Section::add(
			'site_sub_header_section',
			array(
				'title'       => esc_html__( 'Site Sub Header', 'knowx' ),
				'priority'    => 30,
				'description' => '',
			)
		);

		// Site Skin.
		\KnowX\KnowX\Customizer_Framework\Section::add(
			'site_skin_section',
			array(
				'title'       => esc_html__( 'Site Skin', 'knowx' ),
				'priority'    => 30,
				'description' => '',
			)
		);

		// Site Blog Layout.
		\KnowX\KnowX\Customizer_Framework\Section::add(
			'site_blog_section',
			array(
				'title'       => esc_html__( 'Site Blog', 'knowx' ),
				'priority'    => 30,
				'description' => '',
			)
		);

		// Site Sidebar Layout.
		\KnowX\KnowX\Customizer_Framework\Section::add(
			'site_sidebar_layout',
			array(
				'title'       => esc_html__( 'Site Sidebar', 'knowx' ),
				'priority'    => 30,
				'description' => '',
			)
		);

		// Site Knowledge Base.
		\KnowX\KnowX\Customizer_Framework\Panel::add(
			'knowledge_panel',
			array(
				'title'       => esc_html__( 'Knowledge Base', 'knowx' ),
				'priority'    => 31,
				'description' => '',
			)
		);

		\KnowX\KnowX\Customizer_Framework\Section::add(
			'site_knowledge_section_one',
			array(
				'title'       => esc_html__( 'Knowledge Base Layout One', 'knowx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'knowledge_panel',
			)
		);

		\KnowX\KnowX\Customizer_Framework\Section::add(
			'site_knowledge_section_two',
			array(
				'title'       => esc_html__( 'Knowledge Base Layout Two', 'knowx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'knowledge_panel',
			)
		);

		// Site Footer.
		\KnowX\KnowX\Customizer_Framework\Panel::add(
			'site_footer_panel',
			array(
				'title'       => esc_html__( 'Site Footer', 'knowx' ),
				'priority'    => 31,
				'description' => '',
			)
		);

		\KnowX\KnowX\Customizer_Framework\Section::add(
			'site_footer_section',
			array(
				'title'       => esc_html__( 'Footer Section', 'knowx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'site_footer_panel',
			)
		);

		// Site Copyright.
		\KnowX\KnowX\Customizer_Framework\Section::add(
			'site_copyright_section',
			array(
				'title'       => esc_html__( 'Copyright Section', 'knowx' ),
				'priority'    => 31,
				'description' => '',
				'panel'       => 'site_footer_panel',
			)
		);
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
		require_once $fields_dir . 'Blog_Fields.php';
		require_once $fields_dir . 'Sidebar_Fields.php';
		require_once $fields_dir . 'Knowledge_Base_Fields.php';
		require_once $fields_dir . 'Footer_Fields.php';
	}
}
