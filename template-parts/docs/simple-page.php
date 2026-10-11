<?php
/**
 * A single narrow page: title and content. Shared by the FAQ and Changelog page templates.
 *
 * @package knowx
 *
 * @var array $args {
 *     @type string $class Extra class for the main element.
 * }
 */

namespace KnowX\KnowX;

$knowx_class = isset( $args['class'] ) ? sanitize_html_class( $args['class'] ) : '';
?>
<main id="primary" class="knowx-narrow <?php echo esc_attr( $knowx_class ); ?>">
	<?php
	while ( have_posts() ) {
		the_post();
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="entry-header">
				<h1 class="entry-title"><?php the_title(); ?></h1>
			</header>
			<div class="entry-content">
				<?php the_content(); ?>
			</div>
		</article>
		<?php
	}
	?>
</main>
