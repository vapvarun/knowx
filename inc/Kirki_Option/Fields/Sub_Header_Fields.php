<?php
/**
 * Sub Header Kirki Fields
 *
 * @package knowx
 */

defined( 'ABSPATH' ) || exit;

	/**
	 *  Site Sub Header
	 */
	new \Kirki\Field\Checkbox_Switch(
		array(
			'settings' => 'site_sub_header',
			'label'    => esc_html__( 'Site Sub Header?', 'knowx' ),
			'section'  => 'site_sub_header_section',
			'default'  => 'on',
			'choices'  => array(
				'on'  => esc_html__( 'Yes', 'knowx' ),
				'off' => esc_html__( 'No', 'knowx' ),
			),
		)
	);

	new \Kirki\Field\Checkbox_Switch(
		array(
			'settings'        => 'site_sub_header_bg',
			'label'           => esc_html__( 'Customize Background ?', 'knowx' ),
			'section'         => 'site_sub_header_section',
			'default'         => 'off',
			'choices'         => array(
				'on'  => esc_html__( 'Yes', 'knowx' ),
				'off' => esc_html__( 'No', 'knowx' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'site_sub_header',
					'operator' => '==',
					'value'    => '1',
				),
			),
		)
	);

	new \Kirki\Field\Background(
		array(
			'settings'        => 'sub_header_background_setting',
			'label'           => esc_html__( 'Background Control', 'knowx' ),
			'section'         => 'site_sub_header_section',
			'default'         => array(
				'background-color'      => 'rgba(255,255,255,0.5)',
				'background-image'      => '',
				'background-repeat'     => 'repeat',
				'background-position'   => 'center center',
				'background-size'       => 'cover',
				'background-attachment' => 'scroll',
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'element' => '.site-sub-header',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'site_sub_header_bg',
					'operator' => '==',
					'value'    => '1',
				),
			),
		)
	);

	new \Kirki\Field\Typography(
		array(
			'settings'        => 'site_sub_header_typography',
			'label'           => esc_html__( 'Content Typography', 'knowx' ),
			'section'         => 'site_sub_header_section',
			'default'         => array(
				'font-family'    => '',
				'variant'        => '',
				'font-size'      => '',
				'line-height'    => '',
				'letter-spacing' => '',
				'color'          => '#333333',
				'text-transform' => 'none',
			),
			'priority'        => 10,
			'output'          => array(
				array(
					'element' => '.site-sub-header, .site-sub-header .entry-header .entry-title, .site-sub-header .page-header .page-title, .site-sub-header .entry-header, .site-sub-header .page-header, .site-sub-header .entry-title, .site-sub-header .page-title',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'site_sub_header',
					'operator' => '==',
					'value'    => '1',
				),
			),
		)
	);

	new \Kirki\Field\Checkbox_Switch(
		array(
			'settings'        => 'site_breadcrumbs',
			'label'           => esc_html__( 'Site Breadcrumbs?', 'knowx' ),
			'section'         => 'site_sub_header_section',
			'default'         => 'on',
			'choices'         => array(
				'on'  => esc_html__( 'Yes', 'knowx' ),
				'off' => esc_html__( 'No', 'knowx' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'site_sub_header',
					'operator' => '==',
					'value'    => '1',
				),
			),
		)
	);

	new \Kirki\Field\Checkbox_Switch(
		array(
			'settings'        => 'site_search',
			'label'           => esc_html__( 'Site Search?', 'knowx' ),
			'section'         => 'site_sub_header_section',
			'default'         => 'on',
			'choices'         => array(
				'on'  => esc_html__( 'Yes', 'knowx' ),
				'off' => esc_html__( 'No', 'knowx' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'site_sub_header',
					'operator' => '==',
					'value'    => '1',
				),
			),
		)
	);
