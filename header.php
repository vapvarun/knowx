<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package knowx
 */

namespace KnowX\KnowX;

?>
<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="<?php echo esc_url( '#primary' ); ?>"><?php esc_html_e( 'Skip to content', 'knowx' ); ?></a>

	<div class="site-header-wrapper">
			<div class="container">
				<header id="masthead" class="site-header">
					<?php get_template_part( 'template-parts/header/custom_header' ); ?>

					<?php get_template_part( 'template-parts/header/branding' ); ?>

					<?php get_template_part( 'template-parts/header/navigation' ); ?>

					<?php get_template_part( 'template-parts/header/search-trigger' ); ?>

					<?php get_template_part( 'template-parts/header/product-switcher' ); ?>

					<?php knowx()->color_mode_toggle(); ?>
				</header><!-- #masthead -->
			</div>
	</div>
<?php if ( knowx_template_uses_container() ) : ?>
	<div class="container">
<?php endif; ?>