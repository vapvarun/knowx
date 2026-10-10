<?php
/**
 * KnowX\KnowX\Customizer_Framework\Controls\Typography
 *
 * Six-input typography picker: family, weight, size (px), line-height,
 * letter-spacing (em), text-transform. Stores a structured value array
 * that Output_Builder turns into a multi-property declaration block.
 *
 * Live preview wiring + value sync handled by customizer-controls.js (Task 18).
 *
 * @package knowx
 */

namespace KnowX\KnowX\Customizer_Framework\Controls;

defined( 'ABSPATH' ) || exit;

/**
 * Typography
 *
 * Reads available font families from theme.json fontFamilies (registered in
 * 5.0.3) so users see the same palette of fonts the Site Editor exposes.
 * Falls back to a sane minimum if theme.json hasn't registered any.
 */
class Typography extends \WP_Customize_Control {

	/**
	 * @var string
	 */
	public $type = 'knowx-typography';

	/**
	 * Expose default + family/weight choices to the JS template.
	 */
	public function to_json() {
		parent::to_json();
		$this->json['default']      = $this->setting ? $this->setting->default : array();
		$this->json['fontFamilies'] = self::available_font_families();
		$this->json['weights']      = array( '300', '400', '500', '600', '700' );
	}

	/**
	 * Font families for the picker, grouped for <optgroup> rendering.
	 *
	 * Returns:
	 *   array(
	 *     'default' => '',                       // value of the "Default (theme)" entry
	 *     'groups'  => array(
	 *       array( 'label' => 'Theme fonts',  'fonts' => array( slug => label, ... ) ),
	 *       array( 'label' => 'Google Fonts', 'fonts' => array( name => name, ... ) ),
	 *     ),
	 *   )
	 *
	 * Theme fonts come from theme.json fontFamilies (self-hosted); Google fonts
	 * come from the bundled catalog. The JS prepends a "Default (theme)" option.
	 *
	 * @return array
	 */
	protected static function available_font_families(): array {
		$theme_json = function_exists( 'wp_get_global_settings' ) ? wp_get_global_settings() : array();
		$families   = $theme_json['typography']['fontFamilies']['theme'] ?? array();
		$theme      = array();
		foreach ( $families as $f ) {
			if ( isset( $f['slug'], $f['name'] ) ) {
				$theme[ $f['slug'] ] = $f['name'];
			}
		}
		if ( empty( $theme ) ) {
			$theme = array(
				'system' => 'System UI',
				'inter'  => 'Inter',
			);
		}

		$google = array();
		if ( class_exists( '\\KnowX\\KnowX\\Fonts\\Google_Fonts_Catalog' ) ) {
			$google = \KnowX\KnowX\Fonts\Google_Fonts_Catalog::get_family_choices();
		}

		$groups = array(
			array(
				'label' => esc_html__( 'Theme fonts', 'knowx' ),
				'fonts' => $theme,
			),
		);
		if ( ! empty( $google ) ) {
			$groups[] = array(
				'label' => esc_html__( 'Google Fonts', 'knowx' ),
				'fonts' => $google,
			);
		}

		// Fonts uploaded via WordPress's native Font Library
		// (Appearance → Fonts → Upload). Read-only — KnowX never writes to
		// the wp_font_family/wp_font_face post types.
		$custom = array();
		if ( class_exists( '\\KnowX\\KnowX\\Fonts\\Custom_Fonts_Repository' ) ) {
			$custom = \KnowX\KnowX\Fonts\Custom_Fonts_Repository::get_families();
		}
		if ( ! empty( $custom ) ) {
			$groups[] = array(
				'label' => esc_html__( 'Custom Fonts', 'knowx' ),
				'fonts' => $custom,
			);
		}

		return array(
			'default'            => '',
			'default_label'      => esc_html__( 'Default (theme)', 'knowx' ),
			'saved_label'        => esc_html__( 'Saved', 'knowx' ),
			'search_placeholder' => esc_html__( 'Search fonts…', 'knowx' ),
			'groups'             => $groups,
		);
	}

	/**
	 * Render the control content.
	 *
	 * Layout: 5 logical rows, 9 inputs total. All paired-rows are 50/50 grid
	 * so column inputs align perfectly. Labels are kept to one word/line to
	 * prevent vertical drift between paired columns. Unit hints (px, em) live
	 * on the input itself via inline `unit` decoration and step attribute.
	 *
	 *   1. Font family            (full)
	 *   2. Weight | Style         (paired — italic/normal added)
	 *   3. Size   | Line height   (paired)
	 *   4. Letter | Transform     (paired)
	 *   5. Align  | Decoration    (paired — both newly exposed)
	 */
	public function render_content() {
		?>
		<fieldset>
			<?php if ( ! empty( $this->label ) ) : ?>
				<legend class="customize-control-title"><?php echo esc_html( $this->label ); ?></legend>
			<?php endif; ?>
			<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
			<?php endif; ?>
			<div class="knowx-typography-controls" data-setting="<?php echo esc_attr( $this->setting ? $this->setting->id : '' ); ?>">

				<label class="knowx-typo-row knowx-typo-row--full">
					<span><?php esc_html_e( 'Family', 'knowx' ); ?></span>
					<select class="knowx-typo-family"></select>
				</label>

				<div class="knowx-typo-row-pair">
					<label class="knowx-typo-row">
						<span><?php esc_html_e( 'Weight', 'knowx' ); ?></span>
						<select class="knowx-typo-weight"></select>
					</label>
					<label class="knowx-typo-row">
						<span><?php esc_html_e( 'Style', 'knowx' ); ?></span>
						<select class="knowx-typo-style">
							<option value="normal"><?php esc_html_e( 'Normal', 'knowx' ); ?></option>
							<option value="italic"><?php esc_html_e( 'Italic', 'knowx' ); ?></option>
						</select>
					</label>
				</div>

				<div class="knowx-typo-row-pair">
					<label class="knowx-typo-row">
						<span><?php esc_html_e( 'Size', 'knowx' ); ?> <em class="knowx-typo-unit">px</em></span>
						<input class="knowx-typo-size" type="number" min="8" max="200" step="1" />
					</label>
					<label class="knowx-typo-row">
						<span><?php esc_html_e( 'Line height', 'knowx' ); ?></span>
						<input class="knowx-typo-line-height" type="number" min="0.8" max="3" step="0.05" />
					</label>
				</div>

				<div class="knowx-typo-row-pair">
					<label class="knowx-typo-row">
						<span><?php esc_html_e( 'Letter', 'knowx' ); ?> <em class="knowx-typo-unit">em</em></span>
						<input class="knowx-typo-letter-spacing" type="number" min="-0.1" max="0.5" step="0.005" />
					</label>
					<label class="knowx-typo-row">
						<span><?php esc_html_e( 'Transform', 'knowx' ); ?></span>
						<select class="knowx-typo-transform">
							<option value="none"><?php esc_html_e( 'None', 'knowx' ); ?></option>
							<option value="uppercase"><?php esc_html_e( 'UPPER', 'knowx' ); ?></option>
							<option value="lowercase"><?php esc_html_e( 'lower', 'knowx' ); ?></option>
							<option value="capitalize"><?php esc_html_e( 'Capitalize', 'knowx' ); ?></option>
						</select>
					</label>
				</div>

				<div class="knowx-typo-row-pair">
					<label class="knowx-typo-row">
						<span><?php esc_html_e( 'Align', 'knowx' ); ?></span>
						<select class="knowx-typo-align">
							<option value=""><?php esc_html_e( 'Inherit', 'knowx' ); ?></option>
							<option value="left"><?php esc_html_e( 'Left', 'knowx' ); ?></option>
							<option value="center"><?php esc_html_e( 'Center', 'knowx' ); ?></option>
							<option value="right"><?php esc_html_e( 'Right', 'knowx' ); ?></option>
							<option value="justify"><?php esc_html_e( 'Justify', 'knowx' ); ?></option>
						</select>
					</label>
					<label class="knowx-typo-row">
						<span><?php esc_html_e( 'Decoration', 'knowx' ); ?></span>
						<select class="knowx-typo-decoration">
							<option value=""><?php esc_html_e( 'None', 'knowx' ); ?></option>
							<option value="underline"><?php esc_html_e( 'Underline', 'knowx' ); ?></option>
							<option value="line-through"><?php esc_html_e( 'Strikethrough', 'knowx' ); ?></option>
							<option value="overline"><?php esc_html_e( 'Overline', 'knowx' ); ?></option>
						</select>
					</label>
				</div>
			</div>
			<input type="hidden" class="knowx-typo-value" <?php $this->link(); ?> />
		</fieldset>
		<?php
	}
}
