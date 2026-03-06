<?php
/**
 * Header Kirki Fields
 *
 * @package knowx
 */

defined( 'ABSPATH' ) || exit;

	/*
	 * Site Header
	 */
	new \Kirki\Field\Checkbox_Switch(
		array(
			'settings' => 'site_sticky_header',
			'label'    => esc_html__( 'Enable Sticky Header ?', 'knowx' ),
			'section'  => 'site_header_section',
			'default'  => '1',
			'choices'  => array(
				'on'  => esc_html__( 'Yes', 'knowx' ),
				'off' => esc_html__( 'No', 'knowx' ),
			),
		)
	);

	new \Kirki\Field\Color(
		array(
			'settings' => 'site_header_bg_color',
			'label'    => esc_html__( 'Header Background Color', 'knowx' ),
			'section'  => 'site_header_section',
			'default'  => '#ffffff',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
		)
	);
