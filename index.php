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

	<?php
	/** This action is documented in inc/extra.php. */
	do_action( 'knowx_sub_header' );
	?>
	
	<?php
	/** This action is documented in inc/extra.php. */
	do_action( 'knowx_before_content' );
	?>

	<?php get_template_part( 'template-parts/layout/sidebar', 'left' ); ?>
	
	<main id="primary" class="site-main">
		
		<?php
		if ( have_posts() ) {

			if ( ! is_front_page() ) {
				get_template_part( 'template-parts/content/page_header' );
			}

			if ( is_home() ) {
				?>
			<div class="post-layout row default-layout">
				<?php
				while ( have_posts() ) {
					the_post();

					get_template_part( 'template-parts/content/entry', 'layout' );
				}
				?>
				</div>
				<?php
			} else {
				while ( have_posts() ) {
					the_post();

					get_template_part( 'template-parts/content/entry', get_post_type() );
				}
			}

			if ( ! is_singular() ) {
				get_template_part( 'template-parts/content/pagination' );
			}
		} else {
			get_template_part( 'template-parts/content/error' );
		}
		?>

	</main><!-- #primary -->
	<?php get_template_part( 'template-parts/layout/sidebar', 'right' ); ?>

	<?php
	/** This action is documented in inc/extra.php. */
	do_action( 'knowx_after_content' );
	?>
<?php
get_footer();
