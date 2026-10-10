<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package knowx
 */

namespace KnowX\KnowX;

get_header();

knowx()->print_styles( 'knowx-content' );
knowx()->print_styles( 'knowx-sidebar', 'knowx-widgets' );

// Articles that belong to a product use the docs layout.
if ( knowx_current_product() ) {
	get_template_part( 'template-parts/docs/single' );
	get_footer();
	return;
}

$default_sidebar = knowx_mod( 'sidebar_option' );

$post_layout  = knowx_mod( 'blog_layout_option' );
$post_per_row = 'col-md-' . knowx_mod( 'post_per_row' );

?>


	<?php do_action( 'knowx_sub_header' ); ?>
	
	<?php do_action( 'knowx_before_content' ); ?>

	<?php if ( 'left' === $default_sidebar || 'both' === $default_sidebar ) : ?>
		<aside id="secondary-left" class="left-sidebar widget-area">
			<div class="sticky-sidebar">
				<?php knowx()->display_left_sidebar(); ?>
			</div>
		</aside>
	<?php endif; ?>
	
	<main id="primary" class="site-main">
		
		<?php
		if ( have_posts() ) {
			get_template_part( 'template-parts/content/page_header' );

			$classes = get_body_class();
			if ( in_array( 'blog', $classes, true ) || in_array( 'archive', $classes, true ) || in_array( 'search', $classes, true ) ) {
				?>
			<div class="post-layout row <?php echo esc_attr( $post_layout ); ?>">
			<div class="grid-sizer <?php echo esc_attr( $post_per_row ); ?>"></div>
				<?php
				while ( have_posts() ) {

					the_post();

					get_template_part( 'template-parts/content/entry', 'layout' );
				}
				?>
				</div>
				<?php
			} else {
				while ( have_posts() ) {
					the_post();

					get_template_part( 'template-parts/content/entry', get_post_type() );
				}
			}

			if ( ! is_singular() ) {
				get_template_part( 'template-parts/content/pagination' );
			}
		} else {
			get_template_part( 'template-parts/content/error' );
		}
		?>

	</main><!-- #primary -->
	<?php if ( 'right' === $default_sidebar || 'both' === $default_sidebar ) : ?>
		<aside id="secondary" class="primary-sidebar widget-area">
			<div class="sticky-sidebar">
				<?php knowx()->display_right_sidebar(); ?>
			</div>
		</aside>
	<?php endif; ?>

	<?php do_action( 'knowx_after_content' ); ?>


<?php
get_footer();

