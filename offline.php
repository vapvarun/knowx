<?php
/**
 * The template for displaying offline pages
 *
 * @link https://github.com/xwp/pwa-wp#offline--500-error-handling
 *
 * @package helpdesk
 */

namespace Helpdesk\Helpdesk;

// Prevent showing nav menus.
add_filter( 'has_nav_menu', '__return_false' );

get_header();

helpdesk()->print_styles( 'helpdesk-content' );

?>
	<main id="primary" class="site-main">
		<?php get_template_part( 'template-parts/content/error', 'offline' ); ?>
	</main><!-- #primary -->
<?php
get_footer();
