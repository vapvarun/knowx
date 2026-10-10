<?php
/**
 * Color Mode Fields
 *
 * @package knowx
 */

defined( 'ABSPATH' ) || exit;

\KnowX\KnowX\Customizer_Framework\Field::add(
	'radio',
	array(
		'settings'    => 'site_color_mode',
		'label'       => esc_html__( 'Default color mode', 'knowx' ),
		'description' => esc_html__( 'Automatic follows the visitor\'s device setting.', 'knowx' ),
		'section'     => 'site_color_mode_section',
		'default'     => 'light',
		'choices'     => array(
			'light' => esc_html__( 'Light', 'knowx' ),
			'dark'  => esc_html__( 'Dark', 'knowx' ),
			'auto'  => esc_html__( 'Automatic', 'knowx' ),
		),
		'priority'    => 10,
	)
);

\KnowX\KnowX\Customizer_Framework\Field::add(
	'switch',
	array(
		'settings'    => 'site_color_mode_toggle_show',
		'label'       => esc_html__( 'Show a light / dark toggle in the header', 'knowx' ),
		'description' => esc_html__( 'Visitors can switch modes; their choice is remembered in their browser.', 'knowx' ),
		'section'     => 'site_color_mode_section',
		'default'     => 0,
		'priority'    => 20,
	)
);
