# Child theme

**Included in Free**

Use a child theme for any change that goes beyond the Customizer, so theme updates never overwrite your work. Everything below works the same on a site with one product or many.

## Create it

Create a folder `knowx-child` in `wp-content/themes` with two files.

`style.css`:

```css
/*
Theme Name: KnowX Child
Template: knowx
Version: 1.0.0
*/
```

`functions.php`:

```php
<?php
add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'knowx-child', get_stylesheet_uri(), array( 'knowx-global' ), '1.0.0' );
} );
```

Activate it under **Appearance > Themes**. Customizer values are stored per theme, so copy the parent's settings to the child once (see [Move the Customizer settings](#move-the-customizer-settings-to-the-child-theme)).

## What a child theme can change

| Goal | How |
|---|---|
| Colours, radius, shadows | Override the CSS variables in your stylesheet. See [Styling, tokens and assets](11-styling-tokens-and-assets.md). |
| The markup of a page or a part | Copy the file to the same path in the child theme. See [Templates and template parts](08-templates-and-template-parts.md). |
| Behaviour (schema, limits, palettes, breadcrumbs) | Add a filter. See the [Hooks reference](03-hooks.md). |
| Content to add above or below the page | `knowx_before_content`, `knowx_after_content`, `knowx_sub_header`. |
| A new block pattern | Add a file to the child theme's `patterns` folder. |
| A default Customizer value | Filter `knowx_customizer_field_args`. |

## Recipes

### Change the brand colour for the whole site

```css
:root {
	--color-theme-primary: #0a7;
}
```

A colour set in the Customizer wins over the variable in your stylesheet, because the theme prints the owner's choice after the defaults. Leave the Customizer **Theme Color** at its default if the child theme sets it.

### Override one template part

Copy `template-parts/docs/help.php` to `knowx-child/template-parts/docs/help.php` and change the text or markup. Keep the functions it calls. When the parent theme changes that file in an update, compare your copy against the new version: that is the price of a copy, and the reason to prefer a hook where one exists.

### Add a script

```php
add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_script( 'knowx-child-app', get_stylesheet_directory_uri() . '/app.js', array(), '1.0.0', array( 'strategy' => 'defer', 'in_footer' => true ) );
} );
```

### Load a stylesheet only on docs pages

```php
add_action( 'wp_enqueue_scripts', function () {
	if ( is_category() || is_singular( 'post' ) ) {
		wp_enqueue_style( 'knowx-child-docs', get_stylesheet_directory_uri() . '/docs.css', array( 'knowx-global' ), '1.0.0' );
	}
} );
```

### Move the Customizer settings to the child theme

WordPress stores Customizer values separately for each theme. After you activate the child theme, copy the parent's values once:

```bash
wp eval 'update_option( "theme_mods_knowx-child", get_option( "theme_mods_knowx" ) );'
```

Use your child theme's folder name in place of `knowx-child`. Without a copy, every setting shows its default until you publish it again in **Appearance > Customize**.

## Translations

The text domain is `knowx`. Use it in strings that you add to copied template parts so the existing translation files keep working. The translation template is `languages/knowx.pot`.

## Functions that changed in 2.0

The 1.x template tag `knowx_get_breadcrumb( $display = true )` still works but is deprecated: use `knowx_the_breadcrumb()` to print the trail. Many other 1.7 functions were internal callbacks of features that were removed in 2.0 (the login page, the plugin recommendations, the web font loader and the Kirki integration); child themes that called them directly need to drop those calls.

## Before you update the parent theme

1. Test the update on a staging copy.
2. Compare each template file you copied with the new parent file (`diff` or your editor).
3. Check the [Functions reference](09-functions-reference.md) for any helper your child theme calls.
