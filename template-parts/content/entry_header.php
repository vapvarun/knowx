<?php
/**
 * Template part for displaying a post's header
 *
 * @package knowx
 */

namespace KnowX\KnowX;

?>

<header class="entry-header">
	<?php
	if ( ! is_search() ) {
		get_template_part( 'template-parts/content/entry_thumbnail', get_post_type() );
	}
	?>

	<?php if ( has_post_thumbnail() && ! is_search() ) : ?>
		<div class="post-categories"><?php the_category( ' ' ); ?></div>
	<?php endif; ?>

	<?php
	if ( ! is_singular() ) {
		get_template_part( 'template-parts/content/entry_title', get_post_type() );
	}

	get_template_part( 'template-parts/content/entry_meta', get_post_type() );
	?>
</header><!-- .entry-header -->
