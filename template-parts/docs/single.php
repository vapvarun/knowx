<?php
/**
 * Article page in the docs layout: section tree, article, "On this page".
 *
 * @package knowx
 */

namespace KnowX\KnowX;

knowx()->print_styles( 'knowx-content', 'knowx-docs' );

$knowx_product = knowx_current_product();
$knowx_tree    = knowx_product_tree( $knowx_product['id'] );
$knowx_flat    = knowx_tree_flat( $knowx_tree );

// Render the article first so the contents list knows the headings before it is printed.
ob_start();
while ( have_posts() ) {
	the_post();
	get_template_part( 'template-parts/docs/article' );
}
$knowx_article = ob_get_clean();
$knowx_toc     = knowx_is_truthy( knowx_mod( 'knowx_show_toc' ) ) ? knowx_toc_items() : array();

$knowx_index   = array_search( get_queried_object_id(), wp_list_pluck( $knowx_flat, 'id' ), true );
$knowx_prev    = false !== $knowx_index && $knowx_index > 0 ? $knowx_flat[ $knowx_index - 1 ] : null;
$knowx_next    = false !== $knowx_index && isset( $knowx_flat[ $knowx_index + 1 ] ) ? $knowx_flat[ $knowx_index + 1 ] : null;
$knowx_related = array();
$knowx_scan = array_merge( array( $knowx_tree['root'] ), $knowx_tree['nodes'] );
foreach ( $knowx_scan as $knowx_node ) {
	if ( in_array( get_queried_object_id(), wp_list_pluck( $knowx_node['posts'], 'id' ), true ) ) {
		$knowx_related = array_slice(
			array_filter(
				$knowx_node['posts'],
				static function ( $item ) {
					return get_queried_object_id() !== $item['id'];
				}
			),
			0,
			5
		);
		break;
	}
}
?>
<?php do_action( 'knowx_sub_header' ); ?>
<?php do_action( 'knowx_before_content' ); ?>

<div class="knowx-docs">
	<?php get_template_part( 'template-parts/docs/sidebar' ); ?>

	<main id="primary" class="knowx-docs-main">
		<?php if ( $knowx_toc ) : ?>
			<details class="knowx-toc-panel">
				<summary><?php esc_html_e( 'On this page', 'knowx' ); ?></summary>
				<?php knowx_render_toc( $knowx_toc ); ?>
			</details>
		<?php endif; ?>

		<?php echo $knowx_article; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- markup built by the article template part. ?>

		<?php if ( $knowx_prev || $knowx_next ) : ?>
			<nav class="knowx-pager" aria-label="<?php esc_attr_e( 'Previous and next article', 'knowx' ); ?>">
				<?php if ( $knowx_prev ) : ?>
					<a class="knowx-pager-prev" href="<?php echo esc_url( $knowx_prev['url'] ); ?>" rel="prev"><span><?php esc_html_e( 'Previous', 'knowx' ); ?></span><?php echo esc_html( $knowx_prev['title'] ); ?></a>
				<?php endif; ?>
				<?php if ( $knowx_next ) : ?>
					<a class="knowx-pager-next" href="<?php echo esc_url( $knowx_next['url'] ); ?>" rel="next"><span><?php esc_html_e( 'Next', 'knowx' ); ?></span><?php echo esc_html( $knowx_next['title'] ); ?></a>
				<?php endif; ?>
			</nav>
		<?php endif; ?>

		<?php if ( $knowx_related ) : ?>
			<section class="knowx-related" aria-labelledby="knowx-related-title">
				<h2 id="knowx-related-title"><?php esc_html_e( 'More in this section', 'knowx' ); ?></h2>
				<ul>
					<?php foreach ( $knowx_related as $knowx_item ) : ?>
						<li><a href="<?php echo esc_url( $knowx_item['url'] ); ?>"><?php echo esc_html( $knowx_item['title'] ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</section>
		<?php endif; ?>

		<?php
		if ( comments_open() || get_comments_number() ) {
			knowx()->print_styles( 'knowx-comments' );
			comments_template();
		}
		?>
	</main>

	<?php if ( $knowx_toc ) : ?>
		<aside class="knowx-toc" aria-label="<?php esc_attr_e( 'On this page', 'knowx' ); ?>">
			<p class="knowx-toc-title"><?php esc_html_e( 'On this page', 'knowx' ); ?></p>
			<?php knowx_render_toc( $knowx_toc ); ?>
		</aside>
	<?php endif; ?>
</div>

<?php do_action( 'knowx_after_content' ); ?>
