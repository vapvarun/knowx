<?php
/**
 * Template part for displaying the footer info
 *
 * @package knowx
 */

namespace KnowX\KnowX;

$knowx_copyright = knowx_footer_custom_text();
$knowx_privacy   = get_the_privacy_policy_link();

// Nothing to show: no footer menu, no copyright line and no privacy page.
if ( '' === trim( wp_strip_all_tags( $knowx_copyright ) ) && '' === $knowx_privacy && ! has_nav_menu( 'footer' ) ) {
	return;
}
?>
<div class="site-info">
	<?php if ( has_nav_menu( 'footer' ) ) : ?>
		<nav class="footer-navigation container" aria-label="<?php esc_attr_e( 'Footer menu', 'knowx' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'footer',
					'menu_class'     => 'footer-menu',
					'container'      => '',
					'depth'          => 1,
				)
			);
			?>
		</nav>
	<?php endif; ?>
	<?php if ( '' !== trim( wp_strip_all_tags( $knowx_copyright ) ) ) : ?>
		<div class="container">
			<?php echo $knowx_copyright; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	<?php endif; ?>

	<?php echo $knowx_privacy; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core builds the link. ?>
</div><!-- .site-info -->
