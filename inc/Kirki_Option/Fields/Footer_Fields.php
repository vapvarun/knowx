<?php
/**
 * Footer Kirki Fields
 *
 * @package knowx
 */

defined( 'ABSPATH' ) || exit;

	/**
	 *  Site Footer
	 */
	new \Kirki\Field\Checkbox_Switch(
		array(
			'settings' => 'site_footer_bg',
			'label'    => esc_html__( 'Customize Background ?', 'knowx' ),
			'section'  => 'site_footer_section',
			'default'  => 'off',
			'choices'  => array(
				'on'  => esc_html__( 'Yes', 'knowx' ),
				'off' => esc_html__( 'No', 'knowx' ),
			),
		)
	);

	new \Kirki\Field\Background(
		array(
			'settings'        => 'background_setting',
			'label'           => esc_html__( 'Background Control', 'knowx' ),
			'section'         => 'site_footer_section',
			'default'         => array(
				'background-color'      => 'rgba(255,255,255,0.8)',
				'background-image'      => '',
				'background-repeat'     => 'repeat',
				'background-position'   => 'center center',
				'background-size'       => 'cover',
				'background-attachment' => 'scroll',
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'element' => '.site-footer-wrapper',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'site_footer_bg',
					'operator' => '==',
					'value'    => '1',
				),
			),
		)
	);

	new \Kirki\Field\Color(
		array(
			'settings' => 'site_footer_title_color',
			'label'    => esc_html__( 'Title Color', 'knowx' ),
			'section'  => 'site_footer_section',
			'default'  => '#333333',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
		)
	);

	new \Kirki\Field\Color(
		array(
			'settings' => 'site_footer_content_color',
			'label'    => esc_html__( 'Content Color', 'knowx' ),
			'section'  => 'site_footer_section',
			'default'  => '#505050',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
		)
	);

	new \Kirki\Field\Color(
		array(
			'settings' => 'site_footer_links_color',
			'label'    => esc_html__( 'Link Color', 'knowx' ),
			'section'  => 'site_footer_section',
			'default'  => '#222222',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
		)
	);

	new \Kirki\Field\Color(
		array(
			'settings' => 'site_footer_links_hover_color',
			'label'    => esc_html__( 'Link Hover', 'knowx' ),
			'section'  => 'site_footer_section',
			'default'  => '#03A9F4',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
		)
	);

	/**
	 *  Site Copyright
	 */
	new \Kirki\Field\Textarea(
		array(
			'settings'    => 'site_copyright_text',
			'label'       => esc_html__( 'Add Content', 'knowx' ),
			'description' => esc_html__( 'You can use [current_year], [site_title], [theme_author] shortcode if you want.', 'knowx' ),
			'section'     => 'site_copyright_section',
			'default'     => esc_html__( 'Copyright © [current_year] [site_title] | Powered by [theme_author]', 'knowx' ),
			'priority'    => 10,
		)
	);

	new \Kirki\Field\Color(
		array(
			'settings' => 'site_copyright_background_color',
			'label'    => esc_html__( 'Background Color', 'knowx' ),
			'section'  => 'site_copyright_section',
			'default'  => '#ffffff',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
		)
	);

	new \Kirki\Field\Color(
		array(
			'settings' => 'site_copyright_border_color',
			'label'    => esc_html__( 'Border Color', 'knowx' ),
			'section'  => 'site_copyright_section',
			'default'  => '#e8e8e8',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
		)
	);

	new \Kirki\Field\Color(
		array(
			'settings' => 'site_copyright_content_color',
			'label'    => esc_html__( 'Content Color', 'knowx' ),
			'section'  => 'site_copyright_section',
			'default'  => '#505050',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
		)
	);

	new \Kirki\Field\Color(
		array(
			'settings' => 'site_copyright_links_color',
			'label'    => esc_html__( 'Link Color', 'knowx' ),
			'section'  => 'site_copyright_section',
			'default'  => '#222222',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
		)
	);

	new \Kirki\Field\Color(
		array(
			'settings' => 'site_copyright_links_hover_color',
			'label'    => esc_html__( 'Link Hover Color', 'knowx' ),
			'section'  => 'site_copyright_section',
			'default'  => '#03A9F4',
			'choices'  => array( 'alpha' => true ),
			'priority' => 10,
		)
	);
