<?php
/**
 * Template part for displaying a post's metadata
 *
 * @package knowx
 */

namespace KnowX\KnowX;

// Get post object and type once.
$current_post  = get_post();
$post_type_obj = get_post_type_object( get_post_type() );

// Initialize output variables.
$time_string   = '';
$author_string = '';
$parent_string = '';

// Show date only when the post type is 'post' or has an archive.
if ( 'post' === $post_type_obj->name || $post_type_obj->has_archive ) {
	// Build time string only once.
	$is_modified = get_the_time( 'U' ) !== get_the_modified_time( 'U' );

	if ( $is_modified ) {
		$time_string = sprintf(
			'<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>',
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);
	} else {
		$time_string = sprintf(
			'<time class="entry-date published updated" datetime="%1$s">%2$s</time>',
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() )
		);
	}

	// Add permalink only once.
	$time_string = '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>';
}

// Show author only if the post type supports it.
if ( post_type_supports( $post_type_obj->name, 'author' ) ) {
	// Get author ID once.
	$author_id = $current_post->post_author;

	// Get author link once.
	$author_url  = get_author_posts_url( $author_id );
	$author_name = get_the_author();

	$author_string = sprintf(
		'<span class="author vcard"><a class="url fn n" href="%1$s">%2$s</a></span>',
		esc_url( $author_url ),
		esc_html( $author_name )
	);
}

// Show parent post only if available and if the post type is 'attachment'.
if ( ! empty( $current_post->post_parent ) && 'attachment' === get_post_type() ) {
	$parent_string = sprintf(
		'<a href="%1$s">%2$s</a>',
		esc_url( get_permalink( $current_post->post_parent ) ),
		esc_html( get_the_title( $current_post->post_parent ) )
	);
}

?>

<div class="entry-meta">
	<?php
	if ( ! empty( $author_string ) ) {
		?>
		<span class="posted-by">
			<?php
			echo get_avatar( get_the_author_meta( 'user_email' ), '26' );
			/* translators: %s: post author */
			$author_byline = _x( 'By %s', 'post author', 'knowx' );
			if ( ! empty( $time_string ) ) {
				/* translators: %s: post author */
				$author_byline = _x( 'by %s', 'post author', 'knowx' );
			}
			printf(
				esc_html( $author_byline ),
				$author_string // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
			?>
		</span>
		<?php
	}

	if ( ! empty( $parent_string ) ) {
		?>
		<span class="posted-in">
			<?php
			/* translators: %s: post parent title */
			$parent_note = _x( 'In %s', 'post parent', 'knowx' );
			if ( ! empty( $time_string ) || ! empty( $author_string ) ) {
				/* translators: %s: post parent title */
				$parent_note = _x( 'in %s', 'post parent', 'knowx' );
			}
			printf(
				esc_html( $parent_note ),
				$parent_string // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
			?>
		</span>
		<?php
	}

	if ( ! empty( $time_string ) ) {
		?>
		<span class="posted-on">
			<?php
			echo ' ' . wp_kses_post( $time_string );
			?>
		</span>
		<?php
	}
	knowx_posted_on();
	?>
</div><!-- .entry-meta -->
<?php
