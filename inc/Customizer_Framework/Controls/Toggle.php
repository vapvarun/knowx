<?php
/**
 * KnowX\KnowX\Customizer_Framework\Controls\Toggle
 *
 * Visual on/off slider, stores 0 or 1. Field type string is 'switch' to
 * name the switch control; class is named 'Toggle' because
 * 'Switch' is a reserved PHP keyword.
 *
 * @package knowx
 */

namespace KnowX\KnowX\Customizer_Framework\Controls;

defined( 'ABSPATH' ) || exit;

/**
 * Toggle (registered as type 'switch')
 */
class Toggle extends \WP_Customize_Control {

	/**
	 * @var string
	 */
	public $type = 'knowx-switch';

	/**
	 * Render the control content.
	 */
	public function render_content() {
		?>
		<label class="knowx-switch-label">
			<?php if ( ! empty( $this->label ) ) : ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php endif; ?>
			<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
			<?php endif; ?>
			<span class="knowx-switch">
				<input
					type="checkbox"
					class="knowx-switch-input"
					value="1"
					<?php $this->link(); ?>
					<?php checked( knowx_is_truthy( $this->value() ), true ); ?>
				/>
				<span class="knowx-switch-slider" aria-hidden="true"></span>
			</span>
		</label>
		<?php
	}
}
