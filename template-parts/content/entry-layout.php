<?php
/**
 * Template part for displaying a post
 *
 * @package knowx
 */

namespace KnowX\KnowX;

$classes = array(
	'entry',
	'entry-layout',
	'col-md-12',
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