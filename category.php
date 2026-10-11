<?php
/**
 * Product home and section pages.
 *
 * A top-level category is a product and lists its sections with their first articles; a child
 * category is a section and lists its articles. Both sit next to the product's section tree.
 *
 * @package knowx
 */

namespace KnowX\KnowX;

get_header();

knowx()->print_styles( 'knowx-content', 'knowx-docs' );

$knowx_term    = get_queried_object();
$knowx_product = knowx_current_product();
$knowx_tree    = $knowx_product ? knowx_product_tree( $knowx_product['id'] ) : null;
$knowx_is_root = $knowx_product && $knowx_product['id'] === (int) $knowx_term->term_id;
$knowx_per     = 5;
$knowx_updated = knowx_is_truthy( knowx_mod( 'knowx_show_updated' ) ) ? knowx_term_updated( $knowx_term->term_id ) : null;
?>
<?php do_action( 'knowx_sub_header' ); ?>
<?php do_action( 'knowx_before_content' ); ?>

<div class="knowx-docs">
	<?php get_template_part( 'template-parts/docs/sidebar' ); ?>

	<main id="primary" class="knowx-docs-main">
		<header class="page-header knowx-term-header">
			<?php if ( $knowx_is_root ) : ?>
				<span class="knowx-icon knowx-term-icon"><?php echo knowx_icon_svg( $knowx_product['icon'], 36 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in knowx_icon_svg(). ?></span>
			<?php endif; ?>
			<h1 class="page-title"><?php single_cat_title(); ?></h1>
			<?php the_archive_description( '<div class="archive-description">', '</div>' ); ?>
			<?php if ( $knowx_is_root && $knowx_tree ) : ?>
				<?php $knowx_first = knowx_tree_flat( $knowx_tree ); ?>
				<p class="knowx-term-meta">
					<?php
					$knowx_sections = count( $knowx_tree['root']['children'] );
					/* translators: %s: number of sections. */
					echo esc_html( sprintf( _n( '%s section', '%s sections', $knowx_sections, 'knowx' ), number_format_i18n( $knowx_sections ) ) );
					?>
					<span aria-hidden="true">&middot;</span>
					<?php echo esc_html( knowx_article_count_label( $knowx_product['count'] ) ); ?>
				</p>
				<?php $knowx_start = knowx_start_url( $knowx_product, $knowx_first ); ?>
				<?php if ( '' !== $knowx_start ) : ?>
					<a class="knowx-start" href="<?php echo esc_url( $knowx_start ); ?>"><?php esc_html_e( 'Start here', 'knowx' ); ?></a>
				<?php endif; ?>
			<?php elseif ( ! $knowx_is_root ) : ?>
				<p class="knowx-term-meta"><?php echo esc_html( knowx_article_count_label( $knowx_term->count ) ); ?></p>
			<?php endif; ?>
			<?php if ( $knowx_updated ) : ?>
				<p class="knowx-term-updated">
					<?php
					/* translators: %s: date the newest article was changed. */
					printf( esc_html__( 'Updated %s', 'knowx' ), '<time datetime="' . esc_attr( $knowx_updated['iso'] ) . '">' . esc_html( $knowx_updated['date'] ) . '</time>' );
					?>
				</p>
			<?php endif; ?>
		</header>

		<?php if ( $knowx_is_root && $knowx_tree ) : ?>
			<?php
			get_template_part(
				'template-parts/docs/section-grid',
				null,
				array(
					'tree'   => $knowx_tree,
					'per'    => $knowx_per,
					'prefix' => 'knowx-section',
				)
			);
			?>
		<?php elseif ( have_posts() ) : ?>
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
</div>

<?php do_action( 'knowx_after_content' ); ?>
<?php
get_footer();
