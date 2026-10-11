<?php
/**
 * Template part for displaying the page content when a 404 error has occurred
 *
 * @package knowx
 */

namespace KnowX\KnowX;

$knowx_products = knowx_products();
?>
<section class="error knowx-404">
	<header class="page-header">
		<h1 class="page-title"><?php esc_html_e( 'Page not found', 'knowx' ); ?></h1>
		<p><?php esc_html_e( 'The page may have moved or been removed. Search the documentation or pick a product below.', 'knowx' ); ?></p>
	</header>

	<?php get_search_form(); ?>

	<?php if ( $knowx_products ) : ?>
		<h2><?php esc_html_e( 'Browse the documentation', 'knowx' ); ?></h2>
		<ul class="knowx-article-list">
			<?php foreach ( $knowx_products as $knowx_product ) : ?>
				<li><a href="<?php echo esc_url( $knowx_product['url'] ); ?>"><?php echo esc_html( $knowx_product['name'] ); ?></a></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</section><!-- .error -->
