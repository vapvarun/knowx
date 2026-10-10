<?php
/**
 * Template part for displaying the header navigation menu
 *
 * @package knowx
 */

namespace KnowX\KnowX;

?>

<nav id="site-navigation" class="main-navigation nav--toggle-sub nav--toggle-small" aria-label="<?php esc_attr_e( 'Main menu', 'knowx' ); ?>">

	<button id="menu-toggle" class="menu-toggle" aria-label="<?php esc_attr_e( 'Open menu', 'knowx' ); ?>" aria-controls="primary-menu-mobile" aria-expanded="false">
	<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true" focusable="false"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
	</button>

	<div class="primary-menu-container mobile-menu-container">
		<div class="mobile-menu-heading">
			<h3 class="menu-title"><?php esc_html_e( 'Menu', 'knowx' ); ?></h3>
			<button type="button" class="menu-close"><?php esc_html_e( 'Close', 'knowx' ); ?></button>
		</div>
		<?php knowx()->display_primary_nav_menu( array( 'menu_id' => 'primary-menu-mobile' ) ); ?>
	</div>
	
	<div class="primary-menu-container desktop-menu-container">
		<?php knowx()->display_primary_nav_menu( array( 'menu_id' => 'primary-menu' ) ); ?>
	</div>
</nav><!-- #site-navigation -->
