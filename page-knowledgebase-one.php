<?php
/**
 * Template Name: Knowledge Base Layout One
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

$hero_title   = knowx_mod( 'hero_section_heading' );
$hero_content = knowx_mod( 'hero_section_content' );

$support_title   = knowx_mod( 'support_section_heading' );
$support_content = knowx_mod( 'support_section_content' );

$support_button_text = knowx_mod( 'support_section_button_text' );
$support_button_link = knowx_mod( 'support_section_button_link_url' );

$hero_image = get_the_post_thumbnail_url( get_queried_object_id(), 'full' );
$categories = knowx_kb_top_categories();
$featured   = knowx_kb_featured_posts();
$recent     = knowx_kb_recent_posts();
$column     = empty( $featured ) ? 'col-md-12' : 'col-md-6';

?>
	<main id="primary" class="site-main">

	<div class="knowx-section-hero"<?php echo $hero_image ? ' style="background-image: url(\'' . esc_url( $hero_image ) . '\');"' : ''; ?>>
		<div class="container">
			<?php if ( ! empty( $hero_title ) ) { ?>
				<h1 class="entry-title"><?php echo esc_html( $hero_title ); ?></h1>
				<?php
			} else {
				get_template_part( 'template-parts/content/page_header' );
			}
			?>
			<?php if ( ! empty( $hero_content ) ) { ?>
				<div class="entry-summary">
					<?php echo esc_html( $hero_content ); ?>
				</div><!-- .entry-summary -->
			<?php } else { ?>
				<div class="entry-summary">
					<?php the_excerpt(); ?>
				</div><!-- .entry-summary -->
			<?php } ?>
			<div class="knowx-hero-section-search">
				<?php get_search_form(); ?>
			</div>
		</div>
	</div>

	<?php do_action( 'knowx_before_content' ); ?>

	<div class="container">

		<?php if ( ! empty( $categories ) ) : ?>
		<div id="knowx-categories-columns-three" class="knowx-knowledgebase-one">
			<?php foreach ( $categories as $category ) : ?>
				<ul class="cat-list">
					<li class="cat-name"><h2><a href="<?php echo esc_url( $category['url'] ); ?>"><?php echo esc_html( $category['name'] ); ?></a></h2></li>
				</ul>
			<?php endforeach; ?>
		</div><!-- #knowx-categories-columns-three -->
		<?php endif; ?>

		<?php if ( ! empty( $featured ) || ! empty( $recent ) ) : ?>
		<div class="knowx-post-section">
			<div class="row">
				<?php if ( ! empty( $featured ) ) : ?>
				<div class="<?php echo esc_attr( $column ); ?>">
					<div class="knowx-popular-posts">
						<h3><?php esc_html_e( 'Featured Articles', 'knowx' ); ?></h3>
						<ul>
							<?php foreach ( $featured as $item ) : ?>
								<li><a href="<?php echo esc_url( $item['url'] ); ?>" rel="bookmark"><?php echo esc_html( $item['title'] ); ?></a></li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
				<?php endif; ?>
				<?php if ( ! empty( $recent ) ) : ?>
				<div class="<?php echo esc_attr( $column ); ?>">
					<div class="knowx-recent-posts">
						<h3><?php esc_html_e( 'Recent Articles', 'knowx' ); ?></h3>
						<ul>
							<?php foreach ( $recent as $item ) : ?>
								<li><a href="<?php echo esc_url( $item['url'] ); ?>" rel="bookmark"><?php echo esc_html( $item['title'] ); ?></a></li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
				<?php endif; ?>
			</div><!-- .row -->
		</div><!-- .knowx-post-section -->
		<?php endif; ?>

	</div><!-- .container -->

	<div class="knowx-support-section">
		<div class="container">
			<div class="knowx-support-heading">
				<h2 class="knowx-support-title">
					<?php echo esc_html( ! empty( $support_title ) ? $support_title : __( 'Can\'t find what you\'re looking for?', 'knowx' ) ); ?>
				</h2>
			</div>
			<div class="knowx-support-content">
				<p><?php echo esc_html( ! empty( $support_content ) ? $support_content : __( 'Search the articles above, or get in touch and we will help you.', 'knowx' ) ); ?></p>
			</div>
			<?php if ( ! empty( $support_button_text ) && ! empty( $support_button_link ) ) : ?>
			<div class="knowx-support-button">
				<a href="<?php echo esc_url( $support_button_link ); ?>"><?php echo esc_html( $support_button_text ); ?></a>
			</div>
			<?php endif; ?>
		</div><!-- .container -->
	</div><!-- .knowx-support-section -->
</main><!-- #primary -->

	<?php do_action( 'knowx_after_content' ); ?>
<?php
get_footer();
