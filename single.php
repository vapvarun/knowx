<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package knowx
 */

namespace KnowX\KnowX;

get_header();

knowx()->print_styles( 'knowx-content' );
knowx()->print_styles( 'knowx-sidebar', 'knowx-widgets' );

// Articles that belong to a product use the docs layout.
if ( knowx_current_product() ) {
	get_template_part( 'template-parts/docs/single' );
	get_footer();
	return;
}


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

				get_template_part( 'template-parts/content/entry', get_post_type() );
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

