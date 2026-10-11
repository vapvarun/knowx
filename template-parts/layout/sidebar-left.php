<?php
/**
 * The left sidebar, printed when the Sidebar setting is left or both.
 *
 * @package knowx
 */

namespace KnowX\KnowX;

$knowx_layout = knowx_mod( 'sidebar_option' );

if ( 'left' === $knowx_layout || 'both' === $knowx_layout ) :
	?>
	<aside id="secondary-left" class="left-sidebar widget-area" aria-label="<?php esc_attr_e( 'Left sidebar', 'knowx' ); ?>">
		<div class="sticky-sidebar">
			<?php knowx()->display_left_sidebar(); ?>
		</div>
	</aside>
<?php endif; ?>
