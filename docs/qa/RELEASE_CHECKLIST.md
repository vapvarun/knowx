# KnowX 2.0.0 Release Checklist

Branch `1.7.5`, releasing as **2.0.0**, PHP 8.1 and WordPress 6.9 minimum, tested up to 7.1. Do not merge to `master`, tag or release before the open boxes in section H are closed by the owner.
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
- [x] The zip has no tests, scripts, `playground/`, `tools/`, `rector.php`, source maps or markdown; it has `patterns/`, `styles/`, `template-parts/docs/`, `languages/knowx.pot`
- [x] `npm run lint` is one command (JS, CSS, theme.json) and passes with 0 problems
- [x] `npm test` runs the PHPUnit unit suite (33 tests, 67 assertions). Jest, `jest.config.cjs` and the unused Playwright config were removed because there are no JS unit tests
- [x] `vendor/bin/phpstan analyze`: no errors, no baseline
- [x] `vendor/bin/phpcs --standard=phpcs.xml.dist`: 0 errors, 27 warnings in legacy templates and test helpers
- [x] PHPUnit integration suite: 12 tests pass in Docker on WordPress 7.1.3 (`tests/docker/run-integration.sh`)
- [ ] Git pre-commit hook: `simple-git-hooks` installs it, but the global `core.hooksPath` on this machine bypasses it; run the gates by hand
- [x] `knowx-core` is superseded: its `v3.1` branch has two commits (WP Rig 3.1 structure, "Manage block style UI") and 1.7.5 contains `_blocks-style.css` and the rebuilt toolchain. Archive after the release
- [x] `CLAUDE.md`, `audit/manifest.json` (generated), `docs/qa/ROLE_MATRIX.md`, `docs/qa/JOURNEYS.md` exist
- [ ] `npm run dist` with a fresh smoke report: `docs/qa/.last-smoke-pass.json` is written by the final run; `dist` bumps the version, so run it as `npm run dist -- --current`

## B. Customizer (in-house framework, Kirki removed)

43 settings in 10 sections (`docs/customizer-inventory.md`, generated; every setting has a reader or output CSS). No migration from 1.7.4 (decision 2026-10-10): the changelog says Customizer choices reset.

- [x] Opened in the real Customizer over CDP: 48 settings all have a control, 47 sections render, no console error, no PHP notice
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
- [ ] Owner checks the whole flow, then approves the merge of `1.7.5` into `master`
- [ ] Release to `vapvarun/knowx` with the clean zip; switch the blueprint to the wordpress.org slug; archive `knowx-core`; retire `knowx-demo-importer`
- [ ] Rich Results test and HTTPS Lighthouse on the published copy
