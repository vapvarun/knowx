<?php
/**
 * Skin Fields
 *
 * @package knowx
 */

defined( 'ABSPATH' ) || exit;

	/**
	 * Colors
	 */

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'color',
		array(
			'settings'    => 'site_primary_color',
			'label'       => esc_html__( 'Theme Color', 'knowx' ),
			'description' => esc_html__( 'Buttons, links and highlights across the whole site. Each product keeps its own icon colour (Posts > Categories).', 'knowx' ),
			'section'     => 'site_skin_section',
			'default'     => '#03A9F4',
			'choices'     => array(
				// Quick picks under the picker. Any other colour can still be chosen.
				'palette' => array( '#03a9f4', '#2563eb', '#7c3aed', '#047857', '#b45309', '#be123c', '#0f766e', '#c2410c' ),
			),
			'priority'    => 10,
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'custom',
		array(
			'settings' => 'custom-skin-divider',
			'section'  => 'site_skin_section',
			'default'  => '<hr>',
		)
	);

	/**
	 * Site Buttons
	 */

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'color',
		array(
			'settings'    => 'site_link_color',
			'label'       => esc_html__( 'Link Color', 'knowx' ),
			'description' => esc_html__( 'Leave empty to use a darker shade of the theme colour.', 'knowx' ),
			'section'     => 'site_skin_section',
			'default'     => '',
			'priority'    => 11,
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'color',
		array(
			'settings'    => 'site_text_color',
			'label'       => esc_html__( 'Text Color', 'knowx' ),
			'description' => esc_html__( 'Body text. Keep it dark on a light page.', 'knowx' ),
			'section'     => 'site_skin_section',
			'default'     => '',
			'priority'    => 12,
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'color',
		array(
			'settings'    => 'site_heading_color',
			'label'       => esc_html__( 'Heading Color', 'knowx' ),
			'description' => esc_html__( 'Headings and titles.', 'knowx' ),
			'section'     => 'site_skin_section',
			'default'     => '',
			'priority'    => 13,
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'color',
		array(
			'settings'    => 'site_surface_color',
			'label'       => esc_html__( 'Content Background', 'knowx' ),
			'description' => esc_html__( 'Cards, boxes, the header and the article area.', 'knowx' ),
			'section'     => 'site_skin_section',
			'default'     => '',
			'priority'    => 15,
		)
	);
