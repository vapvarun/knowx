# Building and testing

**Included in Free**

For contributors. The theme source is on GitHub.

## Setup

```
npm ci
composer install
```

Node 22 and PHP 8.1 or later. CI uses the same versions.

## Commands

| Command | What it does |
|---|---|
| `npm run build` | Builds CSS and JavaScript into the `.min` files |
| `npm run lint` | ESLint, `tsc --noEmit` (the two TypeScript files), stylelint and `theme.json` validation |
| `npm test` | Runs the PHPUnit unit tests |
| `bash tests/docker/run-integration.sh` | Runs the integration tests in Docker against a real WordPress |
| `vendor/bin/phpcs --standard=phpcs.xml.dist` | WordPress coding standards |
| `vendor/bin/phpstan analyze --memory-limit=1G` | Static analysis |
| `npm run bundle` | Builds the installable zip in `dist/` (no release gate) |
| `node scripts/distribute.js --current --no-tag` | Builds the release zip; refuses to package without a fresh `docs/qa/.last-smoke-pass.json` for this version |
| `python3 tools/build-manifest.py` | Regenerates `audit/manifest.json` |
| `python3 tools/dump-customizer-inventory.py > docs/customizer-inventory.md` | Regenerates the settings inventory |
| `node tools/build-blueprint.mjs` | Regenerates the Playground blueprint |

Built files are committed, so run `npm run build` before you commit a change to a source file. CI runs the build and fails when it changes a committed file.

## The translation template

```
wp i18n make-pot . languages/knowx.pot --slug=knowx --package-name=KnowX --exclude=node_modules,vendor,tests,playground,audit,plans,dist
```

Do not exclude `docs`: WP-CLI skips every folder with that name, including `template-parts/docs/`. Regenerate the template as the last step before a release, after all strings have landed.

## The Docker stack

`tests/docker/docker-compose.yml` starts a fresh WordPress (port 8089) and a MariaDB database. `./wp` runs WP-CLI inside it.

```
cd tests/docker
docker compose up -d
./wp core install --url=http://localhost:8089 --title=QA --admin_user=admin --admin_password=<pick one> --admin_email=qa@example.com --skip-email
./wp theme install /dist/knowx-2.0.0.zip --activate     # after npm run bundle
./wp eval-file /playground/seed.php                     # sample content
python3 settings_qa.py                                  # changes each setting and compares nine page types
```

`settings_qa.py` writes `docs/qa/SETTINGS_QA.md`. To check that every Customizer control is reachable, open the Customizer in a browser and paste `tests/docker/customizer-ui-check.js` in the console: `bad` and `inactive` must both be empty.

## Browser checks

`docs/qa/JOURNEYS.md` lists the pages and actions to check at 390, 768, 1024 and 1440 px in light and dark mode, with an accessibility scan on each page. `docs/qa/AGENT_SMOKE_RUNBOOK.md` is the runbook the release smoke walk follows.
