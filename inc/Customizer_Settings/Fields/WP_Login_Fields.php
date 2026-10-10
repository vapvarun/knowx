<?php
/**
 * WP Login Fields
 *
 * @package knowx
 */

defined( 'ABSPATH' ) || exit;

	/*
	 *  WP Login
	 */
	\KnowX\KnowX\Customizer_Framework\Field::add(
		'switch',
		array(
			'settings' => 'enable_custom_login',
			'label'    => esc_html__( 'Customize Your Logo Section', 'knowx' ),
			'section'  => 'site_wp_login_logo',
			'default'  => '',
			'choices'  => array(
				'on'  => esc_html__( 'Yes', 'knowx' ),
				'off' => esc_html__( 'No', 'knowx' ),
			),
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'switch',
		array(
			'settings'        => 'enable_custom_login_logo',
			'label'           => esc_html__( 'Disable Logo?', 'knowx' ),
			'section'         => 'site_wp_login_logo',
			'default'         => '',
			'choices'         => array(
				'on'  => esc_html__( 'Yes', 'knowx' ),
				'off' => esc_html__( 'No', 'knowx' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'enable_custom_login',
					'operator' => '==',
					'value'    => true,
				),
			),
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'image',
		array(
			'settings'        => 'custom_login_logo_image',
			'label'           => esc_attr__( 'Custom Logo', 'knowx' ),
			'section'         => 'site_wp_login_logo',
			'priority'        => 10,
			'default'         => '',
			'transport'       => 'postMessage',
			'active_callback' => array(
				array(
					'setting'  => 'enable_custom_login',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'enable_custom_login_logo',
					'operator' => '==',
					'value'    => false,
				),
			),
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'dimension',
		array(
			'settings'        => 'custom_login_logo_image_width',
			'label'           => esc_attr__( 'Logo Width', 'knowx' ),
			'description'     => esc_html__( 'Select the logo width (px)', 'knowx' ),
			'section'         => 'site_wp_login_logo',
			'priority'        => 10,
			'default'         => '84px',
			'transport'       => 'postMessage',
			'active_callback' => array(
				array(
					'setting'  => 'enable_custom_login',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'enable_custom_login_logo',
					'operator' => '==',
					'value'    => false,
				),
			),
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'dimension',
		array(
			'settings'        => 'custom_login_logo_image_height',
			'label'           => esc_attr__( 'Logo Height', 'knowx' ),
			'description'     => esc_html__( 'Select the logo height (px)', 'knowx' ),
			'section'         => 'site_wp_login_logo',
			'priority'        => 10,
			'default'         => '84px',
			'transport'       => 'postMessage',
			'active_callback' => array(
				array(
					'setting'  => 'enable_custom_login',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'enable_custom_login_logo',
					'operator' => '==',
					'value'    => false,
				),
			),
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'dimension',
		array(
			'settings'        => 'custom_login_logo_space',
			'label'           => esc_attr__( 'Logo Space Bottom', 'knowx' ),
			'description'     => esc_html__( 'Select the logo bottom spacing (px)', 'knowx' ),
			'section'         => 'site_wp_login_logo',
			'priority'        => 10,
			'default'         => '0px',
			'transport'       => 'postMessage',
			'active_callback' => array(
				array(
					'setting'  => 'enable_custom_login',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'enable_custom_login_logo',
					'operator' => '==',
					'value'    => false,
				),
			),
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'url',
		array(
			'settings'        => 'custom_login_logo_url',
			'label'           => esc_attr__( 'Logo URL', 'knowx' ),
			'section'         => 'site_wp_login_logo',
			'priority'        => 10,
			'default'         => '',
			'transport'       => 'postMessage',
			'active_callback' => array(
				array(
					'setting'  => 'enable_custom_login',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'enable_custom_login_logo',
					'operator' => '==',
					'value'    => false,
				),
			),
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'text',
		array(
			'settings'        => 'custom_login_logo_title',
			'label'           => esc_attr__( 'Logo Title', 'knowx' ),
			'section'         => 'site_wp_login_logo',
			'priority'        => 10,
			'default'         => '',
			'transport'       => 'postMessage',
			'active_callback' => array(
				array(
					'setting'  => 'enable_custom_login',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'enable_custom_login_logo',
					'operator' => '==',
					'value'    => false,
				),
			),
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'text',
		array(
			'settings'        => 'custom_login_page_title',
			'label'           => esc_attr__( 'Login Page Title', 'knowx' ),
			'description'     => esc_attr__( 'Login page title that is shown on WordPress login page.', 'knowx' ),
			'section'         => 'site_wp_login_logo',
			'priority'        => 10,
			'default'         => '',
			'transport'       => 'postMessage',
			'active_callback' => array(
				array(
					'setting'  => 'enable_custom_login',
					'operator' => '==',
					'value'    => true,
				),
			),
		)
	);
