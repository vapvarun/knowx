<?php
/**
 * Skin Fields
 *
 * @package knowx
 */

defined( 'ABSPATH' ) || exit;

	/**
	 * Site Skin
	 */
	\KnowX\KnowX\Customizer_Framework\Field::add(
		'color',
		array(
			'settings' => 'site_primary_color',
			'label'    => esc_html__( 'Theme Color', 'knowx' ),
			'section'  => 'site_skin_section',
			'default'  => '#03A9F4',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'color',
		array(
			'settings' => 'site_links_color',
			'label'    => esc_html__( 'Link Color', 'knowx' ),
			'section'  => 'site_skin_section',
			'default'  => '#03A9F4',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'color',
		array(
			'settings' => 'site_links_focus_hover_color',
			'label'    => esc_html__( 'Link Hover', 'knowx' ),
			'section'  => 'site_skin_section',
			'default'  => '#222222',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
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
			'settings' => 'site_buttons_background_color',
			'label'    => esc_html__( 'Button Background Color', 'knowx' ),
			'section'  => 'site_skin_section',
			'default'  => '#03A9F4',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'color',
		array(
			'settings' => 'site_buttons_background_hover_color',
			'label'    => esc_html__( 'Button Background Hover Color', 'knowx' ),
			'section'  => 'site_skin_section',
			'default'  => '#222222',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'color',
		array(
			'settings' => 'site_buttons_text_color',
			'label'    => esc_html__( 'Button Text Color', 'knowx' ),
			'section'  => 'site_skin_section',
			'default'  => '#ffffff',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'color',
		array(
			'settings' => 'site_buttons_text_hover_color',
			'label'    => esc_html__( 'Button Text Hover Color', 'knowx' ),
			'section'  => 'site_skin_section',
			'default'  => '#ffffff',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'color',
		array(
			'settings' => 'site_buttons_border_color',
			'label'    => esc_html__( 'Button Border Color', 'knowx' ),
			'section'  => 'site_skin_section',
			'default'  => '#03A9F4',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'color',
		array(
			'settings' => 'site_buttons_border_hover_color',
			'label'    => esc_html__( 'Button Border Hover Color', 'knowx' ),
			'section'  => 'site_skin_section',
			'default'  => '#222222',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
		)
	);
