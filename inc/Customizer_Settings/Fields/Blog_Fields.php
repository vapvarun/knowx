<?php
/**
 * Blog Fields
 *
 * @package knowx
 */

defined( 'ABSPATH' ) || exit;

	/**
	 *  Site Blog Layout
	 */
	\KnowX\KnowX\Customizer_Framework\Field::add(
		'radio_image',
		array(
			'settings' => 'blog_layout_option',
			'label'    => esc_html__( 'Blog Layout', 'knowx' ),
			'section'  => 'site_blog_section',
			'priority' => 10,
			'default'  => 'default-layout',
			'choices'  => array(
				'default-layout' => get_template_directory_uri() . '/assets/images/one-column.png',
				'grid-layout'    => get_template_directory_uri() . '/assets/images/one-half-column.png',
				'masonry-layout' => get_template_directory_uri() . '/assets/images/one-third-column.png',
			),
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'select',
		array(
			'settings'        => 'post_per_row',
			'label'           => esc_html__( 'Post Per Row', 'knowx' ),
			'section'         => 'site_blog_section',
			'default'         => '3',
			'priority'        => 10,
			'choices'         => array(
				'12' => esc_html__( 'Column 1', 'knowx' ),
				'6'  => esc_html__( 'Column 2', 'knowx' ),
				'4'  => esc_html__( 'Column 3', 'knowx' ),
				'3'  => esc_html__( 'Column 4', 'knowx' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'blog_layout_option',
					'operator' => '!==',
					'value'    => 'default-layout',
				),
			),
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'switch',
		array(
			'settings' => 'post_box_view_layout',
			'label'    => esc_html__( 'Post Box View Layout', 'knowx' ),
			'section'  => 'site_blog_section',
			'default'  => 0,
			'choices'  => array(
				'on'  => esc_html__( 'Yes', 'knowx' ),
				'off' => esc_html__( 'No', 'knowx' ),
			),
		)
	);
