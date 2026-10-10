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
			'label'    => esc_html__( 'Sticky header', 'knowx' ),
			'description' => esc_html__( 'Keeps the header at the top of the screen while scrolling.', 'knowx' ),
			'section'  => 'site_header_section',
			'default'  => 1,
			'choices'  => array(
				'on'  => esc_html__( 'Yes', 'knowx' ),
				'off' => esc_html__( 'No', 'knowx' ),
			),
			'priority' => 10,
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'color',
		array(
			'settings' => 'site_header_bg_color',
			'label'    => esc_html__( 'Header Background', 'knowx' ),
			'description' => esc_html__( 'The header bar.', 'knowx' ),
			'section'  => 'site_skin_section',
			'default'  => '#ffffff',
			'priority' => 20,
		)
	);
