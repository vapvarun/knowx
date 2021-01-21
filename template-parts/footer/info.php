<?php
/**
 * Template part for displaying the footer info
 *
 * @package helpdesk
 */

namespace Helpdesk\Helpdesk;

?>
<div class="site-info">
    <div class="container">	
        <?php echo helpdesk_footer_custom_text(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    </div>

	<?php
    if ( function_exists( 'the_privacy_policy_link' ) ) {
        the_privacy_policy_link();
    }
    ?>
</div><!-- .site-info -->
