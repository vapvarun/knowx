# Architecture

**Included in Free**

KnowX is a classic PHP theme with block features. It is based on WP Rig: small namespaced classes ("components"), each owning one concern, plus plain `knowx_*` functions that templates call. It has no custom post type, no custom table, no jQuery and makes no external request.

Read this page first, then [Folder structure](07-folder-structure.md) for where things live, and [Content model](02-content-model.md) for how documentation maps onto WordPress.

## How the theme starts

1. WordPress loads `functions.php`.
2. `functions.php` loads `inc/helpers.php` (the `knowx_mod()` family), registers an autoloader for the `KnowX\KnowX` namespace (Composer's if `vendor/autoload.php` exists, a small built-in one otherwise) and calls `knowx()`.
3. `knowx()` in `inc/functions.php` builds one `Theme` object the first time it is called. `Theme` creates the list of components in `get_default_components()` and calls `initialize()` on each. A component adds its own hooks there; nothing else happens at load time.
4. `functions.php` then loads the procedural helpers: `inc/extra.php`, `inc/knowledge-base.php`, `inc/docs.php` and `inc/breadcrumbs.php`.

```php
// A component, reduced to its shape.
namespace KnowX\KnowX\Excerpts;

class Component implements Component_Interface {
	public function get_slug(): string {
		return 'excerpts';
	}

	public function initialize() {
		add_filter( 'excerpt_more', array( $this, 'filter_excerpt_more' ) );
	}
}
```

## Components

Every folder in `inc/` that holds a `Component.php` is one component. They are initialised in this order:

| Component | What it owns |
|---|---|
| `Base_Support` | Core theme support, body classes, the embed width |
| `Editor` | Editor styles and the block editor setup |
| `Accessibility` | The navigation script (menu toggle and keyboard handling) and accessibility improvements to core features; the skip link itself is in `header.php` |
| `Image_Sizes` | The `sizes` attribute so browsers pick the right image width |
| `Comments` | The comments template tag and its styles |
| `Nav_Menus` | The Primary and Footer menu locations, the menu fallback and dropdown buttons |
| `Sidebars` | The two sidebar areas and the sidebar template tags |
| `Custom_Background`, `Custom_Header`, `Custom_Logo` | The matching `add_theme_support()` calls and their filters |
| `Post_Thumbnails` | Featured image support and sizes |
| `Customizer` | WordPress Customizer integration for site identity and live preview |
| `Styles` | Stylesheet registration, enqueueing and preloading |
| `Scripts` | Script registration and the data passed to them |
| `Excerpts` | The excerpt suffix |
| `Customizer_Settings` | All the theme's Customizer panels, sections and fields |
| `Tokens` | The colour variables the Customizer can change, written as one `<style>` tag |
| `Color_Mode` | Light, dark and automatic mode, applied before first paint, plus the visitor toggle |
| `Docs` | Product and article meta, the category screen fields, cache invalidation, structured data, the editor Order field |
| `Activation` | The Knowledge Base page created on first activation |

`Customizer_Framework` is the in-house Customizer API (`Panel::add()`, `Section::add()`, `Field::add()`), which `Customizer_Settings` uses. The theme has no Kirki dependency.

## Template tags

A few components expose methods to templates through `knowx()`:

| Call | From | Purpose |
|---|---|---|
| `knowx()->display_primary_nav_menu( $args )` | `Nav_Menus` | Prints the Primary menu; accepts `wp_nav_menu()` arguments such as `menu_id` |
| `knowx()->display_left_sidebar()`, `display_right_sidebar()` | `Sidebars` | Print a sidebar |
| `knowx()->is_left_sidebar_active()`, `is_right_sidebar_active()` | `Sidebars` | Whether a sidebar has widgets |
| `knowx()->the_comments()` | `Comments` | Prints the comments list and form |
| `knowx()->color_mode_toggle()` | `Color_Mode` | Prints the light and dark toggle button |
| `knowx()->print_styles( ...$handles )` | `Styles` | Prints registered stylesheets in the page body, where they are needed |

## Settings

`knowx_mod( $id )` returns the saved value or the field's default. Switches are `0` or `1`; use `knowx_is_truthy()` to test them. See the [Settings reference](10-settings-reference.md).

## Colours

The Customizer colour settings (theme, link, text, heading, page and content background, header and footer colours) and the five palettes write a few CSS variables in one `<style id="knowx-tokens">` tag. Every other colour is derived in CSS with `color-mix()` from the variables in `assets/css/src/_custom-properties.css`. `theme.json` points its palette at the same variables, so the editor and the front end match. See [Styling, tokens and assets](11-styling-tokens-and-assets.md).

## Caching

`knowx_kb_cached( $key, $callback )` stores a result in one transient per key; the value carries the docs version it was built for. The version changes (`knowx_kb_invalidate()`) when a published article, a category or a `knowx_` category field changes, at most twice per request, so no manual clearing is needed and no old rows pile up. The version option is not autoloaded.

## Rules the code follows

- **Bounded lists.** Every product list, section tree and archive has a limit or is paginated, and is cached. Checked at 1,000 or more articles and 500 or more categories.
- **Three entry points.** Anything stored is reachable from the front end, an admin screen and the REST API. See [Content model](02-content-model.md).
- **No hex colours in stylesheets.** Use the variables.
- **Escape late, translate everything.** Text domain `knowx`.
- **Gates before a commit.** `composer`: PHPStan, PHPCS (`phpcs.xml.dist`), PHPUnit. `npm`: lint and build. See [Building and testing](05-building-and-testing.md).
