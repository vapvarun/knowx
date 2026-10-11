# Hooks reference

**Included in Free**

## Filters

| Filter | Purpose |
|---|---|
| `knowx_schema_graph` | The structured data nodes. Return an empty array to print none. |
| `knowx_meta_description` | The meta description text. Return an empty string to print none. |
| `knowx_show_product_switcher` | Show or hide the header Products menu. |
| `knowx_kb_max_categories` | Maximum products and categories listed (default 100). |
| `knowx_tree_max_posts` | Maximum articles in one product's section tree (default 500). |
| `knowx_footer_copyright_text` | The copyright line markup. |
| `knowx_theme_author` | Name and link used for the `[theme_author]` placeholder. |
| `knowx_css_files` | The registered stylesheets. |
| `knowx_js_files` | The registered scripts. |
| `knowx_preloading_styles_enabled` | Turn the in-body stylesheet preloading on or off. |
| `knowx_custom_logo_args`, `knowx_custom_header_args`, `knowx_custom_background_args` | Arguments for the matching theme support. |
| `knowx_customizer_field_args`, `knowx_customizer_field_type_map` | Adjust Customizer fields. |

## Actions

| Action | Where it fires |
|---|---|
| `knowx_before_content` | Before the main content on every template |
| `knowx_after_content` | After the main content |
| `knowx_sub_header` | Prints the sub header (breadcrumbs and search) |

## Examples

```php
// Turn off structured data.
add_filter( 'knowx_schema_graph', '__return_empty_array' );

// Allow more articles in the section tree.
add_filter( 'knowx_tree_max_posts', fn() => 1000 );
```
