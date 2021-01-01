<?php
/**
 * Template part for displaying the header navigation menu
 *
 * @package helpdesk
 */

namespace Helpdesk\Helpdesk;

if (!helpdesk()->is_primary_nav_menu_active()) {
    return;
}

?>

<nav id="site-navigation" class="main-navigation nav--toggle-sub nav--toggle-small" aria-label="<?php esc_attr_e('Main menu', 'helpdesk'); ?>"
	<?php
    if (helpdesk()->is_amp()) {
        ?>
		[class]=" siteNavigationMenu.expanded ? 'main-navigation nav--toggle-sub nav--toggle-small nav--toggled-on' : 'main-navigation nav--toggle-sub nav--toggle-small' "
		<?php
    }
    ?>
>
	<?php
    if (helpdesk()->is_amp()) {
        ?>
		<amp-state id="siteNavigationMenu">
			<script type="application/json">
				{
					"expanded": false
				}
			</script>
		</amp-state>
		<?php
    }
	?>

	<button class="menu-toggle" aria-label="<?php esc_attr_e('Open menu', 'helpdesk'); ?>" aria-controls="primary-menu" aria-expanded="false"
		<?php
        if (helpdesk()->is_amp()) {
            ?>
			on="tap:AMP.setState( { siteNavigationMenu: { expanded: ! siteNavigationMenu.expanded } } )"
			[aria-expanded]="siteNavigationMenu.expanded ? 'true' : 'false'"
			<?php
        }
        ?>
	>
	<i class="fa fa-bars" aria-hidden="true"></i>
	</button>

	<div class="primary-menu-container">
		<div class="mobile-menu-heading">
			<h3 class="menu-title"><?php esc_html_e('Menu', 'helpdesk'); ?></h3>
			<a href="<?php echo esc_url('#'); ?>" class="menu-close" <?php if (helpdesk()->is_amp()) { ?>
			on="tap:AMP.setState( { siteNavigationMenu: { expanded: ! siteNavigationMenu.expanded } } )"
			[aria-expanded]="siteNavigationMenu.expanded ? 'true' : 'false'"
			<?php } ?>><?php esc_html_e('Close', 'helpdesk'); ?></a>
		</div>
		<?php helpdesk()->display_primary_nav_menu(['menu_id' => 'primary-menu']); ?>
	</div>
</nav><!-- #site-navigation -->
