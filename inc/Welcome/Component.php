<?php
/**
 * KnowX\KnowX\Welcome\Component class
 *
 * Appearance > KnowX: the docs setup buttons (still there after the one-time notice is dismissed),
 * what an owner can do next with a link to each admin screen and its guide, and where to get help.
 *
 * @package knowx
 */

namespace KnowX\KnowX\Welcome;

use KnowX\KnowX\Component_Interface;
use KnowX\KnowX\Starter_Content\Component as Starter_Content;

/**
 * Getting started screen.
 */
class Component implements Component_Interface {

	/**
	 * Admin page slug, under Appearance.
	 */
	const PAGE = 'knowx';

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'welcome';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'admin_menu', array( $this, 'add_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
	}

	/**
	 * Registers Appearance > KnowX.
	 */
	public function add_page() {
		add_theme_page(
			__( 'Getting started with KnowX', 'knowx' ),
			__( 'KnowX', 'knowx' ),
			'edit_theme_options',
			self::PAGE,
			array( $this, 'render_page' )
		);
	}

	/**
	 * Layout for the cards; colours come from core's .card.
	 *
	 * @param string $hook Current admin page hook.
	 */
	public function enqueue_styles( $hook ) {
		if ( 'appearance_page_' . self::PAGE !== $hook ) {
			return;
		}
		wp_add_inline_style(
			'common',
			'.knowx-welcome .card{max-width:none;margin:0;padding:16px 20px}'
			. '.knowx-welcome-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:16px;margin:16px 0 24px}'
			. '.knowx-welcome .card h3{margin:0 0 8px}'
			. '.knowx-welcome .card p{margin:0 0 12px}'
			. '.knowx-welcome-actions{display:flex;flex-wrap:wrap;align-items:center;gap:8px 16px}'
			. '.knowx-welcome-setup{margin-block:16px 8px}'
			. '.knowx-welcome-setup h3{margin-block:24px 8px}'
		);
	}

	/**
	 * Base URL of the KnowX guides.
	 *
	 * @return string
	 */
	public static function docs_url(): string {
		/**
		 * Filters the base URL of the KnowX guides linked from the Getting started screen.
		 *
		 * @param string $url Guides home, with a trailing slash.
		 */
		return (string) apply_filters( 'knowx_docs_url', 'https://docs.wbcomdesigns.com/wordpress-family/knowx-theme/' );
	}

	/**
	 * Cards and help links shown on the screen.
	 *
	 * @return array{tasks: array<int, array<string, string>>, help: array<int, array<string, string>>}
	 */
	public function sections(): array {
		$docs = self::docs_url();

		$sections = array(
			'tasks' => array(
				array(
					'title'  => __( 'Add a product', 'knowx' ),
					'text'   => __( 'A product is a top-level category. Give it an icon and a description; its child categories become sections.', 'knowx' ),
					'label'  => __( 'Add a product', 'knowx' ),
					'url'    => admin_url( 'edit-tags.php?taxonomy=category' ),
					'guide'  => $docs . 'features/01-products-and-sections/',
				),
				array(
					'title'  => __( 'Write an article', 'knowx' ),
					'text'   => __( 'An article is a post filed in a section. Start from a layout: getting started steps, troubleshooting or an API endpoint.', 'knowx' ),
					'label'  => __( 'Write an article', 'knowx' ),
					'url'    => admin_url( 'post-new.php' ),
					'guide'  => $docs . 'templates-and-patterns/02-block-patterns/',
				),
				array(
					'title'  => __( 'FAQ and changelog pages', 'knowx' ),
					'text'   => __( 'Add a page, choose the FAQ or Changelog template and start from its layout. FAQ answers fold open and are marked up for search engines.', 'knowx' ),
					'label'  => __( 'Add a page', 'knowx' ),
					'url'    => admin_url( 'post-new.php?post_type=page' ),
					'guide'  => $docs . 'features/03-faq-and-changelog/',
				),
				array(
					'title'  => __( 'Colours and dark mode', 'knowx' ),
					'text'   => __( 'Pick a theme colour; the other colours are derived from it. Choose light, dark or the visitor\'s system setting.', 'knowx' ),
					'label'  => __( 'Open Colors', 'knowx' ),
					'url'    => admin_url( 'customize.php?autofocus[section]=site_skin_section' ),
					'guide'  => $docs . 'customizing/02-colours-and-dark-mode/',
				),
				array(
					'title'  => __( 'Docs home', 'knowx' ),
					'text'   => __( 'Change the hero, show products as cards or with their sections, and set up the recent list and the support block.', 'knowx' ),
					'label'  => __( 'Open Docs Home', 'knowx' ),
					'url'    => admin_url( 'customize.php?autofocus[section]=site_knowledge_section_one' ),
					'guide'  => $docs . 'customizing/07-docs-home-settings/',
				),
				array(
					'title'  => __( 'Menus', 'knowx' ),
					'text'   => __( 'Link your docs home, FAQ, changelog and any product in the main menu.', 'knowx' ),
					'label'  => __( 'Edit menus', 'knowx' ),
					'url'    => admin_url( 'nav-menus.php' ),
					'guide'  => $docs . 'customizing/05-header-and-sub-header/#menus',
				),
			),
			'help'  => array(
				array(
					'label' => __( 'Getting started guide', 'knowx' ),
					'url'   => $docs . 'getting-started/01-overview/',
				),
				array(
					'label' => __( 'Customizer guide', 'knowx' ),
					'url'   => $docs . 'customizing/01-customizer-overview/',
				),
				array(
					'label' => __( 'Developer guide and hooks', 'knowx' ),
					'url'   => $docs . 'developer-guide/03-hooks/',
				),
				array(
					'label' => __( 'FAQ', 'knowx' ),
					'url'   => $docs . 'faq/01-faq/',
				),
				array(
					'label' => __( 'Support forum', 'knowx' ),
					'url'   => 'https://wordpress.org/support/theme/knowx/',
				),
				array(
					'label' => __( 'Rate KnowX', 'knowx' ),
					'url'   => 'https://wordpress.org/support/theme/knowx/reviews/#new-post',
				),
			),
		);

		/**
		 * Filters the cards and help links on Appearance > KnowX.
		 *
		 * Each task has title, text, label, url and guide; each help link has label and url.
		 *
		 * @param array $sections Keys 'tasks' and 'help'.
		 */
		$filtered = (array) apply_filters( 'knowx_getting_started', $sections );

		// A filter that drops a key leaves that list empty instead of breaking the screen.
		return array(
			'tasks' => (array) ( $filtered['tasks'] ?? array() ),
			'help'  => (array) ( $filtered['help'] ?? array() ),
		);
	}

	/**
	 * Prints the screen.
	 */
	public function render_page() {
		$home     = Starter_Content::docs_home_id();
		$samples  = ( new Starter_Content() )->has_sample_content();
		$sections = $this->sections();
		?>
		<div class="wrap knowx-welcome">
			<h1><?php esc_html_e( 'Getting started with KnowX', 'knowx' ); ?></h1>

			<div class="card knowx-welcome-setup">
				<?php if ( $home ) : ?>
					<h2><?php esc_html_e( 'Your docs home is set up', 'knowx' ); ?></h2>
					<p><?php esc_html_e( 'Add products and articles, and they appear on the docs home and in search.', 'knowx' ); ?></p>
					<p class="knowx-welcome-actions">
						<a class="button button-primary" href="<?php echo esc_url( get_permalink( $home ) ); ?>"><?php esc_html_e( 'View docs home', 'knowx' ); ?></a>
						<a class="button" href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>"><?php esc_html_e( 'Customize', 'knowx' ); ?></a>
					</p>
					<?php if ( current_user_can( 'manage_options' ) && ! $samples ) : ?>
						<h3><?php esc_html_e( 'Want examples to learn from?', 'knowx' ); ?></h3>
						<p><?php esc_html_e( 'Sample docs add two example products with sections and articles, an FAQ page and a changelog page next to your own content. Your docs home, front page and menu stay as they are, and you can delete the samples any time.', 'knowx' ); ?></p>
						<p><a class="button" href="<?php echo esc_url( Starter_Content::setup_url( 'sample' ) ); ?>"><?php esc_html_e( 'Add sample docs', 'knowx' ); ?></a></p>
					<?php endif; ?>
				<?php else : ?>
					<h2><?php esc_html_e( 'Set up your docs site', 'knowx' ); ?></h2>
					<p><?php esc_html_e( 'Sample docs add two products with sections and articles, an FAQ page, a changelog page and a menu, so you can see KnowX working and replace the content with your own. An empty docs home adds only the docs home page and a menu item.', 'knowx' ); ?></p>
					<?php if ( current_user_can( 'manage_options' ) ) : ?>
						<p class="knowx-welcome-actions">
							<?php if ( ! $samples ) : ?>
								<a class="button button-primary" href="<?php echo esc_url( Starter_Content::setup_url( 'sample' ) ); ?>"><?php esc_html_e( 'Add sample docs', 'knowx' ); ?></a>
							<?php endif; ?>
							<a class="button<?php echo $samples ? ' button-primary' : ''; ?>" href="<?php echo esc_url( Starter_Content::setup_url( 'home' ) ); ?>"><?php esc_html_e( 'Create an empty docs home', 'knowx' ); ?></a>
						</p>
					<?php else : ?>
						<p><?php esc_html_e( 'Ask a site administrator to set up the docs home.', 'knowx' ); ?></p>
					<?php endif; ?>
				<?php endif; ?>
			</div>

			<h2><?php esc_html_e( 'What you can do', 'knowx' ); ?></h2>
			<div class="knowx-welcome-grid">
				<?php foreach ( $sections['tasks'] as $task ) : ?>
					<div class="card">
						<h3><?php echo esc_html( $task['title'] ?? '' ); ?></h3>
						<p><?php echo esc_html( $task['text'] ?? '' ); ?></p>
						<p class="knowx-welcome-actions">
							<a class="button" href="<?php echo esc_url( $task['url'] ?? '' ); ?>"><?php echo esc_html( $task['label'] ?? '' ); ?></a>
							<?php if ( ! empty( $task['guide'] ) ) : ?>
								<a href="<?php echo esc_url( $task['guide'] ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Read the guide', 'knowx' ); ?><span class="screen-reader-text"> <?php echo esc_html( $task['title'] ?? '' ); ?> <?php esc_html_e( '(opens in a new tab)', 'knowx' ); ?></span></a>
							<?php endif; ?>
						</p>
					</div>
				<?php endforeach; ?>
			</div>

			<h2><?php esc_html_e( 'Guides and help', 'knowx' ); ?></h2>
			<ul class="knowx-welcome-actions">
				<?php foreach ( $sections['help'] as $link ) : ?>
					<li><a href="<?php echo esc_url( $link['url'] ?? '' ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $link['label'] ?? '' ); ?><span class="screen-reader-text"> <?php esc_html_e( '(opens in a new tab)', 'knowx' ); ?></span></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php
	}
}
