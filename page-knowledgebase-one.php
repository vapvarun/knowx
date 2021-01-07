<?php
/**
 * Template Name: Knowledge Base Layout Two
 * Description: Template for displaying Categories and Posts
 * Template Post Type: page
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package helpdesk
 */

namespace Helpdesk\Helpdesk;

get_header();

helpdesk()->print_styles( 'helpdesk-content' );

$hero_title = get_theme_mod( 'hero_section_heading' );
$hero_content = get_theme_mod( 'hero_section_content' );

?>
	</div><!--close .container-->
	<?php $backgroundImg = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' ); ?>
		<?php if ( has_post_thumbnail() ) { ?>
			<div class="helpdesk-section-hero" style="background-image: url(' <?php echo $backgroundImg[0]; ?> ');">
		<?php }else { ?>
			<div class="helpdesk-section-hero">
		<?php } ?>
		<div class="container">
			<?php if ( ! empty( $hero_title ) ) { ?>
				<h1 class="entry-title"><?php echo $hero_title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h1>
			<?php }else {
				get_template_part( 'template-parts/content/page_header' );
			} ?>
			<?php if ( ! empty( $hero_content ) ) { ?>
				<div class="entry-summary">
					<?php echo $hero_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div><!-- .entry-summary -->
			<?php }else { ?>
				<div class="entry-summary">
					<?php the_excerpt(); ?>
				</div><!-- .entry-summary -->
			<?php } ?>
			<div class="helpdesk-hero-section-search">
				<?php get_search_form(); ?>
			</div>
		</div>
	</div>
	<div class="container">
	
	<?php do_action( 'helpdesk_before_content' ); ?>
	
	<main id="primary" class="site-main">

	<div id="helpdesk-categories-columns-four">
		<?php $exclude_cats = get_post_meta( get_the_ID(), 'helpdesk-exclude-cats', true );
		if ( !empty( $exclude_cats ) ) {
			$exclude = esc_attr( $exclude_cats );
		} else if ( get_theme_mod( 'helpdesk_exclude' ) ) :
			$exclude = esc_attr( get_theme_mod( 'helpdesk_exclude' ) );
		else :
			$exclude = '';
		endif;
		$helpdesk_cat_args = array(
			'hide_empty' => 0,
			'exclude' => $exclude,
			'orderby' => 'name',
			'order' => 'asc'
		);
		$helpdesk_cats = get_categories( $helpdesk_cat_args );

		foreach ( $helpdesk_cats as $category ) :
			echo '<ul class="cat-list"><li class="cat-name"><h2><a href="' . get_category_link( $category->cat_ID ) . '" title="' . $category->name . '" >' . $category->name . '</a></h2></li>';

			if ( get_theme_mod( 'helpdesk_cat_description' ) == "yes" ) :
				if ( category_description( $category->cat_ID ) ) :
					echo '<div class="cat-description">'. wp_kses_post( category_description( $category->cat_ID ) ) .'</div>';
				endif;
			endif;

			if ( get_theme_mod( 'helpdesk_posts' ) ) :
				$posts_per_page = esc_attr( get_theme_mod( 'helpdesk_posts' ) );
			else :
				$posts_per_page = -1;
			endif;

			if ( get_theme_mod( 'helpdesk_order' ) == "name" ) :
				$order_by = 'name';
				$the_order = 'asc';
			else :
				$order_by = 'date';
				$the_order = 'desc';
			endif;

			$helpdesk_post_args = array(
				'post_type' => 'post',
				'tax_query' => array(
					array(
						'taxonomy' => 'category',
						'field' => 'term_id',
						'terms' => $category->term_id,
						'include_children' => false,
					)
				),
				'posts_per_page' => $posts_per_page,
				'orderby' => $order_by,
				'order' => $the_order
			);

			$helpdesk_posts = get_posts( $helpdesk_post_args );

			foreach( $helpdesk_posts AS $single_post ) :
				echo '<li class="post-name"><a href="'. get_permalink( $single_post->ID ) .'" rel="bookmark" title="'. get_the_title( $single_post->ID ) .'">'. get_the_title( $single_post->ID ) .'</a></li>';
			endforeach;

			if ( get_theme_mod( 'helpdesk_view_all' ) == "yes" ) {
				echo '<li class="view-all"><a href="'. get_category_link( $category->cat_ID ) .'" title="'. $category->name .'" >'. __( 'View All &raquo;', 'helpdesk' ). '</a></li>';
			}

			echo '</ul>';
		endforeach; ?>
	</div>

	</main><!-- #primary -->

	<?php do_action( 'helpdesk_after_content' ); ?>
<?php
get_footer();
