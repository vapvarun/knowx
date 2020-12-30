<?php
/**
 * The template for displaying 500 pages (internal server errors)
 *
 * @link https://github.com/xwp/pwa-wp#offline--500-error-handling
 *
 * @package helpdesk
 */

namespace Helpdesk\Helpdesk;

get_header();

helpdesk()->print_styles( 'helpdesk-content' );

?>
	<?php do_action( 'helpdesk_before_content' ); ?>
	
	<main id="primary" class="site-main">
		<?php get_template_part( 'template-parts/content/error', '500' ); ?>
	</main><!-- #primary -->

	<?php do_action( 'helpdesk_after_content' ); ?>
<?php
get_footer();
