<?php
/**
 * KnowX\KnowX\Customizer_Framework\Controls\Repeater
 *
 * Array of repeating sub-fields. Stores a JSON-encoded array of objects.
 * choices.fields defines the row schema:
 *   array(
 *     'icon'  => array( 'type' => 'text',  'label' => 'Icon class' ),
 *     'label' => array( 'type' => 'text',  'label' => 'Label' ),
 *     'url'   => array( 'type' => 'url',   'label' => 'Link URL' ),
 *   )
 *
 * UI behavior (drag-reorder, add/remove rows, per-row inputs) is wired
 * by customizer-controls.js (Task 18) reading the params.fields schema.
 *
 * @package knowx
 */

namespace KnowX\KnowX\Customizer_Framework\Controls;

defined( 'ABSPATH' ) || exit;

/**
 * Repeater
 */
class Repeater extends \WP_Customize_Control {

	/**
	 * @var string
	 */
	public $type = 'knowx-repeater';

	/**
	 * Row schema. Either passed at top level (array shape) or as
	 * choices['fields']. Map of field-key => { type, label, default? }.
	 *
	 * @var array
	 */
	public $fields = array();

	/**
	 * Label shown on each row's "remove" button (and accessible name for the row).
	 *
	 * @var string|array
	 */
	public $row_label = 'Item';

	/**
	 * Expose the row-fields schema and row label to the JS template.
	 */
	public function to_json() {
		parent::to_json();
		$this->json['fields']    = ! empty( $this->fields )
			? $this->fields
			: ( $this->choices['fields'] ?? array() );
		$this->json['row_label'] = $this->row_label;
	}

	/**
	 * Render the control content.
	 */
	public function render_content() {
		?>
		<fieldset class="knowx-repeater" data-setting="<?php echo esc_attr( $this->setting ? $this->setting->id : '' ); ?>">
			<?php if ( ! empty( $this->label ) ) : ?>
				<legend class="customize-control-title"><?php echo esc_html( $this->label ); ?></legend>
			<?php endif; ?>
			<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
			<?php endif; ?>
			<div class="knowx-repeater-rows" role="list"></div>
			<button type="button" class="button knowx-repeater-add">
				<?php
				/* translators: %s: row label, e.g. "Item" */
				printf( esc_html__( 'Add %s', 'knowx' ), esc_html( $this->row_label ) );
				?>
			</button>
			<input type="hidden" class="knowx-repeater-value" <?php $this->link(); ?> />
		</fieldset>
		<?php
	}
}
