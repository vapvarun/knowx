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
use function get_template_directory;
use function wp_add_inline_style;
use function wp_json_file_decode;

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
		add_action( 'wp_enqueue_scripts', array( $this, 'action_add_tokens' ), 20 );
		add_filter( 'block_editor_settings_all', array( $this, 'filter_editor_settings' ) );
	}

	/**
	 * Attaches the saved colours to the main stylesheet.
	 */
	public function action_add_tokens() {
		$css = $this->build_css();
		if ( '' !== $css ) {
			wp_add_inline_style( 'knowx-global', $css );
		}
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
	 * Colour scheme palette from `styles/<slug>.json`, or an empty array.
	 *
	 * @param string $slug Scheme slug saved by the Customizer.
	 * @return string[] Palette as slug => colour.
	 */
	public static function scheme_palette( string $slug ): array {
		if ( ! preg_match( '/^[a-z0-9-]+$/', $slug ) ) {
			return array();
		}
		$file = get_template_directory() . '/styles/' . $slug . '.json';
		$data = is_readable( $file ) ? wp_json_file_decode( $file, array( 'associative' => true ) ) : null;

		$palette = array();
		foreach ( (array) ( $data['settings']['color']['palette'] ?? array() ) as $entry ) {
			$color = self::sanitize_color( $entry['color'] ?? '' );
			if ( '' !== $color && isset( $entry['slug'] ) ) {
				$palette[ $entry['slug'] ] = $color;
			}
		}
		return $palette;
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

		// A chosen colour scheme comes first; colours the owner set explicitly still win.
		$scheme = self::scheme_palette( (string) get_theme_mod( 'site_color_scheme', '' ) );
		if ( $scheme ) {
			$declarations .= '--color-surface:' . $scheme['base'] . ';--color-theme-body:' . $scheme['tertiary'] . ';--color-surface-alt:' . $scheme['tertiary'] . ';'
				. '--color-heading:' . $scheme['contrast'] . ';--global-font-color:color-mix(in srgb,' . $scheme['contrast'] . ' 80%,' . $scheme['base'] . ');'
				. '--color-theme-secondary:' . $scheme['secondary'] . ';--color-muted:color-mix(in srgb,' . $scheme['contrast'] . ' 75%,' . $scheme['base'] . ');';
		}

		$primary = self::sanitize_color( get_theme_mod( 'site_primary_color', '' ) );
		if ( '' === $primary && $scheme ) {
			$primary = $scheme['primary'];
		}
		if ( '' !== $primary ) {
			$declarations .= '--color-theme-primary:' . $primary . ';--color-on-primary:' . self::on_color( $primary ) . ';';

			$link = self::link_colors( $primary, $scheme['tertiary'] ?? '#f7f7f9' );
			if ( $link ) {
				$declarations .= '--color-link:' . $link[0] . ';--color-link-hover:' . $link[1] . ';';
			}
		}

		// Core's Background Color, moved into Site Skin. Dark mode keeps its own page colour.
		$page = sanitize_hex_color_no_hash( get_theme_mod( 'background_color', '' ) );
		if ( $page && strtolower( $page ) !== strtolower( (string) get_theme_support( 'custom-background', 'default-color' ) ) ) {
			$declarations .= '--color-theme-body:#' . $page . ';';
		}

		foreach ( self::VARIABLES as $setting => $variable ) {
			$color = self::sanitize_color( get_theme_mod( $setting, '' ) );
			if ( '' !== $color ) {
				$declarations .= $variable . ':' . $color . ';';
			}
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
