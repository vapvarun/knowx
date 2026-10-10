<?php
/**
 * KnowX\KnowX\Docs\Component class
 *
 * @package knowx
 */

namespace KnowX\KnowX\Docs;

use KnowX\KnowX\Component_Interface;
use KnowX\KnowX\Tokens\Component as Tokens;

use function add_action;
use function add_filter;
use function add_meta_box;
use function add_post_type_support;
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
		add_action( 'wp_footer', array( $this, 'render_search_dialog' ) );
		add_action( 'wp_head', array( $this, 'render_schema' ), 20 );
		add_filter( 'the_content', 'knowx_add_heading_anchors', 20 );
		add_action( 'wp_enqueue_scripts', array( $this, 'action_add_accent' ), 25 );

		add_action( 'category_add_form_fields', array( $this, 'render_add_fields' ) );
		add_action( 'category_edit_form_fields', array( $this, 'render_edit_fields' ) );
		add_action( 'created_category', array( $this, 'save_term_fields' ) );
		add_action( 'edited_category', array( $this, 'save_term_fields' ) );

		add_action( 'add_meta_boxes_post', array( $this, 'add_version_box' ) );
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
	 * Uses the product's accent colour as the brand colour on its pages.
	 *
	 * Dark mode keeps working because the dark palette derives its link colours from the brand colour.
	 */
	public function action_add_accent() {
		$product = knowx_current_product();
		$accent  = $product ? Tokens::sanitize_color( $product['accent'] ) : '';
		if ( '' === $accent ) {
			return;
		}

		$css  = '--color-theme-primary:' . $accent . ';--color-on-primary:' . Tokens::on_color( $accent ) . ';';
		$link = Tokens::link_colors( $accent, '#f7f7f9' );
		if ( $link ) {
			$css .= '--color-link:' . $link[0] . ';--color-link-hover:' . $link[1] . ';';
		}

		wp_add_inline_style( 'knowx-global', ':root{' . $css . '}' );
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
			<p><?php esc_html_e( 'Buttons and links on this product use this colour. Leave unchecked to use the theme colour.', 'knowx' ); ?></p>
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
				<p class="description"><?php esc_html_e( 'Buttons and links on this product use this colour. Leave unchecked to use the theme colour.', 'knowx' ); ?></p>
			</td>
		</tr>
		<?php
	}

	/**
	 * Prints the icon select.
	 *
	 * @param string $current Saved icon slug.
	 */
	protected function icon_select( string $current ) {
		echo '<select name="knowx_icon" id="knowx_icon"><option value="">' . esc_html__( 'Default', 'knowx' ) . '</option>';
		foreach ( knowx_product_icons() as $slug => $icon ) {
			printf( '<option value="%s"%s>%s</option>', esc_attr( $slug ), selected( $current, $slug, false ), esc_html( $icon[0] ) );
		}
		echo '</select>';
	}

	/**
	 * Prints the colour input with its "use accent" switch.
	 *
	 * @param string $current Saved hex colour.
	 */
	protected function accent_input( string $current ) {
		printf(
			'<label><input type="checkbox" name="knowx_accent_on" value="1"%1$s> %2$s</label> <input type="color" name="knowx_accent" id="knowx_accent" value="%3$s">',
			checked( '' !== $current, true, false ),
			esc_html__( 'Use an accent colour', 'knowx' ),
			esc_attr( '' !== $current ? $current : '#03a9f4' )
		);
	}

	/**
	 * Saves the product fields. WordPress has already checked the nonce and capability of the term form.
	 *
	 * @param int $term_id Category id.
	 */
	public function save_term_fields( $term_id ) {
		// phpcs:disable WordPress.Security.NonceVerification.Missing -- the category form is verified by WordPress before these hooks run.
		if ( isset( $_POST['knowx_icon'] ) ) {
			update_term_meta( $term_id, 'knowx_icon', $this->sanitize_icon( sanitize_text_field( wp_unslash( $_POST['knowx_icon'] ) ) ) );
		}
		if ( isset( $_POST['knowx_accent'] ) ) {
			$accent = empty( $_POST['knowx_accent_on'] ) ? '' : (string) sanitize_hex_color( wp_unslash( $_POST['knowx_accent'] ) );
			update_term_meta( $term_id, 'knowx_accent', $accent );
		}
		// phpcs:enable WordPress.Security.NonceVerification.Missing
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

		$query->set(
			'orderby',
			'name' === knowx_mod( 'knowx_order' )
				? array(
					'menu_order' => 'ASC',
					'title'      => 'ASC',
				)
				: array(
					'menu_order' => 'ASC',
					'date'       => 'DESC',
				)
		);
	}
}
