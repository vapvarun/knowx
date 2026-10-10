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
			'label'    => esc_html__( 'Layout Style', 'knowx' ),
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
			'label'       => esc_html__( 'Content Width', 'knowx' ),
			'description' => esc_html__( 'Widest the page content gets, in pixels.', 'knowx' ),
			'section'     => 'site_layout',
			'default'     => '1170px',
			'priority'    => 20,
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



	/*
	 *  Scroll Top
	 */
	\KnowX\KnowX\Customizer_Framework\Field::add(
		'switch',
		array(
			'settings' => 'knowx_scroll_top',
			'label'    => esc_html__( 'Back to top button', 'knowx' ),
			'description' => esc_html__( 'Shows a button after scrolling down a long page.', 'knowx' ),
			'section'  => 'site_layout',
			'priority' => 40,
			'default'  => 0,
			'choices'  => array(
				'on'  => esc_attr__( 'Yes', 'knowx' ),
				'off' => esc_attr__( 'No', 'knowx' ),
			),
		)
	);

	/**
	 *  Corner roundness
	 */
	\KnowX\KnowX\Customizer_Framework\Field::add(
		'radio',
		array(
			'settings' => 'knowx_corners',
			'label'    => esc_html__( 'Corners', 'knowx' ),
			'description' => esc_html__( 'Roundness of cards, buttons and inputs.', 'knowx' ),
			'section'  => 'site_layout',
			'default'  => 'soft',
			'choices'  => array(
				'square' => esc_html__( 'Square', 'knowx' ),
				'soft'   => esc_html__( 'Soft', 'knowx' ),
				'round'  => esc_html__( 'Round', 'knowx' ),
			),
			'priority' => 30,
		)
	);
