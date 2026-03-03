<?php
/**
 * The template for displaying all pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
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

				get_template_part( 'template-parts/content/entry', 'page' );
			}
			?>
		</main><!-- #primary -->
		<?php get_sidebar();?>

	<?php do_action( 'knowx_after_content' ); ?>
<?php
get_footer();
