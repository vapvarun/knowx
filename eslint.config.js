// eslint.config.js

// Using FlatCompat to convert @wordpress/eslint-plugin to flat config format
import { FlatCompat } from '@eslint/eslintrc';
// eslint-disable-next-line import/no-unresolved
import tsParser from '@typescript-eslint/parser';
import path from 'path';
import { fileURLToPath } from 'url';
import globals from 'globals';

const __filename = fileURLToPath( import.meta.url );
const __dirname = path.dirname( __filename );

const compat = new FlatCompat( {
	baseDirectory: __dirname,
} );

export default [
	// Global ignores (alternative to .eslintignore)
	{
		ignores: [
			'node_modules/**',
			'vendor/**',
			'config/**',
			'**/*.min.{js,ts,jsx,tsx}', // All minified files
		],
	},

	// Convert WordPress recommended configuration
	...compat.extends( 'plugin:@wordpress/eslint-plugin/recommended' ),

	// Configuration files: explicitly allow devDependencies
	{
		files: [ 'eslint.config.js', '*.config.js' ],
		rules: {
			'import/no-extraneous-dependencies': [
				'error',
				{
					devDependencies: [ 'eslint.config.js', '*.config.js' ],
				},
			],
		},
	},

	// JSX/TSX files - Use TypeScript parser for JSX support
	{
		files: [ '**/*.jsx', '**/*.tsx' ],
		languageOptions: {
			ecmaVersion: 'latest',
			sourceType: 'module',
			parser: tsParser,
			parserOptions: {
				ecmaFeatures: {
					jsx: true,
				},
			},
			globals: {
				wp: 'readonly',
				knowxScreenReaderText: 'readonly',
				window: 'readonly',
				document: 'readonly',
				console: 'readonly',
			},
		},
	},

	// TypeScript-specific configuration
	{
		files: [ '**/*.ts', '**/*.tsx' ],
		rules: {
			// Relax JSDoc rules for TypeScript
			'jsdoc/no-undefined-types': 'off', // TypeScript types are often not defined in JSDoc
		},
	},

	// Console rule for all file types
	{
		files: [ '**/*.{js,jsx,ts,tsx}' ],
		rules: {
			'no-console': 'warn',
		},
	},

	// Tooling: build and release scripts run in Node.
	{
		files: [ 'scripts/**/*.js', 'build-js.js', 'build-css.js' ],
		languageOptions: {
			ecmaVersion: 'latest',
			sourceType: 'module',
			globals: {
				...globals.node, // Node globals for tooling. No node env in new syntax.
			},
		},
		rules: {
			'no-console': 'off',
			semi: 'error',
			'no-unused-vars': 'error',
			'jsdoc/no-undefined-types': [
				'error',
				{ definedTypes: [ 'Stream' ] },
			],
			'import/no-extraneous-dependencies': [
				'error',
				{
					devDependencies: true, // allow dev deps in tooling files
					optionalDependencies: true,
					peerDependencies: true,
				},
			],
		},
	},
];
