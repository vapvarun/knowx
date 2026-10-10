#!/usr/bin/env node
/**
 * theme.json gate.
 *
 * Fails the run when theme.json or a styles/*.json variation would ship
 * broken: invalid JSON, wrong schema version, duplicate or malformed palette
 * entries, or a palette that cannot give readable text (WCAG AA, 4.5:1).
 *
 * Usage: node scripts/validate-theme-json.js   (also `npm run lint:theme-json`)
 */
import { existsSync, readFileSync, readdirSync } from 'node:fs';
import { dirname, join, relative } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = join(dirname(fileURLToPath(import.meta.url)), '..');
const AA = 4.5;
const NEAR_BLACK = '#0a0a0a';

const channel = (hex, i) => {
	const c = parseInt(hex.slice(i, i + 2), 16) / 255;
	return c <= 0.03928 ? c / 12.92 : Math.pow((c + 0.055) / 1.055, 2.4);
};
const luminance = (hex) =>
	0.2126 * channel(hex, 1) + 0.7152 * channel(hex, 3) + 0.0722 * channel(hex, 5);
const ratio = (a, b) => {
	const [hi, lo] = [luminance(a), luminance(b)].sort((x, y) => y - x);
	return (hi + 0.05) / (lo + 0.05);
};

const stylesDir = join(root, 'styles');
const files = [
	join(root, 'theme.json'),
	...(existsSync(stylesDir)
		? readdirSync(stylesDir)
				.filter((f) => f.endsWith('.json'))
				.map((f) => join(stylesDir, f))
		: []),
];

const errors = [];

for (const file of files) {
	const name = relative(root, file);
	let data;
	try {
		data = JSON.parse(readFileSync(file, 'utf8'));
	} catch (e) {
		errors.push(`${name}: invalid JSON (${e.message})`);
		continue;
	}
	if (data.version !== 3) {
		errors.push(`${name}: "version" must be 3, found ${data.version}`);
	}

	const palette = data.settings?.color?.palette ?? [];
	const colors = {};
	for (const { slug, color } of palette) {
		if (colors[slug]) {
			errors.push(`${name}: duplicate palette slug "${slug}"`);
		}
		// CSS functions such as color-mix() or rgba() are valid palette values;
		// only literal hex colors are checked, and only those feed contrast checks.
		if (!String(color ?? '').startsWith('#')) {
			colors[slug] = color;
			continue;
		}
		if (!/^#[0-9a-f]{6}$/i.test(color)) {
			errors.push(`${name}: palette "${slug}" needs a 6-digit hex, found "${color}"`);
			continue;
		}
		colors[slug] = color;
	}

	// Body text on the page surface.
	const isHex = (c) => /^#[0-9a-f]{6}$/i.test(c ?? '');
	if (isHex(colors.base) && isHex(colors.contrast)) {
		const r = ratio(colors.base, colors.contrast);
		if (r < AA) {
			errors.push(`${name}: contrast on base is ${r.toFixed(2)}:1 (need ${AA}:1)`);
		}
	}

	// Brand colour: either white or near-black must read on it, so the
	// theme can always derive a passing button label.
	const brand = isHex(colors.accent) ? colors.accent : colors.primary;
	if (isHex(brand)) {
		const best = Math.max(ratio(brand, '#ffffff'), ratio(brand, NEAR_BLACK));
		if (best < AA) {
			errors.push(`${name}: no readable label colour on brand ${brand} (best ${best.toFixed(2)}:1)`);
		}
	}
}

if (errors.length) {
	console.error(`theme.json validation failed:\n- ${errors.join('\n- ')}`);
	process.exit(1);
}
console.log(`theme.json validation passed (${files.length} files).`);
