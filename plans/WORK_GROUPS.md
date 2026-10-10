# KnowX work groups: one commit per group, tracked here

Branch `1.7.5` (do not merge to `master`, tag or release until every group is Done and the user approves).
Source of truth for the work: `audit/code-audit-2026-10-10/` (A = PHP inc, B = templates, C = CSS+JS), `plans/2026-10-10-kirki-removal-and-tokens.md`, `docs/qa/RELEASE_CHECKLIST.md`.
Decisions in force: no Kirki, hybrid = classic theme + block features, existing sites are not a constraint (~200 installs, many test), release route develops on wbcomdesigns and releases to the vapvarun mirror, fast docs site on 390/768/1024/1440 with schema.org markup.

## How tracking works
- Each group has a Basecamp card in the KnowX board, column Ready for Development (created 2026-10-10). Move the card with the group: In Development when started, Ready for Testing when its gate passed, Done when verified. Post the proof (commit id, screenshots, command output) as a comment on the card.
- One group = one commit (split into more commits only when a group is large). Commit subject starts with the group id, e.g. `W04: fix Layout One recent articles`.
- A group is Done only when its **gate** passed, not when the code was written. Tick the boxes in the same commit and move the status line.
- Each group lists the checklist section it feeds (`RELEASE_CHECKLIST.md` letters) and audit references (A/B/C + section).
- Status values: `todo`, `doing`, `done`, `blocked`.

## Execution order and rules for the autonomous run (user, 2026-10-10)
The user authorised working through the whole sequence without stopping, pushing fixes to `origin/1.7.5` as each group lands, and a full double check at the end. Still not allowed without a new instruction: merging to `master`, tagging, releasing to the vapvarun mirror, archiving `knowx-core`.

Order (reordered so no effort is spent linting code that is about to be deleted): W00 done, **W02, W01, W03, W05, W04, W06, W07, W08, W09, W10, W11, W12, W13, W14, W15, W16, W17, W18**.

Per group: implement, run the gates, check in the browser (headless screenshots via `scratchpad/shots.sh` compared with `compare -metric AE`, plus interactive checks in Chrome), commit with the group id, push, comment proof on the Basecamp card and move it to Ready for Testing. Local test site: `http://knowx.local` (WP-CLI wrapper in the session scratchpad, theme active, 37 seeded posts in 3 products, `WP_DEBUG_LOG` on). Baseline screenshots: `~/Local Sites/knowx/app/qa-artifacts/baseline-w00/`.

## Status board

| Id | Group | Status | Basecamp card |
|---|---|---|---|
| W00 | Trustworthy gates (tooling) | done | [10392494216](https://app.basecamp.com/5798509/buckets/38311801/card_tables/cards/10392494216) |
| W01 | Lint debt cleared | done | [10392494270](https://app.basecamp.com/5798509/buckets/38311801/card_tables/cards/10392494270) |
| W02 | Dead code, core duplicates and repo hygiene | done | [10392494288](https://app.basecamp.com/5798509/buckets/38311801/card_tables/cards/10392494288) |
| W03 | Compatibility headers and theme-review blockers | done | [10392494297](https://app.basecamp.com/5798509/buckets/38311801/card_tables/cards/10392494297) |
| W04 | Basecamp Bugs cards and KB template defects | todo | [10392494317](https://app.basecamp.com/5798509/buckets/38311801/card_tables/cards/10392494317) |
| W05 | Security and correctness hardening | todo | [10392494332](https://app.basecamp.com/5798509/buckets/38311801/card_tables/cards/10392494332) |
| W06 | Visual baseline and settings inventory | todo | [10392494340](https://app.basecamp.com/5798509/buckets/38311801/card_tables/cards/10392494340) |
| W07 | Customizer_Framework port, Kirki removed | todo | [10392494356](https://app.basecamp.com/5798509/buckets/38311801/card_tables/cards/10392494356) |
| W08 | Settings redesign and audit | todo | [10392494374](https://app.basecamp.com/5798509/buckets/38311801/card_tables/cards/10392494374) |
| W09 | Tokens, dark mode, colour output | todo | [10392494401](https://app.basecamp.com/5798509/buckets/38311801/card_tables/cards/10392494401) |
| W10 | JavaScript: ordering, jQuery, libraries | todo | [10392494423](https://app.basecamp.com/5798509/buckets/38311801/card_tables/cards/10392494423) |
| W11 | CSS: token migration, RTL, focus, responsive | todo | [10392494445](https://app.basecamp.com/5798509/buckets/38311801/card_tables/cards/10392494445) |
| W12 | Fonts and icons | todo | [10392494459](https://app.basecamp.com/5798509/buckets/38311801/card_tables/cards/10392494459) |
| W13 | theme.json, style variations, editor | todo | [10392494471](https://app.basecamp.com/5798509/buckets/38311801/card_tables/cards/10392494471) |
| W14 | Docs templates and structured data | todo | [10392494490](https://app.basecamp.com/5798509/buckets/38311801/card_tables/cards/10392494490) |
| W15 | Starter content, patterns, Playground | todo | [10392494499](https://app.basecamp.com/5798509/buckets/38311801/card_tables/cards/10392494499) |
| W16 | Large-site and speed | todo | [10392494519](https://app.basecamp.com/5798509/buckets/38311801/card_tables/cards/10392494519) |
| W17 | i18n, docs, manifest, QA journeys | todo | [10392494541](https://app.basecamp.com/5798509/buckets/38311801/card_tables/cards/10392494541) |
| W18 | Release gate | todo | [10392494558](https://app.basecamp.com/5798509/buckets/38311801/card_tables/cards/10392494558) |

---

## W00 Trustworthy gates (feeds A)
Why first: today the gates lie, so nothing after this can be proven. (tooling audit 2026-10-10)
- [x] phpcs: PHP only, `assets/`, `scripts/`, `dist/`, `optional/dev`, TGMPA and `setup-wp-env.php` excluded; minimum WP 6.9, `testVersion` 8.1-. Real PHP now reports 189 errors / 82 warnings (was 4256 / 2127 with CSS and JS counted)
- [x] phpstan: WordPress stubs loaded, baseline regenerated (148 entries, mostly Kirki classes that vanish in W07); real bugs recorded under W05 below
- [x] phpunit: unit suite 10/10 green (Accessibility test mocks `is_amp` and `esc_attr__`; duplicate `custom-header` / `custom-background` support removed from `Base_Support`). Integration suite needs the WordPress test database and is not run yet
- [x] jest: duplicate config removed (setup file now in `jest.config.cjs`); `npm run test:js` runs (no JS tests exist yet)
- [x] playwright: no e2e script is exposed until specs exist (`tests/e2e/specs` is missing); added in W16/W17
- [x] `package.json` scripts added: `lint`, `lint:theme-json`, `test`, `test:js`, `dist`. `docs:check` moves to W17 because the docs-manifest tools do not exist yet
- [x] `simple-git-hooks` + `lint-staged` configured and installed. On this machine git uses the global `core.hooksPath` (`~/.config/git/hooks`), so the local pre-commit does not fire here; run `npm run lint` and the gates manually before committing, or chain the hook globally
- [x] untracked generated artifacts (`tests/phpunit/.wp-tests-setup-complete`, `scripts/tests/prod-build/config.local.json`) and ignored them; `config/config.json` stays (theme identity)
- [x] release JS no longer ships inline source maps (`build-js.js`; min.js in the zip 284 KB to 60 KB, zip 1.1 MB to 1.0 MB). The `.ts` / `.tsx` / `.cjs` sources copied beside the minified files are intentional: wordpress.org requires readable source for minified JS
- [ ] `composer.json` platform and `Requires PHP` agree: done in W03 with the version decision (PHP 8.1, WP 6.9)
- Gate: every command runs and reports a real number. **Passed 2026-10-10.**

| Gate | Result |
|---|---|
| `npm run lint:theme-json` | pass (1 file) |
| stylelint (`assets/css/src`) | 1870 errors (W01) |
| eslint (`assets/js/src`) | 1047 errors (W01) |
| phpcs (real PHP) | 189 errors, 82 warnings (W01) |
| phpstan | OK with baseline of 148 |
| phpunit unit | OK, 10 tests, 18 assertions |
| jest | runs, no tests |
| `php -l` | 0 failures |
| bundle zip | 1.0 MB, `Version: 1.7.5` |

## W01 Lint debt cleared (feeds A)
Run after W02 so no effort went into code that was deleted.
- [x] stylelint: 1870 errors to **0**. Autofix (1,676), `.stylelintrc` aligned with BuddyX (it disables the rules that need risky rule reordering: `no-descending-specificity`, `no-duplicate-selectors`, id/class patterns, unit and generic-family rules), 6 manual fixes (named colours, dead shorthand-override properties)
- [x] eslint: 1047 errors to **0**. Autofix, `custom.js` fixed (no-var, strict equality, camelCase); vendored libraries (isotope, superfish, sticky-kit, fitvids, scrollUp) and the Kirki `customizer-controls.js` ignored with a comment because W10 / W07 remove or replace them. The localized flag is renamed `knowxData.enableScrollup` and tested for truthiness (the old `=== true` would have broken scroll-up because `wp_localize_script` turns booleans into strings)
- [x] phpcs: 4256 errors (W00) to 40 on real PHP. `phpcbf` applied; Yoda conditions and comment punctuation fixed; `entry_meta.php`, `navigation.php`, `functions.php` fixed; the duplicate `KnowX Categories` widget (a copy of core's Categories widget) removed
- [x] Files scheduled for rewrite are excluded in `phpcs.xml.dist` with the group that owns them: `external/` and `inc/Kirki_Option/` (W07), `inc/Webfont/` (W05, W12), `inc/class-knowx-breadcrubms.php` (W14)
- [ ] Remaining 40 phpcs errors are owned by later groups: `inc/extra.php` 28 (W05: post-view counter, meta box, docblocks), `page-knowledgebase-one.php` 1 (W04), `searchform.php` 1 (W03), `tests/` 11 (W17: test framework docblocks)
- Gate: CSS and JS lint exit 0; rebuilt assets show **0.00%** pixel difference against the W00 baseline at 390 / 768 / 1024 / 1440 on home, post, category, search and 404; no console errors beyond jQuery Migrate info; `debug.log` empty. **Passed 2026-10-10.**

## W02 Dead code, core duplicates and repo hygiene (feeds A, G)
Verified with `git grep` before every delete. Principle added 2026-10-10: do not re-implement what WordPress core provides (see `docs/LIBRARIES.md`, section 1).
- [x] Dead code removed: `optional/` (incl. the livereload injection file), `sidebar.php`, `inc/EZ_Customizer/`, `inc/Blocks/`, `inc/Options/` (dead admin page, live REST route, React bundle) and its `assets/js/admin` + `src/admin`, `inc/Kirki/` (config only; `inc/Kirki_Option` stays until W07), `wp-cli/`, `global.ts`/`global.min.js`
- [x] `scripts/`: block tooling, `convert-to-block-theme.js`, `dev-modern.js`, `start.js`, block templates and the WP Rig prod-build tests removed; matching npm scripts removed
- [x] Dead functions removed: `knowx_site_menu_icon`, `Template_Tags::get_theme_asset`, `Comments::filter_comment_form_defaults`, `knowx_is_login_page`; three `login.php` branches that used undefined variables
- [x] Core duplicates and integrations removed: custom `knowx_wp_body_open` shim (now core `wp_body_open()`), `wordpress-shims.php`, `back-compat.php` and the version gate (core enforces `Requires at least` / `Requires PHP`), `Localization` loader, `PWA` (service worker support, `offline.php`, `500.php` and their template parts), `AMP` (component, stylesheet, markup branches in navigation, comments, styles, accessibility), `Jetpack`, IE11 skip-link focus fix, duplicate `aria-current` filters
- [x] bbPress and Elementor support removed (decision "keep nothing extra"): `bbpress.php`, `content-bbpress.php`, bbPress CSS, bbPress sidebars and layout setting, Elementor location wrappers in six templates
- [x] 1,145 unused npm packages removed (block editor stack, react, cssnano, esm, tiny-lr and others); `lightningcss` declared (it used to arrive only through `@wordpress/scripts`)
- [x] Added `docs/LIBRARIES.md`: every shipped library with where it is used, why, and a keep / replace / remove verdict (replacements scheduled in W10 to W12)
- [ ] Still to do in later groups: `knowx_metabox_callback` / `knowx-exclude-cats` decision (W04), Kirki-tied assets (W07), duplicate `get_version`/`get_asset_version` in `Fonts` (W12), duplicate footer-copyright branches (W05)
- Gate: build, bundle, phpunit (7 tests), phpstan (baseline 136), `php -l` clean; home, post, category, search and 404 screenshots at 390 / 768 / 1024 / 1440 show **0.00%** pixel difference against the W00 baseline. **Passed 2026-10-10.**

## W03 Compatibility headers and theme-review blockers (feeds A, G)
- [x] Minimum versions decided and applied: **PHP 8.1, WordPress 6.9**, tested up to 7.1 in `style.css` and `readme.txt`; `Stable tag` is the version (not `trunk`); composer platform PHP 8.1; phpcs `testVersion` 8.1-. The functions.php gate was removed in W02 because core enforces the headers
- [x] `wp_body_open()` is core (W02)
- [x] HTML nesting: the `.container` opened in `header.php` and closed in `footer.php` is now decided in one place (`knowx_template_uses_container()`), so Layout One and `full-width-container.php` no longer add a stray `</div>`; `<div>` open/close counts match on home, post, category, search, 404 and all three template test pages
- [x] `searchform.php` rewritten: `role="search"`, visible-to-screen-readers label, `type="search"`, unique ids, the clear control is a `<button>` with an accessible name; CSS selectors moved from `#searchform` to `.searchform`
- [x] No duplicate ids on home, post, 404 or search: mobile menu is `#primary-menu-mobile` (toggle `aria-controls` updated), left sidebar is `#secondary-left`, the mobile "Close" control is a `<button>`
- [x] Sidebar layout logic fixed: `Sidebars::filter_body_classes` checked the right sidebar twice and never the left in "both" mode; strict comparisons; `sidebar.php` was removed in W02
- [x] `theme.json`: removed `customTemplates` / `templateParts` that pointed at files that do not exist; schema pinned to 6.9
- [x] `style.css` / `readme.txt` tags: feature and subject tags that are true today (`accessibility-ready` is not claimed until W11)
- [ ] `readme.txt` FAQ, Upgrade Notice and Resources sections: written in W17 with the final feature set
- Gate: PHP lint clean, phpunit 7 tests, phpstan OK (baseline regenerated), CSS/JS lint 0, theme.json validates; screenshots at 390 / 768 / 1024 / 1440 differ from the baseline by at most 0.01% (the search results page, from the new search form markup); no duplicate ids; `debug.log` empty. **Passed 2026-10-10.**

## W04 Basecamp Bugs cards and KB template defects (feeds D, F)
Cards 10388866381 and 10391809639 plus the related defects from B section 1. Reproduce first, fix, browser-verify, post proof, move card.
- [ ] Layout One: lorem ipsum default in the support section (`page-knowledgebase-one.php:149`) replaced by a real sentence; dead button `esc_url( '#' )` without `echo` (line 158) fixed
- [ ] Popular Articles: native fallback (most commented or most recent) when WordPress Popular Posts is inactive; no "No data found."
- [ ] Empty categories hidden by default (`hide_empty`), no "Uncategorized" tile on a new site
- [ ] Recent Articles and Popular Articles honour the same category exclusion as the tiles; one setting (today `knowx_exclude_posts` in Layout One vs `knowx_exclude` in Layout Two, same label)
- [ ] The per-page `knowx-exclude-cats` meta: register the meta box or remove the meta
- [ ] Recent Articles markup: one `<ul>` with five `<li>` (`:116-123`)
- [ ] Layout Two: `cat-description` `<div>` directly inside `<ul>` fixed; `orderby` title not slug (line 77); bounded `get_categories` and one grouped post query instead of `get_posts` per category (line 99, `posts_per_page` -1)
- [ ] Layout Two page title: needs a default (`knowx_page_title`) since Kirki defaults vanish
- [ ] `esc_html( 'View All &raquo;', 'knowx' )` becomes `esc_html__()` (`page-knowledgebase-two.php:106`)
- [ ] Escape `$category->name`, `get_category_link()` in title attr and link text (`:85`, `:62`); remove blanket `phpcs:ignore`
- [ ] Layouts One and Two list the same set of categories, or the difference is a documented option
- [ ] Docs `docs/website/features/knowledge-base-homepage.md` rewritten to match (after `master` sync, see W17)
- Gate: both cards reproduced then fixed; 390px and desktop screenshots; seeded 1000+ posts / 500+ categories run in acceptable time.

## W05 Security and correctness hardening (feeds A, G)
From A section 4. Real PHP bugs found by phpstan (W00) to fix here: `inc/login.php:122,125,136,145` undefined variables; `inc/class-knowx-breadcrubms.php:337,1349` missing returns, `:704` uses a void result, `:914` `get_day_link` argument count; `inc/back-compat.php:47` `WP_CLI` without guard; `inc/EZ_Customizer/Component.php` class name case (file removed in W02).
- [ ] Webfont loader (`inc/Webfont/class-knowx-webfont-loader.php`): remove entirely (zero callers); it `include`s remote-fetched CSS as PHP (`:238`), saves font files under remote names, recurses forever on empty fetch, can delete the uploads directory, schedules a non-existent `'monthly'` event. Replaced in W12
- [ ] `setPostViews()` (`inc/extra.php:257`, `single.php:42`): remove (plugin territory, DB write per view, breaks caching)
- [ ] AJAX / REST: nonce on `knowx_regenerate_fonts_folder` (removed with the loader), remove `my-theme/v1/settings` route and `Options` component
- [ ] Activation (`Activation/Component.php:46-72`): do not overwrite an existing front page; check page slug before creating; later superseded by starter content in W15
- [ ] `inc/extra.php:234-250`: unslash and sanitize `$_POST`
- [ ] `knowx_categorized_blog()` (`extra.php:163`) uses `wp_count_terms`; fix the never-expiring transient
- [ ] Breadcrumbs `class-knowx-breadcrubms.php`: parenthesis bug line 914, `esc_url()` around HTML line 942, undefined `$post`, unescaped term names, microdata over http (also renamed: file name is misspelled)
- [ ] Unescaped post title in the `the_content()` more-link text (`entry_content.php`, `entry-full-width.php`, `content-bbpress.php`) and `entry_actions.php`
- [ ] `login.php`: undefined variables, colour passed through `esc_url`
- [ ] TGMPA and `wp-admin/includes/plugin.php` loaded on every front-end request (`functions.php:26,72-75`) removed in W07
- Gate: phpcs security sniffs clean; no `phpcs:ignore` blankets on escaping; manual review of each touched output.

## W06 Visual baseline and settings inventory (feeds B)
No longer a byte-identical migration gate (existing sites are not a constraint); this is a visual reference and a diffable list.
- [ ] Settings inventory script ported from BuddyX (`tools/dump-customizer-inventory.py`) and run: id | type | default | where read. A found 84 real settings (90 Kirki objects, 6 dividers) and 4 settings nobody reads (`site_load_google_font_locally`, `site_preload_local_font`, `site_sub_header_bg`, `site_footer_bg`)
- [ ] Screenshots of home, KB1, KB2, post, archive, search, 404, login at 390 / 768 / 1024 / 1440 stored under `~/Local Sites/knowx/app/qa-artifacts/`
- [ ] Local Playground / test site has the theme active with sample posts so later groups can be checked
- Gate: inventory file and screenshots exist.

## W07 Customizer_Framework port, Kirki removed (feeds B)
Plan phase 1. Copy BuddyX `inc/Customizer_Framework/` and rewrite namespace to `KnowX\KnowX`.
- [ ] Framework registered in `Theme.php`; `inc/Kirki_Option` renamed `inc/Customizer_Settings`; `\Kirki\Field\X::add` becomes `Field::add('<type>', ...)`; panels and sections use the framework
- [ ] `class_exists('Kirki')` gates removed so panels, fields, `Dynamic_Style` and login styling always run (A section 2: without Kirki all 24 colours, typography, backgrounds, container width, login customisation vanish)
- [ ] `knowx_defaults()` moved out of `external/kirki-utils.php`; every `get_theme_mod()` has a default
- [ ] Delete: `inc/Kirki/`, `external/kirki-utils.php`, TGMPA (`class-tgm-plugin-activation.php`, `require_plugins.php`), `customizer-controls.js`, Kirki PNG pickers
- [ ] Popular Posts and Collapsing Categories become optional, documented, never prompted
- [ ] Every setting has `sanitize_callback` (wp.org blocker if missing)
- Gate: `git grep -i kirki` only in plans and docs history; every panel opens; no PHP errors or console errors.

## W08 Settings redesign and audit (feeds B)
- [ ] Section topology mirrors BuddyX plus a KnowX Knowledge Base panel; unused settings dropped; duplicates merged (`knowx_exclude` vs `knowx_exclude_posts`)
- [ ] `output` CSS or `refresh` transport for every setting; no `postMessage` without preview JS (7 WP Login settings today, A section 4.19)
- [ ] Defaults chosen once from BuddyX/TT5 practice (not from Kirki-era values); `site_loader` default off
- [ ] Write new settings as 0/1 toggles (no legacy `'on'`/`'off'` handling needed)
- [ ] Three-pass audit simplified: save round-trip, per-field render, live preview
- Gate: each of the 11 sections ticked in checklist B.

## W09 Tokens, dark mode, colour output (feeds B, I)
- [ ] `inc/Tokens`, `Color_Mode_Toggle` copied; `$simple_color_tokens` mapped to KnowX settings (24 colour settings and 13 `output` rules, C section 3)
- [ ] `Dynamic_Style` replaced by Tokens + Output_Builder; one `:root` block; no `!important`
- [ ] Static fallbacks now equal the token defaults (C: line-height 1.4 vs 1.6, hero `#21b4db` vs `#03a9f4`, title `#333332` vs `#333` differ today)
- [ ] Undefined variables fixed (`--border-color-dark`, `--color-link-active`), `--font-size-small` larger than regular fixed; one palette slug set across `theme.json`, `Editor/Component.php`, CSS
- Gate: default install renders consistently with Kirki absent; dark mode passes contrast.

## W10 JavaScript: ordering, jQuery, libraries (feeds I)
From C section 4 and 5.
- [ ] Remove `async` from dependent scripts (`Scripts/Component.php:178-240`, `Base_Support:150`); add missing `jquery` and `knowx-scroll-up` deps; or load with `defer` and proper deps
- [ ] `$(window).load()` (`custom.js:264`) replaced; loader overlay cannot stay up on failure
- [ ] `navigation.ts` inverted expand/collapse labels; `aria-expanded` sync; 55em JS vs 768px CSS breakpoint conflict
- [ ] Drop libraries CSS or native features replace, per `docs/LIBRARIES.md`: superfish, sticky-kit, fitvids, scrollUp, isotope (CSS grid, load masonry only where needed); no jQuery on the front end; use `strategy => defer` instead of the custom async filter
- [x] Production build strips inline source maps (done in W00); `.ts/.tsx` sources beside minified files stay (wp.org requires readable source)
- [ ] No `console.log` leftovers; no `innerHTML` with data
- Gate: console clean on every template; JS under 20 KB gzip per page.

## W11 CSS: token migration, RTL, focus, responsive (feeds C, I)
From C section 2, 3, 7.
- [ ] 143 hard-coded colour uses in 21 files (60 distinct) moved to tokens
- [ ] `:focus-visible` everywhere (zero today), outline restored on `.wp-element-button` and `select`; `prefers-reduced-motion` honoured (infinite loader animation)
- [ ] RTL: logical properties instead of ~170 physical declarations; delete the separate 18 KB `rtl.min.css` or load it correctly
- [ ] Drop Bootstrap-3 grid (about 12 KB for 3 classes), unused FSE block CSS (about 9 KB), bbPress CSS (support removed in W02), per-template CSS split
- [ ] Missing version args on `bbpress`, `rtl`, `amp` styles
- [ ] Responsive at 390 / 768 / 1024 / 1440 (iPad portrait and landscape): sticky ToC, sidebar drawer, 44 px targets, no horizontal scroll
- [ ] Accessibility: skip link, headings order (Layout One `h1` outside `<main>`, Layout Two missing `h1`), `aria-label`s on search toggle and scroll-up
- Gate: stylelint clean; axe scan clean; screenshots at four widths.

## W12 Fonts and icons (feeds I)
- [ ] `inc/Fonts` + Google catalog (value-driven loader) ported or dropped in favour of self-hosted variable woff2 only
- [ ] Google URL fixed (Montserrat requested but unused; `900&display=swap` inside variant), preload, `font-display: swap`
- [ ] FontAwesome 4.7 replaced by inline SVG for the 11 glyphs used; remove 910 KB of eot / svg / ttf / otf
- [ ] `sslverify => false` in font download removed with the old downloader
- Gate: one font request set; zero FontAwesome files; Network panel shows only selected fonts.

## W13 theme.json, style variations, editor (feeds C)
- [ ] `theme.json` v3 is the design source (palette, font sizes, spacing, layout widths); `--bx-*` tokens read `--wp--preset--*`
- [ ] Eight BuddyX style variations in `styles/`
- [ ] Editor styles match the front end; palettes consistent
- Gate: `lint:theme-json` passes; editor and front end compared side by side.

## W14 Docs product: multi-product, search, FAQ, templates, structured data (feeds I)
Decisions in plan section 12.
- [ ] Product model: top-level category = product; term meta for icon, short description, accent colour (sanitised, escaped, edited on the category screen); token integration so accent works in dark mode
- [ ] Docs home: product card grid (icon, description, article count), header product switcher, breadcrumbs start at the product
- [ ] Article layout: 3 columns (collapsible section tree, article, sticky "On this page"); iPad drawer; phone contents dropdown; copy-link on headings; code-block copy button; last updated; previous/next; related
- [ ] Section tree and product grid queries bounded and cached with invalidation on term/post changes
- [ ] Instant search overlay on `/wp/v2/search` (Cmd/Ctrl+K), scoped to current product with "all products" toggle, keyboard and screen-reader accessible, no-JS fallback to the search page
- [ ] FAQ: Details-block pattern, FAQ page template, optional FAQ-category accordion archive
- [ ] Per-article "applies to version" label (post meta) shown as a badge
- [ ] Templates: product home, section index, article, search results with empty state, changelog, FAQ, getting started, full width
- [ ] schema.org: WebSite + SearchAction, BreadcrumbList, TechArticle, FAQPage generated from Details blocks; escaped, filterable, can be switched off
- [ ] Content model documented (`docs/website/developer-guide/content-model.md`, `audit/manifest.json`)
- Gate: Rich Results test passes on article, FAQ and home; search works with keyboard only; product switcher works at 390 / 768 / 1024 / 1440.

## W15 Starter content, patterns, Playground (feeds G)
- [ ] `inc/Starter_Content`: two sample products (3 sections and 8-10 articles each), FAQ page, changelog page, getting-started article, menus, widgets, KB layout pages, no lorem ipsum
- [ ] KB patterns in `patterns/` with docblock headers and registered categories
- [ ] `blueprint.json` installs and activates the theme and imports the sample content (no Kirki step); opens in Playground in one click
- [ ] Remove the `knowx-demo-importer` dependency; retire that repo
- Gate: fresh Playground load shows a complete docs site on all four widths.

## W16 Large-site and speed (feeds F, I)
- [ ] Seed 1000+ posts and 500+ categories with WP-CLI; check KB1, KB2, archives, search
- [ ] Lighthouse CI on four page types, mobile and desktop: performance 95+, LCP under 2.0 s, CLS under 0.05
- [ ] No unbounded queries; caching with invalidation for shared KB data; speculation rules on
- Gate: numbers recorded in this file.

## W17 i18n, docs, manifest, QA journeys (feeds A, G)
- [ ] Merge `master` into `1.7.5` (CI and `docs/website`), user approval first
- [ ] Rewrite `docs/website` for the new settings (no Kirki install step)
- [ ] Regenerate `languages/knowx.pot` (stamped 1.7.4, dated 2026-03-05)
- [ ] `CLAUDE.md`, `audit/manifest.json`, role matrix, QA journeys, `docs-manifest-check` and coverage checks wired
- Gate: `docs:check` passes; docs lint passes.

## W18 Release gate (feeds H)
- [ ] All groups Done and all checklist boxes ticked
- [ ] Full gate set green: lint, phpcs, phpstan, phpunit, jest, Theme Check, Lighthouse, axe
- [ ] Browser smoke walk for 1.7.5 / the new version; Basecamp cards (2 Bugs, 4 Ready for Testing) verified with proof
- [ ] Version **2.0.0** applied across `style.css`, `package.json`, `config`, `readme.txt`; changelog says customizer choices reset
- [ ] User approves merge to `master`; release to `vapvarun/knowx` with the clean zip; archive `knowx-core` after confirming its `v3.1` work is in `1.7.5`
