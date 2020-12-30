<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package helpdesk
 */

namespace Helpdesk\Helpdesk;

get_header();

helpdesk()->print_styles( 'helpdesk-content' );

?>
	<?php do_action( 'helpdesk_before_content' ); ?>

	<main id="primary" class="site-main">
		<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) { ?>
			<?php get_template_part( 'template-parts/content/error', '404' ); ?>
		<?php } ?>
	</main><!-- #primary -->
	
	<?php do_action( 'helpdesk_after_content' ); ?>
<?php
get_footer();
