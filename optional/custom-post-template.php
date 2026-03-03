<?php
/**
 * Template Name: Custom Post Template
 * Template Post Type: post
 *
 * When active, by adding the heading above and providing a custom name
 * this template becomes available in a drop-down panel in the editor.
 *
 * Filename can be anything.
 *
 * @link https://developer.wordpress.org/themes/template-files-section/page-template-files/#creating-page-templates-for-specific-post-types
 *
 * @package knowx
 */

namespace KnowX\KnowX;

get_header();

knowx()->print_styles( 'knowx-content' );

?>
	<?php do_action( 'knowx_before_content' ); ?>

		<main id="primary" class="site-main">
			<?php

			while ( have_posts() ) {
				the_post();

				get_template_part( 'template-parts/content/entry', get_post_type() );
			}
			?>
		</main><!-- #primary -->
		<?php get_sidebar();?>

	<?php do_action( 'knowx_after_content' ); ?>
<?php
get_footer();
