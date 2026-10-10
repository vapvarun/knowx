<?php
/**
 * Knowledge Base Fields
 *
 * @package knowx
 */

defined( 'ABSPATH' ) || exit;

	/**
	 *  Site Knowledge Base Layout One
	 */
	\KnowX\KnowX\Customizer_Framework\Field::add(
		'background',
		array(
			'settings'    => 'hero_section_background_setting',
			'label'       => esc_html__( 'Hero Section Background Control', 'knowx' ),
			'description' => esc_html__( 'Set hero section background color and image. Note: "Background Image" not apply when set page featured image from backend.', 'knowx' ),
			'section'     => 'site_knowledge_section_one',
			'default'     => array(),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'element' => '.knowx-section-hero',
				),
			),
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'custom',
		array(
			'settings' => 'custom-knowledge-divider-one',
			'section'  => 'site_knowledge_section_one',
			'default'  => '<hr>',
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'text',
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

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'textarea',
		array(
			'settings' => 'hero_section_content',
			'label'    => esc_html__( 'Hero Section Content', 'knowx' ),
			'section'  => 'site_knowledge_section_one',
			'default'  => '',
			'priority' => 10,
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'custom',
		array(
			'settings' => 'custom-knowledge-divider-two',
			'section'  => 'site_knowledge_section_one',
			'default'  => '<hr>',
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'custom',
		array(
			'settings' => 'custom-knowledge-divider-three',
			'section'  => 'site_knowledge_section_one',
			'default'  => '<hr>',
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'text',
		array(
			'settings'    => 'knowx_exclude',
			'label'       => esc_html__( 'Exclude category by ID', 'knowx' ),
			'description' => esc_html__( 'Applies to both Knowledge Base layouts. Use a comma to separate multiple category IDs.', 'knowx' ),
			'section'     => 'site_knowledge_section_one',
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'custom',
		array(
			'settings' => 'custom-knowledge-divider-four',
			'section'  => 'site_knowledge_section_one',
			'default'  => '<hr>',
		)
	);

	

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'text',
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

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'textarea',
		array(
			'settings' => 'support_section_content',
			'label'    => esc_html__( 'Support Section Content', 'knowx' ),
			'section'  => 'site_knowledge_section_one',
			'default'  => '',
			'priority' => 10,
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'text',
		array(
			'settings' => 'support_section_button_text',
			'label'    => esc_html__( 'Button Text', 'knowx' ),
			'section'  => 'site_knowledge_section_one',
			'default'  => '',
			'priority' => 10,
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'url',
		array(
			'settings' => 'support_section_button_link_url',
			'label'    => esc_html__( 'Button Link URL', 'knowx' ),
			'section'  => 'site_knowledge_section_one',
			'default'  => '',
			'priority' => 10,
		)
	);



	

	

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'number',
		array(
			'settings'    => 'knowx_posts',
			'label'       => esc_html__( 'Posts per category', 'knowx' ),
			'description' => esc_html__( 'Only numeric characters allowed. Leave empty for 10.', 'knowx' ),
			'section'     => 'site_knowledge_section_two',
			'default'     => 10,
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'radio',
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



	/**
	 *  Articles
	 */
	\KnowX\KnowX\Customizer_Framework\Field::add(
		'switch',
		array(
			'settings' => 'knowx_show_updated',
			'label'    => esc_html__( 'Show last updated date', 'knowx' ),
			'section'  => 'site_articles_section',
			'default'  => 1,
			'choices'  => array(
				'on'  => esc_html__( 'Yes', 'knowx' ),
				'off' => esc_html__( 'No', 'knowx' ),
			),
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'switch',
		array(
			'settings' => 'knowx_show_toc',
			'label'    => esc_html__( 'Show the contents list (On this page)', 'knowx' ),
			'section'  => 'site_articles_section',
			'default'  => 1,
			'choices'  => array(
				'on'  => esc_html__( 'Yes', 'knowx' ),
				'off' => esc_html__( 'No', 'knowx' ),
			),
		)
	);

	

	

	

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'switch',
		array(
			'settings' => 'knowx_show_recent',
			'label'    => esc_html__( 'Show the recent articles list', 'knowx' ),
			'section'  => 'site_knowledge_section_one',
			'default'  => 1,
			'choices'  => array(
				'on'  => esc_html__( 'Yes', 'knowx' ),
				'off' => esc_html__( 'No', 'knowx' ),
			),
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'switch',
		array(
			'settings' => 'knowx_show_support',
			'label'    => esc_html__( 'Show the support block', 'knowx' ),
			'section'  => 'site_knowledge_section_one',
			'default'  => 1,
			'choices'  => array(
				'on'  => esc_html__( 'Yes', 'knowx' ),
				'off' => esc_html__( 'No', 'knowx' ),
			),
		)
	);

	

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'radio',
		array(
			'settings' => 'knowx_home_layout',
			'label'    => esc_html__( 'Products on the docs home', 'knowx' ),
			'section'  => 'site_knowledge_section_one',
			'default'  => 'cards',
			'choices'  => array(
				'cards'    => esc_html__( 'Product cards', 'knowx' ),
				'sections' => esc_html__( 'Each product with its sections', 'knowx' ),
			),
		)
	);

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'radio',
		array(
			'settings' => 'knowx_section_sort',
			'label'    => esc_html__( 'Order articles in a section', 'knowx' ),
			'section'  => 'site_articles_section',
			'default'  => 'date',
			'choices'  => array(
				'date'    => esc_html__( 'Newest first (after the page order)', 'knowx' ),
				'title'   => esc_html__( 'By title (after the page order)', 'knowx' ),
				'updated' => esc_html__( 'Last updated first (after the page order)', 'knowx' ),
			),
		)
	);
