# Child Theme Guide

Use a child theme to customize KnowX so your changes survive theme updates. Because KnowX is a classic PHP theme, standard WordPress child-theme conventions apply.

## Create the child theme

1. In `wp-content/themes/`, create a folder, for example `knowx-child`.
2. Add a `style.css` with a header that names KnowX as the parent (the `Template` value must match KnowX's folder name, `knowx`):

```css
/*
Theme Name: KnowX Child
Template: knowx
Version: 1.0.0
*/
```

3. Add a `functions.php` that enqueues the parent and child styles:

```php
<?php
add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_style( 'knowx-parent', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style(
        'knowx-child',
        get_stylesheet_directory_uri() . '/style.css',
        array( 'knowx-parent' )
    );
} );
```

4. Activate **KnowX Child** under **Appearance > Themes**.

## Override templates

Copy a template from the parent into your child theme with the same relative path, then edit the copy. WordPress uses the child version automatically. This works for root templates (`single.php`, `page.php`, `archive.php`, the page templates) and for files under `template-parts/`.

Keep the file's docblock, including the `Template Name:` line for page templates, so the template stays selectable.

## Prefer hooks and filters over template copies

Before copying a template, check whether a hook or filter does the job. KnowX exposes action hooks (`knowx_before_content`, `knowx_after_content`, `knowx_sub_header`, `knowx_wp_body_open`) and several filters for the footer, styles, fonts, and breadcrumbs. See [Hooks Reference](hooks.md). Using hooks keeps your child theme smaller and more update-safe than duplicating markup.

## Customizer and colors

Most visual customization (colors, typography, header, footer, layout) is available in the Customizer and does not require a child theme. Reach for a child theme when you need markup or logic changes, or CSS beyond what the Customizer and **Additional CSS** provide.

## Note on the component system

KnowX's PHP logic is organized as WP Rig components under `inc/`. These are internal to the parent theme. From a child theme, extend behavior through WordPress hooks and the KnowX filters rather than trying to re-register or replace parent components.
