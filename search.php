<?php
/**
 * Search results: a plain list of articles with the product and section each one belongs to.
 *
 * @package knowx
 */

namespace KnowX\KnowX;

get_header();

knowx()->print_styles( 'knowx-content', 'knowx-docs' );

?>
<?php do_action( 'knowx_sub_header' ); ?>
<?php do_action( 'knowx_before_content' ); ?>

<main id="primary" class="knowx-narrow knowx-search-results">
	<header class="page-header">
		<h1 class="page-title">
			<?php
			/* translators: %s: search query. */
			printf( esc_html__( 'Search results for: %s', 'knowx' ), '<span>' . esc_html( get_search_query() ) . '</span>' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above.
			?>
		</h1>
		<?php get_search_form(); ?>
	</header>

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
		<div class="knowx-empty">
			<h2><?php esc_html_e( 'Nothing found', 'knowx' ); ?></h2>
			<p><?php esc_html_e( 'Check the spelling or try a shorter, more general word.', 'knowx' ); ?></p>
		</div>
	<?php endif; ?>
</main>

<?php do_action( 'knowx_after_content' ); ?>
<?php
get_footer();
