<?php
/**
 * Template part for displaying the header navigation menu
 *
 * @package knowx
 */

namespace KnowX\KnowX;

if ( ! knowx()->is_primary_nav_menu_active() ) {
	//return;
}

// Pre-fetch common variables.
$is_amp    = knowx()->is_amp();
$amp_state = '';
$amp_attrs = '';

// Set up AMP attributes if needed.
if ( $is_amp ) {
	$amp_state = '<amp-state id="siteNavigationMenu">
        <script type="application/json">
            {
                "expanded": false
            }
        </script>
    </amp-state>';

	$amp_attrs = 'on="tap:AMP.setState( { siteNavigationMenu: { expanded: ! siteNavigationMenu.expanded } } )"
        [aria-expanded]="siteNavigationMenu.expanded ? \'true\' : \'false\'"';
}

// Navigation class - prepare once, use multiple times.
$nav_class = 'main-navigation nav--toggle-sub nav--toggle-small';

?>

<nav id="site-navigation" class="main-navigation nav--toggle-sub nav--toggle-small" aria-label="<?php esc_attr_e( 'Main menu', 'knowx' ); ?>"
	<?php if ( $is_amp ) : ?>
	[class]=" siteNavigationMenu.expanded ? '<?php echo esc_attr( $nav_class ); ?> nav--toggled-on' : '<?php echo esc_attr( $nav_class ); ?>' "
	<?php endif; ?>
>
	<?php echo $amp_state; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

	<button id="menu-toggle" class="menu-toggle" aria-label="<?php esc_attr_e( 'Open menu', 'knowx' ); ?>" aria-controls="primary-menu" aria-expanded="false"
		<?php echo $is_amp ? $amp_attrs : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	>
	<i class="fa fa-bars" aria-hidden="true"></i>
	</button>

	<div class="primary-menu-container mobile-menu-container">
		<div class="mobile-menu-heading">
			<h3 class="menu-title"><?php esc_html_e( 'Menu', 'knowx' ); ?></h3>
			<a href="<?php echo esc_url( '#' ); ?>" class="menu-close" <?php echo $is_amp ? $amp_attrs : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php esc_html_e( 'Close', 'knowx' ); ?></a>
		</div>
		<?php knowx()->display_primary_nav_menu( array( 'menu_id' => 'primary-menu' ) ); ?>
	</div>
	
	<div class="primary-menu-container desktop-menu-container">
		<?php knowx()->display_primary_nav_menu( array( 'menu_id' => 'primary-menu' ) ); ?>
	</div>
</nav><!-- #site-navigation -->
