# Folder structure

**Included in Free**

A map of the theme folder as it is in the repository. Files marked *dev only* are not in the installable zip.

```
knowx/
├── style.css                 Theme header (name, version, text domain)
├── theme.json                Block editor settings: palette, fonts, spacing
├── functions.php             Bootstrap: autoloader, knowx(), helper files
├── header.php, footer.php    Page frame
├── index.php, page.php, single.php, archive.php, category.php, search.php, 404.php
├── page-knowledgebase-one.php   Template: Docs Home
├── page-knowledgebase-two.php   Template: Docs Index
├── page-faq.php, page-changelog.php   Templates: FAQ, Changelog
├── full-width.php, full-width-container.php   Templates: no title, no sidebar
├── comments.php, searchform.php
├── readme.txt                WordPress.org readme and changelog
├── screenshot.png
├── inc/                      PHP: components and helpers
├── template-parts/           Reusable template pieces
├── patterns/                 Block patterns (one file each)
├── assets/                   CSS, JS, fonts, images (source and built)
├── languages/                knowx.pot translation template
├── config/                   Build configuration (dev only)
├── scripts/, build-css.js, build-js.js   Build and release scripts (dev only)
├── tools/                    Generators: manifest, blueprint (dev only)
├── playground/, blueprint*.json   Sample content and Playground blueprints (dev only)
├── tests/                    PHPUnit and the Docker QA stack (dev only)
├── audit/                    Generated inventory and security notes (dev only)
├── docs/                     This documentation and the QA files (dev only)
└── plans/                    Work plans (dev only)
```

## `inc/`

| Path | What is in it |
|---|---|
| `Theme.php`, `Template_Tags.php`, `Component_Interface.php`, `Templating_Component_Interface.php` | The component system. `Theme` owns the component list. |
| `<Name>/Component.php` | One component per folder. See [Architecture](01-architecture.md) for the list. |
| `Customizer_Framework/` | The Customizer API: `Panel`, `Section`, `Field`, `Output_Builder` (automatic CSS output), `Active_Callback`, and the controls in `Controls/`. Written to depend only on the theme namespace. |
| `Customizer_Settings/` | `Component.php` creates the panels and sections; `Fields/*_Fields.php` hold the fields, one file per area (Colors, Layout, Header, Docs, Sidebar, Footer, Typography). |
| `Docs/` | Product and article meta, REST fields, schema box, cache hooks. |
| `Tokens/`, `Color_Mode/` | Colour variables and dark mode. |
| `helpers.php` | `knowx_mod()`, `knowx_is_truthy()` and other small helpers, loaded first. |
| `docs.php` | Products, section trees, article neighbours, structured data. |
| `knowledge-base.php` | Docs data queries and the versioned cache. |
| `breadcrumbs.php` | The breadcrumb trail and its filter. |
| `extra.php` | Wrapper and footer helpers used by `header.php` and `footer.php`. |

See the [Functions reference](09-functions-reference.md) for every helper.

## `template-parts/`

| Folder | Contents |
|---|---|
| `content/` | Generic post and page pieces: entry, header, title, meta, summary, thumbnail, taxonomies, pagination, error and 404 |
| `docs/` | The docs layout: `single.php` (article page), `article.php`, `sidebar.php` (section tree), `help.php` ("Still need help?"), `list-item.php`, `section-grid.php`, `simple-page.php` (FAQ and Changelog), `search-dialog.php` (instant search) |
| `header/` | `branding.php`, `navigation.php`, `search-trigger.php`, `product-switcher.php`, `custom_header.php` |
| `footer/` | `info.php`: footer menu and copyright line |
| `layout/` | `sidebar-left.php`, `sidebar-right.php` |

How they fit together is on [Templates and template parts](08-templates-and-template-parts.md).

## `assets/`

| Path | Contents |
|---|---|
| `css/src/` | Source stylesheets. `_*.css` files are partials imported by the entry files. |
| `css/*.min.css` | Built stylesheets (`global`, `content`, `docs`, `sidebar`, `widgets`, `comments`), committed. `css/editor/` holds the editor stylesheet. |
| `js/src/` | Source scripts (`.js` and `.ts`). |
| `js/*.min.js` | Built scripts, committed. |
| `fonts/`, `images/` | Self-hosted Open Sans and theme images |

Never edit a `.min.*` file. Edit the source and run `npm run build`; CI fails when the committed output differs from a fresh build. See [Styling, tokens and assets](11-styling-tokens-and-assets.md).

## `patterns/`

One PHP file per block pattern, with the WordPress pattern header. They are registered by WordPress itself. See [Block patterns](../templates-and-patterns/02-block-patterns.md).

## What ships in the zip

The installable zip holds the theme files, built assets, patterns, the translation template and `readme.txt`. It leaves out source maps, `node_modules`, tests, docs, plans, audit, tools, the build scripts and Markdown files. The release checklist verifies this on every build.
