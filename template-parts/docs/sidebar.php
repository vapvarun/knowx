<?php
/**
 * Left column of the docs layout: the product's section tree.
 *
 * On narrow screens the tree is a collapsible panel above the article.
 *
 * @package knowx
 */

namespace KnowX\KnowX;

$knowx_product = knowx_current_product();
if ( ! $knowx_product ) {
	return;
}

$knowx_tree = knowx_product_tree( $knowx_product['id'] );
?>
<aside class="knowx-tree" aria-label="<?php esc_attr_e( 'Product sections', 'knowx' ); ?>">
	<details class="knowx-tree-panel">
		<summary>
			<span class="knowx-icon"><?php echo knowx_icon_svg( $knowx_product['icon'], 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in knowx_icon_svg(). ?></span>
			<?php echo esc_html( $knowx_product['name'] ); ?>
		</summary>
		<p class="knowx-tree-home"><a href="<?php echo esc_url( $knowx_product['url'] ); ?>"<?php echo is_category() && get_queried_object_id() === $knowx_product['id'] ? ' aria-current="page"' : ''; ?>><?php esc_html_e( 'Product overview', 'knowx' ); ?></a></p>
		<?php knowx_render_tree( $knowx_tree, is_singular() ? get_queried_object_id() : 0, is_category() ? get_queried_object_id() : 0 ); ?>
	</details>
</aside>
