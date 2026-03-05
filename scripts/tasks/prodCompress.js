/* eslint-env es6 */
'use strict';

import path from 'node:path';
import fs from 'node:fs';
import fse from 'fs-extra';
import archiver from 'archiver';

import { prodThemePath, rootPath } from '../lib/constants.js';
import { getThemeConfig } from '../lib/utils.js';

/**
 * Gulp-free production compression task: create a zip from prodThemePath
 * @param {Function} done callback when finished
 * @param {string|null} customDestination optional custom destination path for the zip file
 */
export default function prodCompress( done, customDestination = null ) {
	try {
		const config = getThemeConfig();

		if ( ! config.export.compress ) {
			return done();
		}

		const zipName = `${ config.theme.slug }.zip`;
		let destZip;

		if ( customDestination ) {
			// Custom destination provided (e.g., dist/knowx.zip)
			destZip = path.resolve( rootPath, customDestination );
			// Ensure the directory exists
			const destDir = path.dirname( destZip );
			fse.ensureDirSync( destDir );
		} else {
			// Default behavior: zip in dist/ folder
			const destDir = path.join( rootPath, 'dist' );
			destZip = path.join( destDir, zipName );
			fse.ensureDirSync( destDir );
		}

		const output = fs.createWriteStream( destZip );
		const archive = archiver( 'zip', { zlib: { level: 9 } } );

		output.on( 'close', () => {
			console.log( `Created zip: ${ destZip }` );
			done();
		} );
		output.on( 'error', ( err ) => done( err ) );
		archive.on( 'error', ( err ) => done( err ) );

		archive.pipe( output );
		// Add contents of prodThemePath to the root of the zip
		archive.directory( prodThemePath, false );
		archive.finalize();
	} catch ( e ) {
		done( e );
	}
}
