<?php
/**
 * Template part for the header search button.
 *
 * Without JavaScript it is a link to the search page; docs.js turns it into the instant search overlay.
 *
 * @package knowx
 */

namespace KnowX\KnowX;

?>
<a class="knowx-search-trigger" href="<?php echo esc_url( home_url( '/?s=' ) ); ?>" aria-keyshortcuts="Control+K Meta+K">
	<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true" focusable="false"><circle cx="11" cy="11" r="7"/><path d="M20 20l-4-4"/></svg>
	<span class="knowx-search-label"><?php esc_html_e( 'Search docs', 'knowx' ); ?></span>
	<kbd aria-hidden="true">&#8984;K</kbd>
</a>
