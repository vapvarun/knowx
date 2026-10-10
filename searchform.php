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
	<input id="<?php echo esc_attr( $knowx_search_id ); ?>" name="s" type="search" placeholder="<?php esc_attr_e( 'Enter Keyword', 'knowx' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" class="text_input" />
	<button type="button" class="search-icon" aria-label="<?php esc_attr_e( 'Clear search', 'knowx' ); ?>"><svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true" focusable="false"><path d="M6 6l12 12M18 6L6 18"/></svg></button>
	<input type="submit" value="<?php esc_attr_e( 'Go', 'knowx' ); ?>" />
</form>
