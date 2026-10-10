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
					/* translators: %s: number of sections. */
					echo esc_html( sprintf( _n( '%s section', '%s sections', count( $knowx_tree['root']['children'] ), 'knowx' ), number_format_i18n( count( $knowx_tree['root']['children'] ) ) ) );
					?>
					<span aria-hidden="true">&middot;</span>
					<?php echo esc_html( knowx_article_count_label( $knowx_product['count'] ) ); ?>
				</p>
				<?php if ( $knowx_first ) : ?>
					<a class="knowx-start" href="<?php echo esc_url( $knowx_first[0]['url'] ); ?>"><?php esc_html_e( 'Start here', 'knowx' ); ?></a>
				<?php endif; ?>
			<?php elseif ( ! $knowx_is_root ) : ?>
				<p class="knowx-term-meta"><?php echo esc_html( knowx_article_count_label( $knowx_term->count ) ); ?></p>
			<?php endif; ?>
		</header>

		<?php if ( $knowx_is_root && $knowx_tree ) : ?>
			<div class="knowx-section-grid">
			<?php foreach ( $knowx_tree['root']['children'] as $knowx_id ) : ?>
				<?php $knowx_node = $knowx_tree['nodes'][ $knowx_id ]; ?>
				<section class="knowx-section" aria-labelledby="knowx-section-<?php echo esc_attr( $knowx_id ); ?>">
					<h2 id="knowx-section-<?php echo esc_attr( $knowx_id ); ?>"><a href="<?php echo esc_url( $knowx_node['url'] ); ?>"><?php echo esc_html( $knowx_node['name'] ); ?></a></h2>
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
							/* translators: %s: number of articles. */
							echo esc_html( sprintf( __( 'View all %s articles', 'knowx' ), number_format_i18n( $knowx_node['count'] ) ) );
							?>
						</a>
					<?php endif; ?>
				</section>
			<?php endforeach; ?>
			</div>
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
