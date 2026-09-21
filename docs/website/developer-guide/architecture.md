# Theme Architecture

KnowX is a classic (PHP template) theme built on the [WP Rig](https://github.com/wprig/wprig) starter. It uses the standard WordPress template hierarchy, not Full Site Editing. This page orients developers who want to extend or child-theme KnowX.

## Classic, not block-based

- Templates are PHP files at the theme root: `header.php`, `footer.php`, `index.php`, `single.php`, `page.php`, `archive.php`, `404.php`, `comments.php`, `sidebar.php`, `searchform.php`, and the page templates.
- There is no `templates/*.html` or `parts/*.html` directory, so KnowX is not a block theme. Its `theme.json` is used only to define block editor presets (palette, font sizes, spacing, layout widths) and does not drive site editing.
- Reusable markup lives in `template-parts/` (header branding and navigation, content entry parts, footer info, error/offline parts).

## Component system

KnowX follows WP Rig's component pattern. `functions.php` bootstraps the theme by calling `KnowX\KnowX\knowx()`, which instantiates `inc/Theme.php`. The theme registers a set of components (each in `inc/<Name>/Component.php`) that implement `Component_Interface`. Default components include:

Localization, Base_Support, Editor, Accessibility, Image_Sizes, Lazyload, AMP, PWA, Comments, Nav_Menus, Sidebars, Custom_Background, Custom_Header, Custom_Logo, Post_Thumbnails, Customizer, Styles, Kirki, Kirki_Option, Custom_Js, and Activation.

Two components load conditionally: **Jetpack** (when `JETPACK__VERSION` is defined) and **Dynamic_Style** (when the `Kirki` class exists).

Template tags are exposed through `knowx()->method()` (for example `knowx()->display_primary_nav_menu()` and `knowx()->print_styles()`).

## Theme supports declared

KnowX declares the following `add_theme_support` features:

- `automatic-feed-links`
- `title-tag`
- `html5` (search-form, comment-form, comment-list, gallery, caption)
- `customize-selective-refresh-widgets`
- `responsive-embeds`
- `custom-header`
- `custom-background`
- `custom-logo` (250x250, flex width and height)
- `post-thumbnails` (adds a `knowx-featured` image size at 720x480)
- `editor-styles`
- `wp-block-styles`
- `align-wide`
- `editor-color-palette` and `editor-font-sizes` (legacy palette in `inc/Editor/Component.php`; the active block editor palette is defined in `theme.json`)

## Requirements in code

`style.css` and `readme.txt` state the supported minimums: WordPress 4.8 and PHP 7.4, tested to WordPress 6.8.2. Internally, `functions.php` also defines a hard floor (`KNOWX_MINIMUM_WP_VERSION` 4.5, `KNOWX_MINIMUM_PHP_VERSION` 7.0); below that floor the theme loads `inc/back-compat.php` and stops initializing. Treat 4.8 / PHP 7.4 as the supported baseline.

## Assets and fonts

Front-end CSS and JS live in `assets/`. The `Styles` component manages stylesheet loading and preloading. The `Webfont` loader (`inc/Webfont/`) can download and serve Google Fonts locally, controlled from the Customizer's Site Performance section.

## Extending

- Add hooks: see [Hooks Reference](hooks.md) for the actions and filters KnowX fires.
- Override templates and styling: see [Child Theme Guide](child-theme.md).
