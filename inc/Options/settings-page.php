<?php
/**
 * Content to display in Theme Settings admin page.
 * React loads in the app container div knowx-settings-page.
 *
 * @package knowx
 */

wp_enqueue_style(
	'wp-components'
);
?>
<div class="wrap">
	<h1><?php esc_html_e( 'Theme Settings', 'knowx' ); ?></h1>
	<div id="knowx-settings-page"></div>
</div><?php
