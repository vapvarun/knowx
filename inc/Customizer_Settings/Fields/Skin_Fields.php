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
	