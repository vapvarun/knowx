<?php
/**
 * Helpdesk\Helpdesk\Typography_Options\Component class
 *
 * @package helpdesk
 */

namespace Helpdesk\Helpdesk\Kirki_Option;

use function add_action;
use function add_filter;
use Helpdesk\Helpdesk\Component_Interface;

/**
 * Class for adding custom background support.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'kirki_option';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'customize_register', array( $this, 'add_panels_and_sections' ) );
		add_filter( 'kirki/fields', array( $this, 'add_fields' ) );
		add_filter( 'body_class', [ $this, 'site_width_body_classes' ] );
		add_filter( 'body_class', [ $this, 'site_sticky_sidebar_body_classes' ] );
	}

	public function site_width_body_classes( array $classes ) : array {
		$classes[] = 'layout-' . get_theme_mod( 'site_layout', helpdesk_defaults( 'site-layout' ) );

		return $classes;
	}

	public function site_sticky_sidebar_body_classes( array $classes ) : array {

		$sticky_sidebar = get_theme_mod( 'sticky_sidebar_option', helpdesk_defaults( 'sticky-sidebar' ) );
		if ( $sticky_sidebar ) {
			$classes[] = 'sticky-sidebar-enable';
		}

		return $classes;
	}

	/**
	 * Add Customizer Section
	 */
	public function add_panels_and_sections( $wp_customize ) {
		// Site Layout
		$wp_customize->add_panel(
			'site_layout_panel',
			array(
				'title'       => esc_html__( 'General', 'helpdesk' ),
				'priority'    => 10,
				'description' => '',
			)
		);

		$wp_customize->add_section(
			'site_layout',
			array(
				'title'       => esc_html__( 'Site Layout', 'helpdesk' ),
				'priority'    => 10,
				'description' => '',
				'panel' => 'site_layout_panel',
			)
		);

		// Site Loader
		$wp_customize->add_section(
			'site_loader',
			array(
				'title'       => esc_html__( 'Site Loader', 'helpdesk' ),
				'priority'    => 10,
				'description' => '',
				'panel' => 'site_layout_panel',
			)
		);

		// Page Mapping
        $wp_customize->add_section(
            'page_mapping',
            [
                'title' => esc_html__('Page Mapping', 'helpdesk'),
                'priority' => 10,
                'description' => '',
                'panel' => 'site_layout_panel',
            ]
        );

		// Typography
		$wp_customize->add_panel(
			'typography_panel',
			array(
				'title'       => esc_html__( 'Typography', 'helpdesk' ),
				'priority'    => 10,
				'description' => '',
			)
		);

		$wp_customize->add_section(
			'site_title_typography_section',
			array(
				'title'       => esc_html__( 'Site Title', 'helpdesk' ),
				'priority'    => 10,
				'description' => '',
				'panel' => 'typography_panel',
			)
		);

		$wp_customize->add_section(
			'headings_typography_section',
			array(
				'title'       => esc_html__( 'Headings', 'helpdesk' ),
				'priority'    => 10,
				'description' => '',
				'panel' => 'typography_panel',
			)
		);

		$wp_customize->add_section(
			'menu_typography_section',
			array(
				'title'       => esc_html__( 'Menu', 'helpdesk' ),
				'priority'    => 10,
				'description' => '',
				'panel' => 'typography_panel',
			)
		);

		$wp_customize->add_section(
			'body_typography_section',
			array(
				'title'       => esc_html__( 'Body', 'helpdesk' ),
				'priority'    => 10,
				'description' => '',
				'panel' => 'typography_panel',
			)
		);

		// Site Header
		$wp_customize->add_section(
			'site_header_section',
			array(
				'title'       => esc_html__( 'Site Header', 'helpdesk' ),
				'priority'    => 10,
				'description' => '',
			)
		);

		// Site Sub Header
		$wp_customize->add_section(
			'site_sub_header_section',
			array(
				'title'       => esc_html__( 'Site Sub Header', 'helpdesk' ),
				'priority'    => 10,
				'description' => '',
			)
		);

		// Site Skin
		$wp_customize->add_section(
			'site_skin_section',
			array(
				'title'       => esc_html__( 'Site Skin', 'helpdesk' ),
				'priority'    => 10,
				'description' => '',
			)
		);

		// Site Blog Layout
		$wp_customize->add_section(
			'site_blog_section',
			array(
				'title'       => esc_html__( 'Site Blog', 'helpdesk' ),
				'priority'    => 10,
				'description' => '',
			)
		);

		// Site Sidebar Layout
		$wp_customize->add_section(
			'site_sidebar_layout',
			array(
				'title'       => esc_html__( 'Site Sidebar', 'helpdesk' ),
				'priority'    => 10,
				'description' => '',
			)
		);

		// Site Knowledge Base
		$wp_customize->add_panel(
			'knowledge_panel',
			array(
				'title'       => esc_html__( 'Knowledge Base', 'helpdesk' ),
				'priority'    => 11,
				'description' => '',
			)
		);

		$wp_customize->add_section(
			'site_knowledge_section_two',
			array(
				'title'       => esc_html__( 'Layout Two', 'helpdesk' ),
				'priority'    => 10,
				'description' => '',
				'panel' => 'knowledge_panel',
			)
		);

		// Site Footer
		$wp_customize->add_panel(
			'site_footer_panel',
			array(
				'title'       => esc_html__( 'Site Footer', 'helpdesk' ),
				'priority'    => 11,
				'description' => '',
			)
		);

		$wp_customize->add_section(
			'site_footer_section',
			array(
				'title'       => esc_html__( 'Footer Section', 'helpdesk' ),
				'priority'    => 10,
				'description' => '',
				'panel' => 'site_footer_panel',
			)
		);

		// Site Copyright
		$wp_customize->add_section(
			'site_copyright_section',
			array(
				'title'       => esc_html__( 'Copyright Section', 'helpdesk' ),
				'priority'    => 11,
				'description' => '',
				'panel' => 'site_footer_panel',
			)
		);
	}
	
	public function add_fields( $fields ) {
		/**
		 *  Site Layout
		 */
		$fields[] = array(
			'type'     => 'radio-image',
			'settings' => 'site_layout',
			'label'    => esc_html__( 'Site Layout', 'helpdesk' ),
			'section'  => 'site_layout',
			'priority' => 10,
			'default'  => 'wide',
			'choices'  => [
				'boxed' => get_template_directory_uri() . '/assets/images/boxed.png',
				'wide'  => get_template_directory_uri() . '/assets/images/wide.png',
			],
		);

		/**
		 *  Site Container Width
		 */
		$fields[] = array(
			'type'			 => 'dimension',
			'settings'		 => 'site_container_width',
			'label'			 => esc_html__( 'Max Content Layout Width', 'helpdesk' ),
			'description'	 => esc_html__( 'Select the maximum content width for your website (px)', 'helpdesk' ),
			'section'		 => 'site_layout',
			'default'		 => '1170px',
			'priority'		 => 10,
			'transport'		 => 'auto',
			'output'		 => array(
				array(
					'element'	 => '.container',
					'function'	 => 'css',
					'property'	 => 'max-width',
				),
			),
		);

		/**
		 *  Site Loader
		 */
		$fields[] = array(
			'type' => 'switch',
			'settings'	 => 'site_loader',
			'label'		 => esc_html__( 'Site Loader ?', 'helpdesk' ),
			'section'	 => 'site_loader',
			'default'	 => '2',
			'choices'	 => array(
				'on'	 => esc_html__( 'Yes', 'helpdesk' ),
				'off'	 => esc_html__( 'No', 'helpdesk' )
			),
		);

		$fields[] = array(
			'type' => 'color',
			'settings'	 => 'site_loader_bg',
			'label'		 => esc_html__( 'Site Loader Background', 'helpdesk' ),
			'section'	 => 'site_loader',
			'default'	 => '#03A9F4',
                        'choices' => array('alpha' => true),
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => '.site-loader',
					'property' => 'background-color',
				),
			),
			'active_callback'	 => array(
				array( 'setting' => 'site_loader', 'operator' => '==', 'value' => '1' ),
			)
		);

		/*
         *  Page Mapping
         */
        $fields[] = array(
            'type'			 => 'dropdown-pages',
            'settings'		 => 'helpdesk_login_page',
            'label'			 => esc_attr__( 'Login Page', 'helpdesk' ),
            'description'	 => esc_attr__( 'You can redirect user to custom login page.', 'helpdesk' ),
            'section'		 => 'page_mapping',
            'priority'		 => 10,
            'default'		 => 0,
            'placeholder'	 => '--- Select a Page ---',
        );

        $fields[] = array(
            'type'			 => 'dropdown-pages',
            'settings'		 => 'helpdesk_registration_page',
            'label'			 => esc_attr__( 'Registration Page', 'helpdesk' ),
            'description'	 => esc_attr__( 'You can redirect user to custom registration page.', 'helpdesk' ),
            'section'		 => 'page_mapping',
            'priority'		 => 10,
            'default'		 => 0,
            'placeholder'	 => '--- Select a Page ---',
        );

        $fields[] = array(
            'type'			 => 'dropdown-pages',
            'settings'		 => 'helpdesk_404_page',
            'label'			 => esc_attr__( '404', 'helpdesk' ),
            'description'	 => esc_attr__( 'You can redirect user to custom 404 page.', 'helpdesk' ),
            'section'		 => 'page_mapping',
            'priority'		 => 10,
            'default'		 => 0,
            'placeholder'	 => '--- Select a Page ---',
        );

		/**
		 *  Site Title Typography
		 */
		$fields[] = array(
			'type'        => 'typography',
			'settings'    => 'site_title_typography_option',
			'label'       => esc_html__( 'Site Title Settings', 'helpdesk' ),
			'section'     => 'site_title_typography_section',
			'default'     => array(
				'font-family'    => 'Open Sans',
				'variant'        => '600',
				'font-size'      => '38px',
				'line-height'    => '1.2',
				'letter-spacing' => '0',
				'color'          => '#333333',
				'text-transform' => 'none',
				'text-align'     => 'left',
			),
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => '.site-title a',
				),
			),
		);

		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'site_title_hover_color',
			'label'       => esc_html__( 'Site Title Hover Color', 'helpdesk' ),
			'section'     => 'site_title_typography_section',
			'default'     => '#03A9F4',
                        'choices' => array('alpha' => true),
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => '.site-title a:hover',
					'property' => 'color',
				),
			),
		);

		$fields[] = array(
			'type'        => 'typography',
			'settings'    => 'site_tagline_typography_option',
			'label'       => esc_html__( 'Site Tagline Settings', 'helpdesk' ),
			'section'     => 'site_title_typography_section',
			'default'     => array(
				'font-family'    => 'Open Sans',
				'variant'        => 'regular',
				'font-size'      => '14px',
				'line-height'    => '1.4',
				'letter-spacing' => '0',
				'color'          => '#757575',
				'text-transform' => 'none',
				'text-align'     => 'left',
			),
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => '.site-description',
				),
			),
		);

		/**
		 *  Headings Typography
		 */
		$fields[] = array(
			'type'        => 'typography',
			'settings'    => 'h1_typography_option',
			'label'       => esc_html__( 'H1 Tag Settings', 'helpdesk' ),
			'section'     => 'headings_typography_section',
			'default'     => array(
				'font-family'    => 'Open Sans',
				'variant'        => '500',
				'font-size'      => '30px',
				'line-height'    => '1.4',
				'letter-spacing' => '0',
				'color'          => '#333333',
				'text-transform' => 'none',
				'text-align'     => '',
			),
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => 'h1',
				),
			),
		);

		$fields[] = array(
			'type'        => 'typography',
			'settings'    => 'h2_typography_option',
			'label'       => esc_html__( 'H2 Tag Settings', 'helpdesk' ),
			'section'     => 'headings_typography_section',
			'default'     => array(
				'font-family'    => 'Open Sans',
				'variant'        => '500',
				'font-size'      => '24px',
				'line-height'    => '1.4',
				'letter-spacing' => '0',
				'color'          => '#333333',
				'text-transform' => 'none',
				'text-align'     => '',
			),
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => 'h2',
				),
			),
		);

		$fields[] = array(
			'type'        => 'typography',
			'settings'    => 'h3_typography_option',
			'label'       => esc_html__( 'H3 Tag Settings', 'helpdesk' ),
			'section'     => 'headings_typography_section',
			'default'     => array(
				'font-family'    => 'Open Sans',
				'variant'        => '500',
				'font-size'      => '22px',
				'line-height'    => '1.4',
				'letter-spacing' => '0',
				'color'          => '#333333',
				'text-transform' => 'none',
				'text-align'     => '',
			),
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => 'h3',
				),
			),
		);

		$fields[] = array(
			'type'        => 'typography',
			'settings'    => 'h4_typography_option',
			'label'       => esc_html__( 'H4 Tag Settings', 'helpdesk' ),
			'section'     => 'headings_typography_section',
			'default'     => array(
				'font-family'    => 'Open Sans',
				'variant'        => '500',
				'font-size'      => '20px',
				'line-height'    => '1.4',
				'letter-spacing' => '0',
				'color'          => '#333333',
				'text-transform' => 'none',
				'text-align'     => '',
			),
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => 'h4',
				),
			),
		);

		$fields[] = array(
			'type'        => 'typography',
			'settings'    => 'h5_typography_option',
			'label'       => esc_html__( 'H5 Tag Settings', 'helpdesk' ),
			'section'     => 'headings_typography_section',
			'default'     => array(
				'font-family'    => 'Open Sans',
				'variant'        => '500',
				'font-size'      => '18px',
				'line-height'    => '1.4',
				'letter-spacing' => '0',
				'color'          => '#333333',
				'text-transform' => 'none',
				'text-align'     => '',
			),
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => 'h5',
				),
			),
		);

		$fields[] = array(
			'type'        => 'typography',
			'settings'    => 'h6_typography_option',
			'label'       => esc_html__( 'H6 Tag Settings', 'helpdesk' ),
			'section'     => 'headings_typography_section',
			'default'     => array(
				'font-family'    => 'Open Sans',
				'variant'        => '500',
				'font-size'      => '16px',
				'line-height'    => '1.4',
				'letter-spacing' => '0',
				'color'          => '#333333',
				'text-transform' => 'none',
				'text-align'     => '',
			),
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => 'h6',
				),
			),
		);

		/**
		 *  Menu Typography
		 */
		$fields[] = array(
			'type'        => 'typography',
			'settings'    => 'menu_typography_option',
			'label'       => esc_html__( 'Menu Settings', 'helpdesk' ),
			'section'     => 'menu_typography_section',
			'default'     => array(
				'font-family'    => 'Open Sans',
				'variant'        => '500',
				'font-size'      => '14px',
				'line-height'    => '1.6',
				'letter-spacing' => '0.02em',
				'color'          => '#222222',
				'text-transform' => 'none',
				'text-align'     => 'left',
			),
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => '.main-navigation a, .main-navigation ul li a, .nav--toggle-sub li.menu-item-has-children, .nav--toggle-small .menu-toggle',
				),
				array(
					'element' => '.nav--toggle-small .menu-toggle',
					'property' => 'border-color',
				),
			),
		);

		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'menu_hover_color',
			'label'       => esc_html__( 'Menu Hover Color', 'helpdesk' ),
			'section'     => 'menu_typography_section',
			'default'     => '#03A9F4',
                        'choices' => array('alpha' => true),
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => '.main-navigation a:hover, .main-navigation ul li a:hover, .nav--toggle-sub li.menu-item-has-children:hover, .nav--toggle-small .menu-toggle:hover',
					'property' => 'color',
				),
				array(
					'element' => '.nav--toggle-small .menu-toggle:hover',
					'property' => 'border-color',
				),
			),
		);

		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'menu_active_color',
			'label'       => esc_html__( 'Menu Active Color', 'helpdesk' ),
			'section'     => 'menu_typography_section',
			'default'     => '#03A9F4',
                        'choices' => array('alpha' => true),
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => '.main-navigation ul li.current-menu-item>a',
					'property' => 'color',
				),
			),
		);

		/**
		 * Body Typography
		 */
		$fields[] = array(
			'type'        => 'typography',
			'settings'    => 'typography_option',
			'label'       => esc_html__( 'Settings', 'helpdesk' ),
			'section'     => 'body_typography_section',
			'default'     => array(
				'font-family'    => 'Open Sans',
				'variant'        => 'regular',
				'font-size'      => '14px',
				'line-height'    => '1.6',
				'letter-spacing' => '0',
				'color'          => '#505050',
				'text-transform' => 'none',
				'text-align'     => 'left',
			),
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => 'body:not(.block-editor-page):not(.wp-core-ui), body:not(.block-editor-page):not(.wp-core-ui) pre',
				),
			),
		);

		/**
		 * Site Header
		 */
		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'site_header_bg_color',
			'label'       => esc_html__( 'Header Background Color', 'helpdesk' ),
			'section'     => 'site_header_section',
			'default'     => '#ffffff',
                        'choices' => array('alpha' => true),
			'priority'    => 10,
			'output'      => array(
				[
                    'element' => '.site-header-wrapper, .layout-boxed .site-header-wrapper, .nav--toggle-sub ul ul, #user-profile-menu, .bp-header-submenu, .main-navigation .primary-menu-container, .main-navigation #user-profile-menu, .main-navigation .bp-header-submenu',
                    'property' => 'background-color',
                ],
                [
                    'element' => '.site-header-wrapper',
                    'property' => 'border-color',
                ],
                [
                    'element' => '.menu-item--has-toggle>ul.sub-menu:before, .nav--toggle-sub ul.user-profile-menu .sub-menu:before, .bp-header-submenu:before, .user-profile-menu:before',
                    'property' => 'border-top-color',
                ],
                [
                    'element' => '.menu-item--has-toggle>ul.sub-menu:before, .nav--toggle-sub ul.user-profile-menu .sub-menu:before, .bp-header-submenu:before, .user-profile-menu:before',
                    'property' => 'border-right-color',
                ],
			),
		);
		
		/**
		 *  Site Sub Header
		 */
		$fields[] = array(
			'type'     => 'switch',
			'settings' => 'site_sub_header_bg',
			'label'    => esc_html__( 'Customize Background ?', 'helpdesk' ),
			'section'  => 'site_sub_header_section',
			'default'  => 'off',
			'choices'  => array(
				'on'  => esc_html__( 'Yes','helpdesk' ),
				'off' => esc_html__( 'No', 'helpdesk' )
			),
		);

		$fields[] = array(
			'type'        => 'background',
			'settings'    => 'sub_header_background_setting',
			'label'       => esc_html__( 'Background Control', 'helpdesk' ),
			'section'     => 'site_sub_header_section',
			'default'     => [
				'background-color'      => 'rgba(255,255,255,0.5)',
				'background-image'      => '',
				'background-repeat'     => 'repeat',
				'background-position'   => 'center center',
				'background-size'       => 'cover',
				'background-attachment' => 'scroll',
			],
			'transport'   => 'auto',
			'output'      => [
				[
					'element' => '.site-sub-header',
				],
			],
			'active_callback' => array(
				array( 'setting' => 'site_sub_header_bg', 'operator' => '==', 'value' => '1' )
			)
		);

		$fields[] = array(
			'type'        => 'typography',
			'settings'    => 'site_sub_header_typography',
			'label'       => esc_html__( 'Content Typography', 'helpdesk' ),
			'section'     => 'site_sub_header_section',
			'default'     => array(
				'font-family'    => '',
				'variant'        => '',
				'font-size'      => '',
				'line-height'    => '',
				'letter-spacing' => '',
				'color'          => '#333333',
				'text-transform' => 'none',
			),
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => '.site-sub-header, .site-sub-header .entry-header .entry-title, .site-sub-header .page-header .page-title, .site-sub-header .entry-header, .site-sub-header .page-header, .site-sub-header .entry-title, .site-sub-header .page-title',
				),
			),
		);

		$fields[] = array(
			'type'     => 'switch',
			'settings' => 'site_breadcrumbs',
			'label'    => esc_html__( 'Site Breadcrumbs?', 'helpdesk' ),
			'section'  => 'site_sub_header_section',
			'default'  => 'off',
			'choices'  => array(
				'on'  => esc_html__( 'Yes','helpdesk' ),
				'off' => esc_html__( 'No', 'helpdesk' ),
			),
		);

		/**
		 * Site Skin
		 */
		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'body_background_color',
			'label'       => esc_html__( 'Body Background Color', 'helpdesk' ),
			'section'     => 'site_skin_section',
			'default'     => '#f7f7f9',
                        'choices' => array('alpha' => true),
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => 'body',
					'property' => 'background-color',
				),
			),
		);

		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'site_primary_color',
			'label'       => esc_html__( 'Theme Color', 'helpdesk' ),
			'section'     => 'site_skin_section',
			'default'     => '#03A9F4',
                        'choices' => array('alpha' => true),
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => '.helpdesk-breadcrumbs a, #breadcrumbs a, .pagination .current',
                    'property' => 'color',
				),
				array(
					'element' => '',
					'property' => 'background-color',
				),
				array(
                    'element' => '',
					'property' => 'border-color',
				),
			),
		);

		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'site_links_color',
			'label'       => esc_html__( 'Link Color', 'helpdesk' ),
			'section'     => 'site_skin_section',
			'default'     => '#03A9F4',
                        'choices' => array('alpha' => true),
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => 'a',
					'property' => 'color',
				),
			),
		);

		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'site_links_focus_hover_color',
			'label'       => esc_html__( 'Link Hover', 'helpdesk' ),
			'section'     => 'site_skin_section',
			'default'     => '#222222',
                        'choices' => array('alpha' => true),
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => 'a:hover, a:active, a:focus',
					'property' => 'color',
				),
			),
		);

		$fields[] = array(
			'type'=> 'custom',
			'settings' => 'custom-skin-divider',
			'section'     => 'site_skin_section',
			'default'     => '<hr>',
		);

		// Site Buttons
		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'site_buttons_background_color',
			'label'       => esc_html__( 'Button Background Color', 'helpdesk' ),
			'section'     => 'site_skin_section',
			'default'     => '#03A9F4',
                        'choices' => array('alpha' => true),
			'priority'    => 10,
			'choices'	  => array( 'alpha' => true ),
			'output'      => array(
				array(
					'element' => 'a.read-more.button, button:not(.menu-toggle), input[type="button"], input[type="reset"], input[type="submit"]',
                    'property' => 'background',
				),
			),
		);

		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'site_buttons_background_hover_color',
			'label'       => esc_html__( 'Button Background Hover Color', 'helpdesk' ),
			'section'     => 'site_skin_section',
			'default'     => '#222222',
                        'choices' => array('alpha' => true),
			'priority'    => 10,
			'choices'	  => array( 'alpha' => true ),
			'output'      => array(
				array(
					'element' => 'a.read-more.button:hover, button:not(.menu-toggle):hover, input[type="button"]:hover, input[type="reset"]:hover, input[type="submit"]:hover, button:active, button:focus, input[type="button"]:active, input[type="button"]:focus, input[type="reset"]:active, input[type="reset"]:focus, input[type="submit"]:active, input[type="submit"]:focus',
                    'property' => 'background',
				),
			),
		);

		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'site_buttons_text_color',
			'label'       => esc_html__( 'Button Text Color', 'helpdesk' ),
			'section'     => 'site_skin_section',
			'default'     => '#ffffff',
                        'choices' => array('alpha' => true),
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => 'a.read-more.button, button:not(.menu-toggle), input[type="button"], input[type="reset"], input[type="submit"]',
					'property' => 'color',
				),
			),
		);

		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'site_buttons_text_hover_color',
			'label'       => esc_html__( 'Button Text Hover Color', 'helpdesk' ),
			'section'     => 'site_skin_section',
			'default'     => '#ffffff',
                        'choices' => array('alpha' => true),
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => 'a.read-more.button:hover, button:not(.menu-toggle):hover, input[type="button"]:hover, input[type="reset"]:hover, input[type="submit"]:hover, button:active, button:focus, input[type="button"]:active, input[type="button"]:focus, input[type="reset"]:active, input[type="reset"]:focus, input[type="submit"]:active, input[type="submit"]:focus',
					'property' => 'color',
				),
			),
		);

		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'site_buttons_border_color',
			'label'       => esc_html__( 'Button Border Color', 'helpdesk' ),
			'section'     => 'site_skin_section',
			'default'     => '#03A9F4',
                        'choices' => array('alpha' => true),
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => 'a.read-more.button, button:not(.menu-toggle), input[type="button"], input[type="reset"], input[type="submit"] ',
					'property' => 'border-color',
				),
			),
		);

		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'site_buttons_border_hover_color',
			'label'       => esc_html__( 'Button Border Hover Color', 'helpdesk' ),
			'section'     => 'site_skin_section',
			'default'     => '#222222',
                        'choices' => array('alpha' => true),
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => 'a.read-more.button:hover, button:not(.menu-toggle):hover, input[type="button"]:hover, input[type="reset"]:hover, input[type="submit"]:hover, button:active, button:focus, input[type="button"]:active, input[type="button"]:focus, input[type="reset"]:active, input[type="reset"]:focus, input[type="submit"]:active, input[type="submit"]:focus',
					'property' => 'border-color',
				),
			),
		);

		/**
		 *  Site Blog Layout
		 */
		$fields[] = array(
			'type'     => 'radio-image',
			'settings' => 'blog_layout_option',
			'label'    => esc_html__( 'Blog Layout', 'helpdesk' ),
			'section'  => 'site_blog_section',
			'priority' => 10,
			'default'  => 'default-layout',
			'choices'  => [
				'default-layout' => get_template_directory_uri() . '/assets/images/one-column.png',
				'grid-layout' => get_template_directory_uri() . '/assets/images/one-half-column.png',
				'masonry-layout' => get_template_directory_uri() . '/assets/images/one-third-column.png',
			],
		);

		$fields[] = array(
			'type'        => 'select', 
			'settings'    => 'post_per_row',
			'label'       => esc_html__( 'Post Per Row', 'helpdesk' ),
			'section'     => 'site_blog_section',
			'default'     => '4',
			'priority'    => 10,
			'choices'     => array( 
				'12' => esc_html__( 'Column 1', 'helpdesk' ), 
				'6' => esc_html__( 'Column 2', 'helpdesk' ),
				'4' => esc_html__( 'Column 3', 'helpdesk' ),  
				'3' => esc_html__( 'Column 4', 'helpdesk' ),
			), 
			'active_callback'	 => array(
				array( 'setting' => 'blog_layout_option', 'operator' => '!==', 'value' => 'default-layout' ),
			)
		);
		
		/**
		 *  Site Sidebar Layout
		 */
		$fields[] = array(
			'type'     => 'radio-image',
			'settings' => 'sidebar_option',
			'label'    => esc_html__( 'Sidebar Layout', 'helpdesk' ),
			'section'  => 'site_sidebar_layout',
			'priority' => 10,
			'default'  => 'right',
			'choices'  => [
				'none' => get_template_directory_uri() . '/assets/images/without-sidebar.png',
				'left' => get_template_directory_uri() . '/assets/images/left-sidebar.png',
				'right' => get_template_directory_uri() . '/assets/images/right-sidebar.png',
				'both' => get_template_directory_uri() . '/assets/images/both-sidebar.png',
			],
		);

		if ( function_exists('is_bbpress') ) {
			$fields[] = array(
				'type'     => 'radio-image',
				'settings' => 'bbpress_sidebar_option',
				'label'    => esc_html__( 'bbPress Sidebar Layout', 'helpdesk' ),
				'section'  => 'site_sidebar_layout',
				'priority' => 10,
				'default'  => 'right',
				'choices'  => [
					'none' => get_template_directory_uri() . '/assets/images/without-sidebar.png',
					'left' => get_template_directory_uri() . '/assets/images/left-sidebar.png',
					'right' => get_template_directory_uri() . '/assets/images/right-sidebar.png',
					'both' => get_template_directory_uri() . '/assets/images/both-sidebar.png',
				],
			);
		}

		$fields[] = array(
			'type'     => 'switch',
			'settings' => 'sticky_sidebar_option',
			'label'    => esc_html__( 'Sticky Sidebar ?', 'helpdesk' ),
			'section'  => 'site_sidebar_layout',
			'default'  => '1',
			'choices'  => [
				'on' => esc_html__( 'Yes','helpdesk' ),
				'off'  => esc_html__( 'No','helpdesk' ),
			],
		);

		/**
		 *  Site Knowledge Base
		 */
		$fields[] = array(
			'type'     => 'radio',
			'settings' => 'helpdesk_page_title',
			'label' => __( 'Show page title', 'helpdesk' ),
			'section' => 'site_knowledge_section_two',
			'default' => 'yes',
			'choices' => array(
				'yes' => __('Yes', 'helpdesk'),
				'no' => __('No', 'helpdesk'),
			),
		);

		$fields[] = array(
			'type'     => 'radio',
			'settings' => 'helpdesk_view_all',
			'label' => __( 'Show View All link', 'helpdesk' ),
			'section' => 'site_knowledge_section_two',
			'default' => 'no',
			'choices' => array(
				'yes' => __('Yes', 'helpdesk'),
				'no' => __('No', 'helpdesk'),
			),
		);

		$fields[] = array(
			'type' => 'radio',
			'settings' => 'helpdesk_cat_description',
			'label' => __( 'Show category description', 'helpdesk' ),
			'section' => 'site_knowledge_section_two',
			'default' => 'no',
			'choices' => array(
				'yes' => __('Yes', 'helpdesk'),
				'no' => __('No', 'helpdesk'),
			),
		);

		$fields[] = array(
			'type' => 'input',
			'settings' => 'helpdesk_exclude',
			'label' => __( 'Exclude category by ID', 'helpdesk' ),
			'description' => __( 'Use a comma to separate multiple categories.', 'helpdesk' ),
			'section' => 'site_knowledge_section_two',
		);

		$fields[] = array(
			'type' => 'number',
			'settings' => 'helpdesk_posts',
			'label' => __( 'Posts per category', 'helpdesk' ),
			'description' => __( 'Only numeric characters allowed.', 'helpdesk' ),
			'section' => 'site_knowledge_section_two',
		);

		$fields[] = array(
			'type' => 'radio',
			'settings' => 'helpdesk_order',
			'label' => __( 'Order posts', 'helpdesk' ),
			'section' => 'site_knowledge_section_two',
			'default' => 'date',
			'choices' => array(
				'date' => __('By date', 'helpdesk'),
				'name' => __('By name', 'helpdesk'),
			),
		);

		/**
		 *  Site Footer
		 */
		$fields[] = array(
			'type'     => 'switch',
			'settings' => 'site_footer_bg',
			'label'    => esc_html__( 'Customize Background ?', 'helpdesk' ),
			'section'  => 'site_footer_section',
			'default'  => 'off',
			'choices'  => array(
				'on'  => esc_html__( 'Yes','helpdesk' ),
				'off' => esc_html__( 'No', 'helpdesk' )
			),
		);

		$fields[] = array(
			'type'        => 'background',
			'settings'    => 'background_setting',
			'label'       => esc_html__( 'Background Control', 'helpdesk' ),
			'section'     => 'site_footer_section',
			'default'     => [
				'background-color'      => 'rgba(255,255,255,0.8)',
				'background-image'      => '',
				'background-repeat'     => 'repeat',
				'background-position'   => 'center center',
				'background-size'       => 'cover',
				'background-attachment' => 'scroll',
			],
			'transport'   => 'auto',
			'output'      => [
				[
					'element' => '.site-footer-wrapper',
				],
			],
			'active_callback' => array(
				array( 'setting' => 'site_footer_bg', 'operator' => '==', 'value' => '1' )
			)
		);

		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'site_footer_title_color',
			'label'       => esc_html__( 'Title Color', 'helpdesk' ),
			'section'     => 'site_footer_section',
			'default'     => '#333333',
                        'choices' => array('alpha' => true),
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => '.site-footer .widget-title',
					'property' => 'color',
				),
			),
		);

		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'site_footer_content_color',
			'label'       => esc_html__( 'Content Color', 'helpdesk' ),
			'section'     => 'site_footer_section',
			'default'     => '#505050',
                        'choices' => array('alpha' => true),
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => '.site-footer',
					'property' => 'color',
				),
			),
		);

		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'site_footer_links_color',
			'label'       => esc_html__( 'Link Color', 'helpdesk' ),
			'section'     => 'site_footer_section',
			'default'     => '#222222',
                        'choices' => array('alpha' => true),
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => '.site-footer a',
					'property' => 'color',
				),
			),
		);

		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'site_footer_links_hover_color',
			'label'       => esc_html__( 'Link Hover', 'helpdesk' ),
			'section'     => 'site_footer_section',
			'default'     => '#03A9F4',
                        'choices' => array('alpha' => true),
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => '.site-footer a:hover, .site-footer a:active',
					'property' => 'color',
				),
			),
		);

		/**
		 *  Site Copyright
		 */
		$fields[] = array(
			'type'        => 'textarea',
			'settings'    => 'site_copyright_text',
			'label'       => esc_html__( 'Add Content', 'helpdesk' ),
			'section'     => 'site_copyright_section',
			'default' 	  => esc_html__( 'Copyright © [current_year] [site_title] | Powered by [theme_author]', 'helpdesk' ),
			'priority'    => 10,
		);

		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'site_copyright_background_color',
			'label'       => esc_html__( 'Background Color', 'helpdesk' ),
			'section'     => 'site_copyright_section',
			'default'     => '#ffffff',
                        'choices' => array('alpha' => true),
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => '.site-info',
					'property' => 'background-color',
				),
			),
		);

		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'site_copyright_border_color',
			'label'       => esc_html__( 'Border Color', 'helpdesk' ),
			'section'     => 'site_copyright_section',
			'default'     => '#e8e8e8',
                        'choices' => array('alpha' => true),
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => '.site-info',
					'property' => 'border-color',
				),
			),
		);

		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'site_copyright_content_color',
			'label'       => esc_html__( 'Content Color', 'helpdesk' ),
			'section'     => 'site_copyright_section',
			'default'     => '#505050',
                        'choices' => array('alpha' => true),
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => '.site-info',
					'property' => 'color',
				),
			),
		);

		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'site_copyright_links_color',
			'label'       => esc_html__( 'Link Color', 'helpdesk' ),
			'section'     => 'site_copyright_section',
			'default'     => '#222222',
                        'choices' => array('alpha' => true),
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => '.site-info a',
					'property' => 'color',
				),
			),
		);

		$fields[] = array(
			'type'        => 'color',
			'settings'    => 'site_copyright_links_hover_color',
			'label'       => esc_html__( 'Link Hover Color', 'helpdesk' ),
			'section'     => 'site_copyright_section',
			'default'     => '#03A9F4',
                        'choices' => array('alpha' => true),
			'priority'    => 10,
			'output'      => array(
				array(
					'element' => '.site-info a:hover',
					'property' => 'color',
				),
			),
		);

		return $fields;
	}
}
