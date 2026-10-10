<?php
/**
 * One article in a section listing.
 *
 * @package knowx
 */

namespace KnowX\KnowX;

$knowx_version = knowx_article_version( get_the_ID() );
?>
<?php
$knowx_trail = array();
if ( is_search() ) {
	$knowx_cats = get_the_category();
	if ( $knowx_cats ) {
		$knowx_ids = array_reverse( get_ancestors( $knowx_cats[0]->term_id, 'category', 'taxonomy' ) );
		foreach ( array_merge( $knowx_ids, array( $knowx_cats[0]->term_id ) ) as $knowx_id ) {
			$knowx_trail[] = get_cat_name( $knowx_id );
		}
	}
}
?>
<li <?php post_class( 'knowx-article-card' ); ?>>
	<?php if ( $knowx_trail ) : ?>
		<p class="knowx-crumb"><?php echo esc_html( implode( ' / ', $knowx_trail ) ); ?></p>
	<?php endif; ?>
	<h2><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
	<?php if ( '' !== $knowx_version ) : ?>
		<span class="knowx-version">
			<?php
			/* translators: %s: product version. */
			echo esc_html( sprintf( __( 'Version %s', 'knowx' ), $knowx_version ) );
			?>
		</span>
	<?php endif; ?>
	<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 28 ) ); ?></p>
	<?php if ( knowx_is_truthy( knowx_mod( 'knowx_show_updated' ) ) ) : ?>
		<p class="knowx-item-date">
			<?php
			/* translators: %s: date the article was last changed. */
			printf( esc_html__( 'Updated %s', 'knowx' ), '<time datetime="' . esc_attr( get_the_modified_date( 'c' ) ) . '">' . esc_html( get_the_modified_date() ) . '</time>' );
			?>
		</p>
	<?php endif; ?>
</li>
