<?php
/**
 * A grid of a product's sections, each with its first articles.
 *
 * Used by the product page and by the docs home when it lists sections.
 *
 * @package knowx
 *
 * Args: `tree` (knowx_product_tree() result), `per` (articles per section), `prefix` (id prefix, unique on the page), `level` (heading level, 2 or 3).
 */

namespace KnowX\KnowX;

$knowx_tree   = $args['tree'] ?? null;
$knowx_per    = (int) ( $args['per'] ?? 5 );
$knowx_prefix = $args['prefix'] ?? 'knowx-section';
$knowx_level  = in_array( (int) ( $args['level'] ?? 2 ), array( 2, 3 ), true ) ? (int) ( $args['level'] ?? 2 ) : 2;

if ( ! $knowx_tree || empty( $knowx_tree['root']['children'] ) ) {
	return;
}
?>
<div class="knowx-section-grid">
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
