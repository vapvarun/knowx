<?php
/**
 * KnowX\KnowX\Typography_Options\Component class
 *
 * @package knowx
 */

namespace KnowX\KnowX\Kirki_Option;

use function add_action;
use function add_filter;
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
		return 'kirki_option';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		if ( class_exists( 'Kirki' ) ) {
			add_action( 'init', array( $this, 'add_panels_and_sections' ) );
			add_filter( 'init', array( $this, 'add_fields' ) );
		}
		add_filter( 'body_class', array( $this, 'site_width_body_classes' ) );
		add_filter( 'body_class', array( $this, 'site_sticky_sidebar_body_classes' ) );
		add_filter( 'body_class', array( $this, 'site_sticky_header_classes' ) );
	}

	/**
	 * Site width body class.
	 */
	public function site_width_body_classes( array $classes ): array {
		$classes[] = 'layout-' . get_theme_mod( 'site_layout', knowx_defaults( 'site-layout' ) );

		return $classes;
	}

	/**
	 * Site sticky sidebar body class.
	 */
	public function site_sticky_sidebar_body_classes( array $classes ): array {

		$sticky_sidebar = get_theme_mod( 'sticky_sidebar_option', knowx_defaults( 'sticky-sidebar' ) );
		if ( $sticky_sidebar ) {
			$classes[] = 'sticky-sidebar-enable';
		}

		return $classes;
	}

	/**
	 * Site sticky header body class.
	 */
	public function site_sticky_header_classes( array $classes ): array {
		$sticky_header = get_theme_mod( 'site_sticky_header', knowx_defaults( 'site-sticky-header' ) );
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
		new \Kirki\Panel(
			'site_layout_panel',
			array(
				'title'       => esc_html__( 'General', 'knowx' ),
				'priority'    => 30,
				'description' => '',
			)
		);

		new \Kirki\Section(
			'site_layout',
			array(
				'title'       => esc_html__( 'Site Layout', 'knowx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'site_layout_panel',
			)
		);

		// Site Loader.
		new \Kirki\Section(
			'site_loader',
			array(
				'title'       => esc_html__( 'Site Loader', 'knowx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'site_layout_panel',
			)
		);

		// Site Scroll Top.
		new \Kirki\Section(
			'site_scroll_top',
			array(
				'title'       => esc_html__( 'Scroll Top', 'knowx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'site_layout_panel',
			)
		);

		// Typography.
		new \Kirki\Panel(
			'typography_panel',
			array(
				'title'       => esc_html__( 'Typography', 'knowx' ),
				'priority'    => 30,
				'description' => '',
			)
		);

		new \Kirki\Section(
			'site_title_typography_section',
			array(
				'title'       => esc_html__( 'Site Title', 'knowx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'typography_panel',
			)
		);

		new \Kirki\Section(
			'headings_typography_section',
			array(
				'title'       => esc_html__( 'Headings', 'knowx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'typography_panel',
			)
		);

		new \Kirki\Section(
			'menu_typography_section',
			array(
				'title'       => esc_html__( 'Menu', 'knowx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'typography_panel',
			)
		);

		new \Kirki\Section(
			'body_typography_section',
			array(
				'title'       => esc_html__( 'Body', 'knowx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'typography_panel',
			)
		);

		// Site Header.
		new \Kirki\Section(
			'site_header_section',
			array(
				'title'       => esc_html__( 'Site Header', 'knowx' ),
				'priority'    => 30,
				'description' => '',
			)
		);

		// Site Sub Header.
		new \Kirki\Section(
			'site_sub_header_section',
			array(
				'title'       => esc_html__( 'Site Sub Header', 'knowx' ),
				'priority'    => 30,
				'description' => '',
			)
		);

		// Site Skin.
		new \Kirki\Section(
			'site_skin_section',
			array(
				'title'       => esc_html__( 'Site Skin', 'knowx' ),
				'priority'    => 30,
				'description' => '',
			)
		);

		// Site Blog Layout.
		new \Kirki\Section(
			'site_blog_section',
			array(
				'title'       => esc_html__( 'Site Blog', 'knowx' ),
				'priority'    => 30,
				'description' => '',
			)
		);

		// Site Sidebar Layout.
		new \Kirki\Section(
			'site_sidebar_layout',
			array(
				'title'       => esc_html__( 'Site Sidebar', 'knowx' ),
				'priority'    => 30,
				'description' => '',
			)
		);

		// Site Knowledge Base.
		new \Kirki\Panel(
			'knowledge_panel',
			array(
				'title'       => esc_html__( 'Knowledge Base', 'knowx' ),
				'priority'    => 31,
				'description' => '',
			)
		);

		new \Kirki\Section(
			'site_knowledge_section_one',
			array(
				'title'       => esc_html__( 'Knowledge Base Layout One', 'knowx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'knowledge_panel',
			)
		);

		new \Kirki\Section(
			'site_knowledge_section_two',
			array(
				'title'       => esc_html__( 'Knowledge Base Layout Two', 'knowx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'knowledge_panel',
			)
		);

		// WP Login.
		new \Kirki\Panel(
			'site_wp_login',
			array(
				'title'       => esc_html__( 'WP Login', 'knowx' ),
				'priority'    => 31,
				'description' => '',
			)
		);

		new \Kirki\Section(
			'site_wp_login_logo',
			array(
				'title'       => esc_html__( 'Logo', 'knowx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'site_wp_login',
			)
		);

		// Site Footer.
		new \Kirki\Panel(
			'site_footer_panel',
			array(
				'title'       => esc_html__( 'Site Footer', 'knowx' ),
				'priority'    => 31,
				'description' => '',
			)
		);

		new \Kirki\Section(
			'site_footer_section',
			array(
				'title'       => esc_html__( 'Footer Section', 'knowx' ),
				'priority'    => 30,
				'description' => '',
				'panel'       => 'site_footer_panel',
			)
		);

		// Site Copyright.
		new \Kirki\Section(
			'site_copyright_section',
			array(
				'title'       => esc_html__( 'Copyright Section', 'knowx' ),
				'priority'    => 31,
				'description' => '',
				'panel'       => 'site_footer_panel',
			)
		);

		// Site Performance.
		new \Kirki\Section(
			'site_performance_section',
			array(
				'title'       => esc_html__( 'Site Performance', 'knowx' ),
				'priority'    => 31,
				'description' => '',
			)
		);
	}

	/**
	 * Add Fields
	 */
	public function add_fields() {
		$fields_dir = __DIR__ . '/Fields/';

		require_once $fields_dir . 'General_Fields.php';
		require_once $fields_dir . 'Typography_Fields.php';
		require_once $fields_dir . 'Header_Fields.php';
		require_once $fields_dir . 'Sub_Header_Fields.php';
		require_once $fields_dir . 'Skin_Fields.php';
		require_once $fields_dir . 'Blog_Fields.php';
		require_once $fields_dir . 'Sidebar_Fields.php';
		require_once $fields_dir . 'Knowledge_Base_Fields.php';
		require_once $fields_dir . 'WP_Login_Fields.php';
		require_once $fields_dir . 'Footer_Fields.php';
		require_once $fields_dir . 'Site_Performance.php';
	}
}
