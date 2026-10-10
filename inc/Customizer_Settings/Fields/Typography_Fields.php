<?php
/**
 * Typography Fields
 *
 * @package knowx
 */

defined( 'ABSPATH' ) || exit;

	/**
	 *  Site Title Typography
	 */
	\KnowX\KnowX\Customizer_Framework\Field::add(
		'typography',
		array(
			'settings' => 'site_title_typography_option',
			'label'    => esc_html__( 'Site Title Settings', 'knowx' ),
			'section'  => 'site_title_typography_section',
			'default'  => array(
				'font-family'    => 'Open Sans',
				'variant'        => '600',
				'font-size'      => '38px',
				'line-height'    => '1.2',
				'letter-spacing' => '0',
				'color'          => '#333333',
				'text-transform' => 'none',
				'text-align'     => 'left',
			),
			'priority' => 10,
			'output'   => array(
				array(
					'element' => '.site-title a',
				),
			),
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'color',
		array(
			'settings' => 'site_title_hover_color',
			'label'    => esc_html__( 'Site Title Hover Color', 'knowx' ),
			'section'  => 'site_title_typography_section',
			'default'  => '#03A9F4',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'typography',
		array(
			'settings' => 'site_tagline_typography_option',
			'label'    => esc_html__( 'Site Tagline Settings', 'knowx' ),
			'section'  => 'site_title_typography_section',
			'default'  => array(
				'font-family'    => 'Open Sans',
				'variant'        => 'regular',
				'font-size'      => '14px',
				'line-height'    => '1.4',
				'letter-spacing' => '0',
				'color'          => '#757575',
				'text-transform' => 'none',
				'text-align'     => 'left',
			),
			'priority' => 10,
			'output'   => array(
				array(
					'element' => '.site-description',
				),
			),
		)
	);

	/**
	 *  Headings Typography
	 */
	\KnowX\KnowX\Customizer_Framework\Field::add(
		'typography',
		array(
			'settings' => 'h1_typography_option',
			'label'    => esc_html__( 'H1 Tag Settings', 'knowx' ),
			'section'  => 'headings_typography_section',
			'default'  => array(
				'font-family'    => 'Open Sans',
				'variant'        => '500',
				'font-size'      => '30px',
				'line-height'    => '1.4',
				'letter-spacing' => '0',
				'color'          => '#333333',
				'text-transform' => 'none',
				'text-align'     => '',
			),
			'priority' => 10,
			'output'   => array(
				array(
					'element' => 'h1',
				),
			),
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'typography',
		array(
			'settings' => 'h2_typography_option',
			'label'    => esc_html__( 'H2 Tag Settings', 'knowx' ),
			'section'  => 'headings_typography_section',
			'default'  => array(
				'font-family'    => 'Open Sans',
				'variant'        => '500',
				'font-size'      => '24px',
				'line-height'    => '1.4',
				'letter-spacing' => '0',
				'color'          => '#333333',
				'text-transform' => 'none',
				'text-align'     => '',
			),
			'priority' => 10,
			'output'   => array(
				array(
					'element' => 'h2',
				),
			),
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'typography',
		array(
			'settings' => 'h3_typography_option',
			'label'    => esc_html__( 'H3 Tag Settings', 'knowx' ),
			'section'  => 'headings_typography_section',
			'default'  => array(
				'font-family'    => 'Open Sans',
				'variant'        => '500',
				'font-size'      => '22px',
				'line-height'    => '1.4',
				'letter-spacing' => '0',
				'color'          => '#333333',
				'text-transform' => 'none',
				'text-align'     => '',
			),
			'priority' => 10,
			'output'   => array(
				array(
					'element' => 'h3',
				),
			),
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'typography',
		array(
			'settings' => 'h4_typography_option',
			'label'    => esc_html__( 'H4 Tag Settings', 'knowx' ),
			'section'  => 'headings_typography_section',
			'default'  => array(
				'font-family'    => 'Open Sans',
				'variant'        => '500',
				'font-size'      => '20px',
				'line-height'    => '1.4',
				'letter-spacing' => '0',
				'color'          => '#333333',
				'text-transform' => 'none',
				'text-align'     => '',
			),
			'priority' => 10,
			'output'   => array(
				array(
					'element' => 'h4',
				),
			),
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'typography',
		array(
			'settings' => 'h5_typography_option',
			'label'    => esc_html__( 'H5 Tag Settings', 'knowx' ),
			'section'  => 'headings_typography_section',
			'default'  => array(
				'font-family'    => 'Open Sans',
				'variant'        => '500',
				'font-size'      => '18px',
				'line-height'    => '1.4',
				'letter-spacing' => '0',
				'color'          => '#333333',
				'text-transform' => 'none',
				'text-align'     => '',
			),
			'priority' => 10,
			'output'   => array(
				array(
					'element' => 'h5',
				),
			),
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'typography',
		array(
			'settings' => 'h6_typography_option',
			'label'    => esc_html__( 'H6 Tag Settings', 'knowx' ),
			'section'  => 'headings_typography_section',
			'default'  => array(
				'font-family'    => 'Open Sans',
				'variant'        => '500',
				'font-size'      => '16px',
				'line-height'    => '1.4',
				'letter-spacing' => '0',
				'color'          => '#333333',
				'text-transform' => 'none',
				'text-align'     => '',
			),
			'priority' => 10,
			'output'   => array(
				array(
					'element' => 'h6',
				),
			),
		)
	);

	/**
	 *  Menu Typography
	 */
	\KnowX\KnowX\Customizer_Framework\Field::add(
		'typography',
		array(
			'settings' => 'menu_typography_option',
			'label'    => esc_html__( 'Menu Settings', 'knowx' ),
			'section'  => 'menu_typography_section',
			'default'  => array(
				'font-family'    => 'Open Sans',
				'variant'        => '500',
				'font-size'      => '14px',
				'line-height'    => '1.6',
				'letter-spacing' => '0.02em',
				'color'          => '#222222',
				'text-transform' => 'none',
				'text-align'     => 'left',
			),
			'priority' => 10,
			'output'   => array(
				array(
					'element' => '.main-navigation a, .main-navigation ul li a, .nav--toggle-sub li.menu-item-has-children, .nav--toggle-small .menu-toggle',
				),
				array(
					'element'  => '.nav--toggle-small .menu-toggle',
					'property' => 'border-color',
				),
			),
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'color',
		array(
			'settings' => 'menu_hover_color',
			'label'    => esc_html__( 'Menu Hover Color', 'knowx' ),
			'section'  => 'menu_typography_section',
			'default'  => '#03A9F4',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'color',
		array(
			'settings' => 'menu_active_color',
			'label'    => esc_html__( 'Menu Active Color', 'knowx' ),
			'section'  => 'menu_typography_section',
			'default'  => '#03A9F4',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
		)
	);

	/**
	 * Body Typography
	 */
	\KnowX\KnowX\Customizer_Framework\Field::add(
		'typography',
		array(
			'settings' => 'typography_option',
			'label'    => esc_html__( 'Settings', 'knowx' ),
			'section'  => 'body_typography_section',
			'default'  => array(
				'font-family'    => 'Open Sans',
				'variant'        => 'regular',
				'font-size'      => '14px',
				'line-height'    => '1.6',
				'letter-spacing' => '0',
				'color'          => '#505050',
				'text-transform' => 'none',
				'text-align'     => 'left',
			),
			'priority' => 10,
			'output'   => array(
				array(
					'element' => 'body:not(.block-editor-page):not(.wp-core-ui), body:not(.block-editor-page):not(.wp-core-ui) pre',
				),
			),
		)
	);
