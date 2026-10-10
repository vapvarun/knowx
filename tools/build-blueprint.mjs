#!/usr/bin/env node
/**
 * Builds blueprint.json for WordPress Playground from playground/seed.php.
 *
 * Usage: node tools/build-blueprint.mjs [--ref <git ref>]   (default ref: the current branch)
 * At release the theme step is switched to the wordpress.org slug so Playground installs the published zip.
 */
import { execSync } from 'node:child_process';
import { readFileSync, writeFileSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = join(dirname(fileURLToPath(import.meta.url)), '..');
const refIndex = process.argv.indexOf('--ref');
const ref = refIndex > 0 ? process.argv[refIndex + 1] : execSync('git rev-parse --abbrev-ref HEAD', { cwd: root }).toString().trim();
const release = process.argv.includes('--release');

const seed = readFileSync(join(root, 'playground/seed.php'), 'utf8').replace(/^<\?php\s*/, '<?php ');

const blueprint = {
	$schema: 'https://playground.wordpress.net/blueprint-schema.json',
	landingPage: '/',
	preferredVersions: { php: '8.2', wp: 'latest' },
	features: { networking: false },
	steps: [
		{
			step: 'installTheme',
			themeData: release
				? { resource: 'wordpress.org/themes', slug: 'knowx' }
				: { resource: 'git:directory', url: 'https://github.com/wbcomdesigns/knowx.git', ref, path: '/' },
			options: { activate: true },
		},
		{ step: 'runPHP', code: seed },
	],
};

writeFileSync(join(root, 'blueprint.json'), JSON.stringify(blueprint, null, '\t') + '\n');
console.log(`blueprint.json written (${release ? 'wordpress.org release' : 'git ref ' + ref})`);
