<?php
/**
 * General/Layout Fields
 *
 * @package knowx
 */

defined( 'ABSPATH' ) || exit;

	/**
	 *  Site Layout
	 */
	\KnowX\KnowX\Customizer_Framework\Field::add(
		'radio_image',
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
	\KnowX\KnowX\Customizer_Framework\Field::add(
		'dimension',
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
	\KnowX\KnowX\Customizer_Framework\Field::add(
		'switch',
		array(
			'settings' => 'site_loader',
			'label'    => esc_html__( 'Show a loading animation', 'knowx' ),
			'section'  => 'site_loader',
			'default'  => 0,
			'choices'  => array(
				'on'  => esc_html__( 'Yes', 'knowx' ),
				'off' => esc_html__( 'No', 'knowx' ),
			),
		)
	);

	/*
	 *  Scroll Top
	 */
	\KnowX\KnowX\Customizer_Framework\Field::add(
		'switch',
		array(
			'settings' => 'knowx_scroll_top',
			'label'    => esc_attr__( 'Scroll Top', 'knowx' ),
			'section'  => 'site_scroll_top',
			'priority' => 10,
			'default'  => 0,
			'choices'  => array(
				'on'  => esc_attr__( 'Yes', 'knowx' ),
				'off' => esc_attr__( 'No', 'knowx' ),
			),
		)
	);
