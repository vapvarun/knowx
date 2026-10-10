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
	$knowx_schemes = array( '' => esc_html__( 'Default', 'knowx' ) );
foreach ( (array) glob( get_template_directory() . '/styles/*.json' ) as $knowx_file ) {
	$knowx_data = wp_json_file_decode( $knowx_file, array( 'associative' => true ) );
	if ( ! empty( $knowx_data['title'] ) ) {
		$knowx_schemes[ basename( $knowx_file, '.json' ) ] = esc_html( $knowx_data['title'] );
	}
}

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'select',
		array(
			'settings' => 'site_color_scheme',
			'label'    => esc_html__( 'Colour Scheme', 'knowx' ),
			'section'  => 'site_skin_section',
			'default'  => '',
			'choices'  => $knowx_schemes,
			'priority' => 5,
		)
	);

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
