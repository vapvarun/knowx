<?php
/**
 * KnowX\KnowX\Image_Sizes\Component class
 *
 * @package knowx
 */

namespace KnowX\KnowX\Image_Sizes;

use KnowX\KnowX\Component_Interface;
use WP_Post;

use function KnowX\KnowX\knowx;
use function add_filter;

/**
 * Class for managing responsive image sizes.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'image_sizes';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_filter( 'wp_calculate_image_sizes', array( $this, 'filter_content_image_sizes_attr' ), 10, 2 );
		add_filter( 'get_header_image_tag', array( $this, 'filter_header_image_tag' ), 10, 3 );
		add_filter( 'wp_get_attachment_image_attributes', array( $this, 'filter_post_thumbnail_sizes_attr' ), 10, 3 );
	}

	/**
	 * Adds custom image sizes attribute to enhance responsive image functionality for content images.
	 *
	 * @param string $sizes A source size value for use in a 'sizes' attribute.
	 * @param array  $size  Image size. Accepts an array of width and height
	 *                      values in pixels (in that order).
	 * @return string A source size value for use in a content image 'sizes' attribute.
	 */
	public function filter_content_image_sizes_attr( string $sizes, array $size ): string {
		// Small and medium images keep core's value, which caps the size at the image width.
		if ( 740 > $size[0] ) {
			return $sizes;
		}

		$content = $this->content_width();

		return '100vw' === $content ? '100vw' : '(min-width: 960px) ' . $content . ', 100vw';
	}

	/**
	 * Filters the `sizes` value in the header image markup.
	 *
	 * @param string $html   The HTML image tag markup being filtered.
	 * @param object $header The custom header object returned by 'get_custom_header()'.
	 * @param array  $attr   Array of the attributes for the image tag.
	 * @return string The filtered header image HTML.
	 */
	public function filter_header_image_tag( string $html, $header, array $attr ): string {
		if ( isset( $attr['sizes'] ) ) {
			$html = str_replace( $attr['sizes'], '100vw', $html );
		}

		return $html;
	}

	/**
	 * Adds custom image sizes attribute to enhance responsive image functionality for post thumbnails.
	 *
	 * @param array        $attr       Attributes for the image markup.
	 * @param WP_Post      $attachment Attachment post object.
	 * @param string|array $size       Registered image size or flat array of height and width dimensions.
	 * @return array The filtered attributes for the image markup.
	 */
	public function filter_post_thumbnail_sizes_attr( array $attr, WP_Post $attachment, $size ): array {
		// Only the large post thumbnails; logos, widget thumbnails and small sizes keep core's value.
		if ( 'post-thumbnail' !== $size && 'knowx-featured' !== $size ) {
			return $attr;
		}

		$content       = $this->content_width();
		$attr['sizes'] = '100vw' === $content ? '100vw' : '(min-width: 960px) ' . $content . ', 100vw';

		return $attr;
	}

	/**
	 * Width of the content column on wide screens, from the layout the site prints.
	 *
	 * @return string `100vw` without a sidebar, `75vw` with one and `50vw` with two.
	 */
	protected function content_width(): string {
		$layout = knowx_mod( 'sidebar_option' );
		$right  = knowx()->is_right_sidebar_active();
		$left   = knowx()->is_left_sidebar_active();

		if ( 'both' === $layout && $left && $right ) {
			return '50vw';
		}
		if ( ( 'right' === $layout && $right ) || ( 'left' === $layout && $left ) ) {
			return '75vw';
		}

		return '100vw';
	}
}
