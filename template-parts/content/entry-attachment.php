<?php
/**
 * Template part for displaying a post of post type 'attachment'
 *
 * @package knowx
 */

namespace KnowX\KnowX;

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
	<?php get_template_part( 'template-parts/content/entry_header', get_post_type() ); ?>

	<?php get_template_part( 'template-parts/content/entry_content', get_post_type() ); ?>

	<?php get_template_part( 'template-parts/content/entry_footer', get_post_type() ); ?>
</article><!-- #post-<?php the_ID(); ?> -->

<?php
if ( is_singular( get_post_type() ) ) {
	// Show attachment navigation only when the attachment has a parent.
	if ( ! empty( $post->post_parent ) ) {

		// TODO: There should be a WordPress core function for this, similar to `the_post_navigation()`.
		$attachment_navigation = '';

		ob_start();
		previous_image_link( 'thumbnail' );
		$prev_link = ob_get_clean();
		if ( ! empty( $prev_link ) ) {
			$attachment_navigation .= '<div class="nav-previous">';
			$attachment_navigation .= '<div class="post-navigation-sub"><span>' . esc_html__( 'Previous:', 'knowx' ) . '</span></div>';
			$attachment_navigation .= $prev_link;
			$attachment_navigation .= '</div>';
		}

		ob_start();
		next_image_link( 'thumbnail' );
		$next_link = ob_get_clean();
		if ( ! empty( $next_link ) ) {
			$attachment_navigation .= '<div class="nav-next">';
			$attachment_navigation .= '<div class="post-navigation-sub"><span>' . esc_html__( 'Next:', 'knowx' ) . '</span></div>';
			$attachment_navigation .= $next_link;
			$attachment_navigation .= '</div>';
		}

		if ( ! empty( $attachment_navigation ) ) {
			?>
			<nav class="navigation post-navigation" aria-label="<?php esc_attr_e( 'Post navigation', 'knowx' ); ?>">
				<h2 class="screen-reader-text"><?php esc_html_e( 'Post navigation', 'knowx' ); ?></h2>
				<div class="nav-links"><?php echo $attachment_navigation; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core image links plus escaped labels, built above. ?></div>
			</nav>
			<?php
		}
	}

	// Show comments only when the post type supports it and when comments are open or at least one comment exists.
	if ( post_type_supports( get_post_type(), 'comments' ) && ( comments_open() || get_comments_number() ) ) {
		comments_template();
	}
}
