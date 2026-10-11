<?php
/**
 * Footer Fields
 *
 * @package knowx
 */

defined( 'ABSPATH' ) || exit;

	/**
	 *  Site Footer
	 */
	\KnowX\KnowX\Customizer_Framework\Field::add(
		'background',
		array(
			'settings'        => 'background_setting',
			'label'           => esc_html__( 'Footer Background', 'knowx' ),
			'description' => esc_html__( 'Colour or image behind the footer widgets.', 'knowx' ),
			'section'         => 'site_footer_section',
			'default'         => array(),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'element' => '.site-footer-wrapper',
				),
			),
			'priority' => 10,
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'color',
		array(
			'settings' => 'site_footer_content_color',
			'label'    => esc_html__( 'Footer Text', 'knowx' ),
			'description' => esc_html__( 'Text in the footer.', 'knowx' ),
			'section'  => 'site_skin_section',
			'default'  => '#505050',
			'priority' => 30,
		)
	);

	/**
	 *  Site Copyright
	 */
	\KnowX\KnowX\Customizer_Framework\Field::add(
		'textarea',
		array(
			'settings'    => 'site_copyright_text',
			'label'       => esc_html__( 'Copyright Line', 'knowx' ),
			'description' => esc_html__( 'You can use [current_year], [site_title], [theme_author] shortcode if you want.', 'knowx' ),
			'section'     => 'site_footer_section',
			'default'     => esc_html__( 'Copyright © [current_year] [site_title] | Powered by [theme_author]', 'knowx' ),
			'priority'    => 20,
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'color',
		array(
			'settings' => 'site_copyright_background_color',
			'label'    => esc_html__( 'Copyright Background', 'knowx' ),
			'description' => esc_html__( 'The line at the very bottom of the page.', 'knowx' ),
			'section'  => 'site_skin_section',
			'default'  => '#ffffff',
			'priority' => 31,
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'color',
		array(
			'settings' => 'site_copyright_content_color',
			'label'    => esc_html__( 'Copyright Text', 'knowx' ),
			'description' => esc_html__( 'Text in that line.', 'knowx' ),
			'section'  => 'site_skin_section',
			'default'  => '#505050',
			'priority' => 32,
		)
	);
