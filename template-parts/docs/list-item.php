<?php
/**
 * One article in a section listing.
 *
 * @package knowx
 */

namespace KnowX\KnowX;

$knowx_version = knowx_article_version( get_the_ID() );
?>
<li <?php post_class( 'knowx-article-card' ); ?>>
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
</li>
