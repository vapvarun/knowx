<?php
/**
 * Template part for the product switcher in the header.
 *
 * Needs no JavaScript: it is a details element. Hidden when the site has fewer than two products.
 *
 * @package knowx
 */

namespace KnowX\KnowX;

$knowx_products = knowx_products();

/**
 * Filters whether the header shows the product switcher.
 *
 * @param bool $show True when there are at least two products.
 */
if ( ! apply_filters( 'knowx_show_product_switcher', count( $knowx_products ) > 1 ) ) {
	return;
}

$knowx_current = knowx_current_product_id();
?>
<details class="knowx-switcher">
	<summary><?php esc_html_e( 'Products', 'knowx' ); ?></summary>
	<ul class="knowx-switcher-list">
		<?php foreach ( $knowx_products as $knowx_product ) : ?>
			<li>
				<a href="<?php echo esc_url( $knowx_product['url'] ); ?>"<?php echo $knowx_current === $knowx_product['id'] ? ' aria-current="true"' : ''; ?>>
					<span class="knowx-icon"><?php echo knowx_icon_svg( $knowx_product['icon'], 20 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in knowx_icon_svg(). ?></span>
					<?php echo esc_html( $knowx_product['name'] ); ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</details>
