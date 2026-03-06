<?php
/**
 * Sidebar Kirki Fields
 *
 * @package knowx
 */

defined( 'ABSPATH' ) || exit;

	/**
	 *  Site Sidebar Layout
	 */
	new \Kirki\Field\Radio_Image(
		array(
			'settings' => 'sidebar_option',
			'label'    => esc_html__( 'Sidebar Layout', 'knowx' ),
			'section'  => 'site_sidebar_layout',
			'priority' => 10,
			'default'  => 'right',
			'choices'  => array(
				'none'  => get_template_directory_uri() . '/assets/images/without-sidebar.png',
				'left'  => get_template_directory_uri() . '/assets/images/left-sidebar.png',
				'right' => get_template_directory_uri() . '/assets/images/right-sidebar.png',
				'both'  => get_template_directory_uri() . '/assets/images/both-sidebar.png',
			),
		)
	);

	if ( function_exists( 'is_bbpress' ) ) {
		new \Kirki\Field\Radio_Image(
			array(
				'settings' => 'bbpress_sidebar_option',
				'label'    => esc_html__( 'bbPress Sidebar Layout', 'knowx' ),
				'section'  => 'site_sidebar_layout',
				'priority' => 10,
				'default'  => 'right',
				'choices'  => array(
					'none'  => get_template_directory_uri() . '/assets/images/without-sidebar.png',
					'left'  => get_template_directory_uri() . '/assets/images/left-sidebar.png',
					'right' => get_template_directory_uri() . '/assets/images/right-sidebar.png',
					'both'  => get_template_directory_uri() . '/assets/images/both-sidebar.png',
				),
			)
		);
	}

	new \Kirki\Field\Checkbox_Switch(
		array(
			'settings' => 'sticky_sidebar_option',
			'label'    => esc_html__( 'Sticky Sidebar ?', 'knowx' ),
			'section'  => 'site_sidebar_layout',
			'default'  => '1',
			'choices'  => array(
				'on'  => esc_html__( 'Yes', 'knowx' ),
				'off' => esc_html__( 'No', 'knowx' ),
			),
		)
	);
