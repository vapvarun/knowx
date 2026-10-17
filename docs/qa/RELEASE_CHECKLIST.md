# KnowX 2.0.0 Release Checklist

Everything is on `master` (the former `1.7.5` branch and the later first-view and Customizer work are merged), releasing as **2.0.0**, PHP 8.1 and WordPress 6.9 minimum, tested up to 7.1. Do not tag or release before the open boxes in section H are closed by the owner. QA starts at `HANDOFF.md`.
Release route: develop on `wbcomdesigns/knowx`, release to the `vapvarun/knowx` mirror (same as BuddyX).
Basecamp: KnowX Theme project 38311801, card table 7605386991. Work groups W00 to W18 are in `plans/WORK_GROUPS.md`.

Legend: `[x]` verified on 2026-10-10 with the evidence beside it, `[ ]` open (with the reason). A box is ticked only after the check ran.

## A. Skeleton and build

- [x] `npm ci` installs clean on Node 22 (run in an empty folder, 95 s)
- [x] `npm run build` produces no drift: the committed `*.min.*` files are the build output
- [x] `npm run bundle` produces `dist/knowx-2.0.0.zip` (430 KB, 247 entries) with `Version: 2.0.0`
- [x] Version 2.0.0 is identical in `style.css`, `package.json`, `package-lock.json`, `config/config.default.json` and the `readme.txt` stable tag and changelog
- [x] No committed zips; `dist/` and `*.zip` are ignored
- [x] The only "WP Rig" text left is the credit line in `readme.txt`
- [x] The zip has no tests, scripts, `playground/`, `tools/`, `rector.php`, source maps or markdown; it has `patterns/`, `template-parts/docs/`, `languages/knowx.pot`
- [x] `npm run lint` is one command (JS, CSS, theme.json) and passes with 0 problems
- [x] `npm test` runs the PHPUnit unit suite (33 tests, 115 assertions). Jest, `jest.config.cjs` and the unused Playwright config were removed because there are no JS unit tests
- [x] `vendor/bin/phpstan analyze`: no errors, no baseline
- [x] `vendor/bin/phpcs --standard=phpcs.xml.dist`: 0 errors, 27 warnings in legacy templates and test helpers
- [x] PHPUnit integration suite: 12 tests pass in Docker on WordPress 7.1.3 (`tests/docker/run-integration.sh`)
- [ ] Git pre-commit hook: `simple-git-hooks` installs it, but the global `core.hooksPath` on this machine bypasses it; run the gates by hand
- [x] `knowx-core` is superseded: its `v3.1` branch has two commits (WP Rig 3.1 structure, "Manage block style UI") and 1.7.5 contains `_blocks-style.css` and the rebuilt toolchain. Archive after the release
- [x] `CLAUDE.md`, `audit/manifest.json` (generated), `docs/qa/ROLE_MATRIX.md`, `docs/qa/JOURNEYS.md` exist
- [ ] `npm run dist` with a fresh smoke report: `docs/qa/.last-smoke-pass.json` is written by the final run; `dist` bumps the version, so run it as `npm run dist -- --current`

## B. Customizer (in-house framework, Kirki removed)

43 settings in 10 sections (`docs/customizer-inventory.md`, generated; every setting has a reader or output CSS). No migration from 1.7.4 (decision 2026-10-10): the changelog says Customizer choices reset.

- [x] Opened in the real Customizer over CDP: every setting has a control, all 10 sections render with every control visible (`tests/docker/customizer-ui-check.js`), no console error, no PHP notice. Settings QA: 43 OK, 0 failures (`SETTINGS_QA.md`)
- [x] A save round-trips: primary colour, colour scheme, hero heading and "Posts per category" were set, saved, read back with WP-CLI and seen on the front end; then removed
- [x] Live preview and front end share the same `--color-*` variables; the editor receives the same rule through `block_editor_settings_all`
- [x] Fonts are served from the theme (Open Sans variable woff2); the Customizer font list shows the theme fonts
- [x] Kirki is not referenced anywhere and nothing is recommended or required
- [x] Settings inventory regenerated (`python3 tools/dump-customizer-inventory.py > docs/customizer-inventory.md`)

## C. Front end and templates

Each page type below was loaded at 390, 768, 1024 and 1440 px, in light and dark, with axe (WCAG 2.0/2.1/2.2 A and AA plus best practices): **0 violations, no horizontal scroll, empty console** (the 404 page's own 404 status is the only console line).

- [x] Docs home (`page-knowledgebase-one.php`), Docs Index (`page-knowledgebase-two.php`)
- [x] Article, page, category (product home and section, paged), tag, author, search with results and with no results, 404
- [x] `full-width.php`, `full-width-container.php` (they keep a screen-reader page heading), FAQ and Changelog templates
- [x] Primary navigation, mobile menu, sticky header, product switcher, search overlay
- [x] Sidebar layouts left, right, both and none (landmarks are named, list links meet the target size)
- [x] Comments and reply link on articles
- [x] RTL mirrors correctly (checked with `dir=rtl` at 1440 and 390)
- [x] Block editor: the editor shows the scheme's colours and the same palette
- [x] Pinch-to-zoom: the viewport meta is `width=device-width, initial-scale=1.0`, no `user-scalable=no`
- [x] No bbPress, Elementor, WooCommerce, BuddyPress or other integration code is left
- [x] No jQuery, no icon font, no external request on the front end (checked in the network list)
- [x] No raw hard-coded colours that break dark mode (tokens only)

## D. Basecamp Bugs column

Both cards were assigned to Nitin Patil and are fixed by the redesign. Proof is posted on each card.

### D1. Card 10388866381 "Layout One ships lorem ipsum..."
- [x] No placeholder text anywhere in the theme (`grep` finds none); the support section has real default text
- [x] No Popular Articles column and no WordPress Popular Posts dependency, so "No data found" cannot appear
- [x] Product cards and lists use `hide_empty => true` and hide the default category when other products exist
- [x] The docs are rewritten for 2.0 (`docs/website`, 27 pages, lint clean); the Layout One page is `customizing/07-docs-home-settings.md`

### D2. Card 10391809639 "Layout One: Recent Articles ignores the category exclusion..."
- [x] One setting, "Exclude category by ID", feeds the product cards, featured and recent articles and Layout Two (checked: excluding Acme App removed its card and its articles from both lists)
- [x] Recent and featured articles are one `<ul>` with one `<li>` per article
- [x] `View All &raquo;` uses `esc_html__()`; names and links are escaped
- [x] Decision recorded: Layout One lists products (top-level categories), Layout Two lists every category with its latest articles; both documented in `docs/website/features/products-and-sections.md`
- [x] Tested with sub-categories (the sample content has three levels)

## E. Ready for Testing cards

- [x] 9871419774 Kirki 6.0.4 compatibility: no longer applicable, Kirki is removed (comment posted)
- [x] 9637471691 Rebrand from WP Rig: string sweep clean (section A)
- [x] 9792875706 Pinch-to-zoom: viewport meta checked (section C)
- [x] 9275650905 WP Rig 3.1 structure: install, front end, Customizer, assets, editor, performance, accessibility, build tools and regression checks above pass on 1.7.5
- [ ] Moving these cards to Done is the owner's decision; proof comments are on each

## F. Large-site readiness

- [x] 4,590 articles and 546 categories seeded: warm TTFB 21 to 35 ms, 19 to 41 queries per page, cold worst case 91 ms, HTML 37 to 99 KB (`plans/WORK_GROUPS.md`, W16)
- [x] Every list is bounded (`knowx_kb_max_categories`, `knowx_tree_max_posts`, paged archives); the Docs Index costs 327 queries once after a change and 20 afterwards
- [x] The cache did not hit without a persistent object cache; fixed with a stored version bumped on post, term and term-meta changes
- [x] Empty, error and no-result states exist for the lists, search and the search overlay

## G. WordPress.org theme, Playground and sample content

Theme Review
- [x] Theme Check plugin on the built zip: no errors, no warnings (the only note was the temporary folder name used for the test)
- [x] `style.css` header: allowed tags, tested up to 7.1, requires 6.9 and PHP 8.1
- [x] Licences: GPL theme code, Open Sans under the SIL OFL (listed in `readme.txt`), SVG icons drawn for the theme; nothing else is bundled
- [x] No plugin is required or recommended; no tracking; no external calls
- [x] Output escaped, input sanitised, nonces and capability checks on the category fields and the version box (term form is nonce-checked by core)
- [x] Text domain `knowx`; `languages/knowx.pot` regenerated (276 strings)
- [x] `screenshot.png` is 1200 x 900 and shows the shipped theme
- [x] `readme.txt`: description, installation, FAQ, resources, upgrade notice, changelog in action-prefix format
- [ ] `accessibility-ready` tag is not claimed: it needs the review team's own check

Playground and sample content
- [x] `blueprint.json` (built by `tools/build-blueprint.mjs`) installs the theme and runs `playground/seed.php`; verified in the local Playground CLI with the theme mounted
- [ ] Playground cannot load the theme from the private git repository; the release blueprint (`node tools/build-blueprint.mjs --release`) uses the wordpress.org slug and is switched on at release, then pinned and opened once from the theme preview
- [x] Sample content: two products, three sections and nine real articles each, FAQ, changelog, docs home, menu; no lorem ipsum
- [x] `knowx-demo-importer` is not referenced anywhere in code; retire that repository at release

## I. Docs website: speed, devices, templates, schema

- [x] Lighthouse 12 on home, product, article and search, mobile and desktop: performance 99 to 100, accessibility 100, CLS 0, TBT 0 ms, LCP 0.4 s desktop and 1.7 to 2.0 s mobile, 91 to 99 KiB
- [x] No jQuery; all JS is 9.4 KB minified (4.0 KB gzip); the CSS on an article is 87 KB minified (17.4 KB gzip: global 8.3, content 6.6, docs 1.9, loader 0.6), inside the 20 KB and 40 KB budgets
- [x] One self-hosted variable font (latin and latin-ext, preloaded); icons are SVG
- [x] Layouts verified at 390, 768, 1024 and 1440 px; 44 px targets on buttons, 24 px or more on links
- [x] Tree and contents collapse into panels above the article on tablets and phones; no hover-only controls
- [x] Templates: docs home, Docs Index, product, section, article, search with empty state, 404, FAQ, changelog, full width, full width container
- [x] Structured data parsed and shape-checked locally: WebSite + SearchAction, BreadcrumbList, TechArticle, FAQPage; escaped, filterable
- [ ] Google Rich Results test: needs a public URL, run on the Playground or staging copy before release
- [x] Content model documented (`docs/website/developer-guide/content-model.md`, `audit/manifest.json`)
- [x] Speculation rules: printed by WordPress core, nothing extra shipped
- [x] Products with icon, accent and description, accent in dark mode; docs home grid; header switcher; per-product tree; breadcrumbs from the product
- [x] Three-column article layout, copy-link on headings, code copy button, version badge
- [x] Instant search (Ctrl or Cmd + K), scoped to the product, with an all-products switch and a link fallback without JavaScript
- [x] FAQ pattern and template with FAQPage data

## H. Release (owner decisions)

- [x] Docs rewritten on `1.7.5` instead of merging master's old pages; when `1.7.5` goes into `master` the new docs replace the old ones, and `.github/workflows/ci.yml` from master is kept
- [x] `1.7.5`, first-view-and-defaults and customizer-usability are merged into `master`
- [x] Owner approved the release (2026-10-11)
- [x] Released to `vapvarun/knowx` as v2.0.0 on 2026-10-11 and re-tagged the same day to the final build (public master 86416ff, includes the breadcrumb QA fixes, zip 417 KB, public CI green with the blocking jobs, branch protection on; the Slack post went out after the retag)
- [x] `knowx-core` and `knowx-demo-importer` archived on 2026-10-11
- [ ] Switch the blueprint to the wordpress.org slug (`node tools/build-blueprint.mjs --release`)
- [ ] Rich Results test and HTTPS Lighthouse on the published copy

## J. Releasing with the release skill

To release, tell Claude: "use the release skill to release the theme". Everything the skill reads is in the repo; the owner decisions in section H still come first. Prepared 2026-10-11.

| Skill step | KnowX |
|---|---|
| Repos | Develop on `wbcomdesigns/knowx` (private, Actions off by design, branch `master`). Release to `vapvarun/knowx` (public, Actions on, branch `master`, local remote name `public`). |
| Version check (step 4) | `style.css` `Version`, `readme.txt` `Stable tag`, `package.json`, `config/config.default.json` `theme.version`. No version constant in PHP. All are 2.0.0. |
| Changelog (step 5) | `readme.txt` `= 2.0.0 - October 2026 =` in action-prefix format. The skill's `awk` for `= X.Y.Z =` also matches the Upgrade Notice heading; use the line with the date. |
| Gate A | `find . -name '*.php'` lint on PHP 8.1 to 8.4 (checked 8.1 and 8.4 in Docker), `vendor/bin/phpstan analyze --memory-limit=1G`, `vendor/bin/phpcs --standard=phpcs.xml.dist`. |
| Gate C | `docs/qa/qa-config.json` and `docs/qa/AGENT_SMOKE_RUNBOOK.md` exist. The `wp-plugin-smoke` skill writes `docs/qa/.last-smoke-pass.json` at release time (version-keyed, stale after 24 h, so it is never committed ahead). |
| Gate D | `python3 tools/build-manifest.py` before the version commit. The skill's freshness test compares commit times of `audit/manifest.json` and the source folder (`inc/` here, not `includes/`), so commit the manifest after the last change under `inc/`. Right now the manifest commit is older than the newest `inc/` commit although `build-manifest.py` produces no diff, so the timestamp test reports it stale; the generated content is current, so record that and continue, or add a harmless regenerated change to commit. |
| Build (step 6) | `npm ci && npm run build`, then package with `node scripts/distribute.js --current --no-tag` (the gated path). `npm run bundle` builds the same zip but skips the smoke gate. Output `dist/knowx-2.0.0.zip` (about 426 KB, `Version: 2.0.0`). |
| Zip check (step 7) | `distribute.js` now fails the build when the zip holds `plans/`, `audit/`, `docs/`, tests, tooling, Markdown or source maps (`assertCleanZip`). 243 entries in 2.0.0. Has source and minified `*.css` and `*.js`, `LICENSE`, `readme.txt`, `screenshot.png`, `theme.json`, `patterns/`, `template-parts/docs/`, `languages/knowx.pot`, fonts. Has no `node_modules`, tests, `playground/`, `tools/`, `docs/`, `plans/`, `audit/`, source maps, markdown, `CLAUDE.md` or build scripts. (The TypeScript sources `assets/js/customizer.tsx` and `navigation.ts` ship next to their `.min.js` files.) |
| Theme Check (step 7.5) | Install the Theme Check plugin in the Docker stack and run `wp theme-check run knowx` on the installed zip. Last run: no errors, one advisory warning (core `default` text domain for the stock Uncategorized string). |
| Docker install (step 8) | `wp theme install dist/knowx-2.0.0.zip --activate` on a fresh WordPress (`tests/docker`), then `playground/seed.php`; front end returns 200, debug log clean, `tests/docker/settings_qa.py` and `customizer-ui-check.js` pass. |
| Translations | Regenerate `languages/knowx.pot` last, with the command in the runbook (D10). |
| Merge (step 3, 9) | The public repo has an unrelated history. Create the release branch from the exact tree: `git checkout -b 2.0.0 master && git merge --allow-unrelated-histories -s ours public/master`. The branch then has the public history as an ancestor and a normal PR into `vapvarun/knowx` `master` works (checked in a throwaway clone: 0 file differences from `master`). CI on the public repo must be green (PHP lint 8.1 to 8.4 and `theme.json` validation block; PHPStan, WPCS and Stylelint are advisory). |
| Branch protection (step 2.9) | `vapvarun/knowx` `master` has none yet. The skill applies it on the first release (needs the owner's go-ahead because it changes the public repo). |
| GitHub release (step 10) | Tag `v2.0.0` on the public repo, zip attached, title `KnowX 2.0.0 - one-line summary`, body copied from the `readme.txt` bullets plus `Full changelog:` from `node ~/Code/wbcom-services/scripts/changelog-url.mjs vapvarun/knowx` (resolves to `https://wbcomdesigns.com/release-notes/knowx-theme/`). |
| After the release | `node tools/build-blueprint.mjs --release`, Rich Results test and HTTPS Lighthouse on a public copy, archive `knowx-core`, retire `knowx-demo-importer`. |

## Re-release, 2026-10-18 (branch 2.0.1 shipped as 2.0.0)

- [x] QA cleared every Ready for Testing card; owner gave the go
- [x] Smoke (single mode): 4 findings re-verified and resolved (author breadcrumb null guard, keyboard-scrollable tables, five articles per section in docs, support block wording); report `resolved[]`
- [x] `distribute.js --current --no-tag`: smoke gate OK, 243 entries, sources beside every minified file
- [x] Fresh Docker install of the zip: Theme Check one accepted warning (`default` text domain), every page type 200 or 404 as expected, no debug.log, Customizer check 10 sections with every control visible and 0 console errors
- [x] Dev `master` fast-forwarded to `b41f5dd`; public PR #12 green on all 8 checks, merged as `b1744e6`, master CI green
- [x] `v2.0.0` moved to `b1744e6`, zip replaced (sha256 matches the local build), body from the readme 2.0.0 bullets plus the release-notes link
- [x] Posted to #ready-for-release
- [x] Second retag, 2026-10-18: activation no longer sets the front page or creates content (wordpress.org consent rule); one-time setup notice with Add sample docs / Create an empty docs home / No thanks (inc/Starter_Content, BuddyX 5.1.8 model); six starter patterns. Verified on fresh Docker sites over HTTP: all three buttons, existing front page and menu kept, rerun creates nothing twice, editor and missing nonce refused (403), seed and single seed, Theme Check one accepted warning, debug.log empty. PR #13 green, merged as `6d4482a`, master CI green, tag moved, zip replaced (checksum matches)
- [x] Browser walk of the first run, 2026-10-18 (Playwright MCP, fresh Docker site from the zip): setup notice on Dashboard and Themes at 1440 and 390, Add sample docs, success notice, docs home, product, article, FAQ and changelog at 1440 and 390 in light and dark, layout picker on new post (3 article layouts) and new page (3 page layouts), no console errors, debug.log empty. Found and fixed: `wp_insert_post()` unslashed the sample content, so the curl samples lost their `\` line continuations; content is now `wp_slash()`ed, with a test that fails on the old code. Screenshots: `qa-artifacts/first-run-2026-10-18/`
- [ ] Third retag with the slashing fix
- [ ] Owner: upload 2.0.0 to wordpress.org (still lists 1.7.4)
- [ ] After it is live: `node tools/build-blueprint.mjs --release`
