# KnowX 1.7.5 Release Checklist

Branch: `1.7.5` (do not merge to `master`, tag, or release until every box below is ticked).
Release route: develop on `wbcomdesigns/knowx`, release to the `vapvarun/knowx` mirror (same as BuddyX).
Basecamp: KnowX Theme project 38311801, card table 7605386991.

Work is tracked in groups W00-W18 in `plans/WORK_GROUPS.md`; each group is one commit and feeds the sections below.

Legend: `[x]` verified, `[ ]` open. A box is ticked only after the check was run, not after the code was written.

## A. WP Rig skeleton and build

- [x] `npm ci` installs clean (Node 22)
- [x] `npm run build:css` and `npm run build:js` pass with zero drift against committed `.min` files
- [x] `npm run bundle` produces `dist/knowx-1.7.5.zip` with `Version: 1.7.5`
- [x] Version identical in `style.css`, `package.json`, `config/config.default.json`, `readme.txt` changelog
- [x] No committed zips, `.travis.yml` removed, `dist/` and `*.zip` ignored
- [x] No dead `package.json` scripts or dead `filesToCopy` paths
- [ ] Only credit line "based on WP Rig" remains in the repo (no other WP Rig strings)
- [ ] Bundle zip contains every runtime file and no dev-only file (tests, scripts, wp-cli, docs, `*.map`)
- [ ] `npm run lint` is one command (js, css, theme.json) and passes
- [ ] `npm run lint:theme-json` validates `theme.json` v3
- [ ] `npm run test:js` and `npm run test:e2e` run
- [ ] `npm run dist` bumps version, runs the smoke gate, builds the zip
- [ ] Git pre-commit hook active after `npm install` (lint-staged)
- [ ] `composer install`, then phpcs (WPCS), phpstan, phpunit unit + integration pass; failures listed, not hidden
- [ ] `knowx-core` content verified as superseded (`v3.1` branch, "Manage block style UI") before it is archived
- [ ] `CLAUDE.md`, `audit/manifest.json`, role matrix, QA journeys present and current

## B. Customizer (in-house framework, Kirki removed): 11 sections, 84 settings (90 fields incl. 6 dividers)

For every section: the control renders, the value saves, it round-trips after reload, the front end reflects it, the live preview updates, and defaults match a fresh install. No migration: existing 1.7.4 customizer values are not carried over (decision 2026-10-10). Every setting still needs a default, a sanitize callback and escaped output, and the changelog says customizer choices reset.

| Section (source file) | Fields (incl. dividers) | Verified |
|---|---|---|
| General (`General_Fields`): site layout, loader, scroll top | 5 | [ ] |
| Typography (`Typography_Fields`): site title, headings, menu, body | 13 | [ ] |
| Header (`Header_Fields`): sticky header, header background | 2 | [ ] |
| Sub Header (`Sub_Header_Fields`) | 6 | [ ] |
| Skin (`Skin_Fields`): theme colours, button styles | 11 | [ ] |
| Blog (`Blog_Fields`) | 3 | [ ] |
| Sidebar (`Sidebar_Fields`) | 3 | [ ] |
| Footer (`Footer_Fields`) | 12 | [ ] |
| Knowledge Base (`Knowledge_Base_Fields`): Layout One and Layout Two | 23 | [ ] |
| WP Login (`WP_Login_Fields`) | 9 | [ ] |
| Site Performance (`Site_Performance`) | 3 | [ ] |

- [ ] Toggle switch control works on Kirki 6.0.4 (fixed in `8a5f5bf`, re-verify in browser)
- [ ] Kirki not installed: theme still loads, admin notice and TGMPA prompt correct (`external/kirki-utils.php`)
- [ ] Kirki installed after theme activation: settings apply without re-saving
- [ ] Customizer opens with no JS console errors, no PHP notices in `debug.log`
- [ ] Reset / change of every colour and font reaches the front end (`Dynamic_Style`) and the editor styles
- [ ] Google Fonts "load locally" option serves fonts from the site and respects customizer preview
- [ ] Settings inventory generated and stored in `audit/` so a removed or renamed setting is caught

## C. Front end and templates

- [ ] Theme install and activation: no fatal, no warnings; Knowledge Base page created on activation
- [ ] Home / front page, single post, page, archive (category, tag, author), search, 404, offline, 500
- [ ] `page-knowledgebase-one.php` and `page-knowledgebase-two.php`
- [ ] `full-width.php` and `full-width-container.php`, including a full-width page with no title
- [ ] Primary navigation, dropdowns (superfish), mobile menu, sticky header
- [ ] Sidebar layouts: left, right, both, none; widgets render
- [ ] Footer layouts and widget areas
- [ ] bbPress templates (`bbpress.php`) and `bbpress.min.css` only loaded when bbPress is active
- [ ] Comments, comment reply, lazy loading, scroll-to-top, fitvids, isotope, sticky-kit
- [ ] RTL stylesheet, AMP stylesheet, PWA / offline page
- [ ] Block editor: editor styles match front end, wide and full-width alignment, block styles from `theme.json`
- [ ] Pinch-to-zoom works on mobile (viewport meta) without breaking layout
- [ ] Viewports checked: 390px, 768px, desktop. No horizontal scroll at 390px
- [ ] Dark mode / high contrast: no raw hard-coded colours that break contrast
- [ ] Accessibility: keyboard navigation, skip link, focus outline, alt text, axe scan clean
- [ ] Plugin compatibility: bbPress, WordPress Popular Posts, WooCommerce, BuddyPress

## D. Basecamp Bugs column (2 cards, both assigned to Nitin Patil)

Rule: reproduce at the exact configuration first, fix in this release, browser-verify, post proof on the card, then move it. Functional bugs are fixed without negotiation; item 5 of the first card is a decision, not a bug.

### D1. Card 10388866381 "Layout One ships lorem ipsum in the support section, and the docs describe Layout One wrongly"

- [ ] Reproduce: fresh install shows lorem ipsum in the support section (`page-knowledgebase-one.php`, `support_section_content`)
- [ ] Fix: real default sentence for the support text, no placeholder text on a fresh install
- [ ] Reproduce and fix: Popular Articles prints "No data found." when WordPress Popular Posts is inactive; hide the column or heading when there is no data
- [ ] Decide and fix: category tiles use `hide_empty => 0`, so empty categories (including Uncategorized) show; hide by default or add a toggle
- [ ] Docs: `docs/website/features/knowledge-base-homepage.md` rewritten to match the real template (tiles, Popular Articles, Recent Articles, WP Popular Posts requirement)
- [ ] Docs: the four options `knowx_posts`, `knowx_order`, `knowx_view_all`, `knowx_cat_description` documented under Layout Two only
- [ ] Browser-verified at 390px and desktop; proof posted on the card

### D2. Card 10391809639 "Layout One: Recent Articles ignores the category exclusion, plus template output issues"

- [ ] Reproduce: set "Exclude category by ID" to the newest-posts category; tile disappears but Recent Articles still lists its posts (`page-knowledgebase-one.php:116`)
- [ ] Fix: Recent Articles and Popular Articles use the same exclusion value as the tiles (`knowx_exclude_posts` and per-page `knowx-exclude-cats`)
- [ ] Markup: one `<ul>` with five `<li>` instead of five lists of one item (`page-knowledgebase-one.php:121`)
- [ ] i18n: `esc_html( 'View All &raquo;', 'knowx' )` becomes `esc_html__()` (`page-knowledgebase-two.php:106`)
- [ ] Escaping: `esc_attr()`, `esc_html()`, `esc_url()` on category name and link (`page-knowledgebase-one.php:85`, `page-knowledgebase-two.php:62`); remove the `phpcs:ignore`
- [ ] Decision for the owner: Layout One lists top-level categories only, Layout Two lists all categories flat. Align them or document the difference
- [ ] Both layouts tested with sub-categories
- [ ] Browser-verified; proof posted on the card

## E. Ready for Testing cards (4): confirm before moving to Done

- [ ] 9871419774 Compatibility with Kirki 6.0.4: Section B passes on Kirki 6.0.4
- [ ] 9637471691 Complete rebranding from WP Rig to KnowX: Section A string sweep, customizer settings split into per-section files render identically
- [ ] 9792875706 Manage pinch-to-zoom on mobile: Section C
- [ ] 9275650905 Update theme core structure with WP Rig 3.1: Nitin's 11-point checklist on the card (install, front end, customizer, assets, editor, plugins, performance, accessibility, build tools, edge cases, regression) passes on 1.7.5

## F. Large-site readiness (run on every list, grid and data method)

- [ ] Seed 1000+ posts and 500+ categories with WP-CLI; Layout One and Layout Two render in acceptable time
- [ ] Category tiles, Recent Articles and Popular Articles are bounded queries (`LIMIT`), no per-row queries in loops
- [ ] Layout Two "Posts per category" with many categories: no N+1
- [ ] Archive, search and category pages paginate
- [ ] Caching and invalidation for any shared KB data
- [ ] Empty, error and loading states for every list

## G. WordPress.org theme, Playground and sample content

KnowX is a wordpress.org theme, so the zip must pass the Theme Review rules (Theme Check plugin, no errors) and open in one click on WordPress Playground with sample content.

State today: no `starter-content`, no Playground `blueprint.json`, activation only creates one Knowledge Base home page, `Tags:` in `style.css` has only `translation-ready`, "Tested up to" is 6.8.2.

Theme Review
- [ ] Theme Check plugin: zero errors and warnings on `dist/knowx-1.7.5.zip`
- [ ] `style.css` header: allowed feature tags, "Tested up to" set to the current WordPress release, Requires at least / PHP accurate
- [ ] GPL-compatible licence for all bundled files: fonts, images, FontAwesome, isotope, superfish, TGMPA, Kirki helpers
- [ ] No plugin required for the theme to work; plugins only recommended (Kirki, WP Popular Posts, Collapsing Categories via TGMPA stay `required => false`)
- [ ] With Kirki absent, customizer options degrade to sane defaults, nothing fatal
- [ ] No tracking, no external calls without consent, no minified-only JS without its source (sources shipped or linked)
- [ ] All output escaped, all input sanitized, nonces and capabilities on every save path
- [ ] Text domain `knowx` everywhere, `knowx.pot` regenerated, no untranslated strings (the "View All" bug is one)
- [ ] `screenshot.png` 1200x900, shows the theme as shipped
- [ ] `readme.txt` complete: description, copyright for bundled resources, changelog, FAQ

Playground
- [ ] `blueprint.json` in the repo: installs and activates KnowX, installs Kirki, WordPress Popular Posts and Collapsing Categories, imports sample content, sets the home page
- [ ] Opens in Playground in one click from the wordpress.org theme preview and from the docs site
- [ ] Customizer, Layout One and Layout Two all work inside Playground (no filesystem or cron assumptions)
- [ ] Blueprint pinned to a theme release, not to a moving branch

Sample content
- [ ] WordPress `starter-content` registered: nav menus, widgets, a Knowledge Base home page, sample pages, posts
- [ ] Sample knowledge base: 6+ categories with sub-categories, 30+ articles, featured images, a News category that Layout One excludes
- [ ] Sample content passes the same checks (no lorem ipsum, no broken images, all licences clean)
- [ ] Demo importer decision: `knowx-demo-importer` (2021, dead) retired, starter content or the blueprint replaces it

## I. Docs website: speed, devices, templates, schema

- [ ] Lighthouse CI (mobile, throttled) on docs home, article, category, search: performance 95+, LCP under 2.0 s, CLS under 0.05
- [ ] No jQuery on the front end; JS under 20 KB gzip and CSS under 40 KB gzip per page
- [ ] One self-hosted variable woff2 font, preloaded; FontAwesome replaced by inline SVG
- [ ] Layouts verified at 390, 768 (iPad portrait), 1024 (iPad landscape) and 1440 px; no horizontal scroll; 44 px touch targets
- [ ] Sticky table of contents and sidebar drawer behave on tablet; hover-only UI has a tap alternative
- [ ] Templates present: docs home (two layouts), section index, article, search, 404, offline, changelog, FAQ, getting started, full width
- [ ] Structured data valid in the Rich Results test: WebSite + SearchAction, BreadcrumbList, TechArticle, FAQPage; escaped, filterable, can be turned off
- [ ] Content model documented (`docs/website/developer-guide/content-model.md`, `audit/manifest.json`)
- [ ] Both KB layouts share one exclusion setting
- [ ] Speculation rules prefetch works without extra requests on slow connections
- [ ] Product model: top-level category = product, with icon, description and accent colour term meta; accent works in dark mode
- [ ] Docs home product grid, header product switcher, per-product left tree, breadcrumbs starting at the product
- [ ] 3-column article layout; iPad drawer; phone contents dropdown; copy-link on headings; code copy button
- [ ] Instant search overlay (Cmd/Ctrl+K) scoped to current product with all-products toggle; no-JS fallback
- [ ] FAQ Details-block pattern emits valid FAQPage structured data
- [ ] Per-article version badge
- [ ] Sample content: two products, FAQ, changelog, getting started

## H. Release

- [ ] All sections above ticked; Basecamp cards moved with proof comments
- [ ] `readme.txt` changelog in action-prefix format, no em-dashes, no emoji
- [ ] `master` synced into `1.7.5` (CI and `docs/website`), CI green
- [ ] `npm run dist` with a fresh browser smoke report for 1.7.5
- [ ] Merge to `master` only after the user approves
- [ ] Release to `vapvarun/knowx` with the clean zip attached; `knowx-core` archived
