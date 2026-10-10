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
			'settings' => 'headings_typography_option',
			'label'    => esc_html__( 'Headings (H1 to H6)', 'knowx' ),
			'section'  => 'headings_typography_section',
			'default'  => array(
				'font-family'    => 'Open Sans',
				'variant'        => '500',
				'line-height'    => '1.4',
				'letter-spacing' => '0',
				'text-transform' => 'none',
				'text-align'     => '',
			),
			'priority' => 10,
			'output'   => array(
				array(
					'element' => 'h1, h2, h3, h4, h5, h6',
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
