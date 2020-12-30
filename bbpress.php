<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package helpdesk
 */

namespace Helpdesk\Helpdesk;

get_header();

helpdesk()->print_styles( 'helpdesk-content' );
helpdesk()->print_styles( 'helpdesk-sidebar', 'helpdesk-widgets' );

$bbpress_sidebar = get_theme_mod( 'bbpress_sidebar_option', helpdesk_defaults( 'bbpress-sidebar-option' ) );

?>
	<?php do_action( 'helpdesk_sub_header' ); ?>

	<?php do_action( 'helpdesk_before_content' ); ?>

	<?php if ( $bbpress_sidebar == 'left' || $bbpress_sidebar == 'both' ) : ?>
		<aside id="secondary" class="left-sidebar widget-area">
			<div class="sticky-sidebar">
				<?php helpdesk()->display_bbpress_left_sidebar(); ?>
			</div>
		</aside>
	<?php endif; ?>
	
	<main id="primary" class="site-main">
	
		<?php
		if ( have_posts() ) {

			while ( have_posts() ) {
				the_post();

				get_template_part( 'template-parts/content-bbpress' );
			}

			if ( ! is_singular() ) {
				get_template_part( 'template-parts/content/pagination' );
			}
		} else {
			get_template_part( 'template-parts/content/error' );
		}
		?>
		
	</main><!-- #primary -->
	
	<?php if ( $bbpress_sidebar == 'right' || $bbpress_sidebar == 'both' ) : ?>
		<aside id="secondary" class="primary-sidebar widget-area">
			<div class="sticky-sidebar">
				<?php helpdesk()->display_bbpress_right_sidebar(); ?>
			</div>
		</aside>
	<?php endif; ?>

	<?php do_action( 'helpdesk_after_content' ); ?>
<?php
get_footer();
