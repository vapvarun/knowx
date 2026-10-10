<?php
/**
 * Title: Recently updated
 * Slug: knowx/recently-updated
 * Description: The five articles changed last, with the date.
 * Categories: knowx-docs
 * Keywords: recent, updated, latest, modified
 *
 * @package knowx
 */

?>
<!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Recently updated', 'knowx' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:query {"queryId":0,"query":{"perPage":5,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"modified","author":"","search":"","exclude":[],"sticky":"exclude","inherit":false}} -->
<div class="wp-block-query"><!-- wp:post-template -->
<!-- wp:post-title {"isLink":true,"fontSize":"medium"} /-->

<!-- wp:post-date {"displayType":"modified","fontSize":"small"} /-->
<!-- /wp:post-template --></div>
<!-- /wp:query -->
