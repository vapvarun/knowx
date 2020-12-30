<?php
/**
 * Template Name: Page-Without-Sidebar
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package helpdesk
 */

namespace Helpdesk\Helpdesk;

get_header();

helpdesk()->print_styles( 'helpdesk-content' );
helpdesk()->print_styles( 'helpdesk-sidebar', 'helpdesk-widgets' );

?>	
	<?php do_action( 'helpdesk_sub_header' ); ?>
	
	<?php do_action( 'helpdesk_before_content' ); ?>

		<main id="primary" class="site-main">
			<?php
			if ( have_posts() ) {

				while ( have_posts() ) {
					the_post();

					get_template_part( 'template-parts/content/entry', 'full-width' );
				}
				
			} else {
				get_template_part( 'template-parts/content/error' );
			}
			?>
		</main><!-- #primary -->
	</div><!-- .container -->

	<?php do_action( 'helpdesk_after_content' ); ?>
<?php
get_footer();
