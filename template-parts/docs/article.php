<?php
/**
 * The article inside the docs layout.
 *
 * @package knowx
 */

namespace KnowX\KnowX;

$knowx_version = knowx_article_version( get_the_ID() );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'knowx-article' ); ?>>
	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<p class="knowx-article-meta">
			<?php if ( '' !== $knowx_version && knowx_is_truthy( knowx_mod( 'knowx_show_version' ) ) ) : ?>
				<span class="knowx-version">
					<?php
					/* translators: %s: product version. */
					echo esc_html( sprintf( __( 'Version %s', 'knowx' ), $knowx_version ) );
					?>
				</span>
			<?php endif; ?>
			<?php if ( knowx_is_truthy( knowx_mod( 'knowx_show_updated' ) ) ) : ?>
				<span class="knowx-updated">
					<?php
					/* translators: %s: date the article was last changed. */
					printf( esc_html__( 'Last updated %s', 'knowx' ), '<time datetime="' . esc_attr( get_the_modified_date( 'c' ) ) . '">' . esc_html( get_the_modified_date() ) . '</time>' );
					?>
				</span>
			<?php endif; ?>
		</p>
	</header>

	<div class="entry-content">
		<?php
		the_content();
		wp_link_pages();
		?>
	</div>
</article>
