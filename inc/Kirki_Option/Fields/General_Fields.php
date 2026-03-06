<?php
/**
 * General/Layout Kirki Fields
 *
 * @package knowx
 */

defined( 'ABSPATH' ) || exit;

	/**
	 *  Site Layout
	 */
	new \Kirki\Field\Radio_Image(
		array(
			'settings' => 'site_layout',
			'label'    => esc_html__( 'Site Layout', 'knowx' ),
			'section'  => 'site_layout',
			'priority' => 10,
			'default'  => 'wide',
			'choices'  => array(
				'boxed' => get_template_directory_uri() . '/assets/images/boxed.png',
				'wide'  => get_template_directory_uri() . '/assets/images/wide.png',
			),
		)
	);

	/**
	 *  Site Container Width
	 */
	new \Kirki\Field\Dimension(
		array(
			'settings'    => 'site_container_width',
			'label'       => esc_html__( 'Max Content Layout Width', 'knowx' ),
			'description' => esc_html__( 'Select the maximum content width for your website (px)', 'knowx' ),
			'section'     => 'site_layout',
			'default'     => '1170px',
			'priority'    => 10,
			'transport'   => 'auto',
			'output'      => array(
				array(
					'element'  => '.container',
					'function' => 'css',
					'property' => 'max-width',
				),
			),
		)
	);

	/**
	 *  Site Loader
	 */
	new \Kirki\Field\Checkbox_Switch(
		array(
			'settings' => 'site_loader',
			'label'    => esc_html__( 'Site Loader ?', 'knowx' ),
			'section'  => 'site_loader',
			'default'  => '2',
			'choices'  => array(
				'on'  => esc_html__( 'Yes', 'knowx' ),
				'off' => esc_html__( 'No', 'knowx' ),
			),
		)
	);

	new \Kirki\Field\Color(
		array(
			'settings'        => 'site_loader_bg',
			'label'           => esc_html__( 'Site Loader Background', 'knowx' ),
			'section'         => 'site_loader',
			'default'         => '#03A9F4',
			'choices'         => array( 'alpha' => true ),
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'site_loader',
					'operator' => '==',
					'value'    => '1',
				),
			),
		)
	);

	/*
	 *  Scroll Top
	 */
	new \Kirki\Field\Checkbox_Switch(
		array(
			'settings' => 'knowx_scroll_top',
			'label'    => esc_attr__( 'Scroll Top', 'knowx' ),
			'section'  => 'site_scroll_top',
			'priority' => 10,
			'default'  => '',
			'choices'  => array(
				'on'  => esc_attr__( 'Yes', 'knowx' ),
				'off' => esc_attr__( 'No', 'knowx' ),
			),
		)
	);
