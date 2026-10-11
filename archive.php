<?php
/**
 * Tag, date, author and other archives: the same article list as search and sections.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
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

<main id="primary" class="knowx-narrow knowx-archive">
	<?php get_template_part( 'template-parts/content/page_header' ); ?>

	<?php if ( have_posts() ) : ?>
		<ul class="knowx-article-cards">
			<?php
			while ( have_posts() ) {
				the_post();
				get_template_part( 'template-parts/docs/list-item' );
			}
			?>
		</ul>
		<?php get_template_part( 'template-parts/content/pagination' ); ?>
	<?php else : ?>
		<?php get_template_part( 'template-parts/content/error' ); ?>
	<?php endif; ?>
</main>

<?php
/** This action is documented in inc/extra.php. */
do_action( 'knowx_after_content' );
?>
<?php
get_footer();
