<?php

// Content wrapper.
if ( ! function_exists( 'knowx_content_top' ) ) {
	function knowx_content_top() {
		?>
		<div class="site-wrapper">
		<?php
	}
}

add_action( 'knowx_before_content', 'knowx_content_top' );

if ( ! function_exists( 'knowx_content_bottom' ) ) {
	function knowx_content_bottom() {

		?>
		</div>
		<?php
	}
}

add_action( 'knowx_after_content', 'knowx_content_bottom' );

// Site Sub Header.
if ( ! function_exists( 'knowx_sub_header' ) ) {
	add_action( 'knowx_sub_header', 'knowx_sub_header' );

	function knowx_sub_header() {
		global $post;
		if ( is_front_page() ) {
			return;
		}
		$site_subheader = get_theme_mod( 'site_sub_header', knowx_defaults( 'site-sub-header' ) );
		if ( ! empty( $site_subheader ) ) {
			?>
		<div class="site-sub-header">
			<div class="container">
				<?php
				$breadcrumbs = get_theme_mod( 'site_breadcrumbs', knowx_defaults( 'site-breadcrumbs' ) );
				if ( ! empty( $breadcrumbs ) ) {
					knowx_the_breadcrumb();
				}
				?>
				<?php
				$site_search = get_theme_mod( 'site_search', knowx_defaults( 'site-search' ) );
				if ( ! empty( $site_search ) ) {
					get_search_form();
				}
				?>
			</div>
		</div>
			<?php
		}
	}
}

/**
 * Prints the breadcrumb trail.
 */
function knowx_the_breadcrumb() {
	knowx_get_breadcrumb();
}

// Site Loader.
if ( ! function_exists( 'knowx_site_loader' ) ) {
	function knowx_site_loader() {
		$loader = get_theme_mod( 'site_loader', knowx_defaults( 'site-loader' ) );
		if ( '1' == $loader ) {
			echo '<div class="site-loader"><div class="loader-inner"><span class="dot"></span><span class="dot dot1"></span><span class="dot dot2"></span><span class="dot dot3"></span><span class="dot dot4"></span></div></div>';
		}
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
	 * @param string $option Custom text option name.
	 * @return mixed         Markup of custom text option.
	 */
	function knowx_footer_custom_text() {

		$copyright = get_theme_mod( 'site_copyright_text' );
		if ( ! empty( $copyright ) ) {
			$copyright    = str_replace( '[current_year]', date_i18n( 'Y' ), $copyright );
			$copyright    = str_replace( '[site_title]', '<span class="knowx-footer-site-title"><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( get_bloginfo( 'name' ) ) . '</a></span>', $copyright );
			$theme_author = apply_filters(
				'knowx_theme_author',
				array(
					'theme_name'       => esc_html( 'KnowX WordPress Theme', 'knowx' ),
					'theme_author_url' => esc_url( 'https://wbcomdesigns.com/downloads/knowx-theme/' ),
				)
			);
			$copyright    = str_replace( '[theme_author]', '<a href="' . esc_url( $theme_author['theme_author_url'] ) . '">' . esc_html( $theme_author['theme_name'] ) . '</a>', $copyright );
			return apply_filters( 'knowx_footer_copyright_text', $copyright );
		} else {
			$output       = 'Copyright © [current_year] [site_title] | Powered by [theme_author]';
			$output       = str_replace( '[current_year]', date_i18n( 'Y' ), $output );
			$output       = str_replace( '[site_title]', '<span class="knowx-footer-site-title"><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( get_bloginfo( 'name' ) ) . '</a></span>', $output );
			$theme_author = apply_filters(
				'knowx_theme_author',
				array(
					'theme_name'       => esc_html( 'KnowX WordPress Theme', 'knowx' ),
					'theme_author_url' => esc_url( 'https://wbcomdesigns.com/downloads/knowx-theme/' ),
				)
			);
			$output       = str_replace( '[theme_author]', '<a href="' . esc_url( $theme_author['theme_author_url'] ) . '">' . esc_html( $theme_author['theme_name'] ) . '</a>', $output );
			return apply_filters( 'knowx_footer_copyright_text', $output );
		}
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

	function knowx_posted_on() {

		global $post;

		if ( is_sticky() && is_home() && ! is_paged() ) {
			echo '<span class="entry-featured">' . esc_html__( 'Sticky', 'knowx' ) . '</span>';
		}

		if ( in_array( 'category', get_object_taxonomies( get_post_type() ) ) && knowx_categorized_blog() ) {
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

if ( ! function_exists( 'knowx_is_truthy' ) ) {
	/**
	 * Whether a setting value means "on".
	 *
	 * Accepts booleans, integers and the strings 1, on, yes, true and enable (case
	 * insensitive). Unlike wp_validate_boolean(), the string "off" is false.
	 *
	 * @param mixed $value Raw setting value.
	 * @return bool True when the value is truthy.
	 */
	function knowx_is_truthy( $value ): bool {
		if ( is_bool( $value ) ) {
			return $value;
		}
		if ( is_int( $value ) ) {
			return 0 !== $value;
		}
		if ( is_string( $value ) ) {
			$normalized = strtolower( trim( $value ) );
			if ( in_array( $normalized, array( '1', 'on', 'yes', 'true', 'enable' ), true ) ) {
				return true;
			}
			if ( in_array( $normalized, array( '0', '', 'off', 'no', 'false', 'disable' ), true ) ) {
				return false;
			}
		}
		return (bool) $value;
	}
}
