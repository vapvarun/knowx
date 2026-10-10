<?php
/**
 * Template Name: Docs Home
 * Description: Product cards, featured and recent articles
 * Template Post Type: page
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package knowx
 */

namespace KnowX\KnowX;

get_header();

knowx()->print_styles( 'knowx-content', 'knowx-docs' );

$hero_title   = knowx_mod( 'hero_section_heading' );
$hero_content = knowx_mod( 'hero_section_content' );

$support_title   = knowx_mod( 'support_section_heading' );
$support_content = knowx_mod( 'support_section_content' );

$support_button_text = knowx_mod( 'support_section_button_text' );
$support_button_link = knowx_mod( 'support_section_button_link_url' );

$hero_image = get_the_post_thumbnail_url( get_queried_object_id(), 'full' );
$products   = knowx_products();
$home_count = max( 1, (int) knowx_mod( 'knowx_home_count' ) );
$featured   = knowx_kb_featured_posts( $home_count );
$recent     = knowx_is_truthy( knowx_mod( 'knowx_show_recent' ) ) ? knowx_kb_recent_posts( $home_count ) : array();
$column     = ( empty( $featured ) || empty( $recent ) ) ? 'col-md-12' : 'col-md-6';
$show_date  = knowx_is_truthy( knowx_mod( 'knowx_show_updated' ) );
$single     = 1 === count( $products ) ? knowx_product_tree( $products[0]['id'] ) : null;

?>
	<main id="primary" class="site-main">

	<div class="knowx-section-hero<?php echo $hero_image ? ' knowx-section-hero--image' : ''; ?>"<?php echo $hero_image ? ' style="background-image: url(\'' . esc_url( $hero_image ) . '\');"' : ''; ?>>
		<div class="container">
			<?php if ( ! empty( $hero_title ) ) { ?>
				<h1 class="entry-title"><?php echo esc_html( $hero_title ); ?></h1>
				<?php
			} else {
				get_template_part( 'template-parts/content/page_header' );
			}
			?>
			<?php if ( ! empty( $hero_content ) ) { ?>
				<div class="entry-summary">
					<?php echo esc_html( $hero_content ); ?>
				</div><!-- .entry-summary -->
			<?php } else { ?>
				<div class="entry-summary">
					<?php the_excerpt(); ?>
				</div><!-- .entry-summary -->
			<?php } ?>
			<div class="knowx-hero-section-search">
				<?php get_search_form(); ?>
			</div>
			<?php if ( ! empty( $featured ) ) : ?>
				<p class="knowx-hero-popular">
					<span><?php esc_html_e( 'Popular:', 'knowx' ); ?></span>
					<?php foreach ( array_slice( $featured, 0, 4 ) as $item ) : ?>
						<a href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['title'] ); ?></a>
					<?php endforeach; ?>
				</p>
			<?php endif; ?>
		</div>
	</div>

	<?php do_action( 'knowx_before_content' ); ?>

	<div class="container">

		<?php if ( empty( $products ) ) : ?>
		<div class="knowx-empty">
			<h2><?php esc_html_e( 'Your documentation starts here', 'knowx' ); ?></h2>
			<?php if ( current_user_can( 'manage_categories' ) ) : ?>
				<p>
					<?php esc_html_e( 'Add your first product: create a category with no parent, give it an icon and a description, then publish articles in its sections.', 'knowx' ); ?>
				</p>
				<a class="knowx-start" href="<?php echo esc_url( admin_url( 'edit-tags.php?taxonomy=category' ) ); ?>"><?php esc_html_e( 'Add a product', 'knowx' ); ?></a>
			<?php else : ?>
				<p><?php esc_html_e( 'The documentation is being written. Please check back soon.', 'knowx' ); ?></p>
			<?php endif; ?>
		</div>
		<?php elseif ( $single && $single['root']['children'] ) : ?>
		<div class="knowx-home-sections">
			<?php
			get_template_part(
				'template-parts/docs/section-grid',
				null,
				array(
					'tree'   => $single,
					'per'    => 5,
					'prefix' => 'knowx-home-section',
				)
			);
			?>
		</div>
		<?php elseif ( 'sections' === knowx_mod( 'knowx_home_layout' ) ) : ?>
			<?php foreach ( $products as $product ) : ?>
				<section class="knowx-home-product" aria-labelledby="knowx-home-product-<?php echo esc_attr( $product['id'] ); ?>"<?php echo $product['accent'] ? ' style="--card-accent:' . esc_attr( $product['accent'] ) . '"' : ''; ?>>
					<h2 class="knowx-home-product-title" id="knowx-home-product-<?php echo esc_attr( $product['id'] ); ?>"><span class="knowx-icon"><?php echo knowx_icon_svg( $product['icon'], 24 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in knowx_icon_svg(). ?></span><a href="<?php echo esc_url( $product['url'] ); ?>"><?php echo esc_html( $product['name'] ); ?></a></h2>
					<?php
					get_template_part(
						'template-parts/docs/section-grid',
						null,
						array(
							'tree'   => knowx_product_tree( $product['id'] ),
							'per'    => 5,
							'prefix' => 'knowx-home-' . $product['id'],
						)
					);
					?>
				</section>
			<?php endforeach; ?>
		<?php else : ?>
		<ul class="knowx-products">
			<?php foreach ( $products as $product ) : ?>
				<li class="knowx-product-card"<?php echo $product['accent'] ? ' style="--card-accent:' . esc_attr( $product['accent'] ) . '"' : ''; ?>>
					<span class="knowx-icon"><?php echo knowx_icon_svg( $product['icon'], 28 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in knowx_icon_svg(). ?></span>
					<h2><a class="knowx-product-link" href="<?php echo esc_url( $product['url'] ); ?>"><?php echo esc_html( $product['name'] ); ?></a></h2>
					<?php if ( '' !== $product['description'] ) : ?>
						<p class="knowx-product-desc"><?php echo esc_html( $product['description'] ); ?></p>
					<?php endif; ?>
					<?php if ( ! empty( $product['sections'] ) ) : ?>
						<ul class="knowx-product-sections">
							<?php foreach ( $product['sections'] as $section ) : ?>
								<li><a href="<?php echo esc_url( $section['url'] ); ?>"><?php echo esc_html( $section['name'] ); ?></a></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
					<span class="knowx-product-count"><?php echo esc_html( knowx_article_count_label( $product['count'] ) ); ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php endif; ?>

		<?php if ( ! empty( $featured ) || ! empty( $recent ) ) : ?>
		<div class="knowx-post-section">
			<div class="row">
				<?php if ( ! empty( $featured ) ) : ?>
				<div class="<?php echo esc_attr( $column ); ?>">
					<div class="knowx-popular-posts">
						<h3><?php esc_html_e( 'Featured Articles', 'knowx' ); ?></h3>
						<ul>
							<?php foreach ( $featured as $item ) : ?>
								<li><a href="<?php echo esc_url( $item['url'] ); ?>" rel="bookmark"><?php echo esc_html( $item['title'] ); ?></a></li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
				<?php endif; ?>
				<?php if ( ! empty( $recent ) ) : ?>
				<div class="<?php echo esc_attr( $column ); ?>">
					<div class="knowx-recent-posts">
						<h3><?php echo esc_html( knowx_recent_by_updated() ? __( 'Recently Updated', 'knowx' ) : __( 'Recent Articles', 'knowx' ) ); ?></h3>
						<ul>
							<?php foreach ( $recent as $item ) : ?>
								<li>
									<a href="<?php echo esc_url( $item['url'] ); ?>" rel="bookmark"><?php echo esc_html( $item['title'] ); ?></a>
									<?php if ( $show_date ) : ?>
										<time class="knowx-item-date" datetime="<?php echo esc_attr( $item['iso'] ); ?>"><?php echo esc_html( $item['date'] ); ?></time>
									<?php endif; ?>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
				<?php endif; ?>
			</div><!-- .row -->
		</div><!-- .knowx-post-section -->
		<?php endif; ?>

	</div><!-- .container -->

	<?php if ( knowx_is_truthy( knowx_mod( 'knowx_show_support' ) ) ) : ?>
	<div class="knowx-support-section">
		<div class="container">
			<div class="knowx-support-heading">
				<h2 class="knowx-support-title">
					<?php echo esc_html( ! empty( $support_title ) ? $support_title : __( 'Can\'t find what you\'re looking for?', 'knowx' ) ); ?>
				</h2>
			</div>
			<div class="knowx-support-content">
				<p><?php echo esc_html( ! empty( $support_content ) ? $support_content : __( 'Search the articles above, or get in touch and we will help you.', 'knowx' ) ); ?></p>
			</div>
			<?php if ( ! empty( $support_button_text ) && ! empty( $support_button_link ) ) : ?>
			<div class="knowx-support-button">
				<a href="<?php echo esc_url( $support_button_link ); ?>"><?php echo esc_html( $support_button_text ); ?></a>
			</div>
			<?php endif; ?>
		</div><!-- .container -->
	</div><!-- .knowx-support-section -->
	<?php endif; ?>
</main><!-- #primary -->

	<?php do_action( 'knowx_after_content' ); ?>
<?php
get_footer();
