# Hooks reference

**Included in Free**

## Filters

| Filter | Purpose |
|---|---|
| `knowx_schema_graph` | The structured data nodes. Return an empty array to print none. |
| `knowx_meta_description` | The meta description text. Return an empty string to print none. |
| `knowx_show_product_switcher` | `( bool $show )` Show or hide the header Products menu (default: more than one product). |
| `knowx_kb_max_categories` | Maximum products and categories listed (default 100). |
| `knowx_tree_max_posts` | Maximum articles in one product's section tree (default 500). |
| `knowx_footer_copyright_text` | `( string $copyright )` The copyright line, placeholders already replaced. |
| `knowx_theme_author` | `( array $theme_author )` Keys `theme_name` and `theme_author_url`, used for the `[theme_author]` placeholder. |
| `knowx_css_files` | The registered stylesheets. |
| `knowx_js_files` | The registered scripts. |
| `knowx_preloading_styles_enabled` | Turn the in-body stylesheet preloading on or off. |
| `knowx_custom_logo_args`, `knowx_custom_header_args`, `knowx_custom_background_args` | `( array $args )` Arguments for the matching `add_theme_support()` call. |
| `knowx_customizer_field_args` | `( array $args, WP_Customize_Manager $wp_customize )` Arguments of one Customizer field before it is registered. |
| `knowx_customizer_field_type_map` | `( array $type_map )` The control types: field type to setting class, control class and custom flag. |
| `knowx_color_palettes` | `( array $palettes )` The five palette cards in Customizer, Colors: slug to `label` and `colors` (setting id to hex). |
| `knowx_breadcrumb_items` | `( array $items )` The breadcrumb items (`label`, `url`); the last item has no url. They feed the visible trail and the BreadcrumbList schema. |

## Actions

| Action | Where it fires |
|---|---|
| `knowx_sub_header` | After the header on every template; prints the breadcrumb bar |
| `knowx_before_content` | Opens the `.site-wrapper` that holds the content and the sidebars |
| `knowx_after_content` | Closes the `.site-wrapper` |

## Examples

```php
// Turn off structured data.
add_filter( 'knowx_schema_graph', '__return_empty_array' );

// Allow more articles in the section tree.
add_filter( 'knowx_tree_max_posts', fn() => 1000 );
```
