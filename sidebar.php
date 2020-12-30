<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package helpdesk
 */

namespace Helpdesk\Helpdesk;

if (!helpdesk()->is_left_sidebar_active() || !helpdesk()->is_right_sidebar_active()) {
    return;
}

helpdesk()->print_styles('helpdesk-sidebar', 'helpdesk-widgets');

?>
<aside id="secondary" class="primary-sidebar widget-area">
	<div class="sticky-sidebar">
		<?php
        $sidebar = get_theme_mod('sidebar_option');
        switch ($sidebar) {
            case 'left':
                helpdesk()->display_left_sidebar();
                break;
            case 'right':
                helpdesk()->display_right_sidebar();
                break;
            default:
        }
        ?>
	</div>
</aside><!-- #secondary -->