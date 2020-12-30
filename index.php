<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package helpdesk
 */

namespace Helpdesk\Helpdesk;

get_header();

helpdesk()->print_styles( 'helpdesk-content' );
helpdesk()->print_styles( 'helpdesk-sidebar', 'helpdesk-widgets' );

$default_sidebar = get_theme_mod( 'sidebar_option', helpdesk_defaults( 'sidebar-option' ) );

$post_layout = get_theme_mod( 'blog_layout_option', helpdesk_defaults( 'blog-layout-option' ) );
$post_per_row = 'col-md-' . get_theme_mod( 'post_per_row', helpdesk_defaults( 'post-per-row' ) );

?>

	<?php do_action( 'helpdesk_sub_header' ); ?>
	
	<?php do_action( 'helpdesk_before_content' ); ?>

	<?php if ( $default_sidebar == 'left' || $default_sidebar == 'both' ) : ?>
		<aside id="secondary" class="left-sidebar widget-area">
			<div class="sticky-sidebar">
				<?php helpdesk()->display_left_sidebar(); ?>
			</div>
		</aside>
	<?php endif; ?>
	
	<main id="primary" class="site-main">
		
		<?php
		if ( have_posts() ) {

			$classes = get_body_class();
			if(in_array('blog',$classes) || in_array('archive',$classes) || in_array('search',$classes)){ ?>
			<div class="post-layout row <?php echo esc_attr($post_layout); ?>">
			<div class="grid-sizer <?php echo esc_attr( $post_per_row ); ?>"></div>
			<?php 
				while ( have_posts() ) {
					the_post();
	
					get_template_part( 'template-parts/content/entry', 'layout' );
				} ?>
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
	<?php if ( $default_sidebar == 'right' || $default_sidebar == 'both' ) : ?>
		<aside id="secondary" class="primary-sidebar widget-area">
			<div class="sticky-sidebar">
				<?php helpdesk()->display_right_sidebar(); ?>
			</div>
		</aside>
	<?php endif; ?>

	<?php do_action( 'helpdesk_after_content' ); ?>
<?php
get_footer();
