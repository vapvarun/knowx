# Child theme

**Included in Free**

Create a folder `knowx-child` in `wp-content/themes` with these files.

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

Activate it under **Appearance > Themes**.

## What you can override

- Copy any template (`single.php`, `category.php`) or template part (`template-parts/docs/article.php`) into the child theme and edit it.
- Add patterns in the child theme's `patterns` folder and colour schemes in its `styles` folder.
- Change colours with CSS variables, for example `:root { --color-theme-primary: #0a7; }`. See `assets/css/src/_custom-properties.css`.
- Use the [hooks](03-hooks.md).
