<?php
/**
 * A grid of a product's sections, each with its first articles.
 *
 * Used by the product page and by the docs home when it lists sections.
 *
 * @package knowx
 *
 * Args: `tree` (knowx_product_tree() result), `per` (articles per section), `prefix` (id prefix, unique on the page), `level` (heading level, 2 or 3),
 * `more_url` (product page; when set, the Overview card shows `per` articles and links there, otherwise it lists them all),
 * `product` (product id, passed to the Overview filters).
 *
 * Articles filed on the product itself, not in a section, open the grid as an Overview card, in the same
 * order as the sidebar tree and Start here.
 */

namespace KnowX\KnowX;

$knowx_tree   = $args['tree'] ?? null;
$knowx_per    = (int) ( $args['per'] ?? 5 );
$knowx_prefix = $args['prefix'] ?? 'knowx-section';
$knowx_level  = in_array( (int) ( $args['level'] ?? 2 ), array( 2, 3 ), true ) ? (int) ( $args['level'] ?? 2 ) : 2;
$knowx_more   = $args['more_url'] ?? '';
$knowx_pid    = (int) ( $args['product'] ?? 0 );

if ( ! $knowx_tree ) {
	return;
}
/**
 * Filters the articles of a product's Overview card: the ones filed on the product, not in a section.
 *
 * @param array[] $items      Items with `id`, `title` and `url`, in reading order. Return an empty array to hide the card.
 * @param int     $product_id Product (top-level category) id.
 */
$knowx_own = (array) apply_filters( 'knowx_overview_articles', $knowx_tree['root']['posts'], $knowx_pid );
if ( ! $knowx_own && empty( $knowx_tree['root']['children'] ) ) {
	return;
}
/**
 * Filters the heading of a product's Overview card.
 *
 * @param string $title      Default "Overview".
 * @param int    $product_id Product (top-level category) id.
 */
$knowx_own_title = (string) apply_filters( 'knowx_overview_title', __( 'Overview', 'knowx' ), $knowx_pid );
?>
<div class="knowx-section-grid">
	<?php if ( $knowx_own ) : ?>
		<div class="knowx-section knowx-section--overview">
			<h<?php echo (int) $knowx_level; ?>><?php echo esc_html( $knowx_own_title ); ?></h<?php echo (int) $knowx_level; ?>>
			<ul class="knowx-article-list">
				<?php foreach ( $knowx_more ? array_slice( $knowx_own, 0, $knowx_per ) : $knowx_own as $knowx_item ) : ?>
					<li><a href="<?php echo esc_url( $knowx_item['url'] ); ?>"><?php echo esc_html( $knowx_item['title'] ); ?></a></li>
				<?php endforeach; ?>
			</ul>
			<?php if ( $knowx_more && count( $knowx_own ) > $knowx_per ) : ?>
				<a class="knowx-view-all" href="<?php echo esc_url( $knowx_more ); ?>">
					<?php
					$knowx_count = count( $knowx_own );
					/* translators: %s: number of articles. */
					echo esc_html( sprintf( _n( 'View all %s article', 'View all %s articles', $knowx_count, 'knowx' ), number_format_i18n( $knowx_count ) ) );
					?>
				</a>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<?php foreach ( $knowx_tree['root']['children'] as $knowx_id ) : ?>
		<?php $knowx_node = $knowx_tree['nodes'][ $knowx_id ]; ?>
		<div class="knowx-section">
			<h<?php echo (int) $knowx_level; ?>><a href="<?php echo esc_url( $knowx_node['url'] ); ?>"><?php echo esc_html( $knowx_node['name'] ); ?></a></h<?php echo (int) $knowx_level; ?>>
			<?php if ( '' !== $knowx_node['description'] ) : ?>
				<p class="knowx-section-desc"><?php echo esc_html( $knowx_node['description'] ); ?></p>
			<?php endif; ?>
			<ul class="knowx-article-list">
				<?php foreach ( array_slice( $knowx_node['posts'], 0, $knowx_per ) as $knowx_item ) : ?>
					<li><a href="<?php echo esc_url( $knowx_item['url'] ); ?>"><?php echo esc_html( $knowx_item['title'] ); ?></a></li>
				<?php endforeach; ?>
			</ul>
			<?php if ( $knowx_node['count'] > $knowx_per ) : ?>
				<a class="knowx-view-all" href="<?php echo esc_url( $knowx_node['url'] ); ?>">
					<?php
					$knowx_count = (int) $knowx_node['count'];
					/* translators: %s: number of articles. */
					echo esc_html( sprintf( _n( 'View all %s article', 'View all %s articles', $knowx_count, 'knowx' ), number_format_i18n( $knowx_count ) ) );
					?>
				</a>
			<?php endif; ?>
		</div>
	<?php endforeach; ?>
</div>
