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
	if ( is_singular() ) {
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
				get_the_title()
			)
		);
	} else {
		echo '<p>';
		the_excerpt();
		echo '</p>';
	}
	wp_link_pages(
		array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'knowx' ),
			'after'  => '</div>',
		)
	);
	?>
</div><!-- .entry-content -->
