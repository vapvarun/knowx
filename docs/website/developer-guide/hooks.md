# Hooks Reference

These are the action and filter hooks that KnowX defines in its own code. Use them from a child theme or plugin to extend the theme without editing its files. (WordPress core hooks and hooks from third-party plugins are not listed here.)

## Action hooks

| Action | Fired in | Purpose |
|--------|----------|---------|
| `knowx_wp_body_open` | `inc/wordpress-shims.php` (runs on `wp_body_open`) | Right after the opening `<body>` tag. Add analytics or top-of-page markup. |
| `knowx_sub_header` | Page, single, archive, index, bbPress, and full-width templates | Renders the sub-header (breadcrumbs and search). Hook here to add content to the sub-header area. |
| `knowx_before_content` | Most templates, before the main content | Output markup just before the main content region. |
| `knowx_after_content` | Most templates, after the main content | Output markup just after the main content region. |

KnowX itself attaches `knowx_content_top()` to `knowx_before_content`, `knowx_content_bottom()` to `knowx_after_content`, and `knowx_sub_header()` to `knowx_sub_header`.

## Filter hooks

### Footer and layout

| Filter | Fired in | Purpose |
|--------|----------|---------|
| `knowx_footer_copyright_text` | `inc/extra.php` | Filter the footer copyright text markup. |
| `knowx_theme_author` | `inc/extra.php` | Filter the theme author name and URL used in the copyright output. |
| `knowx_search_slide_toggle_data_attrs` | `inc/extra.php` | Filter data attributes on the search slide-toggle wrapper. |
| `knowx_search_field_toggle_data_attrs` | `inc/extra.php` | Filter data attributes on the search field toggle container. |

### Styles and fonts

| Filter | Fired in | Purpose |
|--------|----------|---------|
| `knowx_preloading_styles_enabled` | `inc/Styles/Component.php` | Enable or disable stylesheet preloading. |
| `knowx_css_files` | `inc/Styles/Component.php` | Filter the registered CSS files and their metadata. |
| `knowx_google_fonts` | `inc/Styles/Component.php` | Filter the Google Fonts KnowX enqueues. |
| `knowx_local_font_file_name` | `inc/Webfont/class-knowx-webfont-loader.php` | Filter the local font file name. |
| `knowx_local_google_fonts_format` | `inc/Webfont/class-knowx-webfont-loader.php` | Filter the local Google Fonts format. |
| `knowx_local_fonts_base_path` | `inc/Webfont/class-knowx-webfont-loader.php` | Filter the base path for locally hosted fonts. |
| `knowx_local_fonts_base_url` | `inc/Webfont/class-knowx-webfont-loader.php` | Filter the base URL for locally hosted fonts. |
| `knowx_local_fonts_directory_name` | `inc/Webfont/class-knowx-webfont-loader.php` | Filter the directory name for locally hosted fonts. |

### Breadcrumbs

| Filter | Fired in | Purpose |
|--------|----------|---------|
| `knowx_breadcrumb_trail_args` | `inc/class-knowx-breadcrubms.php` | Filter the breadcrumb trail arguments. |
| `knowx_breadcrumb_trail_object` | `inc/class-knowx-breadcrubms.php` | Filter the breadcrumb trail object. |
| `knowx_breadcrumb_trail` | `inc/class-knowx-breadcrubms.php` | Filter the final breadcrumb trail markup. |
| `knowx_breadcrumb_trail_labels` | `inc/class-knowx-breadcrubms.php` | Filter the breadcrumb labels. |
| `knowx_breadcrumb_trail_post_taxonomy` | `inc/class-knowx-breadcrubms.php` | Filter the taxonomy used for post breadcrumbs. |
| `knowx_breadcrumb_trail_items` | `inc/class-knowx-breadcrubms.php` | Filter the individual breadcrumb items. |

## Example

```php
// Append a notice after the main content on every page.
add_action( 'knowx_after_content', function () {
    echo '<p class="kb-note">Need more help? Contact support.</p>';
} );
```
