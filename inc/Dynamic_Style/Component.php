<?php
/**
 * KnowX\KnowX\Dynamic_Style\Component class
 *
 * @package knowx
 */

namespace KnowX\KnowX\Dynamic_Style;

use KnowX\KnowX\Component_Interface;
use function KnowX\KnowX\knowx;
use function add_action;

/**
 * Class for improving custom_js among various core features.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'dynamic_style';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'wp_enqueue_scripts', array( $this, 'knowx_color_options' ), 20 );
	}

	/**
	 * Registers or enqueues stylesheets.
	 */
	public function knowx_color_options() {
		$color_var = '';
		if ( class_exists( 'Kirki' ) ) {
			$site_custom_colors = get_theme_mod( 'site_custom_colors', true );

			if ( $site_custom_colors ) {
				$site_loader_bg                      = get_theme_mod( 'site_loader_bg' );
				$site_title_hover_color              = get_theme_mod( 'site_title_hover_color' );
				$site_header_bg_color                = get_theme_mod( 'site_header_bg_color' );
				$menu_hover_color                    = get_theme_mod( 'menu_hover_color' );
				$menu_active_color                   = get_theme_mod( 'menu_active_color' );
				$body_background_color               = get_theme_mod( 'body_background_color' );
				$site_primary_color                  = get_theme_mod( 'site_primary_color' );
				$site_links_color                    = get_theme_mod( 'site_links_color' );
				$site_links_focus_hover_color        = get_theme_mod( 'site_links_focus_hover_color' );
				$site_buttons_background_color       = get_theme_mod( 'site_buttons_background_color' );
				$site_buttons_background_hover_color = get_theme_mod( 'site_buttons_background_hover_color' );
				$site_buttons_text_color             = get_theme_mod( 'site_buttons_text_color' );
				$site_buttons_text_hover_color       = get_theme_mod( 'site_buttons_text_hover_color' );
				$site_buttons_border_color           = get_theme_mod( 'site_buttons_border_color' );
				$site_buttons_border_hover_color     = get_theme_mod( 'site_buttons_border_hover_color' );
				$site_footer_title_color             = get_theme_mod( 'site_footer_title_color' );
				$site_footer_content_color           = get_theme_mod( 'site_footer_content_color' );
				$site_footer_links_color             = get_theme_mod( 'site_footer_links_color' );
				$site_footer_links_hover_color       = get_theme_mod( 'site_footer_links_hover_color' );
				$site_copyright_background_color     = get_theme_mod( 'site_copyright_background_color' );
				$site_copyright_content_color        = get_theme_mod( 'site_copyright_content_color' );
				$site_copyright_border_color         = get_theme_mod( 'site_copyright_border_color' );
				$site_copyright_links_color          = get_theme_mod( 'site_copyright_links_color' );
				$site_copyright_links_hover_color    = get_theme_mod( 'site_copyright_links_hover_color' );

				$light_attrs = 'body { ';
				if ( isset( $site_loader_bg ) && ! empty( $site_loader_bg ) ) {
					$color      = get_theme_mod( 'site_loader_bg' );
					$color_var .= '--color-theme-loader: ' . $color . ';';
				}

				if ( isset( $site_title_hover_color ) && ! empty( $site_title_hover_color ) ) {
					$color      = get_theme_mod( 'site_title_hover_color' );
					$color_var .= '--color-site-title-hover: ' . $color . ';';
				}

				if ( isset( $site_header_bg_color ) && ! empty( $site_header_bg_color ) ) {
					$color      = get_theme_mod( 'site_header_bg_color' );
					$color_var .= '--color-header-bg: ' . $color . ';';
				}

				if ( isset( $menu_hover_color ) && ! empty( $menu_hover_color ) ) {
					$color      = get_theme_mod( 'menu_hover_color' );
					$color_var .= '--color-menu-hover: ' . $color . ';';
				}

				if ( isset( $menu_active_color ) && ! empty( $menu_active_color ) ) {
					$color      = get_theme_mod( 'menu_active_color' );
					$color_var .= '--color-menu-active: ' . $color . ';';
				}

				if ( isset( $body_background_color ) && ! empty( $body_background_color ) ) {
					$color      = get_theme_mod( 'body_background_color' );
					$color_var .= '--color-theme-body: ' . $color . ';';
				}

				if ( isset( $site_primary_color ) && ! empty( $site_primary_color ) ) {
					$color      = get_theme_mod( 'site_primary_color' );
					$color_var .= '--color-theme-primary: ' . $color . ';';
				}

				if ( isset( $site_links_color ) && ! empty( $site_links_color ) ) {
					$color      = get_theme_mod( 'site_links_color' );
					$color_var .= '--color-link: ' . $color . ';';
				}

				if ( isset( $site_links_focus_hover_color ) && ! empty( $site_links_focus_hover_color ) ) {
					$color      = get_theme_mod( 'site_links_focus_hover_color' );
					$color_var .= '--color-link-hover: ' . $color . ';';
				}

				if ( isset( $site_buttons_background_color ) && ! empty( $site_buttons_background_color ) ) {
					$color      = get_theme_mod( 'site_buttons_background_color' );
					$color_var .= '--button-background-color: ' . $color . ';';
				}

				if ( isset( $site_buttons_background_hover_color ) && ! empty( $site_buttons_background_hover_color ) ) {
					$color      = get_theme_mod( 'site_buttons_background_hover_color' );
					$color_var .= '--button-background-hover-color: ' . $color . ';';
				}

				if ( isset( $site_buttons_text_color ) && ! empty( $site_buttons_text_color ) ) {
					$color      = get_theme_mod( 'site_buttons_text_color' );
					$color_var .= '--button-text-color: ' . $color . ';';
				}

				if ( isset( $site_buttons_text_hover_color ) && ! empty( $site_buttons_text_hover_color ) ) {
					$color      = get_theme_mod( 'site_buttons_text_hover_color' );
					$color_var .= '--button-text-hover-color: ' . $color . ';';
				}

				if ( isset( $site_buttons_border_color ) && ! empty( $site_buttons_border_color ) ) {
					$color      = get_theme_mod( 'site_buttons_border_color' );
					$color_var .= '--button-border-color: ' . $color . ';';
				}

				if ( isset( $site_buttons_border_hover_color ) && ! empty( $site_buttons_border_hover_color ) ) {
					$color      = get_theme_mod( 'site_buttons_border_hover_color' );
					$color_var .= '--button-border-hover-color: ' . $color . ';';
				}

				if ( isset( $site_footer_title_color ) && ! empty( $site_footer_title_color ) ) {
					$color      = get_theme_mod( 'site_footer_title_color' );
					$color_var .= '--color-footer-title: ' . $color . ';';
				}

				if ( isset( $site_footer_content_color ) && ! empty( $site_footer_content_color ) ) {
					$color      = get_theme_mod( 'site_footer_content_color' );
					$color_var .= '--color-footer-content: ' . $color . ';';
				}

				if ( isset( $site_footer_links_color ) && ! empty( $site_footer_links_color ) ) {
					$color      = get_theme_mod( 'site_footer_links_color' );
					$color_var .= '--color-footer-link: ' . $color . ';';
				}

				if ( isset( $site_footer_links_hover_color ) && ! empty( $site_footer_links_hover_color ) ) {
					$color      = get_theme_mod( 'site_footer_links_hover_color' );
					$color_var .= '--color-footer-link-hover: ' . $color . ';';
				}

				if ( isset( $site_copyright_background_color ) && ! empty( $site_copyright_background_color ) ) {
					$color      = get_theme_mod( 'site_copyright_background_color' );
					$color_var .= '--color-copyright-bg: ' . $color . ';';
				}

				if ( isset( $site_copyright_content_color ) && ! empty( $site_copyright_content_color ) ) {
					$color      = get_theme_mod( 'site_copyright_content_color' );
					$color_var .= '--color-copyright-content: ' . $color . ';';
				}

				if ( isset( $site_copyright_border_color ) && ! empty( $site_copyright_border_color ) ) {
					$color      = get_theme_mod( 'site_copyright_border_color' );
					$color_var .= '--copyright-border-color: ' . $color . ';';
				}

				if ( isset( $site_copyright_links_color ) && ! empty( $site_copyright_links_color ) ) {
					$color      = get_theme_mod( 'site_copyright_links_color' );
					$color_var .= '--color-copyright-link: ' . $color . ';';
				}

				if ( isset( $site_copyright_links_hover_color ) && ! empty( $site_copyright_links_hover_color ) ) {
					$color      = get_theme_mod( 'site_copyright_links_hover_color' );
					$color_var .= '--color-copyright-link-hover: ' . $color . ';';
				}

				if ( ! empty( $color_var ) ) {
					$light_attrs .= $color_var;
				}
				$light_attrs .= '}';

				if ( ! empty( $light_attrs ) ) {
					wp_add_inline_style( 'knowx-global', $light_attrs );
				}
			}
		}
	}
}
