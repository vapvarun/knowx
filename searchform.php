<?php
/**
 * The template for displaying search forms.
 *
 * @package knowx
 */

$knowx_search_id = wp_unique_id( 'search-field-' );

// Every search landmark needs its own name; the first is the site search.
static $knowx_search_count = 0;
++$knowx_search_count;
$knowx_search_label = 1 === $knowx_search_count
	? __( 'Site search', 'knowx' )
	/* translators: %d: position of the search form on the page. */
	: sprintf( __( 'Search %d', 'knowx' ), $knowx_search_count );
?>

<form role="search" aria-label="<?php echo esc_attr( $knowx_search_label ); ?>" method="get" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="<?php echo esc_attr( $knowx_search_id ); ?>" class="screen-reader-text"><?php esc_html_e( 'Search for:', 'knowx' ); ?></label>
	<input id="<?php echo esc_attr( $knowx_search_id ); ?>" name="s" type="search" placeholder="<?php esc_attr_e( 'Enter Keyword', 'knowx' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" class="text_input" />
	<button type="button" class="search-icon" aria-label="<?php esc_attr_e( 'Clear search', 'knowx' ); ?>"><span class="fa fa-close" aria-hidden="true"></span></button>
	<input type="submit" value="<?php esc_attr_e( 'Go', 'knowx' ); ?>" />
</form>
