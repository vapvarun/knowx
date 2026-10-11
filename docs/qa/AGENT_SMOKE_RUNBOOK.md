# Agent Smoke Runbook - KnowX

**Audience:** a browser-capable agent (Playwright MCP or Chrome) with WP-CLI access, or a human QA person with the same access. It is read by the `wp-plugin-smoke` skill through `docs/qa/qa-config.json` (`"type": "theme"`).

Each C step is a **customer contract**: what the feature promises and what "working" looks like. Pick the mechanism yourself and confirm both the page and the saved value. D steps are repros of past defects, so their fixtures are exact. The full journey list with expected results is `docs/qa/JOURNEYS.md` (J1 to J28); QA's handoff is `docs/qa/HANDOFF.md`.

## Global preconditions

- Working directory: the theme folder (`wp-content/themes/knowx`)
- Site URL: `http://knowx.local` (or the site in `qa-config.json`)
- WP-CLI: `wp --path=<wp root> <cmd>`
- Admin auto-login: `?autologin=1` on any front-end URL
- Sample content: `wp eval-file wp-content/themes/knowx/playground/seed.php` on an empty site. Single-product check: `playground/seed-single.php` on a second site or a database copy (restore afterwards).
- Viewports: 390, 768, 1024, 1440 px, light and dark
- Version: `Version:` in `style.css` (the report's `release_version` must equal it)
- No pair plugin; no plugin is required

## Output contract

Write one file, `docs/qa/.last-smoke-pass.json`, at the end of the walk, in the shape the `wp-plugin-smoke` skill defines (`mode: "single"`, `release_version`, `ran_at`, `sections`, `failures`, `debug_log_issues`, `hard_fails`, `untested`, `coverage`). Every row you did not run goes in `untested` with a reason. The release gate (`scripts/release-smoke-gate.js`) refuses to package without a fresh report whose version matches. Emit a Basecamp draft per failure on project `38311801`, Bugs column `7605388587`.

## Fixture cleanup (before every walk)

```bash
wp --path="<wp root>" eval 'foreach ( array( "site_primary_color","site_link_color","site_text_color","site_heading_color","background_color","site_surface_color","site_header_bg_color","site_footer_content_color","site_copyright_background_color","site_copyright_content_color","knowx_corners","knowx_exclude","knowx_home_layout","knowx_show_updated","knowx_show_recent","sidebar_option","site_color_mode" ) as $m ) { remove_theme_mod( $m ); } echo "theme mods cleared\n";'
```

## Debug log protocol

Enable `WP_DEBUG`, `WP_DEBUG_LOG`, `WP_DEBUG_DISPLAY=false`. Baseline `wp-content/debug.log`, diff after every section into `debug_log_issues[]`. Any new fatal, warning, notice or `_load_textdomain_just_in_time` line is a failure.

---

## A - Fresh install

### A1 - Install the built zip and activate
**Verify:** `wp theme install dist/knowx-<version>.zip --activate` succeeds, the front end returns 200, the debug log is clean.
**Why:** only the built zip proves the packaging (fonts, patterns, min files, languages) is complete.

### A2 - Plug-and-play first view
**Verify:** with the sample content and no Customizer change, the home page is a finished docs home (hero search, product cards with icon, accent and count, recent articles, support block) with no placeholder text. Journeys J1, J19.

---

## C - Core customer flows

### C.visitor.home-and-products
**Verify:** home, a product page (sections with six articles, tree on the left on wide screens), a section page (paging, tree keeps the section open). J1 to J3, J12 (Products menu opens, marks the current product, closes with Escape and on an outside click).

### C.visitor.article-and-navigation
**Verify:** article shows title, version badge, last updated, code with a Copy button, previous and next, related list; heading links copy and jump; On this page works; Ctrl or Cmd + K search (scoped, all-products switch, no-result message, "1 result" and "N results" wording). J4 to J9.

### C.visitor.search-archive-404
**Verify:** search results with filter chips and the empty state, date, tag and author archives as docs lists, 404 with a search form, the FAQ and changelog pages (FAQPage data present). J10, J11, J18, J22.

### C.visitor.comments
**Verify:** the comment form and list render as cards in light and dark; a posted comment and a reply work. J23.

### C.visitor.rtl
**Verify:** with `dir="rtl"` the layout mirrors, paragraphs and headings start on the right, the code Copy button stays on the physical right of the code, list arrows point and sit at the inline end.

### C.owner.fresh-install-defaults
**Verify:** Customizer, Docs, Docs Home switches between cards and sections; Articles switches hide and restore the updated date and the table of contents. J19, J21.

### C.owner.palettes-and-colours
**Verify:** Customizer, Colors: each palette card applies, an own colour wins over the palette, Default resets everything including Link Color, text stays readable, and the preview updates without a reload. Corners square and round. J14, J25, J28.

### C.owner.customizer-every-section
**Verify:** expand all ten sections; every control is visible (paste `tests/docker/customizer-ui-check.js` in the console, `bad` must be empty); Typography groups collapse and keep their titles. J26.

### C.owner.editor-order-and-patterns
**Verify:** an article's Order can be set in the block editor ("Order in section" panel) and saved (the tree and lists change); `menu_order` is in `GET /wp/v2/posts/<id>?context=edit`; all 17 KnowX patterns are in the inserter and render. J16, J24.

### C.owner.single-product-site
**Verify:** on the single-product seed the home lists that product's sections directly, with no product card and no switcher. J20.

---

## D - Regression guards (exact repros of past defects)

- **D1 Excluded product on the Docs Index.** Set "Exclude category by ID" to the first product's id and open a page on the Knowledge Base Two template. No section or article of that product may appear. Excluding one section hides it on both layouts.
- **D2 Products menu.** Open it, press Escape (focus returns to the Products control), open it again, click elsewhere; it closes both times.
- **D3 Article Order.** Set Order 5 in the editor, save, read it from the database and REST.
- **D4 RTL alignment.** Typography defaults must not print `text-align:left`; with `dir=rtl` a paragraph computes `text-align: start`.
- **D5 Docs home pattern.** Insert the pattern on a page: one `h1` on the page (the page title), the hero is an `h2`, axe heading-order passes.
- **D6 Footer-small pattern.** `knowx/footer-small` must not be registered; the inserter lists exactly 17 KnowX patterns.
- **D7 Link colour.** Set a Link Color, click the Default palette card: the Link Color control returns to empty.
- **D8 Plural forms.** The search status uses wp.i18n `_n` (1 result, 5 results); "View all N articles" uses `_n()`; `languages/knowx.pot` contains both.
- **D9 Support text.** With no support link set, the docs home text does not promise "get in touch" and shows no button.
- **D10 Translation template.** `languages/knowx.pot` is regenerated as the last step before tagging with `wp i18n make-pot . languages/knowx.pot --slug=knowx --package-name=KnowX --exclude=node_modules,vendor,tests,playground,audit,plans,dist` (never exclude `docs`: WP-CLI skips `template-parts/docs/`).

---

## Failure protocol

A failure is logged with `id`, `expected`, `actual`, URL and screenshot, a Basecamp draft is prepared, and the walk continues. Hard fails (fatal, a setting that does not save, a hidden product still public) block the release on their own.
