<?php
/**
 * The block at the end of an article that sends a reader who is still stuck to support.
 *
 * It uses the support button of the docs home (Customizer, Docs, Docs Home) and is not printed when no link is set.
 *
 * @package knowx
 */

namespace KnowX\KnowX;

$knowx_link = (string) knowx_mod( 'support_section_button_link_url' );
$knowx_text = (string) knowx_mod( 'support_section_button_text' );

if ( '' === $knowx_link || '' === $knowx_text || ! knowx_is_truthy( knowx_mod( 'knowx_show_support' ) ) ) {
	return;
}
?>
<aside class="knowx-help" aria-labelledby="knowx-help-title">
	<h2 id="knowx-help-title"><?php esc_html_e( 'Still need help?', 'knowx' ); ?></h2>
	<p><?php esc_html_e( 'If this page did not answer your question, tell us what was missing.', 'knowx' ); ?></p>
	<a class="knowx-help-link" href="<?php echo esc_url( $knowx_link ); ?>"><?php echo esc_html( $knowx_text ); ?></a>
</aside>
