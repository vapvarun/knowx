# KnowX: remove Kirki, move to the BuddyX 5.1 architecture

Status: PLAN, not started. Branch `1.7.5` (not merged to `master` until every check passes).
Reference: `~/dev/repos/buddyx` (5.1.8) and the skill `wbcom-kirki-to-tokens`. Mirror BuddyX, do not invent.
Goal: a lightweight wordpress.org theme with no plugin dependency, Playground-ready, with sample content.

## 1. Inventory (measured on 1.7.5, 2026-10-10)

| Item | KnowX today |
|---|---|
| Settings | 85 across 11 files in `inc/Kirki_Option/Fields` (KB 23, Typography 13, Footer 12, Skin 11, WP Login 9, Sub Header 6, General 5, Blog 3, Sidebar 3, Performance 3, Header 2) |
| Kirki field classes | Color 24, Typography 15, Checkbox_Switch 14, Text 7, Custom 6, Radio_Image 4, Radio 4, Dimension 4, Background 4, Textarea 3, URL 2, Select 1, Number 1, Image 1. All have a Customizer_Framework equivalent |
| Storage | `theme_mod` (same as BuddyX), so saved values carry over with no migration if IDs, defaults and shapes are kept |
| Kirki code | `inc/Kirki/` (config), `inc/Kirki_Option/` (panels + fields, ~1,900 lines), `external/kirki-utils.php`, `external/require_plugins.php` (TGMPA recommends Kirki), `assets/js/src/customizer-controls.js`, `inc/Customizer/Component.php`, `inc/Theme.php`, `functions.php`, `inc/login.php`, `inc/Dynamic_Style` |
| Inline colour CSS | `inc/Dynamic_Style/Component.php` (201 lines, reads ~12 colour mods) |
| Reads of theme mods | 102 call sites outside the field files; `knowx_defaults()` used in 10 files; no truthy helper yet |
| Hard-coded hex | 168 values in 20 files under `assets/css/src` (BuddyX needed a much bigger sweep) |
| Install base | wp.org KnowX: ~100 active installs, current wp.org version 1.7.4, rating 100 (1 rating) |
| Dead code | `inc/EZ_Customizer/` (commented out in `Theme.php`), `knowx-demo-importer` repo |

Small install base lowers the blast radius, but these are real sites and the value-preservation rule still applies in full.

## 2. Target architecture (copied from BuddyX, namespace `KnowX\KnowX`)

1. `inc/Customizer_Framework/` replaces Kirki (Panel, Section, Field, Active_Callback, Output_Builder, 12 controls).
2. `inc/Tokens/Component.php` plus `_bx-tokens.css`: one `:root` block, 9 derived variants per base colour, dark-mode block.
3. `inc/Color_Mode_Toggle/` visitor light/dark/auto button (off by default; enqueue only when on).
4. `inc/Starter_Content/` fresh-install demo; this is also the sample content for wordpress.org and Playground.
5. `inc/Fonts/` value-driven Google Fonts loader and 1,358-family catalog; self-hosted Inter and system fonts in `theme.json`. Never open `google-fonts.json` with Read.
6. `styles/` eight style variations (copied verbatim) plus `plans/` for this work.

Removed: `inc/Kirki/`, `external/kirki-utils.php`, TGMPA (`class-tgm-plugin-activation.php`, `require_plugins.php`), `customizer-controls.js` Kirki code, `inc/EZ_Customizer/`.

## 3. KnowX-specific decisions (recommendation first)

1. Token prefix: keep `--bx-*` verbatim so BuddyX-family fixes port cleanly; document it in `CLAUDE.md`. Alternative `--kx-*` costs a rename in every ported file.
2. Panels and sections: mirror BuddyX topology (General, Typography, Header, Sub Header, Skin, Blog, Sidebar, WP Login, Footer, Performance) and keep KnowX's own Knowledge Base panel as a fourth KnowX-specific panel with Layout One and Layout Two sections. Section and setting IDs stay unchanged.
3. Defaults: preserve every KnowX default. The one risk is `site_loader` (KnowX default `'2'`, loader on). BuddyX turned its loader off for new installs. Recommendation: keep KnowX's saved values untouched, make the new-install default OFF, and record it in the changelog.
4. Recommended plugins: drop TGMPA entirely. WordPress Popular Posts and Collapsing Categories become optional features documented in the docs; Popular Articles hides itself when WPP is inactive (this is Bugs card 10388866381).
5. Version: this is a large internal change. Recommendation: ship as 1.8.0 (rename the readme/changelog entry, keep working on this branch). If you want to keep 1.7.5, the changelog must still list the Kirki removal.
6. Rollout: wp.org theme, so no beta channel. Run a beta by zip on a handful of known sites, keep `dist/legacy/knowx-1.7.4.zip` for 14 days.

## 4. Phases (BuddyX order, each gate must pass before the next)

### Phase 0. Baseline and safety net
- [ ] Fix the two Bugs cards first, as separate commits, so regressions stay attributable (Layout One lorem ipsum and empty states; Recent Articles exclusion, `<ul>` markup, `esc_html__`, escaping).
- [ ] Build a **before** snapshot: install KnowX 1.7.4 and Kirki 6.0.4 on the local site, set every one of the 85 settings to a non-default value with WP-CLI, export `theme_mods_knowx`, save front-end HTML and computed CSS for home, KB layouts, post, archive, login.
- [ ] Settings inventory script (port `tools/dump-customizer-inventory.py`) so field IDs, types and defaults are diffable.
- [ ] Branch checks: `git grep -i kirki` count recorded.

### Phase 1. Customizer_Framework port
- [ ] Copy `inc/Customizer_Framework/` verbatim, rewrite namespace, register in `Theme.php`.
- [ ] Rename `inc/Kirki_Option` to `inc/Customizer_Settings`; atomic sweep of `\Kirki\Field\X::add` to `Field::add('<type>', ...)`, `\Kirki\Panel` and `\Kirki\Section` to the framework wrappers.
- [ ] Remove the `class_exists( 'Kirki' )` gate so panels and fields always register.
- [ ] Delete `inc/Kirki/`, `external/kirki-utils.php`, TGMPA, Kirki JS; replace `kirki_*` helpers with `get_theme_mod()`.
- Gate: every panel opens, every control renders, no PHP errors, no console errors.

### Phase 2. Three-pass audit
- [ ] Pass 1: save every section without edits; `theme_mods_knowx` diff vs snapshot is empty.
- [ ] Pass 2: render each field from its original Kirki args; check `default`, `output`, `active_callback`, `transport`.
- [ ] Pass 3: load the Phase 0 snapshot onto a Kirki-free install, click every control, check live preview.
- [ ] Live-preview audit (lesson 21): no `postMessage` setting without an `output` map.

### Phase 3. Tokens and dark mode
- [ ] Copy `Tokens`, `Color_Mode_Toggle`, token CSS and JS; adapt `$simple_color_tokens` to KnowX's colour fields (Skin, Header, Sub Header, Footer, Loader).
- [ ] Replace `Dynamic_Style` with Tokens plus Output_Builder (single source, one `:root`).
- Gate: default theme mods render byte-identical to the Phase 0 baseline.

### Phase 4. Stylesheet cleanup
- [ ] 168 hex values across 20 files in `assets/css/src` move to `var(--bx-color-*)`; legacy `--global-*` aliased to tokens.
- [ ] Edit sources only (`assets/css/src`), rebuild; never edit `.min.css`.
- [ ] Lint debt: clear the 1870 stylelint and 1047 eslint errors (mostly autofix) in a separate commit before this phase so diffs stay readable.

### Phase 5. Style variations and colour mode
- [ ] Add `styles/` (8 variations) and the Skin field to pick one.
- [ ] Browser-verify all 8 variations in light and dark on the KB home, a post and an archive; zero contrast failures (3.5:1 minimum, 4.5:1 for body text via `lint:theme-json`).

### Phase 6. Starter content, patterns, Playground
- [ ] `inc/Starter_Content`: KB home (Layout One), a Layout Two page, 6+ categories with children, 30+ articles, menus, widgets, no lorem ipsum, no premium-plugin dependency.
- [ ] KB block patterns in `patterns/` (hero search, category grid, article list).
- [ ] `blueprint.json` for Playground (theme + sample content; no Kirki step any more).
- [ ] Retire `knowx-demo-importer`.

### Phase 7. Truthy normalisation
- [ ] Add `knowx_is_truthy()`; wrap all ~102 `get_theme_mod` reads that drive an `if` (loader, sticky header, sub header, search, breadcrumbs, sidebar, KB toggles).
- Gate: legacy `'on'`/`'off'`/`'1'`/`1` values all behave the same on the front end.

### Phase 8. WCAG, AMP, editor styles
- [ ] AA contrast on links and body text, link distinguisher, AMP attribute leaks, editor styles match front end.

### Phase 9. Release prep
- [ ] `git grep -i kirki` returns zero hits outside `plans/` and docs history.
- [ ] Orphan Kirki JS swept from `dist/` and `assets/js`.
- [ ] phpcs (WPCS), phpstan, phpunit, eslint, stylelint all clean; no `--no-verify`.
- [ ] wp.org: Theme Check clean, allowed feature tags only (max 5), Tested up to current, readme and screenshot updated.
- [ ] Docs rewritten (customizer reference, no Kirki install step); `docs/website` synced from `master`.
- [ ] Merge only after the user approves.

## 5. Value-preservation rules (non-negotiable)

- Setting IDs, types, defaults, value shapes and sanitize callbacks unchanged; `Active_Callback` keeps loose equality (`'on' == '1'`).
- Existing site that upgrades sees the same front end with Kirki installed or not; Kirki left installed is harmless and simply unused.
- Any intentional default change goes in the changelog (loader default, base font if changed).
- Rollback zip `dist/legacy/knowx-1.7.4.zip` kept for 14 days after release.

## 6. Verification matrix (feeds `docs/qa/RELEASE_CHECKLIST.md` sections B, C, F, G)

Browser-verify at 390px, 768px and desktop, light and dark, with Kirki absent and with Kirki installed. Seed 1000+ posts and 500+ categories with WP-CLI for the KB lists. Compare against the Phase 0 snapshot after each phase, not only at the end.

## 7. Risks

1. Truthy regressions from `'on'`/`'off'` string mods (largest source of post-Kirki bugs in BuddyX).
2. Kirki-era custom font families: the Saved optgroup plus value-driven loader must be ported verbatim.
3. Dynamic_Style to Tokens switch can change cascade order; the byte-identical baseline gate catches it.
4. The copy from BuddyX brings BuddyPress-specific fields; strip them (KnowX has no BuddyPress panel).
5. Size: Tokens (about 2,000 lines) and the framework are large; only KnowX-specific mapping is hand-written.

## 8. Lessons from Twenty Twenty-Five 1.5 (WordPress default theme, checked locally 2026-10-10)

Facts: block theme, no Customizer, no JavaScript, 159-line `functions.php`, 8 HTML templates, 7 parts, 98 patterns, `theme.json` v3 (schema 6.7) with 8 palette colours, 5 font sizes, 7 spacing sizes, 2 self-hosted variable `woff2` fonts, 8 style variations (plus `colors/`, `typography/`, `sections/`, `blocks/` partials), a 611-byte `style.min.css`. Header: Requires at least 6.7, Tested up to 7.1, Requires PHP 7.2, 17 feature tags including `accessibility-ready`, `style-variations`, `rtl-language-support`. The local site runs WordPress 7.1.3.

What KnowX takes from it (KnowX stays a classic theme with its own Customizer, per the BuddyX route):
1. **Header truth.** Set `Tested up to` to the current release (7.1), pick one honest `Requires PHP`, and make code, `functions.php` gate and header agree. KnowX today: header 7.4, gate 7.0, code needs 8.0.
2. **`theme.json` as the design source.** Palette, font sizes, spacing and layout widths live in `theme.json`; the `--bx-*` tokens should read from `--wp--preset--*` where the preset exists instead of duplicating values.
3. **Tiny front-end CSS.** Only the CSS the theme needs, with `.min` chosen by `SCRIPT_DEBUG` and `wp_style_add_data( ..., 'path' )` so WordPress can inline small styles.
4. **Fonts.** Self-host variable `woff2` only. KnowX ships FontAwesome 4.7 in six formats (about 1 MB); keep `woff2` (and `woff` fallback) only.
5. **Patterns carry the demo content.** Each pattern file has a docblock header (Title, Slug, Categories, Description). KB layouts and the sample content should be patterns, registered categories included.
6. **Style variations as JSON.** Eight palettes plus typography presets; KnowX copies the BuddyX eight and may add a typography set.
7. **No JS unless needed.** Drop jQuery-era libraries that CSS or native features replace (sticky-kit, fitvids, scrollUp, lazyload when `loading="lazy"` exists) after the CSS/JS audit confirms usage.
8. **`readme.txt` and screenshot** follow the wp.org layout (1200x900 screenshot, changelog per version).

Not taken: converting KnowX to a block theme. The user chose "lightweight like BuddyX", which is a classic theme with the in-house Customizer; the unused `scripts/convert-to-block-theme.js` can be removed.

## 9. Docs website target: fast, responsive, templated, schema-ready (user requirement, 2026-10-10)

KnowX is a documentation theme, so the finished result is judged as a docs site on phone, iPad and desktop. Interpretation used here (confirm with the user): "data schema" means schema.org structured data plus a documented content model. WordPress.org themes cannot register custom post types, so docs stay as posts, categories, tags and post meta.

**Speed budgets (mobile, throttled, docs home + article + category + search)**
- LCP under 2.0 s, CLS under 0.05, INP under 200 ms, Lighthouse performance 95+ (existing `.lighthouserc.cjs` becomes the gate).
- No jQuery on the front end; JS under 20 KB gzip total per page; CSS under 40 KB gzip per page, split per template and loaded only where used.
- Fonts: one self-hosted variable `woff2` family, `font-display: swap`, preloaded; FontAwesome replaced by inline SVG for the few icons used.
- Images: `loading="lazy"`, `decoding="async"`, explicit width/height, `sizes`, WebP; first image eager.
- Navigation: reuse BuddyX `Speculation_Rules` for instant page loads; no layout-blocking third-party calls; no remote font or webfont-loader fetch at render.
- KB queries bounded and cached (fixes the unbounded per-category `get_posts` in Layout Two).

**Responsive contract (checked at 390, 768, 1024, 1440 px)**
- Phone 390: single column, collapsed menu, search first, 44 px touch targets, no horizontal scroll.
- iPad portrait 768 and landscape 1024: two-column article with sticky table of contents, sidebar collapses to a drawer, hover-only behaviour replaced by tap.
- Desktop 1440: readable measure (65-75 characters), sticky sidebar navigation, keyboard navigation and visible focus.
- Logical CSS properties (`margin-inline`, `padding-block`) so RTL works; container queries for cards; dark mode through tokens.

**Templates the theme must ship**
- Docs home (Layout One and Layout Two, both from patterns), section/category index, single article (breadcrumbs, table of contents, last updated, previous/next, related, "was this helpful" link to a comment/contact), search results with empty state, 404, offline, changelog page template, FAQ page template, getting-started template, full-width template.

**Schema and content model**
- JSON-LD or microdata: `WebSite` with `SearchAction` on the home page, `BreadcrumbList` on every template that shows breadcrumbs, `TechArticle` (headline, dateModified, author) on articles, `FAQPage` generated from the FAQ pattern. Output escaped, filterable, and switchable off (SEO plugins may already emit it).
- Content model documented in `docs/website/developer-guide/content-model.md` and `audit/manifest.json`: posts as articles, categories as sections (children allowed), tags as topics, post meta keys (`knowx-exclude-cats`, reading time, version), and every theme_mod ID.
- Both layouts read the same exclusion setting (today they differ: `knowx_exclude_posts` vs `knowx_exclude`).

**Verification**: Lighthouse CI on the four page types at mobile and desktop, axe scan, Rich Results test on the structured data, Playwright screenshots at the four widths, and the 1000+ post / 500+ category seed.

## 10. Decision: hybrid means classic theme plus block features (user, 2026-10-10)

KnowX stays a classic PHP theme (`wp_is_block_theme()` stays false; no `templates/index.html`). Existing sites keep their templates and the in-house Customizer.

What "hybrid" adds:
1. `theme.json` v3 is the design source: palette, font sizes, spacing, layout widths, element and block styles; the `--bx-*` tokens read `--wp--preset--*` where a preset exists.
2. Block patterns (`patterns/*.php` with docblock headers) carry the KB layouts, hero, category grid, article list, FAQ and the starter content.
3. Style variations in `styles/*.json` (the BuddyX eight) and block style variations.
4. Block-editor support: editor styles that match the front end, `wp-block-styles`, wide and full alignments.
5. Optional later step: header/footer as block template parts rendered with `block_template_part()` from the PHP templates, only if it does not change the markup customers rely on.

What it does not do: convert PHP templates to HTML templates, remove the Customizer, or move settings to the Site Editor. That stays a possible future release.

Cleanup this implies: `theme.json` currently declares `customTemplates` (lines 304-343) and `templateParts` (lines 344-365) for files that do not exist; remove or replace them with real classic `Template Name:` entries so Theme Check and the editor stay consistent. Remove `scripts/convert-to-block-theme.js`.

## 11. Decision: existing sites are not a constraint (user, 2026-10-10)

"We are not worried about existing sites." This relaxes section 5:
- No requirement to keep setting IDs, defaults, value shapes or `'on'`/`'off'` compatibility. Settings are redesigned cleanly (BuddyX defaults and field shapes), and `knowx_defaults()` plus the Kirki-era default tables are deleted, not preserved.
- Dropped: the legacy truthy-normalisation phase (Phase 7) beyond writing the new settings as 0/1; the "Saved" font optgroup backward-compat; the Pass 3 real-customer-database check; the beta/RC rollout and the `dist/legacy` rollback zip; the byte-identical-to-1.7.4 baseline gate (Phase 0 snapshot is now only a visual reference for the redesigned defaults).
- Still required: every setting has a `sanitize_callback`, a default and escaped output; fresh installs, wordpress.org rules and Playground are the only audience.
- Consequence to put in the changelog: updating from 1.7.4 resets customizer choices to the new defaults. Release as a major version (2.0.0 recommended) so it is clearly not a drop-in patch.
