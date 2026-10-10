<?php
/**
 * Template Name: Knowledge Base Layout Two
 * Description: Template for displaying Categories and Posts
 * Template Post Type: page
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package knowx
 */

namespace KnowX\KnowX;

get_header();

knowx()->print_styles( 'knowx-content' );

$sections         = knowx_kb_sections();
$show_description = 'yes' === knowx_mod( 'knowx_cat_description' );
$show_view_all    = 'yes' === knowx_mod( 'knowx_view_all' );

?>

	<?php do_action( 'knowx_sub_header' ); ?>

	<?php do_action( 'knowx_before_content' ); ?>

	<main id="primary" class="site-main">

	<?php
	if ( have_posts() ) {

		if ( 'yes' === knowx_mod( 'knowx_page_title' ) ) {
			get_template_part( 'template-parts/content/page_header' );
		}

		while ( have_posts() ) {
			the_post();

			get_template_part( 'template-parts/content/entry', 'page' );
		}
	} else {
		get_template_part( 'template-parts/content/error' );
	}
	?>

	<?php if ( ! empty( $sections ) ) : ?>
	<div id="knowx-categories-columns-four" class="knowx-knowledgebase-two">
		<?php foreach ( $sections as $section ) : ?>
			<ul class="cat-list">
				<li class="cat-name"><h2><a href="<?php echo esc_url( $section['url'] ); ?>"><?php echo esc_html( $section['name'] ); ?></a></h2></li>
				<?php if ( $show_description && '' !== trim( $section['description'] ) ) : ?>
					<li class="cat-description"><?php echo wp_kses_post( wpautop( $section['description'] ) ); ?></li>
				<?php endif; ?>
				<?php foreach ( $section['posts'] as $item ) : ?>
					<li class="post-name"><a href="<?php echo esc_url( $item['url'] ); ?>" rel="bookmark"><?php echo esc_html( $item['title'] ); ?></a></li>
				<?php endforeach; ?>
				<?php if ( $show_view_all ) : ?>
					<li class="view-all"><a href="<?php echo esc_url( $section['url'] ); ?>"><?php echo esc_html__( 'View All &raquo;', 'knowx' ); ?></a></li>
				<?php endif; ?>
			</ul>
		<?php endforeach; ?>
	</div>
	<?php endif; ?>

	</main><!-- #primary -->

	<?php do_action( 'knowx_after_content' ); ?>
<?php
get_footer();
