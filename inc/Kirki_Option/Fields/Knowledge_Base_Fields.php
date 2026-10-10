<?php
/**
 * Knowledge Base Kirki Fields
 *
 * @package knowx
 */

defined( 'ABSPATH' ) || exit;

	/**
	 *  Site Knowledge Base Layout One
	 */
	new \Kirki\Field\Background(
		array(
			'settings'    => 'hero_section_background_setting',
			'label'       => esc_html__( 'Hero Section Background Control', 'knowx' ),
			'description' => esc_html__( 'Set hero section background color and image. Note: "Background Image" not apply when set page featured image from backend.', 'knowx' ),
			'section'     => 'site_knowledge_section_one',
			'default'     => array(
				'background-color'      => 'rgba(3, 169, 244, 1)',
				'background-image'      => '',
				'background-repeat'     => 'repeat',
				'background-position'   => 'center center',
				'background-size'       => 'cover',
				'background-attachment' => 'scroll',
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'element' => '.knowx-section-hero',
				),
			),
		)
	);

	new \Kirki\Field\Custom(
		array(
			'settings' => 'custom-knowledge-divider-one',
			'section'  => 'site_knowledge_section_one',
			'default'  => '<hr>',
		)
	);

	new \Kirki\Field\Text(
		array(
			'settings'    => 'hero_section_heading',
			'label'       => esc_html__( 'Hero Section Heading', 'knowx' ),
			'section'     => 'site_knowledge_section_one',
			'default'     => '',
			'input_attrs' => array(
				'placeholder' => esc_html__( 'Create a Knowledge Base with KnowX', 'knowx' ),
			),
			'priority'    => 10,
		)
	);

	new \Kirki\Field\Textarea(
		array(
			'settings' => 'hero_section_content',
			'label'    => esc_html__( 'Hero Section Content', 'knowx' ),
			'section'  => 'site_knowledge_section_one',
			'default'  => '',
			'priority' => 10,
		)
	);

	new \Kirki\Field\Custom(
		array(
			'settings' => 'custom-knowledge-divider-two',
			'section'  => 'site_knowledge_section_one',
			'default'  => '<hr>',
		)
	);

	new \Kirki\Field\Typography(
		array(
			'settings' => 'hero_section_title_typo',
			'label'    => esc_html__( 'Hero Section Title Typography', 'knowx' ),
			'section'  => 'site_knowledge_section_one',
			'default'  => array(
				'font-family'    => '',
				'variant'        => '',
				'font-size'      => '',
				'line-height'    => '',
				'letter-spacing' => '',
				'color'          => '#ffffff',
				'text-transform' => 'none',
			),
			'priority' => 10,
			'output'   => array(
				array(
					'element' => '.knowx-section-hero .entry-title',
				),
			),
		)
	);

	new \Kirki\Field\Typography(
		array(
			'settings' => 'hero_section_content_typo',
			'label'    => esc_html__( 'Hero Section Content Typography', 'knowx' ),
			'section'  => 'site_knowledge_section_one',
			'default'  => array(
				'font-family'    => '',
				'variant'        => '',
				'font-size'      => '',
				'line-height'    => '',
				'letter-spacing' => '',
				'color'          => '#ffffff',
				'text-transform' => 'none',
			),
			'priority' => 10,
			'output'   => array(
				array(
					'element' => '.knowx-section-hero .entry-summary, .knowx-section-hero .entry-summary p',
				),
			),
		)
	);

	new \Kirki\Field\Custom(
		array(
			'settings' => 'custom-knowledge-divider-three',
			'section'  => 'site_knowledge_section_one',
			'default'  => '<hr>',
		)
	);

	new \Kirki\Field\Text(
		array(
			'settings'    => 'knowx_exclude',
			'label'       => esc_html__( 'Exclude category by ID', 'knowx' ),
			'description' => esc_html__( 'Applies to both Knowledge Base layouts. Use a comma to separate multiple category IDs.', 'knowx' ),
			'section'     => 'site_knowledge_section_one',
		)
	);

	new \Kirki\Field\Custom(
		array(
			'settings' => 'custom-knowledge-divider-four',
			'section'  => 'site_knowledge_section_one',
			'default'  => '<hr>',
		)
	);

	new \Kirki\Field\Background(
		array(
			'settings'    => 'support_section_background_setting',
			'label'       => esc_html__( 'Support Section Background Control', 'knowx' ),
			'description' => esc_html__( 'Set support section background color and image.', 'knowx' ),
			'section'     => 'site_knowledge_section_one',
			'default'     => array(
				'background-color'      => 'rgba(236,236,236,.94)',
				'background-image'      => '',
				'background-repeat'     => 'repeat',
				'background-position'   => 'center center',
				'background-size'       => 'cover',
				'background-attachment' => 'scroll',
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'element' => '.knowx-support-section',
				),
			),
		)
	);

	new \Kirki\Field\Text(
		array(
			'settings'    => 'support_section_heading',
			'label'       => esc_html__( 'Support Section Heading', 'knowx' ),
			'section'     => 'site_knowledge_section_one',
			'default'     => '',
			'input_attrs' => array(
				'placeholder' => esc_html__( 'Can not find what you are looking for?', 'knowx' ),
			),
			'priority'    => 10,
		)
	);

	new \Kirki\Field\Textarea(
		array(
			'settings' => 'support_section_content',
			'label'    => esc_html__( 'Support Section Content', 'knowx' ),
			'section'  => 'site_knowledge_section_one',
			'default'  => '',
			'priority' => 10,
		)
	);

	new \Kirki\Field\Typography(
		array(
			'settings' => 'support_section_title_typo',
			'label'    => esc_html__( 'Support Section Heading Typography', 'knowx' ),
			'section'  => 'site_knowledge_section_one',
			'default'  => array(
				'font-family'    => '',
				'variant'        => '',
				'font-size'      => '',
				'line-height'    => '',
				'letter-spacing' => '',
				'color'          => '#333333',
				'text-transform' => 'none',
			),
			'priority' => 10,
			'output'   => array(
				array(
					'element' => '.knowx-support-title',
				),
			),
		)
	);

	new \Kirki\Field\Typography(
		array(
			'settings' => 'support_section_content_typo',
			'label'    => esc_html__( 'Support Section Content Typography', 'knowx' ),
			'section'  => 'site_knowledge_section_one',
			'default'  => array(
				'font-family'    => '',
				'variant'        => '',
				'font-size'      => '',
				'line-height'    => '',
				'letter-spacing' => '',
				'color'          => '#333333',
				'text-transform' => 'none',
			),
			'priority' => 10,
			'output'   => array(
				array(
					'element' => '.knowx-support-content, .knowx-support-content p',
				),
			),
		)
	);

	new \Kirki\Field\Text(
		array(
			'settings' => 'support_section_button_text',
			'label'    => esc_html__( 'Button Text', 'knowx' ),
			'section'  => 'site_knowledge_section_one',
			'default'  => '',
			'priority' => 10,
		)
	);

	new \Kirki\Field\URL(
		array(
			'settings' => 'support_section_button_link_url',
			'label'    => esc_html__( 'Button Link URL', 'knowx' ),
			'section'  => 'site_knowledge_section_one',
			'default'  => '',
			'priority' => 10,
		)
	);

	/**
	 *  Site Knowledge Base Layout Two
	 */
	new \Kirki\Field\Radio(
		array(
			'settings' => 'knowx_page_title',
			'label'    => esc_html__( 'Show page title', 'knowx' ),
			'section'  => 'site_knowledge_section_two',
			'default'  => 'yes',
			'choices'  => array(
				'yes' => esc_html__( 'Yes', 'knowx' ),
				'no'  => esc_html__( 'No', 'knowx' ),
			),
		)
	);

	new \Kirki\Field\Radio(
		array(
			'settings' => 'knowx_view_all',
			'label'    => esc_html__( 'Show View All link', 'knowx' ),
			'section'  => 'site_knowledge_section_two',
			'default'  => 'no',
			'choices'  => array(
				'yes' => esc_html__( 'Yes', 'knowx' ),
				'no'  => esc_html__( 'No', 'knowx' ),
			),
		)
	);

	new \Kirki\Field\Radio(
		array(
			'settings' => 'knowx_cat_description',
			'label'    => esc_html__( 'Show category description', 'knowx' ),
			'section'  => 'site_knowledge_section_two',
			'default'  => 'no',
			'choices'  => array(
				'yes' => esc_html__( 'Yes', 'knowx' ),
				'no'  => esc_html__( 'No', 'knowx' ),
			),
		)
	);

	new \Kirki\Field\Number(
		array(
			'settings'    => 'knowx_posts',
			'label'       => esc_html__( 'Posts per category', 'knowx' ),
			'description' => esc_html__( 'Only numeric characters allowed. Leave empty for 10.', 'knowx' ),
			'section'     => 'site_knowledge_section_two',
		)
	);

	new \Kirki\Field\Radio(
		array(
			'settings' => 'knowx_order',
			'label'    => esc_html__( 'Order posts', 'knowx' ),
			'section'  => 'site_knowledge_section_two',
			'default'  => 'date',
			'choices'  => array(
				'date' => esc_html__( 'By date', 'knowx' ),
				'name' => esc_html__( 'By name', 'knowx' ),
			),
		)
	);
