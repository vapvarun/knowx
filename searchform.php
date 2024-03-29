<?php
/**
 * The template for displaying search forms.
 *
 * @package knowx
 */
?>

<?php $search_text = empty( $_GET['s'] ) ? esc_html__( 'Enter Keyword', 'knowx' ) : get_search_query(); ?>
<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<input id="s" name="s" type="text" placeholder="<?php echo esc_attr( $search_text ); ?>" class="text_input" />
	<a href="<?php esc_url( '#' ); ?>" class="search-icon"> <span class="fa fa-close"> </span> </a>
	<input name="submit" type="submit"  value="<?php esc_attr_e( 'Go', 'knowx' ); ?>" />
</form>