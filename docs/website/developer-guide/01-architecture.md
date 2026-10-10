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
| `inc/Tokens`, `inc/Color_Mode` | Colour variables, schemes and dark mode |
| `template-parts/docs`, `template-parts/header` | Docs layout parts and header parts |
| `patterns`, `styles` | Block patterns and colour-scheme style variations |
| `assets/css/src`, `assets/js/src` | Source files; built files end in `.min.css` and `.min.js` |

## Settings

`knowx_mod( $id )` returns the saved value or the field's default. Switches are `0` or `1`; use `knowx_is_truthy()` to test them.

## Colours

Five colour settings drive the whole palette. Every other colour is derived in CSS with `color-mix()` from the variables in `assets/css/src/_custom-properties.css`. `theme.json` points its palette at the same variables, so the editor and the front end match.

## Caching

`knowx_kb_cached( $key, $callback )` stores a result in a transient keyed by a version option. The version changes whenever a post, category or category meta changes (`knowx_kb_bump_version()`), so no manual clearing is needed.
