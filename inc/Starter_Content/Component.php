<?php
/**
 * KnowX\KnowX\Starter_Content\Component class
 *
 * Activating the theme changes nothing. A one-time notice on the Dashboard and Themes screens
 * offers to add sample docs or an empty docs home; content is created only when the owner clicks,
 * from data bundled in the theme (no external requests). Core's starter-content API is not used:
 * it cannot create categories, so it cannot build products, and it saves everything the first
 * time the owner publishes any change in the Customizer.
 *
 * @package knowx
 */

namespace KnowX\KnowX\Starter_Content;

use KnowX\KnowX\Component_Interface;
use KnowX\KnowX\Nav_Menus\Component as Nav_Menus_Component;

/**
 * Setup notice, sample docs and the docs home page.
 */
class Component implements Component_Interface {

	/**
	 * Option holding the owner's choice: sample, home or dismiss.
	 */
	const STATE_OPTION = 'knowx_setup';

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'starter_content';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'admin_notices', array( $this, 'render_notice' ) );
		add_action( 'admin_post_knowx_setup', array( $this, 'handle_setup' ) );
	}

	/**
	 * Whether the setup offer is still open.
	 *
	 * @return bool
	 */
	public function is_pending(): bool {
		// knowx_theme_activated: sites that ran the 1.x activation setup already have a docs home.
		$pending = ! get_option( self::STATE_OPTION ) && ! get_option( 'knowx_theme_activated' );

		/**
		 * Filters whether the docs setup notice is shown.
		 *
		 * @param bool $pending True until the owner has chosen an option or dismissed the notice.
		 */
		return (bool) apply_filters( 'knowx_show_setup_notice', $pending );
	}

	/**
	 * Prints the setup notice, or the confirmation after a setup.
	 */
	public function render_notice() {
		$screen = get_current_screen();
		if ( ! $screen || ! in_array( $screen->id, array( 'dashboard', 'themes', 'appearance_page_knowx' ), true ) || ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only: picks which confirmation to show.
		$page_id = isset( $_GET['knowx-setup'] ) ? absint( $_GET['knowx-setup'] ) : 0;
		if ( $page_id && 'page' === get_post_type( $page_id ) ) {
			printf(
				'<div class="notice notice-success is-dismissible"><p>%1$s <a href="%2$s">%3$s</a> | <a href="%4$s">%5$s</a></p></div>',
				'sample' === get_option( self::STATE_OPTION ) ? esc_html__( 'Sample docs are added.', 'knowx' ) : esc_html__( 'Your docs home is ready.', 'knowx' ),
				esc_url( get_permalink( $page_id ) ),
				esc_html__( 'View docs home', 'knowx' ),
				esc_url( admin_url( 'customize.php' ) ),
				esc_html__( 'Customize', 'knowx' )
			);
			return;
		}

		if ( ! $this->is_pending() || 'appearance_page_knowx' === $screen->id ) {
			return;
		}

		?>
		<div class="notice notice-info">
			<h2><?php esc_html_e( 'Set up your docs site', 'knowx' ); ?></h2>
			<p><?php esc_html_e( 'Sample docs add two products with sections and articles, an FAQ page, a changelog page and a menu, so you can see KnowX working and replace the content with your own. An empty docs home adds only the docs home page and a menu item.', 'knowx' ); ?></p>
			<p><?php esc_html_e( 'The docs home becomes the front page only if your site shows its latest posts there, and a menu is added only if none is assigned.', 'knowx' ); ?></p>
			<p>
				<?php
				printf(
					/* translators: %s: link to the Getting started screen. */
					esc_html__( 'You can do this later from %s.', 'knowx' ),
					'<a href="' . esc_url( admin_url( 'themes.php?page=knowx' ) ) . '">' . esc_html__( 'Appearance > KnowX', 'knowx' ) . '</a>'
				);
				?>
			</p>
			<p>
				<a class="button button-primary" href="<?php echo esc_url( self::setup_url( 'sample' ) ); ?>"><?php esc_html_e( 'Add sample docs', 'knowx' ); ?></a>
				<a class="button" href="<?php echo esc_url( self::setup_url( 'home' ) ); ?>"><?php esc_html_e( 'Create an empty docs home', 'knowx' ); ?></a>
				<a class="button button-link" href="<?php echo esc_url( self::setup_url( 'dismiss' ) ); ?>"><?php esc_html_e( 'No thanks', 'knowx' ); ?></a>
			</p>
		</div>
		<?php
	}

	/**
	 * Runs the option the owner picked in the notice.
	 */
	public function handle_setup() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to do this.', 'knowx' ), '', array( 'response' => 403 ) );
		}
		check_admin_referer( 'knowx_setup' );

		$mode    = isset( $_GET['mode'] ) ? sanitize_key( wp_unslash( $_GET['mode'] ) ) : '';
		$page_id = 0;
		if ( 'sample' === $mode ) {
			$page_id = $this->create_sample_content();
		} elseif ( 'home' === $mode ) {
			$page_id = $this->create_docs_home();
			if ( $page_id ) {
				$this->add_menu( array( $page_id => __( 'Docs', 'knowx' ) ) );
			}
		} elseif ( 'dismiss' !== $mode ) {
			wp_die( esc_html__( 'Unknown setup option.', 'knowx' ), '', array( 'response' => 400 ) );
		}
		update_option( self::STATE_OPTION, $mode, false );

		$referer = wp_get_referer();
		$back    = $referer ? $referer : admin_url();
		wp_safe_redirect( $page_id ? add_query_arg( 'knowx-setup', $page_id, $back ) : $back );
		exit;
	}

	/**
	 * Finds the site's published docs home page.
	 *
	 * @return int Page ID, 0 when the site has none.
	 */
	public static function docs_home_id(): int {
		return self::page_with_template( 'page-knowledgebase-one.php' );
	}

	/**
	 * Finds a published page that uses one of the theme's page templates.
	 *
	 * @param string $template Template file, such as page-faq.php.
	 * @return int Page ID, 0 when the site has none.
	 */
	public static function page_with_template( string $template ): int {
		$existing = get_posts(
			array(
				'post_type'   => 'page',
				'post_status' => 'publish',
				'numberposts' => 1,
				'fields'      => 'ids',
				'meta_key'    => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- Admin only: the setup click and the Getting started screen.
				'meta_value'  => $template, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			)
		);
		return $existing ? (int) $existing[0] : 0;
	}

	/**
	 * Link that runs one setup option: sample, home or dismiss.
	 *
	 * @param string $mode Setup option.
	 * @return string Nonced admin-post URL.
	 */
	public static function setup_url( string $mode ): string {
		return wp_nonce_url( admin_url( 'admin-post.php?action=knowx_setup&mode=' . $mode ), 'knowx_setup' );
	}

	/**
	 * Creates the docs home page, or reuses the one the site has. The menu is left to the caller.
	 *
	 * @param string $excerpt Intro shown under the docs home heading.
	 * @return int Page ID, 0 on failure.
	 */
	public function create_docs_home( string $excerpt = '' ): int {
		$page_id = self::docs_home_id();
		$page_id = $page_id ? $page_id : $this->insert_page( __( 'Documentation', 'knowx' ), '', 'page-knowledgebase-one.php', $excerpt );
		if ( ! $page_id ) {
			return 0;
		}

		if ( 'posts' === get_option( 'show_on_front' ) ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $page_id );
		}

		return $page_id;
	}

	/**
	 * The sample docs bundled with the theme, after the knowx_sample_content filter.
	 *
	 * @return array
	 */
	private function sample_data(): array {
		/**
		 * Filters the sample docs, so a child theme or plugin can ship its own products.
		 *
		 * @param array $content Keys: home_excerpt, products, faq, changelog (see inc/Starter_Content/sample-content.php).
		 */
		return (array) apply_filters( 'knowx_sample_content', require __DIR__ . '/sample-content.php' );
	}

	/**
	 * Whether the sample docs were added, by the button or by the Playground seed, so they are not offered again.
	 *
	 * @return bool
	 */
	public function has_sample_content(): bool {
		if ( 'sample' === get_option( self::STATE_OPTION ) ) {
			return true;
		}
		foreach ( (array) ( $this->sample_data()['products'] ?? array() ) as $product ) {
			if ( ! empty( $product['slug'] ) && get_term_by( 'slug', $product['slug'], 'category' ) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Creates the sample products, sections, articles, FAQ and changelog pages, docs home and menu.
	 * Running it again creates nothing twice.
	 *
	 * @return int Docs home page ID, 0 on failure.
	 */
	public function create_sample_content(): int {
		$content  = $this->sample_data();
		$featured = array();
		$products = array();

		foreach ( (array) ( $content['products'] ?? array() ) as $product ) {
			if ( get_term_by( 'slug', $product['slug'], 'category' ) ) {
				continue;
			}
			$root = wp_insert_term(
				$product['name'],
				'category',
				array(
					'slug'        => $product['slug'],
					'description' => $product['desc'] ?? '',
				)
			);
			if ( is_wp_error( $root ) ) {
				continue;
			}
			$root       = (int) $root['term_id'];
			$products[] = $root;
			update_term_meta( $root, 'knowx_icon', $product['icon'] ?? '' );
			update_term_meta( $root, 'knowx_accent', $product['accent'] ?? '' );

			$first = true;
			foreach ( $product['sections'] as $section => $articles ) {
				$term = wp_insert_term(
					$section,
					'category',
					array(
						'parent' => $root,
						'slug'   => $product['slug'] . '-' . sanitize_title( $section ),
					)
				);
				if ( is_wp_error( $term ) ) {
					continue;
				}
				foreach ( $articles as $position => $article ) {
					// wp_insert_post() unslashes its input; slash it so code samples keep their backslashes.
					$id = wp_insert_post(
						wp_slash(
							array(
								'post_type'      => 'post',
								'post_status'    => 'publish',
								'post_author'    => get_current_user_id(),
								'post_title'     => $article[0],
								'post_excerpt'   => $article[1],
								'post_content'   => $this->article_content( $article ),
								'post_category'  => array( (int) $term['term_id'] ),
								'menu_order'     => $position + 1,
							)
						)
					);
					if ( ! $id ) {
						continue;
					}
					update_post_meta( $id, 'knowx_version', $product['version'] ?? '' );
					if ( $first ) {
						$featured[] = $id;
						$first      = false;
					}
				}
			}
		}
		if ( $featured ) {
			update_option( 'sticky_posts', array_values( array_unique( array_merge( (array) get_option( 'sticky_posts', array() ), $featured ) ) ) );
		}

		$faq = '';
		foreach ( (array) ( $content['faq'] ?? array() ) as $row ) {
			$faq .= '<!-- wp:details -->' . "\n" . '<details class="wp-block-details"><summary>' . esc_html( $row[0] ) . '</summary>' . $this->block( 'paragraph', '<p>' . esc_html( $row[1] ) . '</p>' ) . '</details>' . "\n" . '<!-- /wp:details -->' . "\n\n";
		}
		$changelog = '';
		foreach ( (array) ( $content['changelog'] ?? array() ) as $release => $items ) {
			$changelog .= $this->heading( $release, 2 ) . $this->items( $items, false );
		}

		$home  = $this->create_docs_home( (string) ( $content['home_excerpt'] ?? '' ) );
		$pages = array( $home => __( 'Docs', 'knowx' ) );
		if ( $faq ) {
			$pages[ $this->insert_page( __( 'FAQ', 'knowx' ), $faq, 'page-faq.php' ) ] = __( 'FAQ', 'knowx' );
		}
		if ( $changelog ) {
			$pages[ $this->insert_page( __( 'Changelog', 'knowx' ), $changelog, 'page-changelog.php' ) ] = __( 'Changelog', 'knowx' );
		}
		$this->add_menu( array_filter( $pages, 'boolval', ARRAY_FILTER_USE_KEY ) );

		if ( function_exists( 'knowx_kb_bump_version' ) ) {
			knowx_kb_bump_version();
		}

		/**
		 * Fires after the sample docs are created.
		 *
		 * @param int   $home     Docs home page ID.
		 * @param int[] $products Product (top-level category) IDs created in this run.
		 */
		do_action( 'knowx_sample_content_created', $home, $products );

		return $home;
	}

	/**
	 * Adds a page, or returns the published page with the same title.
	 *
	 * @param string $title    Title.
	 * @param string $content  Block markup.
	 * @param string $template Page template file.
	 * @param string $excerpt  Excerpt.
	 * @return int Page ID, 0 on failure.
	 */
	private function insert_page( string $title, string $content, string $template, string $excerpt = '' ): int {
		$by_template = self::page_with_template( $template );
		if ( $by_template ) {
			return $by_template;
		}
		$existing = get_page_by_path( sanitize_title( $title ) );
		if ( $existing && 'publish' === $existing->post_status ) {
			return (int) $existing->ID;
		}
		$id = wp_insert_post(
			wp_slash(
				array(
					'post_type'    => 'page',
					'post_status'  => 'publish',
					'post_author'  => get_current_user_id(),
					'post_title'   => $title,
					'post_excerpt' => $excerpt,
					'post_content' => $content,
					'meta_input'   => array( '_wp_page_template' => $template ),
				)
			)
		);
		return (int) $id;
	}

	/**
	 * Puts the pages in a menu at the primary location, unless a menu is already assigned there.
	 *
	 * @param array<int, string> $pages Page ID => menu label.
	 */
	private function add_menu( array $pages ) {
		$locations = (array) get_theme_mod( 'nav_menu_locations', array() );
		if ( ! empty( $locations[ Nav_Menus_Component::PRIMARY_NAV_MENU_SLUG ] ) || ! $pages ) {
			return;
		}
		$menu_id = wp_create_nav_menu( __( 'Main menu', 'knowx' ) );
		if ( is_wp_error( $menu_id ) ) {
			return;
		}
		foreach ( $pages as $page_id => $label ) {
			wp_update_nav_menu_item(
				$menu_id,
				0,
				array(
					'menu-item-title'     => $label,
					'menu-item-object'    => 'page',
					'menu-item-object-id' => $page_id,
					'menu-item-type'      => 'post_type',
					'menu-item-status'    => 'publish',
				)
			);
		}
		$locations[ Nav_Menus_Component::PRIMARY_NAV_MENU_SLUG ] = $menu_id;
		set_theme_mod( 'nav_menu_locations', $locations );
	}

	/**
	 * Block markup for one sample article: lead, steps, optional example and a note.
	 *
	 * @param array $article Title, lead, steps, note and an optional code example.
	 * @return string
	 */
	private function article_content( array $article ): string {
		$content  = $this->block( 'paragraph', '<p>' . $article[1] . '</p>' );
		$content .= $this->heading( __( 'Steps', 'knowx' ), 2 ) . $this->items( $article[2], true );
		if ( ! empty( $article[4] ) ) {
			$content .= $this->heading( __( 'Example', 'knowx' ), 3 ) . $this->block( 'code', '<pre class="wp-block-code"><code>' . esc_html( $article[4] ) . '</code></pre>' );
		}
		$note = $this->block( 'paragraph', '<p><strong>' . esc_html__( 'Note.', 'knowx' ) . '</strong> ' . $article[3] . '</p>' );
		return $content . '<!-- wp:group {"style":{"border":{"left":{"width":"4px"}},"spacing":{"padding":{"left":"1rem","top":"0.5rem","bottom":"0.5rem"}}},"backgroundColor":"tertiary"} -->' . "\n"
			. '<div class="wp-block-group has-tertiary-background-color has-background" style="border-left-width:4px;padding-top:0.5rem;padding-bottom:0.5rem;padding-left:1rem">' . "\n" . $note . '</div>' . "\n" . '<!-- /wp:group -->' . "\n\n";
	}

	/**
	 * Wraps markup in block comments.
	 *
	 * @param string $name  Core block name without the prefix.
	 * @param string $html  Inner markup.
	 * @param string $attrs JSON attributes, with a leading space.
	 * @return string
	 */
	private function block( string $name, string $html, string $attrs = '' ): string {
		return "<!-- wp:$name$attrs -->\n$html\n<!-- /wp:$name -->\n\n";
	}

	/**
	 * Heading block.
	 *
	 * @param string $text  Text.
	 * @param int    $level 2 or 3.
	 * @return string
	 */
	private function heading( string $text, int $level ): string {
		return $this->block( 'heading', "<h$level class=\"wp-block-heading\">" . esc_html( $text ) . "</h$level>", 2 === $level ? '' : ' {"level":' . $level . '}' );
	}

	/**
	 * List block.
	 *
	 * @param string[] $items   Items (theme markup).
	 * @param bool     $ordered Numbered list.
	 * @return string
	 */
	private function items( array $items, bool $ordered ): string {
		$li = '';
		foreach ( $items as $item ) {
			$li .= "<!-- wp:list-item -->\n<li>$item</li>\n<!-- /wp:list-item -->\n";
		}
		$tag = $ordered ? 'ol' : 'ul';
		return $this->block( 'list', "<$tag class=\"wp-block-list\">$li</$tag>", $ordered ? ' {"ordered":true}' : '' );
	}
}
