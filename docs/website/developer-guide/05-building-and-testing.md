# Building and testing

**Included in Free**

For contributors. The theme source is on GitHub.

## Setup

```
npm ci
composer install
```

Node 22 and PHP 8.1 or later.

## Commands

| Command | What it does |
|---|---|
| `npm run build` | Builds CSS and JavaScript into the `.min` files |
| `npm run lint` | Lints JavaScript, CSS and `theme.json` |
| `npm test` | Runs the PHPUnit unit tests |
| `vendor/bin/phpcs --standard=phpcs.xml.dist` | WordPress coding standards |
| `vendor/bin/phpstan analyze` | Static analysis |
| `npm run bundle` | Builds the installable zip in `dist/` |
| `python3 tools/build-manifest.py` | Regenerates `audit/manifest.json` |
| `node tools/build-blueprint.mjs` | Regenerates the Playground blueprint |

Built files are committed, so run `npm run build` before you commit a change to a source file.

## Browser checks

`docs/qa/JOURNEYS.md` lists the pages and actions to check at 390, 768, 1024 and 1440 px in light and dark mode, with an accessibility scan on each page.
