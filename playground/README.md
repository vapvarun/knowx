# Sample content and Playground

`seed.php` creates a complete docs site through the theme's own **Add sample docs** code (`inc/Starter_Content`): two products (Orbit Helpdesk and Orbit API, three sections and nine articles each), a docs home page, an FAQ page, a changelog page and the main menu. On top of that it sets what only a demo needs: the site title, tags, two comments, pretty permalinks, and no WordPress sample post or page. Running it twice does nothing the second time. It is not shipped in the theme zip.

## Try it

- **Playground:** open `https://playground.wordpress.net/?blueprint-url=<raw URL of blueprint.json>`.
- **Your own site:** `wp eval-file wp-content/themes/knowx/playground/seed.php` on an empty site with KnowX active.

## Rebuild the blueprint

`node tools/build-blueprint.mjs` writes `blueprint.json` from `seed.php`. It installs the theme from the git ref you pass (`--ref <branch or tag>`, default the current branch). A private repository cannot be installed that way, so the blueprint for a release uses `--release`, which installs the published theme from wordpress.org instead.

## Test locally

```
npx @wp-playground/cli server --blueprint=bp-local.json --mount=<theme folder>:/wordpress/wp-content/themes/knowx
```

with `bp-local.json` equal to the blueprint but with the first step replaced by `{ "step": "activateTheme", "themeFolderName": "knowx" }`.
