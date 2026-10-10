<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package knowx
 */

namespace KnowX\KnowX;

?>
	<?php
	$classes = get_body_class();
	if ( ! in_array( 'page-template-full-width', $classes ) ) {
		?>
		</div><!-- .container -->
	<?php } ?>

	<footer id="colophon" class="site-footer">
			<div class="site-footer-wrapper">
				<div class="container">
					<?php
					$footer_sidebars = array( 'footer-1', 'footer-2', 'footer-3', 'footer-4' );
					$active_sidebars = array_filter( $footer_sidebars, 'is_active_sidebar' );

					if ( ! empty( $active_sidebars ) ) :
						?>
						<div class="footer-inner" role="complementary" aria-label="<?php esc_attr_e( 'Footer', 'knowx' ); ?>">
							<?php foreach ( $active_sidebars as $sidebar ) : ?>
								<div class="footer-widget">
									<?php dynamic_sidebar( $sidebar ); ?>
								</div>
							<?php endforeach; ?>
						</div><!-- .footer-inner -->
					<?php endif; ?>    
				</div><!-- .container -->
			</div><!-- .site-footer-wrapper -->

			<?php get_template_part( 'template-parts/footer/info' ); ?>
	</footer><!-- #colophon -->

</div><!-- #page -->

<div class="mobile-menu-close"></div>
<?php wp_footer(); ?>

</body>
</html>
