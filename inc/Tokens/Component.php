<?php
/**
 * KnowX\KnowX\Tokens\Component class
 *
 * @package knowx
 */

namespace KnowX\KnowX\Tokens;

use KnowX\KnowX\Component_Interface;

use function add_action;
use function add_filter;
use function is_customize_preview;

/**
 * Prints the few colour variables the Customizer can change.
 *
 * Every other colour is derived in CSS (`color-mix()`) from these and from the
 * neutral tokens in `_custom-properties.css`, which also hold the dark palette.
 */
class Component implements Component_Interface {

	/**
	 * Customizer setting => CSS variable, for settings that map one to one.
	 *
	 * @var string[]
	 */
	const VARIABLES = array(
		'site_text_color'                 => '--global-font-color',
		'site_heading_color'              => '--color-heading',
		'site_header_bg_color'            => '--color-header-bg',
		'site_footer_content_color'       => '--color-footer-content',
		'site_copyright_background_color' => '--color-copyright-bg',
		'site_copyright_content_color'    => '--color-copyright-content',
	);

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'tokens';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'wp_head', array( $this, 'action_print_tokens' ), 20 );
		add_action( 'customize_register', array( $this, 'action_customize_register' ), 100 );
		add_filter( 'block_editor_settings_all', array( $this, 'filter_editor_settings' ) );
	}

	/**
	 * Prints the saved colours after the stylesheets.
	 *
	 * In the Customizer preview the tag is always printed, so the live preview has an element to replace.
	 */
	public function action_print_tokens() {
		$css = $this->build_css();
		if ( '' !== $css || is_customize_preview() ) {
			echo '<style id="knowx-tokens">' . $css . '</style>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Colours are validated in build_css().
		}
	}

	/**
	 * Updates the colours in the Customizer preview without reloading it.
	 *
	 * The preview asks the server for the same rule the front end prints and swaps the style tag.
	 *
	 * @param \WP_Customize_Manager $wp_customize Customizer manager.
	 */
	public function action_customize_register( $wp_customize ) {
		$ids = array_merge( array_keys( self::VARIABLES ), array( 'site_primary_color', 'site_link_color', 'site_surface_color', 'background_color', 'knowx_corners' ) );
		foreach ( $ids as $id ) {
			$setting = $wp_customize->get_setting( $id );
			if ( $setting ) {
				$setting->transport = 'postMessage';
			}
		}

		$wp_customize->selective_refresh->add_partial(
			'knowx_tokens',
			array(
				'selector'            => '#knowx-tokens',
				'settings'            => $ids,
				'container_inclusive' => true,
				'fallback_refresh'    => true,
				'render_callback'     => array( $this, 'action_print_tokens' ),
			)
		);
	}

	/**
	 * Gives the block editor the same colours as the front end.
	 *
	 * @param array $settings Editor settings.
	 * @return array Editor settings with the token rule added.
	 */
	public function filter_editor_settings( $settings ) {
		$css = $this->build_css();
		if ( '' !== $css ) {
			$settings['styles'][] = array( 'css' => $css );
		}
		return $settings;
	}

	/**
	 * Ready-made colour palettes for the Customizer. Choosing one fills the colour controls, which can then be changed one by one.
	 *
	 * Light mode only: dark mode keeps its own palette.
	 *
	 * @return array[] Palette slug => label and colours keyed by setting id.
	 */
	public static function palettes(): array {
		$palettes = array(
			'ocean'  => array( __( 'Ocean', 'knowx' ), '#0369a1', '#1e293b', '#0f172a', '#f0f7fb', '#ffffff', '#ffffff', '#334155', '#e6f1f7', '#334155' ),
			'forest' => array( __( 'Forest', 'knowx' ), '#047857', '#1f2937', '#064e3b', '#f3f8f5', '#ffffff', '#ffffff', '#374151', '#e8f1ec', '#374151' ),
			'sunset' => array( __( 'Sunset', 'knowx' ), '#c2410c', '#292524', '#431407', '#fbf5ef', '#fffdfb', '#fffdfb', '#44403c', '#f3e8dc', '#44403c' ),
			'berry'  => array( __( 'Berry', 'knowx' ), '#7c3aed', '#27272a', '#2e1065', '#f7f4fc', '#ffffff', '#ffffff', '#3f3f46', '#ece6f7', '#3f3f46' ),
			'slate'  => array( __( 'Slate', 'knowx' ), '#334155', '#1e293b', '#0f172a', '#f1f5f9', '#ffffff', '#ffffff', '#334155', '#e2e8f0', '#334155' ),
		);
		$keys     = array( 'site_primary_color', 'site_text_color', 'site_heading_color', 'background_color', 'site_surface_color', 'site_header_bg_color', 'site_footer_content_color', 'site_copyright_background_color', 'site_copyright_content_color' );

		$out = array();
		foreach ( $palettes as $slug => $row ) {
			$label        = array_shift( $row );
			$out[ $slug ] = array(
				'label'  => $label,
				'colors' => array_combine( $keys, $row ),
			);
		}

		/**
		 * Filters the colour palettes offered in the Customizer.
		 *
		 * @param array[] $out Palette slug => `label` and `colors` (setting id => hex).
		 */
		return apply_filters( 'knowx_color_palettes', $out );
	}

	/**
	 * Builds the `:root` rule from the settings the owner changed.
	 *
	 * Only saved values are printed; unsaved settings keep the stylesheet default.
	 *
	 * @return string CSS, or an empty string when nothing was changed.
	 */
	public function build_css(): string {
		$declarations = '';

		$primary = self::sanitize_color( get_theme_mod( 'site_primary_color', '' ) );
		if ( '' !== $primary ) {
			$declarations .= '--color-theme-primary:' . $primary . ';--color-on-primary:' . self::on_color( $primary ) . ';';

			$page = sanitize_hex_color_no_hash( get_theme_mod( 'background_color', '' ) );
			$link = self::link_colors( $primary, $page ? '#' . $page : '#f7f7f9' );
			if ( $link ) {
				$declarations .= '--color-link:' . $link[0] . ';--color-link-hover:' . $link[1] . ';';
			}
		}

		// Core's Background Color, moved into Site Skin. Dark mode keeps its own page colour.
		$page = sanitize_hex_color_no_hash( get_theme_mod( 'background_color', '' ) );
		if ( $page && strtolower( $page ) !== strtolower( (string) get_theme_support( 'custom-background', 'default-color' ) ) ) {
			$declarations .= '--color-theme-body:#' . $page . ';';
		}

		// Content background: cards and boxes, with the neutral shades next to it following it.
		$surface = self::sanitize_color( get_theme_mod( 'site_surface_color', '' ) );
		if ( '' !== $surface ) {
			$declarations .= '--color-surface:' . $surface . ';--color-surface-alt:color-mix(in srgb,' . $surface . ' 94%,#000);';
		}
		if ( '' !== self::sanitize_color( get_theme_mod( 'site_text_color', '' ) ) ) {
			$declarations .= '--color-muted:color-mix(in srgb,var(--global-font-color) 72%,var(--color-surface));';
		}

		// Link colour: the owner's own, with a darker hover; otherwise the one derived from the theme colour above.
		$link_color = self::sanitize_color( get_theme_mod( 'site_link_color', '' ) );
		if ( '' !== $link_color ) {
			$declarations .= '--color-link:' . $link_color . ';--color-link-hover:color-mix(in srgb,' . $link_color . ' 75%,#000);';
		}

		// Corner roundness: soft is the stylesheet default.
		$corners = array(
			'square' => array( 0, 0, 0 ),
			'round'  => array( 8, 14, 20 ),
		);
		$corner  = (string) get_theme_mod( 'knowx_corners', '' );
		if ( isset( $corners[ $corner ] ) ) {
			$declarations .= sprintf( '--radius-sm:%1$dpx;--radius-md:%2$dpx;--radius-lg:%3$dpx;', ...$corners[ $corner ] );
		}

		foreach ( self::VARIABLES as $setting => $variable ) {
			$color = self::sanitize_color( get_theme_mod( $setting, '' ) );
			if ( '' !== $color ) {
				$declarations .= $variable . ':' . $color . ';';
			}
		}

		// Footer titles and links follow the owner's footer text colour, or, with only a footer background
		// colour chosen, a readable colour for that background. Otherwise they stay dark on a dark footer.
		$footer_text = self::sanitize_color( get_theme_mod( 'site_footer_content_color', '' ) );
		$footer_bg   = (array) get_theme_mod( 'background_setting', array() );
		$footer_bg   = self::sanitize_color( $footer_bg['background-color'] ?? '' );
		if ( '' === $footer_text && '' !== $footer_bg ) {
			$footer_text   = self::on_color( $footer_bg );
			$declarations .= '--color-footer-content:' . $footer_text . ';';
		}
		if ( '' !== $footer_text ) {
			$declarations .= '--color-footer-title:' . $footer_text . ';--color-footer-link:' . $footer_text . ';--color-footer-link-hover:' . $footer_text . ';';
		}

		return '' === $declarations ? '' : ':root{' . $declarations . '}';
	}

	/**
	 * Accepts hex, rgb(a) and hsl(a) colours and returns an empty string otherwise.
	 *
	 * @param mixed $value Saved value.
	 * @return string Safe colour value or an empty string.
	 */
	public static function sanitize_color( $value ): string {
		$value = trim( (string) $value );

		if ( preg_match( '/^#(?:[0-9a-f]{3,4}|[0-9a-f]{6}|[0-9a-f]{8})$/i', $value ) ) {
			return $value;
		}
		if ( preg_match( '/^(?:rgb|hsl)a?\(\s*[0-9.%\s,\/-]+\)$/i', $value ) ) {
			return $value;
		}

		return '';
	}

	/**
	 * Darkens the brand colour until link text on the light page background reaches 4.5:1.
	 *
	 * @param string $color Hex or rgb() colour.
	 * @param string $page  Page background the links sit on; the default is the light palette's body colour.
	 * @return string[] Link and link hover colours as rgb(), or an empty array when the colour cannot be parsed.
	 */
	public static function link_colors( string $color, string $page = '#f7f7f9' ): array {
		$rgb = self::to_rgb( $color );
		$bg  = self::to_rgb( $page );
		if ( null === $rgb || null === $bg ) {
			return array();
		}

		$background = self::luminance( $bg );

		for ( $black = 0; $black <= 90; $black += 5 ) {
			$mixed = array_map(
				static function ( $channel ) use ( $black ) {
					return (int) round( $channel * ( 100 - $black ) / 100 );
				},
				$rgb
			);

			if ( ( $background + 0.05 ) / ( self::luminance( $mixed ) + 0.05 ) >= 4.5 ) {
				$hover = array_map(
					static function ( $channel ) {
						return (int) round( $channel * 0.75 );
					},
					$mixed
				);

				return array(
					'rgb(' . implode( ',', $mixed ) . ')',
					'rgb(' . implode( ',', $hover ) . ')',
				);
			}
		}

		return array();
	}

	/**
	 * Parses a hex or rgb() colour.
	 *
	 * @param string $color Colour value.
	 * @return int[]|null Red, green and blue, or null when not parseable.
	 */
	protected static function to_rgb( string $color ): ?array {
		if ( preg_match( '/^#([0-9a-f]{3})$/i', $color, $m ) ) {
			return array_map(
				static function ( $c ) {
					return hexdec( $c . $c );
				},
				str_split( $m[1] )
			);
		}
		if ( preg_match( '/^#([0-9a-f]{6})/i', $color, $m ) ) {
			return array_map( 'hexdec', str_split( $m[1], 2 ) );
		}
		if ( preg_match( '/^rgba?\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)/i', $color, $m ) ) {
			return array( (int) $m[1], (int) $m[2], (int) $m[3] );
		}

		return null;
	}

	/**
	 * Relative luminance of an RGB colour (WCAG).
	 *
	 * @param int[] $rgb Red, green and blue.
	 * @return float Luminance between 0 and 1.
	 */
	protected static function luminance( array $rgb ): float {
		$linear = static function ( $channel ) {
			$c = $channel / 255;
			return $c <= 0.03928 ? $c / 12.92 : pow( ( $c + 0.055 ) / 1.055, 2.4 );
		};

		return 0.2126 * $linear( $rgb[0] ) + 0.7152 * $linear( $rgb[1] ) + 0.0722 * $linear( $rgb[2] );
	}

	/**
	 * Picks white or near-black text for a background so labels stay readable (WCAG contrast).
	 *
	 * @param string $color Hex or rgb() colour; anything else falls back to white.
	 * @return string `#fff` or `#111`.
	 */
	public static function on_color( string $color ): string {
		$rgb = self::to_rgb( $color );
		if ( null === $rgb ) {
			return '#fff';
		}

		// White text needs a luminance of at most about 0.18 to reach 4.5:1.
		return self::luminance( $rgb ) > 0.18 ? '#111' : '#fff';
	}
}
