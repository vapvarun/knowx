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
			'settings' => 'site_primary_color',
			'label'    => esc_html__( 'Theme Color', 'knowx' ),
			'section'  => 'site_skin_section',
			'default'  => '#03A9F4',
			'choices'  => array(
				// Quick picks under the picker. Any other colour can still be chosen.
				'palette' => array( '#03a9f4', '#2563eb', '#7c3aed', '#047857', '#b45309', '#be123c', '#0f766e', '#c2410c' ),
			),
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
