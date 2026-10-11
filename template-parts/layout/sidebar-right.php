<?php
/**
 * The right sidebar, printed when the Sidebar setting is right or both.
 *
 * @package knowx
 */

namespace KnowX\KnowX;

$knowx_layout = knowx_mod( 'sidebar_option' );

if ( 'right' === $knowx_layout || 'both' === $knowx_layout ) :
	?>
	<aside id="secondary" class="primary-sidebar widget-area" aria-label="<?php esc_attr_e( 'Sidebar', 'knowx' ); ?>">
		<div class="sticky-sidebar">
			<?php knowx()->display_right_sidebar(); ?>
		</div>
	</aside>
<?php endif; ?>
