<?php
/**
 * KnowX\KnowX\Docs\Component class
 *
 * @package knowx
 */

namespace KnowX\KnowX\Docs;

use KnowX\KnowX\Component_Interface;
use KnowX\KnowX\Tokens\Component as Tokens;

use function KnowX\KnowX\knowx;
use function add_action;
use function add_filter;
use function add_meta_box;
use function add_post_type_support;
use function get_post_status;
use function get_post_type;
use function get_theme_file_path;
use function get_theme_file_uri;
use function register_block_pattern_category;
use function register_post_meta;
use function register_term_meta;
use function wp_add_inline_style;

/**
 * Product settings (category icon and accent colour), the per-article version and article order.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'docs';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'init', array( $this, 'action_register_meta' ) );
		add_action( 'init', array( $this, 'action_register_pattern_category' ) );
		// Only changes that can alter a product list, tree or count refresh the cached docs data.
		add_action( 'save_post_post', array( $this, 'invalidate_on_save_post' ), 10, 2 );
		add_action( 'transition_post_status', array( $this, 'invalidate_on_status_change' ), 10, 3 );
		add_action( 'deleted_post', array( $this, 'invalidate_on_delete_post' ), 10, 2 );
		add_action( 'set_object_terms', array( $this, 'invalidate_on_terms' ), 10, 4 );
		foreach ( array( 'created_category', 'edited_category', 'delete_category', 'update_option_sticky_posts' ) as $hook ) {
			add_action( $hook, array( $this, 'invalidate' ) );
		}
		foreach ( array( 'added_term_meta', 'updated_term_meta', 'deleted_term_meta' ) as $hook ) {
			add_action( $hook, array( $this, 'invalidate_on_term_meta' ), 10, 3 );
		}

		add_action( 'wp_footer', array( $this, 'render_search_dialog' ) );
		add_action( 'wp_head', array( $this, 'render_schema' ), 20 );
		add_action( 'wp_head', array( $this, 'render_meta_description' ), 2 );
		add_filter( 'the_content', 'knowx_add_heading_anchors', 20 );
		add_action( 'wp_enqueue_scripts', array( $this, 'action_add_accent' ), 25 );

		add_action( 'category_add_form_fields', array( $this, 'render_add_fields' ) );
		add_action( 'category_edit_form_fields', array( $this, 'render_edit_fields' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_picker_styles' ) );
		add_action( 'created_category', array( $this, 'save_term_fields' ) );
		add_action( 'edited_category', array( $this, 'save_term_fields' ) );

		add_action( 'add_meta_boxes_post', array( $this, 'add_version_box' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_order_panel' ) );
		add_action( 'save_post_post', array( $this, 'save_version' ) );
		add_action( 'pre_get_posts', array( $this, 'order_category_archives' ) );
	}

	/**
	 * Registers the pattern category the theme's patterns (patterns/*.php) are listed under.
	 */
	public function action_register_pattern_category() {
		register_block_pattern_category( 'knowx-docs', array( 'label' => __( 'KnowX Docs', 'knowx' ) ) );
	}

	/**
	 * Prints the meta description.
	 */
	public function render_meta_description() {
		$description = knowx_meta_description();
		if ( '' !== $description ) {
			echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
		}
	}

	/**
	 * Prints the schema.org graph as JSON-LD.
	 */
	public function render_schema() {
		$graph = knowx_schema_graph();
		if ( empty( $graph ) ) {
			return;
		}

		echo '<script type="application/ld+json">' . wp_json_encode(
			array(
				'@context' => 'https://schema.org',
				'@graph'   => $graph,
			),
			JSON_HEX_TAG | JSON_HEX_AMP | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
		) . "</script>\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON encoded with the hex tag flags.
	}

	/**
	 * Prints the search overlay once, at the end of the page.
	 */
	public function render_search_dialog() {
		get_template_part( 'template-parts/docs/search-dialog' );
	}

	/**
	 * Registers the product meta so it is also readable and writable through the REST API.
	 */
	public function action_register_meta() {
		// Adds the Order field to the editor so owners can sort articles inside a section.
		add_post_type_support( 'post', 'page-attributes' );

		// Core's posts endpoint has a fixed field list without menu_order, so the editor and REST clients cannot read or save the order.
		register_rest_field(
			'post',
			'menu_order',
			array(
				'get_callback'    => static function ( $post ) {
					return (int) get_post_field( 'menu_order', $post['id'] );
				},
				'update_callback' => static function ( $value, $post ) {
					if ( ! current_user_can( 'edit_post', $post->ID ) ) {
						return new \WP_Error( 'rest_cannot_edit', __( 'Sorry, you are not allowed to edit this post.', 'knowx' ), array( 'status' => rest_authorization_required_code() ) );
					}
					wp_update_post(
						array(
							'ID'         => $post->ID,
							'menu_order' => (int) $value,
						)
					);
					return true;
				},
				'schema'          => array(
					'description' => __( 'The order of the article inside its section.', 'knowx' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
			)
		);

		register_post_meta(
			'post',
			'knowx_version',
			array(
				'type'              => 'string',
				'single'            => true,
				'show_in_rest'      => true,
				'sanitize_callback' => 'sanitize_text_field',
				'auth_callback'     => static function ( $allowed, $meta_key, $post_id ) {
					return current_user_can( 'edit_post', $post_id );
				},
			)
		);

		register_term_meta(
			'category',
			'knowx_icon',
			array(
				'type'              => 'string',
				'single'            => true,
				'show_in_rest'      => true,
				'sanitize_callback' => array( $this, 'sanitize_icon' ),
				'auth_callback'     => static function () {
					return current_user_can( 'manage_categories' );
				},
			)
		);
		register_term_meta(
			'category',
			'knowx_order',
			array(
				'type'              => 'integer',
				'single'            => true,
				'default'           => 0,
				'show_in_rest'      => true,
				'sanitize_callback' => 'absint',
				'auth_callback'     => static function () {
					return current_user_can( 'manage_categories' );
				},
			)
		);
		register_term_meta(
			'category',
			'knowx_start',
			array(
				'type'              => 'string',
				'single'            => true,
				'show_in_rest'      => true,
				'sanitize_callback' => 'esc_url_raw',
				'auth_callback'     => static function () {
					return current_user_can( 'manage_categories' );
				},
			)
		);
		register_term_meta(
			'category',
			'knowx_accent',
			array(
				'type'              => 'string',
				'single'            => true,
				'show_in_rest'      => true,
				'sanitize_callback' => 'sanitize_hex_color',
				'auth_callback'     => static function () {
					return current_user_can( 'manage_categories' );
				},
			)
		);
	}

	/**
	 * Accepts only icons the theme ships.
	 *
	 * @param mixed $value Submitted icon slug.
	 * @return string Slug or an empty string.
	 */
	public function sanitize_icon( $value ): string {
		$value = sanitize_key( (string) $value );

		return isset( knowx_product_icons()[ $value ] ) ? $value : '';
	}

	/**
	 * Colours the product's icon and header with its accent. Buttons and links always follow the theme colour.
	 */
	public function action_add_accent() {
		$product = knowx_current_product();
		$accent  = $product ? Tokens::sanitize_color( $product['accent'] ) : '';
		if ( '' === $accent ) {
			return;
		}

		wp_add_inline_style( 'knowx-global', '.knowx-docs{--card-accent:' . $accent . ';}' );
	}

	/**
	 * Styles for the icon and colour pickers on the category screens.
	 */
	public function enqueue_picker_styles() {
		$screen = get_current_screen();
		if ( ! $screen || 'category' !== $screen->taxonomy ) {
			return;
		}
		wp_add_inline_style(
			'common',
			'.knowx-picker{display:flex;flex-wrap:wrap;align-items:center;gap:6px;max-width:520px}'
			. '.knowx-pick{position:relative;display:inline-flex;align-items:center;justify-content:center;min-width:38px;height:38px;margin:0;border:1px solid #c3c4c7;border-radius:6px;background:#fff;cursor:pointer}'
			. '.knowx-pick-text{padding:0 10px;font-size:13px}'
			. '.knowx-pick input{position:absolute;opacity:0;inset:0;margin:0;cursor:pointer}'
			. '.knowx-pick:has(input:checked){border-color:#2271b1;box-shadow:0 0 0 1px #2271b1;background:#f0f6fc}'
			. '.knowx-pick:has(input:focus-visible){outline:2px solid #2271b1;outline-offset:2px}'
			. '.knowx-swatch{display:block;width:22px;height:22px;border-radius:50%}'
		);
	}

	/**
	 * Fields on the Add category form.
	 */
	public function render_add_fields() {
		?>
		<div class="form-field">
			<label for="knowx_icon"><?php esc_html_e( 'Product icon', 'knowx' ); ?></label>
			<?php $this->icon_select( '' ); ?>
			<p><?php esc_html_e( 'Shown on the product card when this is a top-level category.', 'knowx' ); ?></p>
		</div>
		<div class="form-field">
			<label for="knowx_accent"><?php esc_html_e( 'Product accent colour', 'knowx' ); ?></label>
			<?php $this->accent_input( '' ); ?>
			<p><?php esc_html_e( 'Buttons and links on this product use this colour. Automatic picks one from the theme palette when the site has several products.', 'knowx' ); ?></p>
		</div>
		<?php
		$this->add_form_extra_fields();
	}

	/**
	 * Order and "Start here" fields for the Add category form.
	 */
	private function add_form_extra_fields() {
		?>
		<div class="form-field">
			<label for="knowx_order"><?php esc_html_e( 'Product order', 'knowx' ); ?></label>
			<input type="number" min="0" name="knowx_order" id="knowx_order" value="0" class="small-text">
			<p><?php esc_html_e( 'Products are listed from the lowest number to the highest, then by name.', 'knowx' ); ?></p>
		</div>
		<div class="form-field">
			<label for="knowx_start"><?php esc_html_e( 'Start here link', 'knowx' ); ?></label>
			<input type="url" name="knowx_start" id="knowx_start" value="">
			<p><?php esc_html_e( 'Where the Start here button on the product page goes. Leave empty to open the first article.', 'knowx' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Fields on the Edit category form.
	 *
	 * @param \WP_Term $term Category being edited.
	 */
	public function render_edit_fields( $term ) {
		?>
		<tr class="form-field">
			<th scope="row"><label for="knowx_icon"><?php esc_html_e( 'Product icon', 'knowx' ); ?></label></th>
			<td>
				<?php $this->icon_select( (string) get_term_meta( $term->term_id, 'knowx_icon', true ) ); ?>
				<p class="description"><?php esc_html_e( 'Shown on the product card when this is a top-level category.', 'knowx' ); ?></p>
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row"><label for="knowx_accent"><?php esc_html_e( 'Product accent colour', 'knowx' ); ?></label></th>
			<td>
				<?php $this->accent_input( (string) get_term_meta( $term->term_id, 'knowx_accent', true ) ); ?>
				<p class="description"><?php esc_html_e( 'Buttons and links on this product use this colour. Automatic picks one from the theme palette when the site has several products.', 'knowx' ); ?></p>
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row"><label for="knowx_order"><?php esc_html_e( 'Product order', 'knowx' ); ?></label></th>
			<td>
				<input type="number" min="0" name="knowx_order" id="knowx_order" value="<?php echo esc_attr( (string) absint( get_term_meta( $term->term_id, 'knowx_order', true ) ) ); ?>" class="small-text">
				<p class="description"><?php esc_html_e( 'Products are listed from the lowest number to the highest, then by name.', 'knowx' ); ?></p>
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row"><label for="knowx_start"><?php esc_html_e( 'Start here link', 'knowx' ); ?></label></th>
			<td>
				<input type="url" name="knowx_start" id="knowx_start" value="<?php echo esc_attr( (string) get_term_meta( $term->term_id, 'knowx_start', true ) ); ?>">
				<p class="description"><?php esc_html_e( 'Where the Start here button on the product page goes. Leave empty to open the first article.', 'knowx' ); ?></p>
			</td>
		</tr>
		<?php
	}

	/**
	 * Prints the icon picker: Automatic plus every icon the theme ships, as a grid.
	 *
	 * @param string $current Saved icon slug.
	 */
	protected function icon_select( string $current ) {
		echo '<div class="knowx-picker" role="radiogroup" aria-label="' . esc_attr__( 'Product icon', 'knowx' ) . '" id="knowx_icon">';
		printf(
			'<label class="knowx-pick knowx-pick-text"><input type="radio" name="knowx_icon" value=""%1$s><span>%2$s</span></label>',
			checked( '', $current, false ),
			esc_html__( 'Automatic', 'knowx' )
		);
		foreach ( knowx_product_icons() as $slug => $icon ) {
			printf(
				'<label class="knowx-pick" title="%1$s"><input type="radio" name="knowx_icon" value="%2$s"%3$s><span>%4$s</span><span class="screen-reader-text">%1$s</span></label>',
				esc_attr( $icon[0] ),
				esc_attr( $slug ),
				checked( $current, $slug, false ),
				knowx_icon_svg( $slug, 22 ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in knowx_icon_svg().
			);
		}
		echo '</div>';
	}

	/**
	 * Prints the accent picker: Automatic, the theme's palette as swatches, and a colour of the owner's own.
	 *
	 * @param string $current Saved hex colour.
	 */
	protected function accent_input( string $current ) {
		$palette = knowx_default_product_looks()['accents'];
		$custom  = '' !== $current && ! in_array( strtolower( $current ), $palette, true );

		echo '<div class="knowx-picker" role="radiogroup" aria-label="' . esc_attr__( 'Product accent colour', 'knowx' ) . '" id="knowx_accent">';
		printf(
			'<label class="knowx-pick knowx-pick-text"><input type="radio" name="knowx_accent_choice" value=""%1$s><span>%2$s</span></label>',
			checked( '', $current, false ),
			esc_html__( 'Automatic', 'knowx' )
		);
		foreach ( $palette as $hex ) {
			printf(
				'<label class="knowx-pick" title="%1$s"><input type="radio" name="knowx_accent_choice" value="%1$s"%2$s><span class="knowx-swatch" style="background:%1$s"></span><span class="screen-reader-text">%1$s</span></label>',
				esc_attr( $hex ),
				checked( strtolower( $current ), $hex, false )
			);
		}
		printf(
			'<label class="knowx-pick knowx-pick-text"><input type="radio" name="knowx_accent_choice" value="custom"%1$s><span>%2$s</span></label> <input type="color" name="knowx_accent_custom" value="%3$s" aria-label="%4$s">',
			checked( $custom, true, false ),
			esc_html__( 'Your own', 'knowx' ),
			esc_attr( $custom ? $current : '#03a9f4' ),
			esc_attr__( 'Your own colour', 'knowx' )
		);
		echo '</div>';
	}

	/**
	 * Saves the product fields. WordPress has already checked the nonce and capability of the term form.
	 *
	 * @param int $term_id Category id.
	 */
	public function save_term_fields( $term_id ) {
		if ( ! current_user_can( 'edit_term', $term_id ) ) {
			return;
		}

		// phpcs:disable WordPress.Security.NonceVerification.Missing -- the category form is verified by WordPress before these hooks run.
		if ( isset( $_POST['knowx_icon'] ) ) {
			update_term_meta( $term_id, 'knowx_icon', $this->sanitize_icon( sanitize_text_field( wp_unslash( $_POST['knowx_icon'] ) ) ) );
		}
		if ( isset( $_POST['knowx_accent_choice'] ) ) {
			$choice = sanitize_text_field( wp_unslash( $_POST['knowx_accent_choice'] ) );
			if ( 'custom' === $choice ) {
				$choice = isset( $_POST['knowx_accent_custom'] ) ? sanitize_text_field( wp_unslash( $_POST['knowx_accent_custom'] ) ) : '';
			}
			update_term_meta( $term_id, 'knowx_accent', (string) sanitize_hex_color( $choice ) );
		}
		if ( isset( $_POST['knowx_order'] ) ) {
			update_term_meta( $term_id, 'knowx_order', absint( wp_unslash( $_POST['knowx_order'] ) ) );
		}
		if ( isset( $_POST['knowx_start'] ) ) {
			update_term_meta( $term_id, 'knowx_start', esc_url_raw( wp_unslash( $_POST['knowx_start'] ) ) );
		}
		// phpcs:enable WordPress.Security.NonceVerification.Missing
	}

	/**
	 * Refreshes the cached docs data, at most twice per request (see knowx_kb_invalidate()).
	 */
	public function invalidate() {
		knowx_kb_invalidate();
	}

	/**
	 * A published article was saved. Drafts, autosaves and revisions do not change what readers see.
	 *
	 * @param int      $post_id Post id.
	 * @param \WP_Post $post    Post.
	 */
	public function invalidate_on_save_post( $post_id, $post ) {
		if ( 'publish' === $post->post_status ) {
			$this->invalidate();
		}
	}

	/**
	 * An article was published, unpublished or trashed.
	 *
	 * @param string   $new_status New status.
	 * @param string   $old_status Old status.
	 * @param \WP_Post $post       Post.
	 */
	public function invalidate_on_status_change( $new_status, $old_status, $post ) {
		if ( 'post' === $post->post_type && ( 'publish' === $new_status || 'publish' === $old_status ) && $new_status !== $old_status ) {
			$this->invalidate();
		}
	}

	/**
	 * An article was deleted.
	 *
	 * @param int      $post_id Post id.
	 * @param \WP_Post $post    Post.
	 */
	public function invalidate_on_delete_post( $post_id, $post ) {
		if ( 'post' === $post->post_type ) {
			$this->invalidate();
		}
	}

	/**
	 * The categories of a published article changed.
	 *
	 * @param int    $object_id Object id.
	 * @param array  $terms     Terms.
	 * @param array  $tt_ids    Term taxonomy ids.
	 * @param string $taxonomy  Taxonomy.
	 */
	public function invalidate_on_terms( $object_id, $terms, $tt_ids, $taxonomy ) {
		if ( 'category' === $taxonomy && 'post' === get_post_type( $object_id ) && 'publish' === get_post_status( $object_id ) ) {
			$this->invalidate();
		}
	}

	/**
	 * A product field (icon, accent, order, start link) changed.
	 *
	 * @param int    $meta_id  Meta id.
	 * @param int    $term_id  Term id.
	 * @param string $meta_key Meta key.
	 */
	public function invalidate_on_term_meta( $meta_id, $term_id, $meta_key ) {
		if ( 0 === strpos( (string) $meta_key, 'knowx_' ) ) {
			$this->invalidate();
		}
	}

	/**
	 * Adds the Order field to the block editor sidebar for articles.
	 */
	public function enqueue_order_panel() {
		if ( 'post' !== get_post_type() ) {
			return;
		}

		$file = get_theme_file_path( '/assets/js/editor-order.min.js' );
		wp_enqueue_script( 'knowx-editor-order', get_theme_file_uri( '/assets/js/editor-order.min.js' ), array( 'wp-plugins', 'wp-editor', 'wp-components', 'wp-data', 'wp-element', 'wp-i18n' ), knowx()->get_asset_version( $file ), true );
		wp_set_script_translations( 'knowx-editor-order', 'knowx' );
	}

	/**
	 * Adds the "applies to version" box to the post editor.
	 */
	public function add_version_box() {
		add_meta_box( 'knowx-version', __( 'Docs', 'knowx' ), array( $this, 'render_version_box' ), 'post', 'side' );
	}

	/**
	 * Prints the version field.
	 *
	 * @param \WP_Post $post Post being edited.
	 */
	public function render_version_box( $post ) {
		wp_nonce_field( 'knowx_version', 'knowx_version_nonce' );
		printf(
			'<p><label for="knowx_version">%1$s</label><input type="text" class="widefat" id="knowx_version" name="knowx_version" value="%2$s" placeholder="%3$s"></p><p class="description">%4$s</p>',
			esc_html__( 'Applies to version', 'knowx' ),
			esc_attr( knowx_article_version( $post->ID ) ),
			esc_attr__( 'e.g. 2.4', 'knowx' ),
			esc_html__( 'Shown as a badge on the article. Leave empty when it applies to every version.', 'knowx' )
		);
	}

	/**
	 * Saves the version field.
	 *
	 * @param int $post_id Post id.
	 */
	public function save_version( $post_id ) {
		if ( ! isset( $_POST['knowx_version_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['knowx_version_nonce'] ) ), 'knowx_version' ) || ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$version = isset( $_POST['knowx_version'] ) ? sanitize_text_field( wp_unslash( $_POST['knowx_version'] ) ) : '';
		if ( '' === $version ) {
			delete_post_meta( $post_id, 'knowx_version' );
		} else {
			update_post_meta( $post_id, 'knowx_version', $version );
		}
	}

	/**
	 * Category archives list articles by the editor's Order field first, then by the Customizer choice.
	 *
	 * @param \WP_Query $query Query being built.
	 */
	public function order_category_archives( $query ) {
		if ( is_admin() || ! $query->is_main_query() || ! $query->is_category() ) {
			return;
		}

		$sort = knowx_mod( 'knowx_section_sort' );
		if ( 'title' === $sort ) {
			$orderby = array(
				'menu_order' => 'ASC',
				'title'      => 'ASC',
			);
		} elseif ( 'updated' === $sort ) {
			$orderby = array(
				'menu_order' => 'ASC',
				'modified'   => 'DESC',
			);
		} else {
			$orderby = array(
				'menu_order' => 'ASC',
				'date'       => 'DESC',
			);
		}

		$query->set( 'orderby', $orderby );
	}
}
