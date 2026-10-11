# Contributing to KnowX

## Before you start

1. Read [`README.md`](README.md) for setup and the folder map.
2. Check `audit/manifest.json` (and regenerate it with `python3 tools/build-manifest.py`) before you add a function, hook, template, pattern or setting: reuse what exists.
3. Work on a branch and open a pull request. Do not push to `master`.

## Rules the code follows

- WordPress already provides it, so use it: core blocks, `theme.json`, the REST API, categories and posts. A library or a custom feature needs a written reason in [`docs/LIBRARIES.md`](docs/LIBRARIES.md).
- Every list, grid and tree is bounded or paginated and cached with `knowx_kb_cached()`. Check at 1,000 articles and 500 categories.
- Every feature is reachable three ways: front end, admin screen and REST API.
- Mobile, right-to-left and dark mode are part of the change: logical CSS properties (`inline-start`, `inset-inline-end`), colour tokens instead of hex values, a check at 390 px.
- Accessibility is a gate: axe with no violations on each page type in light and dark.
- Output is escaped late, input is sanitised, state changes check a capability and a nonce.
- No hex colours, `z-index` numbers or shadows in a stylesheet: use the tokens in `assets/css/src/_custom-properties.css`. `!important` only in the accessibility utilities.
- Text domain `knowx`. Plurals use `_n()` (in JavaScript `wp.i18n._n`).
- Release notes use the action prefix format (New, Improve, Fix, Security, Dev, Compat).

## Checks before a pull request

```bash
npm run build && git diff --exit-code assets/   # built files are committed
npm run lint
npm test
vendor/bin/phpcs --standard=phpcs.xml.dist
vendor/bin/phpstan analyze --memory-limit=1G
bash tests/docker/run-integration.sh
```

For a change you can see, also walk the pages in [`docs/qa/JOURNEYS.md`](docs/qa/JOURNEYS.md) at 390, 768, 1024 and 1440 px in light and dark mode.

## Adding things

See [Adding a setting, a pattern or a hook](docs/website/developer-guide/06-adding-settings-patterns-and-hooks.md).
