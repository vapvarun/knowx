<?php
/**
 * Title: Still need help
 * Slug: knowx/article-feedback
 * Description: Two buttons at the end of an article for readers it did not help.
 * Categories: knowx-docs
 * Keywords: feedback, helpful, contact, edit
 *
 * @package knowx
 */

?>
<!-- wp:group {"className":"is-style-knowx-note"} -->
<div class="wp-block-group is-style-knowx-note"><!-- wp:paragraph -->
<p><strong><?php esc_html_e( 'Did this answer your question?', 'knowx' ); ?></strong> <?php esc_html_e( 'If not, tell us what was missing.', 'knowx' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#"><?php esc_html_e( 'Contact support', 'knowx' ); ?></a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="#"><?php esc_html_e( 'Suggest an edit', 'knowx' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->
