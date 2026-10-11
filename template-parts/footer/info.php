<?php
/**
 * Template part for displaying the footer info
 *
 * @package knowx
 */

namespace KnowX\KnowX;

?>
<div class="site-info">
	<?php if ( has_nav_menu( 'footer' ) ) : ?>
		<nav class="footer-navigation container" aria-label="<?php esc_attr_e( 'Footer menu', 'knowx' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'footer',
					'menu_class'     => 'footer-menu',
					'container'      => false,
					'depth'          => 1,
				)
			);
			?>
		</nav>
	<?php endif; ?>
	<div class="container">	
		<?php echo knowx_footer_custom_text(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>

	<?php the_privacy_policy_link(); ?>
</div><!-- .site-info -->
