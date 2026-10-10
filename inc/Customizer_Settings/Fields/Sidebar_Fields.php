<?php
/**
 * Sidebar Fields
 *
 * @package knowx
 */

defined( 'ABSPATH' ) || exit;

	/**
	 *  Site Sidebar Layout
	 */
	\KnowX\KnowX\Customizer_Framework\Field::add(
		'radio_image',
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

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'switch',
		array(
			'settings' => 'sticky_sidebar_option',
			'label'    => esc_html__( 'Sticky Sidebar ?', 'knowx' ),
			'section'  => 'site_sidebar_layout',
			'default'  => 1,
			'choices'  => array(
				'on'  => esc_html__( 'Yes', 'knowx' ),
				'off' => esc_html__( 'No', 'knowx' ),
			),
		)
	);
