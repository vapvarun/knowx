<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package knowx
 */

namespace KnowX\KnowX;

get_header();

knowx()->print_styles( 'knowx-content', 'knowx-docs' );

?>
	<?php
	/** This action is documented in inc/extra.php. */
	do_action( 'knowx_sub_header' );
	?>
	<?php
	/** This action is documented in inc/extra.php. */
	do_action( 'knowx_before_content' );
	?>

	<main id="primary" class="knowx-narrow">
			<?php get_template_part( 'template-parts/content/error', '404' ); ?>
	</main><!-- #primary -->
	
	<?php
	/** This action is documented in inc/extra.php. */
	do_action( 'knowx_after_content' );
	?>
<?php
get_footer();
