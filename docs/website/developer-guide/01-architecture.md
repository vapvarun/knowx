# Architecture

**Included in Free**

KnowX is a classic theme with block features. It is based on WP Rig and uses namespaced components.

## Code layout

| Path | Purpose |
|---|---|
| `functions.php`, `inc/Theme.php` | Bootstrap and the component list |
| `inc/<Name>/Component.php` | One class per concern, namespace `KnowX\KnowX\<Name>` |
| `inc/docs.php`, `inc/knowledge-base.php`, `inc/breadcrumbs.php`, `inc/helpers.php`, `inc/extra.php` | Helper functions used by templates (`knowx_*`) |
| `inc/Customizer_Framework`, `inc/Customizer_Settings` | The built-in Customizer API and the settings |
| `inc/Tokens`, `inc/Color_Mode` | Colour variables and dark mode |
| `template-parts/docs`, `template-parts/header` | Docs layout parts and header parts |
| `patterns` | Block patterns |
| `assets/css/src`, `assets/js/src` | Source files; built files end in `.min.css` and `.min.js` |

## Settings

`knowx_mod( $id )` returns the saved value or the field's default. Switches are `0` or `1`; use `knowx_is_truthy()` to test them.

## Colours

The Customizer colour settings (theme, link, text, heading, page and content background, header and footer colours) and the five palettes write a few CSS variables in one `<style id="knowx-tokens">` tag. Every other colour is derived in CSS with `color-mix()` from the variables in `assets/css/src/_custom-properties.css`. `theme.json` points its palette at the same variables, so the editor and the front end match.

## Caching

`knowx_kb_cached( $key, $callback )` stores a result in one transient per key; the value carries the docs version it was built for. The version changes (`knowx_kb_invalidate()`) when a published article, a category or a `knowx_` category field changes, at most twice per request, so no manual clearing is needed and no old rows pile up.
