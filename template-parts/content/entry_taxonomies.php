<?php
/**
 * Template part for displaying a post's taxonomy terms
 *
 * @package knowx
 */

namespace KnowX\KnowX;

$knowx_post = get_post();

$taxonomies = wp_list_filter(
	get_object_taxonomies( $knowx_post, 'objects' ),
	array(
		'public' => true,
	)
);

// Print the wrapper only when at least one term list exists.
ob_start();
?>
<div class="entry-taxonomies">
	<?php
	// Show terms for all taxonomies associated with the post.
	foreach ( $taxonomies as $taxo ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- template part variables, local to the include.

		/* translators: separator between taxonomy terms */
		$separator = _x( ', ', 'list item separator', 'knowx' );

		// Categories are printed once, in the entry meta line.
		if ( 'category' === $taxo->name ) {
			continue;
		}

		switch ( $taxo->name ) {
			case 'post_tag':
				$class = 'tag-links term-links';
				$list  = get_the_tag_list( '', esc_html( $separator ), '', $knowx_post->ID );
				/* translators: %s: list of taxonomy terms */
				$placeholder_text = __( 'Tagged %s', 'knowx' );
				break;
			default:
				$class            = str_replace( '_', '-', $taxo->name ) . '-links term-links';
				$list             = get_the_term_list( $knowx_post->ID, $taxo->name, '', esc_html( $separator ), '' );
				$placeholder_text = sprintf(
					/* translators: %s: taxonomy name */
					__( '%s:', 'knowx' ),
					$taxo->labels->name
				);
		}

		if ( empty( $list ) ) {
			continue;
		}
		?>
		<span class="<?php echo esc_attr( $class ); ?>">
			<?php
			printf(
				esc_html( $placeholder_text ),
				wp_kses_post( $list )
			);
			?>
		</span>
		<?php
	}
	?>
</div><!-- .entry-taxonomies -->
<?php
$knowx_taxonomies = ob_get_clean();
if ( false !== strpos( $knowx_taxonomies, 'term-links' ) ) {
	echo $knowx_taxonomies; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built above from escaped term lists.
}
