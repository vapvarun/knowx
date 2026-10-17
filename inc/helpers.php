<?php
/**
 * Setting helpers.
 *
 * @package knowx
 */

/**
 * Returns a theme setting with its registered default.
 *
 * The default is the one declared on the Customizer field, so it lives in one place.
 *
 * @param string $id Setting id.
 * @return mixed Saved value, or the field default when nothing is saved.
 */
function knowx_mod( $id ) {
	static $defaults = array();

	if ( ! isset( $defaults[ $id ] ) && ! array_key_exists( $id, $defaults ) ) {
		$defaults[ $id ] = false;
		foreach ( \KnowX\KnowX\Customizer_Framework\Component::get_fields() as $field ) {
			if ( ( $field['settings'] ?? '' ) === $id ) {
				$defaults[ $id ] = $field['default'] ?? false;
				break;
			}
		}
	}

	return get_theme_mod( $id, $defaults[ $id ] );
}

if ( ! function_exists( 'knowx_is_truthy' ) ) {
	/**
	 * Whether a setting value means "on".
	 *
	 * Accepts booleans, integers and the strings 1, on, yes, true and enable (case
	 * insensitive). Unlike wp_validate_boolean(), the string "off" is false.
	 *
	 * @param mixed $value Raw setting value.
	 * @return bool True when the value is truthy.
	 */
	function knowx_is_truthy( $value ): bool {
		if ( is_bool( $value ) ) {
			return $value;
		}
		if ( is_int( $value ) ) {
			return 0 !== $value;
		}
		if ( is_string( $value ) ) {
			$normalized = strtolower( trim( $value ) );
			if ( in_array( $normalized, array( '1', 'on', 'yes', 'true', 'enable' ), true ) ) {
				return true;
			}
			if ( in_array( $normalized, array( '0', '', 'off', 'no', 'false', 'disable' ), true ) ) {
				return false;
			}
		}
		return (bool) $value;
	}
}

/**
 * Accessible name for the next search form on the page.
 *
 * Every search landmark needs its own name: the first is the site search, the others are numbered.
 *
 * @return string Label.
 */
function knowx_search_form_label() {
	static $count = 0;
	++$count;

	/* translators: %d: position of the search form on the page. */
	return 1 === $count ? __( 'Site search', 'knowx' ) : sprintf( __( 'Search %d', 'knowx' ), $count );
}

/**
 * Keeps the Content Width setting between 1000 and 1920 pixels.
 *
 * Below 1000 the article beside the section tree and the contents column is too narrow to read.
 * Used when the setting is saved and when it is read, so a width saved by an earlier version is corrected too.
 *
 * @param mixed $value Saved or submitted width, for example 1170px.
 * @return mixed Width in pixels, or a non-scalar value untouched.
 */
function knowx_sanitize_container_width( $value ) {
	// Core passes a stdClass sentinel when it checks whether the setting has a saved value.
	if ( ! is_scalar( $value ) ) {
		return $value;
	}

	$width = (int) $value;

	return ( $width > 0 ? min( 1920, max( 1000, $width ) ) : 1170 ) . 'px';
}
