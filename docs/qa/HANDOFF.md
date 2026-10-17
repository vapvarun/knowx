# QA handoff: KnowX 2.0.0 (re-release from branch 2.0.1)

Start here. This is the one page QA needs. Everything else in `docs/qa/` is linked from it.

## What this round is

The first 2.0.0 build was bounced by QA and never reached wordpress.org (the directory still lists 1.7.4). Branch `2.0.1` holds every fix since then and ships as **2.0.0**: the version in `style.css` and `readme.txt` stays 2.0.0, the `v2.0.0` tag is moved and the zip replaced, then 2.0.0 is uploaded to wordpress.org.

**Release gate:** every card in Ready for Testing is moved to Done by QA, and the three checks below (A, B, C) pass. Then the owner merges and retags. Nothing is merged or tagged before that.

## What to test, and where

- **Code:** branch `2.0.1` of `wbcomdesigns/knowx`, at origin HEAD. Version `2.0.0`.
- **Build the zip QA tests:** `npm ci && npm run bundle` gives `dist/knowx-2.0.0.zip`. Test the installed zip, not the git folder.
- **Environment:** WordPress 6.9 to 7.1, PHP 8.1 or newer, no plugins. Theme folder `knowx`.
- **Sample content:** `wp eval-file wp-content/themes/knowx/playground/seed.php` on an empty site (two products, sections, articles, FAQ, changelog, docs home, menu). Single-product check: `playground/seed-single.php` on a second empty site.
- **Clean Docker site (recommended):** from `tests/docker`:
  ```
  docker compose down -v && docker compose up -d
  docker compose run --rm -T cli core install --url=http://localhost:8089 --title=KnowX --admin_user=admin --admin_password=admin --admin_email=qa@example.com --skip-email
  docker compose run --rm -T cli theme install /dist/knowx-2.0.0.zip --activate
  docker compose run --rm -T cli eval-file /playground/seed.php
  ```
  Site: http://localhost:8089, login `admin` / `admin`.
- **Do not test:** Playground from the git URL (private repo; switched to the wordpress.org slug after release), the `accessibility-ready` tag (not claimed, see C).

## The cards

- **Ready for Testing: 45 cards.** Each has a proof comment with the commit, what changed and how to test. Verify each as its card says, then move it to Done or bounce it with page, viewport, light or dark, expected, seen and a screenshot.
- **Left in Bugs, owner decision pending (not part of this gate):** 10415338524 (product with sections hides its direct articles), 10403690882 (docs breakpoints), 10403683143 (not reproduced), 10403480547 (switcher row height, pushed back), 10403216987 (spacing tokens), 10398980276 (Google Fonts, feature request). Reasons are on each card.

## Check A: WordPress coding standards

Run from the theme folder; each must pass with zero errors.

| Check | Command | Pass |
|---|---|---|
| WPCS | `vendor/bin/phpcs --standard=phpcs.xml.dist` | no output |
| PHPStan | `vendor/bin/phpstan analyze --memory-limit=1G` | `[OK] No errors` |
| Unit tests | `vendor/bin/phpunit` | 41 tests OK |
| Integration tests | `tests/docker/run-integration.sh` | all pass |
| JS, TS, CSS, theme.json lint | `npm run lint` | exit 0, no `✖` |
| Build is committed | `npm run build && git status --short` | no changed `*.min.*` files |
| PHP versions | `find . -name '*.php' -not -path './vendor/*' -not -path './node_modules/*' -exec php -l {} \;` on PHP 8.1 and 8.4 | no syntax errors |
| Debug log | browse every journey with `WP_DEBUG_LOG` on | `wp-content/debug.log` gains no lines |

## Check B: Theme Check and the wordpress.org review rules

1. **Theme Check plugin** on the installed zip (Docker site above):
   ```
   docker compose run --rm -T cli plugin install theme-check --activate
   docker compose run --rm -T cli theme-check run knowx
   ```
   Pass: no `REQUIRED` and no `ERROR`. One known `WARNING` is accepted: two text domains (`knowx`, `default`), from `inc/docs.php` using core's own translation of "Uncategorized" to hide the empty default category. Anything else is a bug. Also open Appearance, Theme Check in the admin once and confirm the same result.
2. **Manual review against the required guidelines** (https://make.wordpress.org/themes/handbook/review/required/). Tick each:
   - [ ] `style.css` header: name, version 2.0.0, requires 6.9, tested up to 7.1, requires PHP 8.1, GPL licence, text domain `knowx`, only allowed tags
   - [ ] `readme.txt`: same version and requirements, description, FAQ, changelog, Resources section lists every bundled font and image with its licence
   - [ ] `screenshot.png` is 1200 x 900 (4:3), shows the theme's docs home with the KnowX logo, does not look like an advert
   - [ ] No plugin territory: no custom post types, shortcodes for content, analytics or SEO features
   - [ ] No external requests: with DevTools Network open, every request on every page goes to the site's own host (fonts are bundled)
   - [ ] No admin notices, upsells or nag screens after activation; nothing outside Appearance
   - [ ] All options live in the Customizer and save, reset and preview live (`SETTINGS_QA.md`, `customizer-ui-check.js`)
   - [ ] Every string is translatable with domain `knowx`; `languages/knowx.pot` exists
   - [ ] The zip holds no `docs/`, `plans/`, `audit/`, `tests/`, `tools/`, `playground/`, `node_modules`, `vendor`, source maps or Markdown (`unzip -l dist/knowx-2.0.0.zip`)
   - [ ] Core features work: custom logo (a square logo keeps the header at its normal height), menus, widgets, comments with threading, sticky posts, post formats, editor styles match the front end
   - [ ] Theme Unit Test data: import https://github.com/WPTT/theme-test-data on a clean site and walk home, a long post, image alignment, comments, pagination and the menu. This is the content wordpress.org's preview uses.

## Check C: Accessibility

The `accessibility-ready` tag is not claimed, but the theme is checked to that bar (https://make.wordpress.org/themes/handbook/review/accessibility/required/).

1. **axe** (browser extension or `@axe-core/cli`): docs home, product, section, article, search results, 404, FAQ, changelog and blog, in light and dark, at 390 and 1440. Pass: no violations.
2. **Keyboard only** (no mouse), on the same pages:
   - [ ] First Tab shows "Skip to content" and it moves focus to the main content
   - [ ] Every link, button and field shows a visible focus outline
   - [ ] Main menu and submenus open and close with the keyboard
   - [ ] Mobile menu (390 px): Enter opens it, focus moves to Close, Tab stays inside the drawer, Escape closes it and focus returns to the menu button
   - [ ] Products switcher, search overlay, colour-mode toggle and docs tree all work with Enter, Space and Escape
   - [ ] No keyboard trap anywhere
3. **Content and markup:**
   - [ ] One `h1` per page, headings in order
   - [ ] Landmarks: header, nav (labelled), main, footer
   - [ ] Every form field has a label (search, comments)
   - [ ] Links inside article text are underlined
   - [ ] Repeated links ("Read more", "View all") have screen-reader text naming the target
   - [ ] Images have alt text; decorative icons are hidden from screen readers
   - [ ] Text contrast at least 4.5:1 (3:1 for large text and UI borders) in light and dark, including the hero, chips, cards and footer
   - [ ] Page works at 200% browser zoom with no lost content or sideways scroll
   - [ ] Nothing animates when the system setting "reduce motion" is on
4. **Screen reader spot check** (VoiceOver or NVDA): docs home, an article and the mobile menu read in a sensible order with the right names and states (expanded or collapsed).

## Known limits (not bugs)

- "Hide Categories" takes category IDs, not a picker.
- A custom text, background or surface colour applies in light mode only. Dark mode keeps its own palette (the Customizer says so under each colour).
- The product accent colour decorates cards only. The theme colour drives buttons and links.
- Rich Results test and HTTPS Lighthouse need a public URL and run after release.

## Reporting a problem

Put in the card: page and viewport, light or dark, Customizer values changed, expected, seen, a screenshot. A layout or taste call is an input to the owner, not a verdict; a broken function is fixed straight away.

## After QA clears the gate (owner)

Merge `2.0.1` into `master`, rebuild with `node scripts/distribute.js --current --no-tag`, release branch and PR to `vapvarun/knowx`, green CI, move the `v2.0.0` tag and replace the zip, upload 2.0.0 to wordpress.org, then `node tools/build-blueprint.mjs --release`. Full list: `RELEASE_CHECKLIST.md`.
