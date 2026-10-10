<?php
/**
 * Instant search overlay, opened by docs.js. A native dialog element: focus trap, Escape and backdrop come from the browser.
 *
 * @package knowx
 */

namespace KnowX\KnowX;

$knowx_product    = knowx_current_product();
$knowx_categories = $knowx_product ? array_merge( array( $knowx_product['id'] ), array_map( 'intval', get_term_children( $knowx_product['id'], 'category' ) ) ) : array();
?>
<dialog class="knowx-search-dialog" aria-label="<?php esc_attr_e( 'Search the documentation', 'knowx' ); ?>"
	data-endpoint="<?php echo esc_url( rest_url( 'wp/v2/posts' ) ); ?>"
	data-categories="<?php echo esc_attr( implode( ',', $knowx_categories ) ); ?>">
	<form method="get" role="search" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="knowx-search-form">
		<label class="screen-reader-text" for="knowx-search-input"><?php esc_html_e( 'Search for:', 'knowx' ); ?></label>
		<input id="knowx-search-input" type="search" name="s" autocomplete="off" placeholder="<?php esc_attr_e( 'Search articles', 'knowx' ); ?>">
		<button type="button" class="knowx-search-close"><?php esc_html_e( 'Close', 'knowx' ); ?></button>
	</form>
	<?php if ( $knowx_product ) : ?>
		<label class="knowx-search-scope">
			<input type="checkbox" checked>
			<?php
			/* translators: %s: product name. */
			echo esc_html( sprintf( __( 'Only in %s', 'knowx' ), $knowx_product['name'] ) );
			?>
		</label>
	<?php endif; ?>
	<p class="knowx-search-status" role="status"></p>
	<ul class="knowx-search-results"></ul>
</dialog>
