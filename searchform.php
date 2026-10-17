<?php
/**
 * The template for displaying search forms.
 *
 * @package knowx
 */

$knowx_search_id = wp_unique_id( 'search-field-' );

$knowx_search_label = knowx_search_form_label();
?>

<form role="search" aria-label="<?php echo esc_attr( $knowx_search_label ); ?>" method="get" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="<?php echo esc_attr( $knowx_search_id ); ?>" class="screen-reader-text"><?php esc_html_e( 'Search for:', 'knowx' ); ?></label>
	<input id="<?php echo esc_attr( $knowx_search_id ); ?>" name="s" type="search" placeholder="<?php esc_attr_e( 'Search the documentation', 'knowx' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" class="text_input" />
	<input type="submit" value="<?php esc_attr_e( 'Go', 'knowx' ); ?>" />
</form>
