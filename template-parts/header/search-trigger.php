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
<a class="knowx-search-trigger" href="<?php echo esc_url( home_url( '/?s=' ) ); ?>" aria-label="<?php esc_attr_e( 'Search', 'knowx' ); ?>" aria-keyshortcuts="Control+K Meta+K" title="<?php esc_attr_e( 'Search (Ctrl+K)', 'knowx' ); ?>">
	<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true" focusable="false"><circle cx="11" cy="11" r="7"/><path d="M20 20l-4-4"/></svg>
</a>
