# QA handoff: KnowX 2.0.0

Start here. This is the one page QA needs. Everything else in `docs/qa/` is linked from it.

## What to test, and where

- **Code:** branch `master` of `wbcomdesigns/knowx` (it already contains everything from `1.7.5`). Version `2.0.0`. Nothing is tagged or released yet.
- **Environment:** WordPress 6.9 to 7.1, PHP 8.1 or newer, no plugins needed. Theme folder `knowx`.
- **Sample content:** run `wp eval-file wp-content/themes/knowx/playground/seed.php` on an empty site. It creates two products (Orbit Helpdesk, Orbit API), sections, articles, FAQ, changelog, docs home and the menu. For the single-product check run `playground/seed-single.php` on a second empty site.
- **Large-site check:** a seed of 1,000+ articles and 500+ categories (results in `plans/WORK_GROUPS.md`, W16).
- **Do not test:** Playground from the git URL (the repo is private; it is switched to the wordpress.org slug at release), the `accessibility-ready` tag (not claimed).

## What changed since the last QA build

| Area | What to look for | Where it is checked |
|---|---|---|
| First view | Docs home looks like a real docs site on a fresh install, with no setup | J1, J19 |
| Single and multi product | One product shows its sections directly; several show product cards | J2, J20 |
| Docs home layouts | Customizer, Docs, Docs Home: cards or sections | J19 |
| Updated date | "Last updated" on articles, products and sections; hide it with one switch | J4, J21 |
| Search, archive, 404 | Docs-style results with filter chips; docs-style list; friendly 404 | J8, J9, J18, J22 |
| Comments | Form and list are styled as cards | J23 |
| Patterns | 17 patterns: callouts, prerequisites, troubleshooting, API endpoint, compatibility table, next steps, feedback, docs home, recently updated, popular tags, FAQ, steps, changelog, support | J24 |
| Colours | One place: Customizer, Colors. Palette cards at the top, then your own choices | J14, J25 |
| Customizer | Flat sections, plain labels and hints, collapsible Typography groups; colours and corners update live in the preview | J26, J28 |
| Footer menu | New `footer` menu location | J27 |

Removed on purpose, do not report as missing: page loader, search options, blog layout options, style variations, Kirki, the old colour-scheme picker, the extra colour settings that are now derived (link hover, button colours and similar).

## How to run the checks

1. **Browser journeys:** `docs/qa/JOURNEYS.md`, J1 to J28, at 390, 768, 1024 and 1440 px, light and dark. Each ends with axe clean, console empty, no sideways scroll.
2. **Every Customizer setting:** `docs/qa/SETTINGS_QA.md` is the last report (43 settings, 0 failures). To rerun on a clean Docker stack: `cd tests/docker && docker compose up -d`, then `python3 settings_qa.py` (site on port 8089).
3. **Customizer screens render:** open the Customizer, expand every section, paste `tests/docker/customizer-ui-check.js` into the browser console. It must print every section with all controls visible and nothing flagged.
4. **Integration tests:** `tests/docker/run-integration.sh` (12 tests).
5. **Automatic gates:** `vendor/bin/phpstan analyze`, `vendor/bin/phpcs --standard=phpcs.xml.dist`, `vendor/bin/phpunit` (33 tests), `npm run lint`, `npm run build` (no diff in `*.min.*`).

## Known limits (not bugs)

- "Hide Categories" takes category IDs, not a picker.
- A custom text, background or surface colour applies in light mode only. Dark mode keeps its own colours.
- The product accent colour is decoration on cards only. The theme colour in Customizer, Colors drives buttons and links.
- Rich Results test and HTTPS Lighthouse need a public URL and are run after release.

## Release status

Not released. Open items (owner): create the Ready for Testing cards, approve the release, publish to `vapvarun/knowx`, switch the blueprint with `node tools/build-blueprint.mjs --release`, archive `knowx-core`, retire `knowx-demo-importer`. Full list: `RELEASE_CHECKLIST.md`.

## Reporting a problem

Put in the card: page and viewport, light or dark, the Customizer values changed, what you expected, what you saw, a screenshot. A layout or taste call is an input to the owner, not a verdict; a broken function is fixed straight away.
