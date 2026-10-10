# Completeness plan: single-product and multi-product docs sites

Status 2026-10-10. Found by reviewing the shipped pages with fresh eyes. Nothing here is released.

## A. Last updated and modified info (decided: do first)

- [x] Article page shows "Last updated"; setting added to hide it (Articles section).
- [x] Updated date in `list-item.php` (section list, search results, archives).
- [x] Docs home: "Recently updated" list ordered by modified date (setting: Recent articles list), cached.
- [ ] Product and section pages: "Updated <date>" of the newest article in the header.
- [ ] Sort option on sections: menu order (default), title, last updated.

## B. Single-product site

- [x] Docs home with one product: one card per section, no product card, switcher hidden.
- [ ] Header and breadcrumb skip the product level when there is one product.
- [ ] Sidebar tree opens straight on the product (no product label row).
- [ ] Sample content variant for Playground: "Orbit Helpdesk only".

## C. Multi-product site

- [ ] Product order: by name today; add drag order through term meta `knowx_order`.
- [ ] Per-product hero text and "Start here" target (term meta).
- [ ] Search results: product filter chips (All, each product).
- [ ] Docs home: option to list products as cards, or as full sections.

## D. Archives

- [x] Tag, date and author archives use the docs list (like search), no default widget sidebar.
- [ ] Tag cloud block on the docs home or in the sidebar (core block, no code).

## E. Customizer settings still missing

| Setting | Why |
|---|---|
| Show last updated on articles | done |
| Show contents (TOC) on articles | Short articles |
| Show previous / next on articles | Same |
| Show version badge | Single-product sites without versions |
| Number of recent and featured articles on the docs home | Today fixed |
| Docs home sections: show / hide featured, recent, support | Remove blocks a site does not need |
| Footer menu location and a "Help" link row | Only a primary menu exists |
| Default article comments on / off for new posts | Docs rarely want comments |

Done 2026-10-10: show TOC, show prev/next, show More in this section, show version badge, home recent and support toggles, home list size, footer menu location. Left to core: comments default for new posts (Settings > Discussion).

## F. Widgets and blocks

- [ ] Sidebar widget areas exist (right, left, four footer). Add docs-aware blocks as patterns, not widgets: Product list, Recently updated, Popular tags.
- [ ] Core blocks cover everything else (Details, Table of contents is not in core: keep the theme TOC).

## G. Patterns to add (category `knowx-docs`; five exist: callout, changelog entry, FAQ, steps, support CTA)

- [x] Warning and danger callouts (same markup as the note).
- [x] Prerequisites list.
- [x] Troubleshooting (symptom, cause, fix).
- [x] API endpoint (method, URL, parameters table, example).
- [x] Compatibility table (version by version).
- [x] Related articles and Next steps.
- [x] Was this helpful? (links to support, no storage).
- [x] Docs home built from blocks (hero, product grid, recent, support) for sites that prefer the editor.

## H. Verification for each item

Browser check at 390, 768, 1024 and 1440 px in light and dark, axe, the settings QA harness, and both sample sites (one product, two products).
