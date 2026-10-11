# Styling, tokens and assets

**Included in Free**

How the CSS and JavaScript are organised, which variables you can override, how dark mode works and how to build. Everything here is a source file you edit; the `.min.*` files are generated.

## The CSS variables

All design values are CSS custom properties on `:root`, declared in `assets/css/src/_custom-properties.css`. The stylesheets never contain a hex colour; they read these.

| Group | Variables |
|---|---|
| Brand | `--color-theme-primary`, `--color-on-primary`, `--color-link`, `--color-link-hover` |
| Text | `--color-heading`, `--color-h1` to `--color-h6`, `--color-muted`, `--global-font-color` |
| Surfaces | `--color-theme-body` (page), `--color-surface`, `--color-surface-alt`, `--color-block-bg-subtle`, `--color-header-bg` |
| Footer | `--color-footer-content`, `--color-footer-link`, `--color-footer-title`, `--color-copyright-bg`, `--color-copyright-content` |
| Code and marks | `--color-code-bg`, `--color-code-text`, `--color-mark-bg`, `--color-mark-text`, `--color-quote-border` |
| Buttons | `--button-background-color`, `--button-text-color` and their `-hover`, border and radius variants |
| Type | `--global-font-family`, `--highlight-font-family`, `--global-font-size`, `--global-font-line-height` |
| Shape | `--global-border-radius`, `--radius-sm`, `--radius-md`, `--radius-lg`, `--global-border-color` |
| Depth | `--shadow-sm`, `--shadow-md`, `--shadow-lg` |
| Stacking | `--z-base`, `--z-raised`, `--z-sticky`, `--z-header`, `--z-menu`, `--z-drawer`, `--z-overlay`, `--z-toolbar` |
| Layout | `--container-width`, `--content-width`, `--menu-offset`, `--icon-side` |

Use them in your own CSS, and override them in a child theme:

```css
:root {
	--color-theme-primary: #0a7;
	--radius-md: 4px;
}
```

Use the `--z-*` scale for any positioned element instead of a number, so a new element never ends up behind the header or the search dialog.

## How the Customizer changes colours

The Customizer writes only the colours the owner picked, in one `<style id="knowx-tokens">` tag printed in the head by `inc/Tokens/Component.php`. That tag overrides the defaults in `_custom-properties.css`. Colours the owner did not set are derived in CSS with `color-mix()` from the ones they did, which is why changing the theme colour re-tints links, buttons and highlights together. The live preview in the Customizer rebuilds the same tag in JavaScript (`assets/js/src/customizer.ts`), so the preview matches what is published.

`theme.json` points its palette at the same variables, so the block editor shows the same colours as the site.

## Dark mode

Dark mode is a second set of the same variables:

- `:root[data-color-mode="dark"]` holds the dark palette.
- `:root[data-color-mode="auto"]` repeats it inside `@media (prefers-color-scheme: dark)`.

`inc/Color_Mode/Component.php` sets the `data-color-mode` attribute from the Customizer default and the visitor's saved choice in an inline script in the head, before first paint, so there is no flash. `assets/js/src/color-mode-toggle.js` cycles the modes and remembers the choice. Light-mode colours the owner set do not apply in dark mode, so write your own overrides under the `[data-color-mode="dark"]` selector when a colour needs a dark counterpart.

## Stylesheets

| Handle | File | Loaded |
|---|---|---|
| `knowx-global` | `global.min.css` | On every page |
| `knowx-content` | `content.min.css` | Where a template calls `knowx()->print_styles( 'knowx-content' )` |
| `knowx-docs` | `docs.min.css` | On docs pages (home, product, section, article, FAQ, changelog, search) |
| `knowx-sidebar`, `knowx-widgets` | `sidebar.min.css`, `widgets.min.css` | With a sidebar |
| `knowx-comments` | `comments.min.css` | On a singular page with comments |

Only `knowx-global` is enqueued in the head. The others are registered and printed in the page body by the templates that need them, so a page never loads CSS it does not use. Filter `knowx_css_files` to add a stylesheet or remove one, and `knowx_preloading_styles_enabled` to switch the body printing off.

```php
add_filter( 'knowx_css_files', function ( $files ) {
	$files['my-extras'] = array(
		'file'   => 'my-extras.min.css', // Relative to assets/css of the active theme.
		'global' => true,
	);
	return $files;
} );
```

In a child theme prefer a normal `wp_enqueue_style()` after `knowx-global` (see [Child theme](04-child-theme.md)).

## Scripts

| Handle | Source | Purpose |
|---|---|---|
| `knowx-theme` | `theme.js` | Table cell labels and the optional back-to-top button |
| `knowx-docs` | `docs.js` | Instant search, copy buttons on code blocks, copyable heading links, the open section tree on wide screens |
| `knowx-navigation` | `navigation.ts` | Menu toggle, dropdowns and their keyboard handling; enqueued by the Accessibility component |
| `knowx-color-mode` | `color-mode-toggle.js` | The visitor's light and dark toggle; loaded only when the toggle is switched on |
| `knowx-editor-order` | `editor-order.js` | The Order field in the post editor; loaded in the block editor only |
| (Customizer) | `customizer.ts` | The live preview; loaded in the Customizer preview only |

The front-end scripts are deferred. There is no jQuery. Strings passed to scripts come from `wp_localize_script`-style data (`knowxData`, `knowxDocs`) set in `inc/Scripts/Component.php`, so they are translatable. Filter `knowx_js_files` to add or remove one of the `knowx-theme` and `knowx-docs` scripts.

The docs enhancements are progressive: with JavaScript off the section tree is native `<details>` and search goes to the normal results page.

## Build

Requires Node 22. From the theme folder:

```bash
npm ci
npm run dev       # watch CSS and JS while you work
npm run build     # build the CSS and the JavaScript
npm run lint      # ESLint, TypeScript, Stylelint, theme.json validation
```

CSS is bundled with Lightning CSS from `assets/css/src` into `assets/css/*.min.css` (nesting, custom media and custom properties are processed there). JavaScript and TypeScript are bundled with esbuild into `assets/js/*.min.js`. The built files are committed; CI rebuilds and fails if they differ. See [Building and testing](05-building-and-testing.md) for the test and release commands.

## Add a rule

1. Find the partial in `assets/css/src` that owns the component (`_header.css`, `_navigation.css`, `docs.css`, `_blocks.css`).
2. Use variables and logical properties (`margin-inline-start`, not `margin-left`) so right-to-left works without extra rules.
3. Add a `@media` rule for phones in the same change. Check 390px, light and dark, and right-to-left.
4. Run `npm run build` and commit the source and the built file together.
