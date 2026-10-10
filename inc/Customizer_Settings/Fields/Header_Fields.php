<?php
/**
 * Header Fields
 *
 * @package knowx
 */

defined( 'ABSPATH' ) || exit;

	/*
	 * Site Header
	 */
	\KnowX\KnowX\Customizer_Framework\Field::add(
		'switch',
		array(
			'settings' => 'site_sticky_header',
			'label'    => esc_html__( 'Keep the header in view while scrolling', 'knowx' ),
			'section'  => 'site_header_section',
			'default'  => 1,
			'choices'  => array(
				'on'  => esc_html__( 'Yes', 'knowx' ),
				'off' => esc_html__( 'No', 'knowx' ),
			),
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'color',
		array(
			'settings' => 'site_header_bg_color',
			'label'    => esc_html__( 'Header Background Color', 'knowx' ),
			'section'  => 'site_header_section',
			'default'  => '#ffffff',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
		)
	);
