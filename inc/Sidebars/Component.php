<?php
/**
 * KnowX\KnowX\Sidebars\Component class
 *
 * @package knowx
 */

namespace KnowX\KnowX\Sidebars;

use KnowX\KnowX\Component_Interface;
use KnowX\KnowX\Templating_Component_Interface;
use function add_action;
use function add_filter;
use function register_sidebar;
use function esc_html__;
use function is_active_sidebar;
use function dynamic_sidebar;

/**
 * Class for managing sidebars.
 *
 * Exposes template tags:
 * * `knowx()->is_right_sidebar_active()`
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/
 */
class Component implements Component_Interface, Templating_Component_Interface {

	const LEFT_SIDEBAR_SLUG  = 'sidebar-left';
	const RIGHT_SIDEBAR_SLUG = 'sidebar-right';

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'sidebars';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'widgets_init', array( $this, 'action_register_sidebars' ) );
		add_filter( 'body_class', array( $this, 'filter_body_classes' ) );
	}

	/**
	 * Gets template tags to expose as methods on the Template_Tags class instance, accessible through `knowx()`.
	 *
	 * @return array Associative array of $method_name => $callback_info pairs. Each $callback_info must either be
	 *               a callable or an array with key 'callback'. This approach is used to reserve the possibility of
	 *               adding support for further arguments in the future.
	 */
	public function template_tags(): array {
		return array(
			'display_left_sidebar'    => array( $this, 'display_left_sidebar' ),
			'is_left_sidebar_active'  => array( $this, 'is_left_sidebar_active' ),
			'is_right_sidebar_active' => array( $this, 'is_right_sidebar_active' ),
			'display_right_sidebar'   => array( $this, 'display_right_sidebar' ),

		);
	}

	/**
	 * Registers the sidebars.
	 */
	public function action_register_sidebars() {
		register_sidebar(
			array(
				'name'          => esc_html__( 'Right Sidebar', 'knowx' ),
				'id'            => static::RIGHT_SIDEBAR_SLUG,
				'description'   => esc_html__( 'Add widgets here.', 'knowx' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			)
		);

		register_sidebar(
			array(
				'name'          => esc_html__( 'Left Sidebar', 'knowx' ),
				'id'            => static::LEFT_SIDEBAR_SLUG,
				'description'   => esc_html__( 'Add widgets here.', 'knowx' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			)
		);

		register_sidebar(
			array(
				'name'          => esc_html__( 'Footer 1', 'knowx' ),
				'id'            => 'footer-1',
				'description'   => esc_html__( 'Add widgets here.', 'knowx' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			)
		);

		register_sidebar(
			array(
				'name'          => esc_html__( 'Footer 2', 'knowx' ),
				'id'            => 'footer-2',
				'description'   => esc_html__( 'Add widgets here.', 'knowx' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			)
		);

		register_sidebar(
			array(
				'name'          => esc_html__( 'Footer 3', 'knowx' ),
				'id'            => 'footer-3',
				'description'   => esc_html__( 'Add widgets here.', 'knowx' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			)
		);

		register_sidebar(
			array(
				'name'          => esc_html__( 'Footer 4', 'knowx' ),
				'id'            => 'footer-4',
				'description'   => esc_html__( 'Add widgets here.', 'knowx' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			)
		);
	}

	/**
	 * Adds custom classes to indicate whether a sidebar is present to the array of body classes.
	 *
	 * @param array $classes Classes for the body element.
	 * @return array Filtered body classes.
	 */
	public function filter_body_classes( array $classes ): array {
		global $template;

		if ( in_array( basename( (string) $template ), array( '404.php' ), true ) ) {
			return $classes;
		}

		// Docs pages (product, section, search, archives and product articles) have no sidebar.
		if ( is_search() || is_archive() || is_404() || ( is_singular( 'post' ) && knowx_current_product() ) || is_page_template( array( 'page-faq.php', 'page-changelog.php', 'full-width.php', 'full-width-container.php', 'page-knowledgebase-one.php', 'page-knowledgebase-two.php' ) ) ) {
			return $classes;
		}

		$default_sidebar = knowx_mod( 'sidebar_option' );

		if ( 'left' === $default_sidebar && $this->is_left_sidebar_active() ) {
			$classes[] = 'has-sidebar-left';
		} elseif ( 'right' === $default_sidebar && $this->is_right_sidebar_active() ) {
			$classes[] = 'has-sidebar-right';
		} elseif ( 'both' === $default_sidebar && $this->is_left_sidebar_active() && $this->is_right_sidebar_active() ) {
			$classes[] = 'has-sidebar-both';
		}

		return $classes;
	}

	/**
	 * Checks whether the left sidebar is active.
	 *
	 * @return bool True if the left sidebar is active, false otherwise.
	 */
	public function is_left_sidebar_active(): bool {
		return (bool) is_active_sidebar( static::LEFT_SIDEBAR_SLUG );
	}

	/**
	 * Displays the left sidebar.
	 */
	public function display_left_sidebar() {
		dynamic_sidebar( static::LEFT_SIDEBAR_SLUG );
	}

	/**
	 * Checks whether the right sidebar is active.
	 *
	 * @return bool True if the right sidebar is active, false otherwise.
	 */
	public function is_right_sidebar_active(): bool {
		return (bool) is_active_sidebar( static::RIGHT_SIDEBAR_SLUG );
	}

	/**
	 * Displays the right sidebar.
	 */
	public function display_right_sidebar() {
		dynamic_sidebar( static::RIGHT_SIDEBAR_SLUG );
	}
}
