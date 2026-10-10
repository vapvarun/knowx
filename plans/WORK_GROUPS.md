# KnowX work groups: one commit per group, tracked here

Branch `1.7.5` (do not merge to `master`, tag or release until every group is Done and the user approves).
Source of truth for the work: `audit/code-audit-2026-10-10/` (A = PHP inc, B = templates, C = CSS+JS), `plans/2026-10-10-kirki-removal-and-tokens.md`, `docs/qa/RELEASE_CHECKLIST.md`.
Decisions in force: no Kirki, hybrid = classic theme + block features, existing sites are not a constraint (~200 installs, many test), release route develops on wbcomdesigns and releases to the vapvarun mirror, fast docs site on 390/768/1024/1440 with schema.org markup.

## How tracking works
- One group = one commit (split into more commits only when a group is large). Commit subject starts with the group id, e.g. `W04: fix Layout One recent articles`.
- A group is Done only when its **gate** passed, not when the code was written. Tick the boxes in the same commit and move the status line.
- Each group lists the checklist section it feeds (`RELEASE_CHECKLIST.md` letters) and audit references (A/B/C + section).
- Status values: `todo`, `doing`, `done`, `blocked`.

## Status board

| Id | Group | Status |
|---|---|---|
| W00 | Trustworthy gates (tooling) | todo |
| W01 | Lint debt cleared | todo |
| W02 | Dead code and repo hygiene | todo |
| W03 | Compatibility headers and theme-review blockers | todo |
| W04 | Basecamp Bugs cards and KB template defects | todo |
| W05 | Security and correctness hardening | todo |
| W06 | Visual baseline and settings inventory | todo |
| W07 | Customizer_Framework port, Kirki removed | todo |
| W08 | Settings redesign and audit | todo |
| W09 | Tokens, dark mode, colour output | todo |
| W10 | JavaScript: ordering, jQuery, libraries | todo |
| W11 | CSS: token migration, RTL, focus, responsive | todo |
| W12 | Fonts and icons | todo |
| W13 | theme.json, style variations, editor | todo |
| W14 | Docs templates and structured data | todo |
| W15 | Starter content, patterns, Playground | todo |
| W16 | Large-site and speed | todo |
| W17 | i18n, docs, manifest, QA journeys | todo |
| W18 | Release gate | todo |

---

## W00 Trustworthy gates (feeds A)
Why first: today the gates lie, so nothing after this can be proven. (tooling audit 2026-10-10)
- [ ] phpcs: drop `css` from `extensions`, exclude `assets/`, `tests/`, `scripts/`, `optional/dev`, stale `*/gulp/tests/*`; set minimum WP version to match the header. Target: real PHP only (today inc 110, template-parts 6, root 24 errors)
- [ ] phpstan: load WordPress stubs (`php-stubs/wordpress-stubs` bootstrap), regenerate the stale baseline (72 unmatched ignores)
- [ ] phpunit: fix the failing Base_Support test (expected theme-support list) and the Accessibility error; both suites green
- [ ] jest: remove the duplicate config (`package.json` `jest` key vs `jest.config.cjs`); `npm run test:js` runs
- [ ] playwright: `testDir` exists (`tests/e2e/specs`) or the script is removed
- [ ] `package.json` scripts added: `lint` (js + css + theme-json), `lint:theme-json` (port `validate-theme-json.js`), `test`, `dist`, `docs:check`
- [ ] git hooks: `simple-git-hooks` + `lint-staged` (php -l via script, stylelint, eslint)
- [ ] untrack artifacts: `tests/phpunit/.wp-tests-setup-complete`, `scripts/tests/prod-build/config.local.json`, decide `config/config.json`
- [ ] bundle excludes: confirm zip has no dev files, no `.ts/.tsx` source copies, no inline source maps (C: `build-js.js:108` only strips maps when `NODE_ENV=production`, so the 4.6x JS bloat is in the zip)
- [ ] `composer.json` platform / `Requires PHP` agree
- Gate: `npm run lint`, `composer phpcs`, `phpstan`, `phpunit`, `jest` all run and report real numbers; recorded in this file.

## W01 Lint debt cleared (feeds A)
- [ ] stylelint: 1870 errors under `assets/css/src` (1638 autofixable) fixed; rebuild; visual diff on home, KB1, KB2, post, archive
- [ ] eslint: 1047 errors under `assets/js/src` (about 80% prettier) fixed
- [ ] phpcs on real PHP: remaining errors in `inc/`, `template-parts/`, root templates fixed (Yoda, loose comparisons, missing docblocks, translators comments, spaces/tabs, `MissingUnslash` in `inc/extra.php:238-248`)
- Gate: lint commands exit 0; rebuilt `.min` output visually unchanged.

## W02 Dead code and repo hygiene (feeds A, G)
Verify each before deleting (grep callers); A section 3, B section 6, C sections 4 and 6.
- [ ] `optional/` (11 files) and `optional/dev/dev-proxy-livereload.php` (script-injection risk) removed
- [ ] `sidebar.php`: only `optional/*` calls `get_sidebar()`; confirm `Styles::print_styles` use at `sidebar.php:16`, then remove
- [ ] `inc/EZ_Customizer/` (commented out in `Theme.php`; JSON loopback `wp_remote_get`), `inc/Kirki/` (inverted dead logic), `inc/Blocks/` (`assets/blocks` missing)
- [ ] `inc/Options/` dead admin page: unregistered menu but live REST route `my-theme/v1/settings` and a React bundle enqueued on every wp-admin screen
- [ ] `wp-cli/knowx-commands.php` never required: wire it or delete
- [ ] `scripts/`: remove block tooling (`blocks.js`, `build-all-blocks.js`, `build-blocks.js`, `templates/block`), `convert-to-block-theme.js`, `dev-modern.js`, `start.js` if unused, prod-build tests with mmenu-light
- [ ] dead functions: `knowx_site_menu_icon`, `knowx_metabox_callback`/`knowx_save_meta` (decide: register meta box or drop), `Template_Tags::get_theme_asset`, `Comments::filter_comment_form_defaults`, `knowx_log`, `knowx_is_login_page`
- [ ] `login.php:122-151` undefined-variable branches
- [ ] No extra plugin integrations (decision 2026-10-10): remove bbPress support (`bbpress.php`, `template-parts/content/content-bbpress.php`, `bbpress.css`, `bbpress_sidebar_option`, `custom_bbp_no_breadcrumb`) and Elementor location checks (`elementor_theme_do_location` in `404.php`, `archive.php`, `footer.php`, `header.php`, `single.php`)
- [ ] unused JS/CSS: `global.min.js` never enqueued, `admin/knowx-customizer.js` never loaded, `knowx-customizer.css`, 9 PNG layout pickers (tied to Kirki, removed in W07)
- [ ] duplicate helpers (`get_version`/`get_asset_version`, two footer-copyright branches, duplicate `add_theme_support` for custom-header/background)
- Gate: build, bundle and `php -l` pass; browser smoke on home, KB1, KB2, single, archive, 404 shows no regressions.

## W03 Compatibility headers and theme-review blockers (feeds A, G)
- [ ] Minimum versions decided: **PHP 8.1, WordPress 6.9**, tested up to 7.1. Code already uses PHP 8.0 syntax (`Options/Component.php:141`, `Fonts/Component.php:331`, `Blocks/Component.php:166,194`) under a 7.4 header and 7.0 gate; make `style.css`, `readme.txt`, `functions.php` gate (`KNOWX_MINIMUM_*`) and composer all say PHP 8.1 / WP 6.9
- [ ] `Requires at least` 6.9, `Tested up to` 7.1, `Stable tag` = version (not `trunk`)
- [ ] `wp_body_open()` called in `header.php:27` (today only a custom `knowx_wp_body_open` action fires); remove the shim
- [ ] HTML nesting: stray `</div>` from `.container` closed in `page-knowledgebase-one.php:28` and `full-width-container.php:36` and again in `footer.php:19`
- [ ] `searchform.php`: label, `type="search"`, no duplicate ids `s` / `searchform`, accessible name on the icon link
- [ ] duplicate `#primary-menu` (printed twice, `template-parts/header/navigation.php:46-55`) and duplicate `#secondary` in "both sidebars" mode; mobile close control is an `<a href="#">`
- [ ] `sidebar-layout` logic: `Sidebars/Component.php:197,221` check the right sidebar twice (left never checked in "both")
- [ ] `theme.json`: remove `customTemplates` / `templateParts` pointing at missing files (lines 304-365)
- [ ] `style.css` tags: allowed feature tags (max count), add `accessibility-ready` only after W11 a11y fixes
- [ ] `readme.txt`: FAQ, Upgrade Notice, Resources (licences for bundled assets), 1.7.5 changelog final
- Gate: Theme Check plugin shows no blockers for these items.

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
From A section 4.
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
- [ ] Drop libraries CSS or native features replace: sticky-kit, fitvids, scrollUp, lazyload; evaluate isotope and superfish; target no jQuery on the front end
- [ ] Production build strips inline source maps; no `.ts/.tsx` copied into the zip
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
