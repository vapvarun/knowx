<?php
/**
 * KnowX\KnowX\Customizer_Framework\Controls\Background
 *
 * Composite background picker (Kirki-shape):
 *   - background-color      hex/rgba
 *   - background-image      attachment URL
 *   - background-repeat     repeat | no-repeat | repeat-x | repeat-y
 *   - background-position   center center | top left | …
 *   - background-size       auto | cover | contain
 *   - background-attachment scroll | fixed | local
 *
 * Stores a structured array. Output_Builder expands the array into a
 * multi-declaration CSS block on the configured selector.
 *
 * @package knowx
 */

namespace KnowX\KnowX\Customizer_Framework\Controls;

defined( 'ABSPATH' ) || exit;

class Background extends \WP_Customize_Control {

	/**
	 * @var string
	 */
	public $type = 'knowx-background';

	/**
	 * Expose the default array to JS so the control can hydrate sub-inputs.
	 */
	public function to_json() {
		parent::to_json();
		$this->json['default'] = $this->setting && is_array( $this->setting->default )
			? $this->setting->default
			: array();
	}

	/**
	 * Render six sub-inputs + a hidden field bound to the setting via $this->link().
	 */
	public function render_content() {
		$repeat_options     = array( 'repeat', 'no-repeat', 'repeat-x', 'repeat-y' );
		$position_options   = array( 'left top', 'left center', 'left bottom', 'center top', 'center center', 'center bottom', 'right top', 'right center', 'right bottom' );
		$size_options       = array( 'auto', 'cover', 'contain' );
		$attachment_options = array( 'scroll', 'fixed', 'local' );
		?>
		<fieldset>
			<?php if ( ! empty( $this->label ) ) : ?>
				<legend class="customize-control-title"><?php echo esc_html( $this->label ); ?></legend>
			<?php endif; ?>
			<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
			<?php endif; ?>
			<div class="knowx-background-controls" data-setting="<?php echo esc_attr( $this->setting ? $this->setting->id : '' ); ?>">
				<label class="knowx-bg-row">
					<span><?php esc_html_e( 'Color', 'knowx' ); ?></span>
					<input class="knowx-bg-color" type="text" />
				</label>
				<label class="knowx-bg-row">
					<span><?php esc_html_e( 'Image URL', 'knowx' ); ?></span>
					<input class="knowx-bg-image" type="url" placeholder="https://…" />
					<button type="button" class="button knowx-bg-image-pick"><?php esc_html_e( 'Select Image', 'knowx' ); ?></button>
				</label>
				<label class="knowx-bg-row">
					<span><?php esc_html_e( 'Repeat', 'knowx' ); ?></span>
					<select class="knowx-bg-repeat">
						<?php foreach ( $repeat_options as $opt ) : ?>
							<option value="<?php echo esc_attr( $opt ); ?>"><?php echo esc_html( $opt ); ?></option>
						<?php endforeach; ?>
					</select>
				</label>
				<label class="knowx-bg-row">
					<span><?php esc_html_e( 'Position', 'knowx' ); ?></span>
					<select class="knowx-bg-position">
						<?php foreach ( $position_options as $opt ) : ?>
							<option value="<?php echo esc_attr( $opt ); ?>"><?php echo esc_html( $opt ); ?></option>
						<?php endforeach; ?>
					</select>
				</label>
				<label class="knowx-bg-row">
					<span><?php esc_html_e( 'Size', 'knowx' ); ?></span>
					<select class="knowx-bg-size">
						<?php foreach ( $size_options as $opt ) : ?>
							<option value="<?php echo esc_attr( $opt ); ?>"><?php echo esc_html( $opt ); ?></option>
						<?php endforeach; ?>
					</select>
				</label>
				<label class="knowx-bg-row">
					<span><?php esc_html_e( 'Attachment', 'knowx' ); ?></span>
					<select class="knowx-bg-attachment">
						<?php foreach ( $attachment_options as $opt ) : ?>
							<option value="<?php echo esc_attr( $opt ); ?>"><?php echo esc_html( $opt ); ?></option>
						<?php endforeach; ?>
					</select>
				</label>
			</div>
			<input type="hidden" class="knowx-bg-value" <?php $this->link(); ?> />
		</fieldset>
		<?php
	}
}
