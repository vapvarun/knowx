<?php
/**
 * Template part for displaying a post's content
 *
 * @package knowx
 */

namespace KnowX\KnowX;

?>

<div class="entry-content">
	<?php
	// Full-width pages print no visible title; keep a page heading for screen readers unless the content brings its own.
	if ( false === stripos( get_the_content(), '<h1' ) ) {
		the_title( '<h1 class="screen-reader-text">', '</h1>' );
	}

	the_content(
		sprintf(
			wp_kses(
				/* translators: %s: Name of current post. Only visible to screen readers */
				__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'knowx' ),
				array(
					'span' => array(
						'class' => array(),
					),
				)
			),
			esc_html( get_the_title() )
		)
	);

	wp_link_pages(
		array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'knowx' ),
			'after'  => '</div>',
		)
	);
	?>
</div><!-- .entry-content -->
