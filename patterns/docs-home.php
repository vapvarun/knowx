<?php
/**
 * Title: Docs home
 * Slug: knowx/docs-home
 * Description: A hero with search, product columns and the newest articles, built from blocks.
 * Categories: knowx-docs
 * Keywords: home, landing, hero, products
 *
 * @package knowx
 */

?>
<!-- wp:group {"align":"full","className":"knowx-pattern-hero","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull knowx-pattern-hero"><!-- wp:heading {"textAlign":"center","level":1} -->
<h1 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'How can we help?', 'knowx' ); ?></h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center"><?php esc_html_e( 'Guides, references and answers for every product.', 'knowx' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:search {"label":"Search","showLabel":false,"placeholder":"Search the documentation","buttonText":"Search"} /--></div>
<!-- /wp:group -->

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"className":"is-style-knowx-note"} -->
<div class="wp-block-group is-style-knowx-note"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php esc_html_e( 'Product one', 'knowx' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'One sentence about what this product does.', 'knowx' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"className":"is-style-knowx-note"} -->
<div class="wp-block-group is-style-knowx-note"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php esc_html_e( 'Product two', 'knowx' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'One sentence about what this product does.', 'knowx' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Latest articles', 'knowx' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:latest-posts {"postsToShow":5,"displayPostDate":true} /-->
