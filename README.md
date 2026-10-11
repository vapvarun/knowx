# KnowX

A fast documentation and FAQ theme for WordPress. One product or many, instant search, a section tree, FAQ and changelog templates, structured data and 17 block patterns. No plugin is required, no jQuery is loaded and nothing is fetched from another site.

- Requires WordPress 6.9, PHP 8.1. Tested up to WordPress 7.1.
- Classic theme with block features, light and dark mode, right-to-left support.
- Licence: GPL v3 or later. Release notes: [`readme.txt`](readme.txt). User docs: [`docs/website`](docs/website).

## Try it

- **Playground:** open `https://playground.wordpress.net/?blueprint-url=<raw URL of blueprint.json>` for a finished sample docs site.
- **Your own site:** install the theme, then `wp eval-file wp-content/themes/knowx/playground/seed.php` on an empty test site (two products, FAQ, changelog, menu).

## Develop

```bash
npm ci            # Node 22
composer install  # PHP 8.1 or later
npm run build     # builds the .min.css and .min.js files (they are committed)
npm run lint      # ESLint, tsc, stylelint and theme.json
npm test          # PHPUnit unit tests
vendor/bin/phpcs --standard=phpcs.xml.dist
vendor/bin/phpstan analyze --memory-limit=2G
bash tests/docker/run-integration.sh   # integration tests (needs Docker)
```

Run `npm run build` before you commit a change to a source file: CI fails when the committed built files differ from the build.

## Where things are

| Path | What |
|---|---|
| `functions.php`, `inc/Theme.php` | Bootstrap and the list of components |
| `inc/<Name>/Component.php` | One class per concern (namespace `KnowX\KnowX\<Name>`) |
| `inc/docs.php`, `inc/knowledge-base.php`, `inc/breadcrumbs.php`, `inc/extra.php`, `inc/helpers.php` | Functions the templates call (`knowx_*`) |
| `inc/Customizer_Framework`, `inc/Customizer_Settings` | The built-in Customizer API and the settings |
| `inc/Tokens`, `inc/Color_Mode` | Colour variables, palettes and dark mode |
| `template-parts/` | Template parts (docs layout, header, footer, content) |
| `patterns/` | Block patterns |
| `assets/css/src`, `assets/js/src` | Sources; built files sit next to them |
| `tests/phpunit`, `tests/docker` | Unit tests, integration tests, the Docker QA stack |
| `docs/website` | User and developer documentation |
| `docs/qa` | QA handoff, journeys, release checklist |
| `audit/manifest.json` | Generated inventory of hooks, templates, patterns and settings |

## Guides

- [Building and testing](docs/website/developer-guide/05-building-and-testing.md)
- [Adding a setting, a pattern or a hook](docs/website/developer-guide/06-adding-settings-patterns-and-hooks.md)
- [Hooks reference](docs/website/developer-guide/03-hooks.md) and [content model](docs/website/developer-guide/02-content-model.md)
- [Contributing](CONTRIBUTING.md)

## Release

Follow [`docs/qa/RELEASE_CHECKLIST.md`](docs/qa/RELEASE_CHECKLIST.md). The release is built with `node scripts/distribute.js --current --no-tag`, which refuses to package without a fresh browser smoke report.
