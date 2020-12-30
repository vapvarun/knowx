<?php
/**
 * Template part for displaying the header branding
 *
 * @package helpdesk
 */

namespace Helpdesk\Helpdesk;

?>

<div class="site-branding">
	<?php the_custom_logo(); ?>

	<?php
	if ( is_front_page() && is_home() ) {
		?>
		<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
		<?php
	} else {
		?>
		<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
		<?php
	}
	?>

	<?php
	$helpdesk_description = get_bloginfo( 'description', 'display' );
	if ( $helpdesk_description || is_customize_preview() ) {
		?>
		<p class="site-description">
			<?php echo $helpdesk_description; /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */ ?>
		</p>
		<?php
	}
	?>
</div><!-- .site-branding -->
