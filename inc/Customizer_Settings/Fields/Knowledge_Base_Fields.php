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
		'background',
		array(
			'settings'    => 'support_section_background_setting',
			'label'       => esc_html__( 'Support Section Background Control', 'knowx' ),
			'description' => esc_html__( 'Set support section background color and image.', 'knowx' ),
			'section'     => 'site_knowledge_section_one',
			'default'     => array(),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'element' => '.knowx-support-section',
				),
			),
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

	/**
	 *  Site Knowledge Base Layout Two
	 */
	\KnowX\KnowX\Customizer_Framework\Field::add(
		'radio',
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

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'radio',
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

	\KnowX\KnowX\Customizer_Framework\Field::add(
		'radio',
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
	 *  Docs home: how the recent list is ordered
	 */
	\KnowX\KnowX\Customizer_Framework\Field::add(
		'radio',
		array(
			'settings' => 'knowx_recent_by',
			'label'    => esc_html__( 'Recent articles list', 'knowx' ),
			'section'  => 'site_knowledge_section_one',
			'default'  => 'updated',
			'choices'  => array(
				'updated' => esc_html__( 'Recently updated', 'knowx' ),
				'newest'  => esc_html__( 'Newest', 'knowx' ),
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
			'settings' => 'knowx_show_pager',
			'label'    => esc_html__( 'Show previous and next links', 'knowx' ),
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
			'settings' => 'knowx_show_related',
			'label'    => esc_html__( 'Show More in this section', 'knowx' ),
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
			'settings' => 'knowx_show_version',
			'label'    => esc_html__( 'Show the version badge', 'knowx' ),
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
		'select',
		array(
			'settings' => 'knowx_home_count',
			'label'    => esc_html__( 'Articles in the featured and recent lists', 'knowx' ),
			'section'  => 'site_knowledge_section_one',
			'default'  => '5',
			'choices'  => array(
				'3'  => '3',
				'5'  => '5',
				'8'  => '8',
				'10' => '10',
			),
		)
	);
