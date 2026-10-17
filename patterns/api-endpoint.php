<?php
/**
 * Title: API endpoint
 * Slug: knowx/api-endpoint
 * Description: Method, URL, parameters and an example request.
 * Categories: knowx-docs
 * Block Types: core/post-content
 * Post Types: post
 * Keywords: api, endpoint, reference, rest
 *
 * @package knowx
 */

?>
<!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'List tickets', 'knowx' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'Returns the tickets the key can see, newest first.', 'knowx' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:code -->
<pre class="wp-block-code"><code>GET /v2/tickets</code></pre>
<!-- /wp:code -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php esc_html_e( 'Parameters', 'knowx' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:table -->
<figure class="wp-block-table"><table class="has-fixed-layout"><thead><tr><th><?php esc_html_e( 'Name', 'knowx' ); ?></th><th><?php esc_html_e( 'Type', 'knowx' ); ?></th><th><?php esc_html_e( 'Description', 'knowx' ); ?></th></tr></thead><tbody><tr><td><?php esc_html_e( 'status', 'knowx' ); ?></td><td><?php esc_html_e( 'string', 'knowx' ); ?></td><td><?php esc_html_e( 'Filter by status, for example open.', 'knowx' ); ?></td></tr><tr><td><?php esc_html_e( 'page', 'knowx' ); ?></td><td><?php esc_html_e( 'integer', 'knowx' ); ?></td><td><?php esc_html_e( 'Page number. Default 1.', 'knowx' ); ?></td></tr></tbody></table></figure>
<!-- /wp:table -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php esc_html_e( 'Example', 'knowx' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:code -->
<pre class="wp-block-code"><code>curl https://api.example.com/v2/tickets -H "Authorization: Bearer YOUR_API_KEY"</code></pre>
<!-- /wp:code -->

