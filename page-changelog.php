<?php
/**
 * Template Name: Changelog
 * Description: Release notes, newest first
 * Template Post Type: page
 *
 * @package knowx
 */

namespace KnowX\KnowX;

get_header();

knowx()->print_styles( 'knowx-content', 'knowx-docs' );

?>
<?php do_action( 'knowx_sub_header' ); ?>
<?php do_action( 'knowx_before_content' ); ?>

<?php get_template_part( 'template-parts/docs/simple-page', null, array( 'class' => 'knowx-narrow-changelog' ) ); ?>

<?php do_action( 'knowx_after_content' ); ?>
<?php
get_footer();
