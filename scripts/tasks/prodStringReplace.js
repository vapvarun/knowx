/* eslint-env es6 */
'use strict';

import path from 'node:path';
import fse from 'fs-extra';

import {
	isProd,
	rootPath,
	prodThemePath,
	paths,
} from '../lib/constants.js';
import { globFiles, writeFileEnsured } from '../lib/filepipe.js';

/**
 * Copy the selected export files into prod directory.
 * @param {Function} done
 */
export default async function prodStringReplace( done ) {
	try {
		if ( ! isProd ) {
			return done();
		}

		const files = await globFiles( paths.export.stringReplaceSrc );

		await Promise.all(
			files.map( async ( srcFile ) => {
				const rel = path.relative( rootPath, srcFile );
				const destFile = path.join( prodThemePath, rel );
				const content = await fse.readFile( srcFile, 'utf8' );
				await writeFileEnsured( destFile, content, 'utf8' );
			} )
		);

		return done();
	} catch ( e ) {
		return done( e );
	}
}
