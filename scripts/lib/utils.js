/* eslint-env es6 */
'use strict';

import log from 'fancy-log';
import colors from 'ansi-colors';
import { rimraf } from 'rimraf';
import { mkdirp } from 'mkdirp';
import fs from 'fs';
import { Transform } from 'node:stream';

/**
 * Internal dependencies
 */
import {
	nameFieldDefaults,
	prodThemePath,
	isProd,
	rootPath,
} from './constants.js';

import config from '../../config/themeConfig.js';

/**
 * Get theme configuration.
 *
 * @return {Object} Theme configuration data.
 */
export function getThemeConfig() {
	if ( ! config.theme.slug ) {
		config.theme.slug = config.theme.name
			.toLowerCase()
			.replace( /[\s_]+/g, '-' )
			.replace( /[^a-z0-9-]+/g, '' );
	}

	if ( ! config.theme.underscoreCase ) {
		config.theme.underscoreCase = config.theme.slug.replace( /-/g, '_' );
	}

	if ( ! config.theme.constant ) {
		config.theme.constant = config.theme.underscoreCase.toUpperCase();
	}

	if ( ! config.theme.camelCase ) {
		config.theme.camelCase = config.theme.slug
			.split( '-' )
			.map( ( part ) => part[ 0 ].toUpperCase() + part.substring( 1 ) )
			.join( '' );
	}

	if ( ! config.theme.camelCaseVar ) {
		config.theme.camelCaseVar =
			config.theme.camelCase[ 0 ].toLowerCase() +
			config.theme.camelCase.substring( 1 );
	}

	return config;
}

/**
 * Processes a buffer by converting it to a string, performing replacements, and converting it back to a buffer.
 *
 * @param {Buffer}        content                     - The buffer to be processed.
 * @param {Array<Object>} replacements                - An array of objects where each object contains 'searchValue' to find and 'replaceValue' to replace within the content string.
 * @param {string|RegExp} replacements[].searchValue  - The value to search for within the content. It can be a string or regular expression.
 * @param {string}        replacements[].replaceValue - The value to replace the found content with.
 * @return {Buffer} - A new buffer with applied replacements.
 */
function processBuffer( content, replacements ) {
	let contentStr = content.toString(); // Default UTF-8
	replacements.forEach( ( { searchValue, replaceValue } ) => {
		contentStr = contentStr.replace( searchValue, replaceValue );
	} );
	// eslint-disable-next-line no-undef
	return Buffer.from( contentStr ); // Default UTF-8
}

/**
 * Creates a transform stream that replaces occurrences of a search value with a replacement value.
 * This is a simpler alternative to the 'replacestream' package.
 *
 * @param {string|RegExp} searchValue  - The value to search for in the stream chunks.
 * @param {string} replaceValue - The value to replace the found occurrences with.
 * @return {Transform} A transform stream that performs the replacements.
 */
function createReplaceStream( searchValue, replaceValue ) {
	let buffer = '';

	return new Transform({
		transform( chunk, encoding, callback ) {
			const str = buffer + chunk.toString();
			const replaced = str.replace( searchValue, replaceValue );

			// Keep a small buffer in case the search pattern spans chunk boundaries
			const maxPatternLength = searchValue instanceof RegExp ?
				100 : // Reasonable buffer size for regex
				searchValue.length;

			if (str.length > maxPatternLength) {
				// Push most of the processed content, but keep a small buffer
				// in case the pattern spans across chunks
				buffer = str.slice(str.length - maxPatternLength);
				this.push(replaced.slice(0, replaced.length - maxPatternLength));
			} else {
				buffer = str;
			}

			callback();
		},
		flush( callback ) {
			// Process any remaining buffered content
			if (buffer.length > 0) {
				this.push(buffer.replace(searchValue, replaceValue));
			}
			callback();
		}
	});
}

/**
 * Creates the production theme directory at the specified path, removing any existing directory at that location before creation.
 *
 * Logs the creation process and ensures that the directory is freshly created even if it previously existed.
 *
 * @return {void} This function does not return any value.
 */
export function createProdDir() {
	log(
		colors.green(
			`Creating the production theme directory ${ prodThemePath }`
		)
	);
	if ( fs.existsSync( prodThemePath ) ) {
		rimraf.sync( prodThemePath );
	}
	mkdirp( prodThemePath );
}

/**
 * Determine if a config value is defined
 * @param {string} configValueLocation a config value path to search for, e.g. 'config.theme.slug'
 * @return {boolean} whethere the config value is defined
 */
export function configValueDefined( configValueLocation ) {
	if ( 0 === configValueLocation.length ) {
		return false;
	}

	let themeConfig = getThemeConfig();

	const configValueLocationArray = configValueLocation.split( '.' );

	if ( 'config' === configValueLocationArray[ 0 ] ) {
		configValueLocationArray.shift();
	}

	for ( const currentValueLocation of configValueLocationArray ) {
		if (
			! Object.prototype.hasOwnProperty.call(
				themeConfig,
				currentValueLocation
			)
		) {
			return false;
		}
		themeConfig = themeConfig[ currentValueLocation ];
	}

	return true;
}

/**
 * Replaces all occurrences of a specific inline CSS class name in a given string
 * with another class name.
 *
 * @param {string} code - The code containing inline CSS where the replacement
 *                      is to be performed.
 * @return {string} The modified code with the specified inline CSS class name
 * replaced.
 */
export function replaceInlineCSS( code ) {
	if ( ! isProd ) {
		return code;
	}
	const searchValue = nameFieldDefaults.slug;
	const replaceValue = config.theme.slug;
	return code.replace( new RegExp( searchValue, 'g' ), replaceValue );
}

/**
 * Converts a hyphenated string to camelCase notation.
 *
 * @param {string} str - The input string containing words separated by hyphens.
 * @return {string} The converted string in camelCase notation.
 */
function toCamelCase( str ) {
	return str
		.toLowerCase()
		.replace( /-([a-z])/g, ( match, group1 ) => group1.toUpperCase() );
}

/**
 * Replaces inline JavaScript code by substituting specified placeholders with corresponding values.
 *
 * @param {string} code - The inline JavaScript code that contains placeholders to be replaced.
 * @return {string} - The modified JavaScript code with placeholders replaced by their respective values.
 */
export function replaceInlineJS( code ) {
	if ( ! isProd ) {
		return code;
	}
	const replacements = [
		{
			searchValue: nameFieldDefaults.slug,
			replaceValue: config.theme.slug,
		},
		{
			searchValue: toCamelCase( nameFieldDefaults.slug ),
			replaceValue: toCamelCase( config.theme.slug ),
		},
	];

	return code.replace(
		new RegExp(
			replacements.map( ( r ) => r.searchValue ).join( '|' ),
			'g'
		),
		( match ) => {
			const replacement = replacements.find( ( r ) =>
				new RegExp( r.searchValue ).test( match )
			);
			return replacement ? replacement.replaceValue : match;
		}
	);
}
