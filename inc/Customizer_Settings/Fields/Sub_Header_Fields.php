<?php
/**
 * Sub Header Fields
 *
 * @package knowx
 */

defined( 'ABSPATH' ) || exit;

	/**
	 *  Site Sub Header
	 */
	\KnowX\KnowX\Customizer_Framework\Field::add(
		'switch',
		array(
			'settings' => 'site_sub_header',
			'label'    => esc_html__( 'Site Sub Header?', 'knowx' ),
			'section'  => 'site_sub_header_section',
			'default'  => 1,
			'choices'  => array(
				'on'  => esc_html__( 'Yes', 'knowx' ),
				'off' => esc_html__( 'No', 'knowx' ),
			),
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'background',
		array(
			'settings'        => 'sub_header_background_setting',
			'label'           => esc_html__( 'Background Control', 'knowx' ),
			'section'         => 'site_sub_header_section',
			'default'         => array(),
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

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'switch',
		array(
			'settings'        => 'site_breadcrumbs',
			'label'           => esc_html__( 'Site Breadcrumbs?', 'knowx' ),
			'section'         => 'site_sub_header_section',
			'default'         => 1,
			'choices'         => array(
				'on'  => esc_html__( 'Yes', 'knowx' ),
				'off' => esc_html__( 'No', 'knowx' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'site_sub_header',
					'operator' => '==',
					'value'    => true,
				),
			),
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'switch',
		array(
			'settings'        => 'site_search',
			'label'           => esc_html__( 'Site Search?', 'knowx' ),
			'section'         => 'site_sub_header_section',
			'default'         => 1,
			'choices'         => array(
				'on'  => esc_html__( 'Yes', 'knowx' ),
				'off' => esc_html__( 'No', 'knowx' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'site_sub_header',
					'operator' => '==',
					'value'    => true,
				),
			),
		)
	);
