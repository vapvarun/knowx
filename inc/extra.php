<?php
/**
 * Template helpers: content wrapper, sub header, loader, footer text and post meta.
 *
 * @package knowx
 */

// Content wrapper.
/**
 * Hooks around the main content, fired by every template.
 *
 * - `knowx_sub_header`: after the header; prints the breadcrumb bar.
 * - `knowx_before_content`: opens the `.site-wrapper` that holds the content and the sidebars.
 * - `knowx_after_content`: closes it.
 *
 * Add your own callbacks with add_action( 'knowx_before_content', ... ).
 */
if ( ! function_exists( 'knowx_content_top' ) ) {
	/**
	 * Opens the site wrapper that holds the content and the sidebars.
	 */
	function knowx_content_top() {
		?>
		<div class="site-wrapper">
		<?php
	}
}

add_action( 'knowx_before_content', 'knowx_content_top' );

if ( ! function_exists( 'knowx_content_bottom' ) ) {
	/**
	 * Closes the site wrapper.
	 */
	function knowx_content_bottom() {

		?>
		</div>
		<?php
	}
}

add_action( 'knowx_after_content', 'knowx_content_bottom' );

// Breadcrumb bar.
if ( ! function_exists( 'knowx_sub_header' ) ) {
	add_action( 'knowx_sub_header', 'knowx_sub_header' );

	/**
	 * Prints the bar with the breadcrumbs under the header.
	 */
	function knowx_sub_header() {
		if ( is_front_page() || ! knowx_is_truthy( knowx_mod( 'site_breadcrumbs' ) ) ) {
			return;
		}
		?>
		<div class="site-sub-header">
			<div class="container">
				<?php knowx_get_breadcrumb(); ?>
			</div>
		</div>
		<?php
	}
}

/**
 * Function Footer Custom Text
 */
if ( ! function_exists( 'knowx_footer_custom_text' ) ) {
	/**
	 * Function Footer Custom Text
	 *
	 * @since 1.0.14
	 */
	function knowx_footer_custom_text() {

		$copyright = (string) knowx_mod( 'site_copyright_text' );
		if ( '' === trim( $copyright ) ) {
			return '';
		}

		/**
		 * Filters the theme name and link used for the [theme_author] placeholder in the copyright line.
		 *
		 * @param array $theme_author Keys `theme_name` and `theme_author_url`.
		 */
		$theme_author = apply_filters(
			'knowx_theme_author',
			array(
				'theme_name'       => esc_html__( 'KnowX WordPress Theme', 'knowx' ),
				'theme_author_url' => esc_url( 'https://wbcomdesigns.com/downloads/knowx-theme/' ),
			)
		);
		$copyright    = str_replace( '[current_year]', date_i18n( 'Y' ), $copyright );
		$copyright    = str_replace( '[site_title]', '<span class="knowx-footer-site-title"><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( get_bloginfo( 'name' ) ) . '</a></span>', $copyright );
		$copyright    = str_replace( '[theme_author]', '<a href="' . esc_url( $theme_author['theme_author_url'] ) . '">' . esc_html( $theme_author['theme_name'] ) . '</a>', $copyright );

		/**
		 * Filters the footer copyright line after the placeholders are replaced.
		 *
		 * @param string $copyright Markup of the copyright line.
		 */
		return apply_filters( 'knowx_footer_copyright_text', $copyright );
	}
}

/**
 * Whether the site has more than one category in use.
 *
 * @return bool True when posts are spread over several categories.
 */
function knowx_categorized_blog() {
	return (int) wp_count_terms(
		array(
			'taxonomy'   => 'category',
			'hide_empty' => true,
		)
	) > 1;
}

/**
 * Blog Post Meta
 */
if ( ! function_exists( 'knowx_posted_on' ) ) {

	/**
	 * Prints the author, date and category line of a post.
	 */
	function knowx_posted_on() {

		if ( is_sticky() && is_home() && ! is_paged() ) {
			echo '<span class="entry-featured">' . esc_html__( 'Sticky', 'knowx' ) . '</span>';
		}

		// With a featured image the categories sit on the image (entry_header.php); without one they are listed here.
		if ( ! ( has_post_thumbnail() && ! is_search() ) && in_array( 'category', get_object_taxonomies( get_post_type() ), true ) && knowx_categorized_blog() ) {
			echo '<span class="entry-cat-links">' . get_the_category_list( ', ' ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		if ( ! is_search() ) {

			if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
				echo '<span class="entry-comments-link">';
				comments_popup_link( esc_html__( 'Leave a comment', 'knowx' ), esc_html__( '1 Comment', 'knowx' ), esc_html__( '% Comments', 'knowx' ) );
				echo '</span>';
			}
		}

		edit_post_link( esc_html__( 'Edit', 'knowx' ), '<span class="entry-edit-link">', '</span>' );
	}

}


/**
 * Whether header.php should wrap the page content in a `.container`.
 *
 * Full width pages and the Knowledge Base Layout One page build their own
 * full-bleed sections, so the container opened in header.php and closed in
 * footer.php is skipped for them.
 *
 * @return bool True when the content is wrapped in a container.
 */
function knowx_template_uses_container() {
	return ! is_page_template( array( 'full-width.php', 'page-knowledgebase-one.php' ) );
}
