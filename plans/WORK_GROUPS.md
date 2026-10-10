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
| W04 | Basecamp Bugs cards and KB template defects | done | [10392494317](https://app.basecamp.com/5798509/buckets/38311801/card_tables/cards/10392494317) |
| W05 | Security and correctness hardening | done | [10392494332](https://app.basecamp.com/5798509/buckets/38311801/card_tables/cards/10392494332) |
| W06 | Visual baseline and settings inventory | done | [10392494340](https://app.basecamp.com/5798509/buckets/38311801/card_tables/cards/10392494340) |
| W07 | Customizer_Framework port, Kirki removed | done | [10392494356](https://app.basecamp.com/5798509/buckets/38311801/card_tables/cards/10392494356) |
| W08 | Settings redesign and audit | done | [10392494374](https://app.basecamp.com/5798509/buckets/38311801/card_tables/cards/10392494374) |
| W09 | Tokens, dark mode, colour output | done | [10392494401](https://app.basecamp.com/5798509/buckets/38311801/card_tables/cards/10392494401) |
| W10 | JavaScript: ordering, jQuery, libraries | done | [10392494423](https://app.basecamp.com/5798509/buckets/38311801/card_tables/cards/10392494423) |
| W11 | CSS: token migration, RTL, focus, responsive | done | [10392494445](https://app.basecamp.com/5798509/buckets/38311801/card_tables/cards/10392494445) |
| W12 | Fonts and icons | done | [10392494459](https://app.basecamp.com/5798509/buckets/38311801/card_tables/cards/10392494459) |
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
Cards 10388866381 and 10391809639 plus the related defects from B section 1. Reproduced first on the W00 baseline (lorem ipsum, "No data found.", "Uncategorized" tile visible in `baseline-w00/home-*.png`), then fixed.
- [x] New `inc/knowledge-base.php`: bounded, cached data helpers shared by both layouts (categories capped at 100 via `knowx_kb_max_categories`, per-category posts default 10, `no_found_rows`, meta/term cache off, cache key includes the settings and the posts/terms "last changed" stamps so it invalidates on any edit); 4 unit tests
- [x] Layout One: placeholder lorem ipsum replaced by a real sentence; the dead `esc_url( '#' )` button is no longer rendered without a link; empty categories hidden (`hide_empty`)
- [x] "Popular Articles" depended on WordPress Popular Posts and printed "No data found."; it is now **Featured Articles** from core sticky posts and the column is not rendered when there are none (Recent then takes the full width)
- [x] One exclusion setting `knowx_exclude` for both layouts (was `knowx_exclude_posts` and `knowx_exclude`); Recent and Featured articles honour it, children included; verified on the live site (excluding a parent removes it and its children from tiles and Recent)
- [x] Recent Articles is one `<ul>` with one `<li>` per article (was five single-item lists)
- [x] Layout Two: one bounded query per listed category instead of unbounded `get_posts` per category; `orderby` title instead of slug; `cat-description` is a list item, not a `<div>` inside the `<ul>`; `esc_html__()` for "View All"; all names, links and titles escaped (no blanket `phpcs:ignore`); `knowx_page_title` has its `'yes'` default so the title does not vanish without Kirki
- [x] Hero heading and search are inside `<main>`; `$post` global override removed; the per-page `knowx-exclude-cats` meta is no longer read
- [x] Decision for card item 5: Layout One lists top-level categories, Layout Two lists every category; documented as by design and carried into the product model in W14
- [ ] Docs page `docs/website/features/knowledge-base-homepage.md` exists only on `master`; rewritten in W17 after the `master` sync (the card's docs item)
- Gate: lint and tests pass (phpunit 15 tests, phpstan baseline 131); verified on the live site: tiles, exclusion with children, sticky featured, Layout Two options (view all, descriptions, posts per category), div balance, no `debug.log` entries; Layout One screenshots at 390 / 768 / 1024 / 1440. **Passed 2026-10-10** (the 390 px page is wider than the viewport on every template; that pre-existing overflow is fixed in W11).

## W05 Security and correctness hardening (feeds A, G)
From A section 4.
- [x] Webfont loader removed (`inc/Webfont/`, 782 lines): it `include`d remote-fetched CSS as PHP, saved font files under remote names, recursed forever on an empty fetch, could delete the uploads directory, scheduled a non-existent `'monthly'` event and registered an AJAX action without a nonce. Fonts are replaced in W12
- [x] `setPostViews()` and its call in `single.php` removed (an unauthenticated database write on every view that also defeated page caching)
- [x] The never-registered meta box (`knowx_metabox_callback`, `knowx_save_meta`, raw `$_POST` use) removed; the leftover read of `knowx-exclude-cats` in the two Knowledge Base templates is removed in W04
- [x] `knowx_categorized_blog()` uses `wp_count_terms()` instead of loading every category and caching it forever in a transient
- [x] Breadcrumbs rewritten (1,386-line class to `inc/breadcrumbs.php`, about 130 lines): fixes the parenthesis and `esc_url` bugs, missing returns, undefined `$post`, unescaped term names and http microdata; output is a `<nav aria-label="Breadcrumb">` ordered list, every label escaped at output, with the category ancestor chain; Yoast breadcrumb integration removed; 4 unit tests added
- [x] Unescaped post titles in more-link text, edit links and the comments heading now use `esc_html( get_the_title() )`
- [x] Activation no longer overwrites an existing front page and reuses an existing `knowledge-base` page
- [x] `wp-admin/includes/plugin.php` no longer loaded on every front-end request
- [x] phpstan real bugs found in W00 are gone (EZ_Customizer, login branches, breadcrumbs); `login.php` dead branches removed in W02
- [ ] Later groups: TGMPA load per request (W07), Fonts component `sslverify => false` and remote downloads (W12), `login.php` customisation rework (W08)
- Gate: PHP lint clean; phpunit 11 tests; phpstan OK (baseline 132); phpcs 19 errors left (all in `extra.php` docblocks, `page-knowledgebase-one.php`, `searchform.php`-class issues and tests, owned by W04/W17); breadcrumbs verified on post, category, search, page and 404; `debug.log` empty. **Passed 2026-10-10.**

## W06 Visual baseline and settings inventory (feeds B)
No longer a byte-identical migration gate (existing sites are not a constraint); this is a visual reference and a diffable list.
- [x] `tools/dump-customizer-inventory.py` and `docs/customizer-inventory.md`: **88 fields = 82 settings + 6 dividers** with type, section, default, output CSS, transport and where each is read (the counts in earlier notes, 85 and 84, were off: bbPress and the duplicate exclusion field are gone)
- [x] Four settings are never read and have no output CSS: `site_footer_bg`, `site_load_google_font_locally`, `site_preload_local_font`, `site_sub_header_bg`; 20 settings only act through Kirki `output` CSS (they stop working the moment Kirki goes, which is what W07 and W09 replace)
- [x] Screenshots of home, post, category, search and 404 at 390 / 768 / 1024 / 1440 in `~/Local Sites/knowx/app/qa-artifacts/baseline-w00/` plus one capture folder per group (`after-w01` to `after-w05`); `scratchpad/shots.sh` and `imgdiff.py` produce and compare them (ImageMagick has no PNG support here, so the diff uses Pillow)
- [x] Local site `http://knowx.local`: theme active, 37 seeded posts in three products, template test pages for Layout Two, Full-Width and Page-Without-Sidebar, `WP_DEBUG_LOG` on
- Gate: inventory file and baseline screenshots exist. **Passed 2026-10-10.**

## W07 Customizer_Framework port, Kirki removed (feeds B)
Plan phase 1. The BuddyX `inc/Customizer_Framework/` was copied and renamed to `KnowX\KnowX`; BuddyX's style-variation and Tokens coupling is left out (it returns in W09 and W13).
- [x] Framework registered through `inc/Customizer_Settings/Component.php` (renamed from `Kirki_Option`), which boots it and registers panels, sections and fields on `init`; all 88 fields converted from `new \Kirki\Field\X(...)` to `Field::add( 'type', ... )`, panels and sections to `Panel::add` / `Section::add`; a `number` field type added to the framework (sanitized with `absint`)
- [x] `class_exists( 'Kirki' )` gates removed: panels, fields, `Dynamic_Style`, login styling and the live-preview scripts always run
- [x] Deleted: `inc/Kirki/`, `external/` (Kirki utils, TGMPA, plugin recommendations, `require_plugins.php`), Kirki JS and CSS (`customizer-controls.js`, `knowx-customizer.css`), the dead Options admin CSS; TGMPA no longer loads on every request
- [x] `knowx_defaults()` moved to `inc/defaults.php` (W08 removes it); new `knowx_is_truthy()` helper in `inc/extra.php`; the framework's `Toggle` control needs it (without it the Customizer fataled, found by driving the real Customizer)
- [x] Customizer guard: `blogname`, `blogdescription` and `header_textcolor` are only switched to `postMessage` when the setting exists (the unguarded code fataled when header text was off)
- [x] `config` bundle list updated; the zip ships the framework assets (975 KB zip); the empty "Theme Options" section is gone
- [x] Every one of the 82 settings registers a control (checked headlessly); the real Customizer loads in Chrome with all theme sections
- [x] Every setting has a sanitize callback chosen by field type (framework default)
- [x] phpstan: no errors, baseline file deleted; phpcs: framework doc-block sniffs scoped out so it stays diff-able against the BuddyX copy
- [x] `git grep -i kirki` finds nothing outside plans, audit, docs history, old changelog entries and the inventory tool name
- Gate: PHP lint clean; phpunit 15 tests; phpstan clean; stylelint and eslint 0; **real Customizer driven over Chrome DevTools**: loads as "Customize: knowx" with 15 theme sections and 122 controls, no console or PHP errors; changing a link colour updates the live preview from rgb(3,169,244) to rgb(255,0,0); the dependent loader-background control is active only when the loader is on; the Site Skin section renders colour pickers with opacity sliders (`qa-artifacts/customizer-skin-w07.png`). **Passed 2026-10-10.**
- Note: the front end now prints each field's default as CSS (as Kirki did when it was active), so the default look is the designed one (for example the hero is `#03A9F4`); W09 puts this on tokens.

## W08 Settings redesign and audit (feeds B)
Colour settings are decided in W09 together with the token system; everything else is done here. No migration, so settings were cut and renamed freely.
- [x] **82 settings down to 58** (63 fields incl. dividers): removed the WP Login panel (9 settings) and `inc/login.php` (login branding is plugin territory on wordpress.org), the Site Performance section (two settings nothing read), `site_footer_bg` and `site_sub_header_bg` (never read), the four hero/support typography controls and the sub-header typography control (static CSS already styles them), five of the six heading controls, and `body_background_color`
- [x] Core first: the body background is core `custom-background` (default `#f7f7f9`), not a duplicate setting; headings are one **Headings (H1 to H6)** control for family, weight, line-height and colour, with the size scale in static CSS (`_typography.css`)
- [x] One default source: `knowx_mod( $id )` returns the saved value or the default declared on the field; `knowx_defaults()` and every `get_theme_mod( ..., knowx_defaults() )` are gone (12 files); helpers live in `inc/helpers.php` with `knowx_is_truthy()`
- [x] Every switch is a plain 0/1 default; `site_loader` is off by default; `post_per_row` default 3; `knowx_posts` default 10; switch consumers use `knowx_is_truthy()`
- [x] No setting is unread (`docs/customizer-inventory.md`: "Settings with no reader and no output CSS: none"); no `postMessage` transport without preview support remains (the login settings were the only ones)
- [x] Unit tests: 25 tests (accessor default, truthiness table, KB helpers, breadcrumbs); the unit bootstrap defines `ABSPATH` because framework files exit without it, which used to end the PHPUnit run silently
- [x] **Three-pass audit on the real Customizer** (Chrome DevTools): (1) save round trip: six values including the headings typography array saved through `wp.customize.previewer.save()` and read back with WP-CLI, and the front end showed them (container width 1100px, loader markup, heading weight 700 and colour, hero heading, excluded category tile gone); (2) every one of the 58 theme settings has a rendered control and 13 sections focus without render errors; (3) live preview and dependent controls verified in W07
- [ ] Colour settings (24), the footer and copyright colours and `site_container_width` output move to tokens in W09
- Gate: lint 0; phpstan clean; phpcs 19 errors left (extra.php docblocks, page-knowledgebase-one, tests; scoped excludes unchanged); screenshots at 4 widths differ from W07 by at most 0.07%; zip 972 KB. **Passed 2026-10-10.**

## W09 Tokens, dark mode, colour output (feeds B, I)
Decision change: BuddyX's 2,079-line Tokens component carries Kirki-era purge logic, 96 tokens and the variation overlay that KnowX does not need, so a lean KnowX version was written instead (about 150 lines of PHP). The existing `--color-*` variable names stay (137 uses, no churn) and the mode attribute is `data-color-mode`; the earlier `--bx-*` prefix decision is dropped.
- [x] **Colour settings cut from 23 to 5** (`site_primary_color`, `site_header_bg_color`, `site_footer_content_color`, `site_copyright_background_color`, `site_copyright_content_color`); links, hovers, buttons, borders, menu, title hover and loader colours are derived in CSS with `color-mix()` from the brand colour (settings now 43 + the two colour-mode fields)
- [x] `inc/Tokens/Component.php` replaces `Dynamic_Style`: prints one `:root{...}` rule with only the colours the owner changed, picks a white or near-black label (`--color-on-primary`) for contrast, and computes a link colour that reaches 4.5:1 on the page background for any brand colour; colour values are validated before printing (hex, rgb, hsl only)
- [x] `_custom-properties.css` reorganised: brand, neutral surfaces and text, derived variables, then a **dark palette** (`:root[data-color-mode="dark"]` and the same under `prefers-color-scheme: dark` for automatic mode, with `color-scheme: dark`)
- [x] `inc/Color_Mode/Component.php`: Customizer "Color Mode" section (default light / dark / automatic, header toggle on/off), an inline script that sets the mode before first paint (no flash), and an accessible toggle button (three inline SVG icons, aria-label per state); the 40-line toggle script loads only when the toggle is on
- [x] Typography fields no longer carry colours (headings, body, menu and site title colours come from tokens); background fields default to empty so the stylesheet owns the surfaces
- [x] Stylesheet migration: 51 literal colour declarations moved to tokens by a property-aware script and about 25 by hand (surfaces, text, borders, hero and tile text on the brand colour, KB hero follows the brand colour); the remaining literals are intentional (white on black overlays, neutral shadows, error red); two SVG arrows are themed for dark mode (CSS mask for the chevron, a light SVG for select arrows)
- [x] Tests: 28 unit tests including `on_color`, link contrast for four brand colours and colour sanitising
- [x] **Measured contrast** (computed colours in the real browser, 18 text/background pairs per page, six page types, light and dark, default / purple / yellow brand): **no failures** after the link-colour and breadcrumb fixes; the toggle cycles light, dark, auto, updates its aria-label and stores the choice
- [ ] Remaining colour work in W11: focus styles and the editor stylesheet palette
- Gate: PHP lint, phpstan clean, phpunit 28 tests, stylelint and eslint 0, zip 978 KB; light-mode screenshots differ from W08 by at most 1.04% (darker, readable link blue; dark hero text on the brand blue); dark mode reviewed in screenshots. **Passed 2026-10-10.**

## W10 JavaScript: ordering, jQuery, libraries (feeds I)
From C section 4 and 5 and `docs/LIBRARIES.md`.
- [x] **No jQuery and no vendored libraries on the front end**: Superfish, Isotope, Sticky-kit, FitVids and jQuery.scrollUp are deleted, `custom.js` (jQuery, `$(window).load`, duplicate mobile-menu handlers, dead header-search code) is replaced by a 90-line `theme.js` (page loader that can never stay up, table cell labels, back-to-top with a reduced-motion guard); the front end now loads two scripts, `navigation.min.js` and `theme.min.js`, and the full set shipped in the zip is **6.4 KB minified** (was 284 KB with inline source maps)
- [x] Script loading uses core `strategy => defer` through the enqueue args; the custom `async` flags, the `script_loader_tag` filter and the dependency race are gone
- [x] `navigation.ts`: the mobile drawer state (body class, `aria-expanded`, `nav--toggled-on`) lives in one function; Close button, overlay and Escape close it and return focus to the toggle; the JS breakpoint is the CSS one (767px via `matchMedia`, was 55em = 880px); the submenu toggle label said the opposite of what it did and is fixed
- [x] CSS replaced the libraries: sticky header and sticky sidebar use `position: sticky` (admin-bar offset from `--wp-admin--admin-bar--height`), grid layout is flex and masonry is CSS columns, video iframes keep 16:9 with `aspect-ratio`
- [x] Verified in the browser at 390px and 1280px: loader class, drawer open / Escape / Close button, focus return, sub-menu toggle labels and `aria-expanded` on mobile and desktop, back-to-top appears after scrolling and returns to the top, header is `position: sticky`, no duplicate ids, `jQuery` is undefined, no console errors, masonry screenshot
- [ ] Not done here: the focus trap for the open mobile drawer and the full docs layout (sticky table of contents) are part of W11 and W14
- Gate: lint 0, phpstan clean, phpunit 28 tests, stylelint 0, zip 944 KB. **Passed 2026-10-10.**

## W11 CSS: token migration, RTL, focus, responsive (feeds C, I)
From C section 2, 3 and 7. Screenshots now use real device emulation (`shots2.sh`): the earlier headless "390px" captures were wider layouts cropped, so the horizontal-overflow finding in the CSS audit was an artifact; measured with the real viewport there is **no horizontal overflow** at 390px (only the closed off-canvas menu extends past the edge).
- [x] Literal colours moved to tokens in W09; this group finished the stragglers (`hsl` comment-notes background, search fields, placeholder colour) and made dark mode pass
- [x] **Bootstrap-era grid (18 KB) replaced by a 1 KB layout** (`container`, `row`, `col-md-1..12` from 992px, same stacking as before, `display: flow-root` so margins stay contained)
- [x] **RTL without a separate stylesheet**: 136 physical declarations became logical properties (`margin-inline-start`, `inset-inline-end`, `text-align: start`, ...); `rtl.css` (and its 20 KB build and enqueue) are deleted; verified in a real right-to-left render (header, sidebar, post, comments mirror correctly)
- [x] **Modern browser targets** (`.browserslistrc`: Chrome 111+, Firefox 113+, Safari 16.4+): logical properties, `color-mix()`, `aspect-ratio` and `:focus-visible` compile as written; the old "last 2 versions" target made lightningcss add `:lang()` fallbacks with higher specificity that changed the cascade. **All theme CSS shrank from 203 KB to 128 KB minified**
- [x] Focus: `:focus-visible` outline on every interactive element (zero before); the rules that removed outlines from buttons, selects and links are gone
- [x] `prefers-reduced-motion` honoured (animations and transitions off, smooth scrolling off)
- [x] **Measured page weight** (gzip): home 22.8 KB CSS, post about 25 KB (budget 40 KB)
- [x] **axe-core** (WCAG 2.0/2.1/2.2 A and AA plus best practices) run in the real browser on six page types at 390 and 1280 px in light and dark mode: **0 violations** after fixes found by the scan: empty author link when a post has no author, unnamed search landmarks, brand-blue link text at 2.5:1, links in running text not underlined, 24px target size for comment dates, muted text at 4.4:1, menu active colour, light grey comment notes and search fields in dark mode
- [x] Not done on purpose: pruning block-editor CSS (a Query Loop or Site Title block can be placed in a classic page, so the rules are not dead); the sticky table of contents and drawer layout are W14; `accessibility-ready` tag review is W17
- Gate: lint 0, phpstan clean, phpunit green, zip 934 KB, axe 0 violations, screenshots vs the W10 baseline differ only where the container is now fluid up to 1170px at 1024px (previously fixed at 970px). **Passed 2026-10-10.**

## W12 Fonts and icons (feeds I)
- [x] `inc/Fonts` and the Google catalog dropped; Open Sans variable woff2 (latin and latin-ext, OFL) self-hosted and declared in `theme.json` `fontFace`, so the Customizer picker, the editor and the Font Library all see the same families
- [x] Single font request set: one preload for the latin file, `font-display: swap`, no Google request, Montserrat gone
- [x] FontAwesome 4.7 removed (about 1.1 MB); the glyphs used are CSS-mask SVG files (`assets/images/icon-*.svg`) and inline SVG in the search form, menu and back-to-top
- [x] Downloader with `sslverify => false` removed together with `inc/Fonts`
- Gate: phpstan clean, phpunit 28 green, lint 0, phpcs 18 errors (all in files owned by W14/W17), zip 399 KB, no FontAwesome or Google reference on the front end, console clean. **Passed 2026-10-10.**

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
