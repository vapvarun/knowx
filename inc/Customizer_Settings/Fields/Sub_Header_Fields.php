<?php
/**
 * Sub Header Fields
 *
 * @package knowx
 */

defined( 'ABSPATH' ) || exit;



	\KnowX\KnowX\Customizer_Framework\Field::add(
		'background',
		array(
			'settings'        => 'sub_header_background_setting',
			'label'           => esc_html__( 'Breadcrumb Bar Background', 'knowx' ),
			'description'     => esc_html__( 'Colour or image behind the breadcrumbs.', 'knowx' ),
			'section'         => 'site_header_section',
			'default'         => array(),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'element' => '.site-sub-header',
				),
			),
			'priority'        => 30,
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'switch',
		array(
			'settings'        => 'site_breadcrumbs',
			'label'           => esc_html__( 'Show breadcrumbs', 'knowx' ),
			'description'     => esc_html__( 'The trail under the header: Home, product, section, article.', 'knowx' ),
			'section'         => 'site_header_section',
			'default'         => 1,
			'choices'         => array(
				'on'  => esc_html__( 'Yes', 'knowx' ),
				'off' => esc_html__( 'No', 'knowx' ),
			),
			'priority'        => 20,
		)
	);
