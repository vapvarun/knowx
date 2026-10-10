<?php
/**
 * Template part for displaying a post
 *
 * @package knowx
 */

namespace KnowX\KnowX;

$post_per_row    = 'col-md-' . knowx_mod( 'post_per_row' );
$post_box_layout = knowx_mod( 'post_box_view_layout' );

if ( $post_box_layout ) {
	$layout_class = 'post-box-layout';
} else {
	$layout_class = '';
}

$classes = array(
	'entry',
	'entry-layout',
	$post_per_row,
	$layout_class,
);

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>
	<div class="knowx-post-content-wrapper">
		<?php
		get_template_part( 'template-parts/content/entry_header', get_post_type() );

		if ( is_search() ) {
			get_template_part( 'template-parts/content/entry_summary', get_post_type() );
		} else {
			get_template_part( 'template-parts/content/entry_content', get_post_type() );
		}
		if ( is_singular() ) {
			get_template_part( 'template-parts/content/entry_footer', get_post_type() );
		}
		?>
	</div>
</article><!-- #post-<?php the_ID(); ?> -->