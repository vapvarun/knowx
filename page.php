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
 * @package knowx
 */

namespace KnowX\KnowX;

get_header();

knowx()->print_styles( 'knowx-content' );
knowx()->print_styles( 'knowx-sidebar', 'knowx-widgets' );

?>

	<?php do_action( 'knowx_sub_header' ); ?>
	
	<?php do_action( 'knowx_before_content' ); ?>

	<?php get_template_part( 'template-parts/layout/sidebar', 'left' ); ?>
	
	<main id="primary" class="site-main">
		
	<?php
	if ( have_posts() ) {

		get_template_part( 'template-parts/content/page_header' );

		while ( have_posts() ) {
			the_post();

			get_template_part( 'template-parts/content/entry', 'page' );
		}
	} else {
		get_template_part( 'template-parts/content/error' );
	}
	?>

	</main><!-- #primary -->
	<?php get_template_part( 'template-parts/layout/sidebar', 'right' ); ?>

	<?php do_action( 'knowx_after_content' ); ?>
<?php
get_footer();
