<?php
/**
 * Template part for displaying a post
 *
 * @package helpdesk
 */

namespace Helpdesk\Helpdesk;

$post_per_row = 'col-md-' . get_theme_mod( 'post_per_row', helpdesk_defaults( 'post-per-row' ) );

$classes = [
    'entry',
    'entry-layout',
    $post_per_row
];

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>
	<div class="helpdesk-post-content-wrapper">
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