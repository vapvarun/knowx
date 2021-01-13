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

$hero_title = get_theme_mod( 'hero_section_heading' );
$hero_content = get_theme_mod( 'hero_section_content' );

$support_title = get_theme_mod( 'support_section_heading' );
$support_content = get_theme_mod( 'support_section_content' );

$support_button_text = get_theme_mod( 'support_section_button_text' );
$support_button_link = get_theme_mod( 'support_section_button_link_url' );

?>
	</div><!--close .container-->
	<div class="helpdesk-section-hero">
		<div class="container">
			<?php if ( ! empty( $hero_title ) ) { ?>
				<h1 class="entry-title"><?php echo $hero_title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h1>
			<?php }else { ?>
				<h1 class="entry-title"><?php esc_html_e( 'Create a Knowledge Base with Helpdesk', 'helpdesk' ); ?></h1>
			<?php } ?>
			<?php if ( ! empty( $hero_content ) ) { ?>
				<div class="entry-summary">
					<?php echo $hero_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div><!-- .entry-summary -->
			<?php }else { ?>
				<div class="entry-summary">
					<p><?php esc_html_e( 'Creating a knowledge base website or wiki can be an effective way to support your users, members, clients, and customers..', 'helpdesk' ); ?></p>
				</div><!-- .entry-summary -->
			<?php } ?>
			<div class="helpdesk-hero-section-search">
				<?php get_search_form(); ?>
			</div>
		</div>
	</div>
	
	<?php do_action( 'helpdesk_before_content' ); ?>
	
	<main id="primary" class="site-main">

	<div class="container">

		<div id="helpdesk-categories-columns-three" class="helpdesk-knowledgebase-one">
			<?php $exclude_cats = get_post_meta( get_the_ID(), 'helpdesk-exclude-cats', true );
			if ( !empty( $exclude_cats ) ) {
				$exclude = esc_attr( $exclude_cats );
			} else if ( get_theme_mod( 'helpdesk_exclude_posts' ) ) :
				$exclude = esc_attr( get_theme_mod( 'helpdesk_exclude_posts' ) );
			else :
				$exclude = '';
			endif;
			$helpdesk_cat_args = array(
				'parent' => 0,
				'hide_empty' => 0,
				'exclude' => $exclude,
				'orderby' => 'name',
				'order' => 'asc'
			);
			$helpdesk_cats = get_categories( $helpdesk_cat_args );

			foreach ( $helpdesk_cats as $category ) :
				echo '<ul class="cat-list"><li class="cat-name"><h2><a href="' . get_category_link( $category->cat_ID ) . '" title="' . $category->name . '" >' . $category->name . '</a></h2></li>';
				echo '</ul>';
			endforeach; ?>
		</div><!-- #helpdesk-categories-columns-three -->

		<div class="helpdesk-post-section">
			<div class="row">
				<div class="col-md-6">
					<div class="helpdesk-popular-posts">
					<h3><?php esc_html_e( 'Popular Articles', 'helpdesk' ); ?></h3>
					<?php
						query_posts('meta_key=post_views_count&orderby=meta_value_num&order=DESC&posts_per_page=5');
						if (have_posts()) : while (have_posts()) : the_post();
						?><ul>
							<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
						</ul>
						<?php
						endwhile; endif;
						wp_reset_query();
					?>
					</div>
				</div>
				<div class="col-md-6">
					<div class="helpdesk-recent-posts">
					<h3><?php esc_html_e( 'Recent Articles', 'helpdesk' ); ?></h3>
					<?php
						$related = get_posts( array( 'numberposts' => 5 ) );
							if( $related ) foreach( $related as $post ) {
							setup_postdata($post); ?>
								<ul> 
									<li><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></li>
								</ul>   
							<?php }
						wp_reset_postdata(); 
					?>
					</div>
				</div>
			</div><!-- .row -->
		</div><!-- .helpdesk-post-section -->

	</div><!-- .container -->

	<div class="helpdesk-support-section">
		<div class="container">
			<div class="helpdesk-support-heading">
			<?php if ( ! empty( $support_title ) ) { ?>
					<h2 class="helpdesk-support-title"><?php echo $support_title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
				<?php }else { ?>
					<h2 class="helpdesk-support-title"><?php esc_html_e( 'Can\'t find what you\'re looking for?', 'helpdesk' ); ?></h2>
				<?php } ?>
			</div>
			<div class="helpdesk-support-content">
				<?php if ( ! empty( $support_content ) ) { ?>
					<p><?php echo $support_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
				<?php }else { ?>
					<p><?php esc_html_e( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.', 'helpdesk' ); ?></p>
				<?php } ?>
			</div>
			<div class="helpdesk-support-button">
				<?php if ( ! empty( $support_button_text ) && ! empty( $support_button_link ) ) { ?>
					<a href="<?php esc_url( $support_button_link ); ?>">
						<?php echo $support_button_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</a>
				<?php }else { ?>
					<a href="#"><?php esc_html_e( 'Contact Support' ); ?></a>
				<?php } ?>
			</div>
		</div><!-- .helpdesk-support-section -->
	</div><!-- .container -->
	</main><!-- #primary -->

	<?php do_action( 'helpdesk_after_content' ); ?>
<?php
get_footer();