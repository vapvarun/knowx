# KnowX

A fast documentation and FAQ theme for WordPress, for one product or many. No plugin is required.

## What it does

**Structure**

- Products are top-level categories, sections are child categories and articles are posts. No custom post type.
- Product cards on the Docs Home page, each with an icon, a short description and an accent colour, and a header menu to switch products on sites with more than one.
- An Order field in the editor to sort articles inside a section.

**Reading**

- Article pages with a section tree, an "On this page" list, copy-link on headings, copy buttons on code blocks, previous and next links and an optional "Last updated" date.
- An "applies to version" badge on articles.
- Instant search (Ctrl or Cmd + K) that can search the current product or everything.
- Breadcrumbs.

**Templates and patterns**

- Page templates: Docs Home, Docs Index, FAQ, Changelog, Page Without Sidebar, and Full Width, No Title.
- 17 block patterns in the **KnowX Docs** category: API endpoint, article feedback, four callouts (note, tip, warning, danger), changelog entry, compatibility table, Docs Home, FAQ, getting started steps, next steps, popular tags, prerequisites, recently updated, support call to action and troubleshooting.

**Search engines and performance**

- schema.org data: WebSite with a search action on the home page, TechArticle and BreadcrumbList on articles, and FAQPage on pages with two or more Details blocks. The theme prints no meta description when Yoast SEO, Rank Math, All in One SEO, SEOPress or The SEO Framework is active.
- Open Sans is served from the theme, the front end loads no jQuery and nothing is fetched from another site.

**Design**

- Light and dark mode with an optional visitor toggle, a theme colour, a page background colour, right-to-left support, and Customizer settings for typography, layout, header, footer and Docs Home.

## Who it is for

Teams that publish product documentation, a help centre, a knowledge base, an FAQ or a changelog on WordPress and want it to work without adding plugins.

## Where to get it

- WordPress.org: https://wordpress.org/themes/knowx/
- Download page: https://wbcomdesigns.com/downloads/knowx-theme/
- Source and issues: https://github.com/vapvarun/knowx

## Documentation

- Documentation hub: https://docs.wbcomdesigns.com/wordpress-family/knowx-theme/
- Installation: https://docs.wbcomdesigns.com/wordpress-family/knowx-theme/getting-started/03-installation/
- First setup: https://docs.wbcomdesigns.com/wordpress-family/knowx-theme/getting-started/04-first-setup/
- Release notes: https://wbcomdesigns.com/release-notes/knowx-theme/

The same documentation is in [`docs/website`](docs/website).

## Requirements

- WordPress 6.9 or later (tested up to 7.1)
- PHP 8.1 or later
- Pretty permalinks are recommended, for example **Post name**.

## Installation

1. Go to **Appearance > Themes > Add New Theme**, search for KnowX, then install and activate it. Or upload the zip under **Upload Theme**.
2. Under **Posts > Categories**, create a top-level category for each product and child categories for its sections.
3. Publish articles in those categories and use the Order field in the editor to sort them inside a section.
4. Create a page with the **Docs Home** template and set it as your homepage under **Settings > Reading**.
5. Adjust colours, typography and layout under **Appearance > Customize**.

To see a finished site first, choose **Add sample docs** in the setup notice on the Dashboard after activating the theme. It creates two products, an FAQ, a changelog and a menu, and changes your homepage only if it shows latest posts. You can also open the sample site in WordPress Playground using `https://playground.wordpress.net/?blueprint-url=<raw URL of blueprint.json>`.

## For developers

**Identifiers.** Text domain `knowx`, function prefix `knowx_`, PHP namespace `KnowX\KnowX`. Classic theme with block features, built from one class per concern in `inc/<Name>/Component.php`, listed in `inc/Theme.php`.

**Hooks.** Filters include `knowx_schema_graph`, `knowx_meta_description`, `knowx_show_product_switcher`, `knowx_kb_max_categories`, `knowx_tree_max_posts`, `knowx_footer_copyright_text` and `knowx_customizer_field_args`. Full list: https://docs.wbcomdesigns.com/wordpress-family/knowx-theme/developer-guide/03-hooks/

**Child themes.** https://docs.wbcomdesigns.com/wordpress-family/knowx-theme/developer-guide/04-child-theme/

**Templates and template parts.** Page templates sit in the theme root; template parts are in `template-parts/` (`docs/`, `content/`, `header/`, `footer/`) and patterns in `patterns/`. Guide: https://docs.wbcomdesigns.com/wordpress-family/knowx-theme/developer-guide/08-templates-and-template-parts/

**Blocks, REST and WP-CLI.** The theme registers no custom blocks, REST routes or WP-CLI commands.

| Path | What |
|---|---|
| `functions.php`, `inc/Theme.php` | Bootstrap and the list of components |
| `inc/docs.php`, `inc/knowledge-base.php`, `inc/breadcrumbs.php`, `inc/extra.php`, `inc/helpers.php` | Functions the templates call (`knowx_*`) |
| `inc/Customizer_Framework`, `inc/Customizer_Settings` | The built-in Customizer API and the settings |
| `inc/Tokens`, `inc/Color_Mode` | Colour variables, palettes and dark mode |
| `assets/css/src`, `assets/js/src` | Sources; built files sit next to them |
| `tests/phpunit`, `tests/docker` | Unit tests and the Docker integration tests |

**Build and test.**

```bash
npm ci            # Node 22 is used in CI
composer install  # PHP 8.1 or later
npm run build     # builds the .min.css and .min.js files (they are committed)
npm run lint      # ESLint, tsc, stylelint and theme.json
npm test          # PHPUnit unit tests
vendor/bin/phpcs --standard=phpcs.xml.dist
bash tests/docker/run-integration.sh   # integration tests (needs Docker)
```

Run `npm run build` before you commit a change to a source file, because CI fails when the committed built files differ from the build. More: https://docs.wbcomdesigns.com/wordpress-family/knowx-theme/developer-guide/05-building-and-testing/ and [CONTRIBUTING.md](CONTRIBUTING.md).

## Support

Questions and bug reports: https://wbcomdesigns.com/support/

## License

GPL v3 or later. See [LICENSE](LICENSE).
