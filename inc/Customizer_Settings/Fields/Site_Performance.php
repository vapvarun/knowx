<?php
/**
 * Site Performance Fields
 *
 * @package knowx
 */

defined( 'ABSPATH' ) || exit;

	/**
	 *  Site Performance
	 */
	\KnowX\KnowX\Customizer_Framework\Field::add(
		'switch',
		array(
			'settings' => 'site_load_google_font_locally',
			'label'    => esc_html__( 'Load Google Fonts Locally ?', 'knowx' ),
			'section'  => 'site_performance_section',
			'default'  => '',
			'choices'  => array(
				'on'  => esc_html__( 'Enable', 'knowx' ),
				'off' => esc_html__( 'Disable', 'knowx' ),
			),
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'switch',
		array(
			'settings'        => 'site_preload_local_font',
			'label'           => esc_html__( 'Preload Local Fonts ?', 'knowx' ),
			'section'         => 'site_performance_section',
			'default'         => '',
			'choices'         => array(
				'on'  => esc_html__( 'Enable', 'knowx' ),
				'off' => esc_html__( 'Disable', 'knowx' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'site_load_google_font_locally',
					'operator' => '==',
					'value'    => 1,
				),
			),
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'custom',
		array(
			'settings'        => 'site_flush_local_font',
			'label'           => esc_html__( 'Flush Local Fonts Cache', 'knowx' ),
			'description'     => esc_html__( 'Click the button to reset the local fonts cache.', 'knowx' ),
			'section'         => 'site_performance_section',
			'default'         => '<input type="submit" value="Flush Local Font Files" class="button button-secondary knowx-flush-font-files">',
			'active_callback' => array(
				array(
					'setting'  => 'site_load_google_font_locally',
					'operator' => '==',
					'value'    => 1,
				),
			),
		)
	);
