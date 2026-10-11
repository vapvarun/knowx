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

Put these in a child theme's `functions.php` or a small plugin.

```php
// Turn off structured data, for example when an SEO plugin prints its own.
add_filter( 'knowx_schema_graph', '__return_empty_array' );

// Allow more articles in the section tree.
add_filter( 'knowx_tree_max_posts', fn() => 1000 );

// Always show the Products menu, even with one product.
add_filter( 'knowx_show_product_switcher', '__return_true' );

// Add a palette to Customize > Colors. Keys are setting ids; values are hex colours.
add_filter( 'knowx_color_palettes', function ( $palettes ) {
	$palettes['mint'] = array(
		'label'  => 'Mint',
		'colors' => array(
			'site_primary_color' => '#0f766e',
			'site_text_color'    => '#1f2937',
			'site_heading_color' => '#042f2e',
			'background_color'   => '#f0fdfa',
			'site_surface_color' => '#ffffff',
		),
	);
	return $palettes;
} );

// Add a "Docs" crumb after Home on every trail. Items are arrays with label and url;
// the last item (the current page) has no url.
add_filter( 'knowx_breadcrumb_items', function ( $items ) {
	array_splice( $items, 1, 0, array( array( 'label' => 'Docs', 'url' => home_url( '/docs/' ) ) ) );
	return $items;
} );

// Change the copyright line.
add_filter( 'knowx_footer_copyright_text', fn( $text ) => $text . ' All rights reserved.' );

// Replace the meta description on the home page.
add_filter( 'knowx_meta_description', function ( $text ) {
	return is_front_page() ? 'Guides and answers for Orbit.' : $text;
} );

// Print a notice above every page's content.
add_action( 'knowx_before_content', function () {
	echo '<p class="notice">Docs for version 3.</p>';
} );
```

## Filtering the registered files

`knowx_css_files` and `knowx_js_files` receive an array of `handle => data`. See [Styling, tokens and assets](11-styling-tokens-and-assets.md) for the keys.

## Customizer filters

`knowx_customizer_field_args` runs for every field before it is registered, with the field's arguments and the `WP_Customize_Manager`. Use it to change a default or a label from a child theme:

```php
add_filter( 'knowx_customizer_field_args', function ( $args ) {
	if ( 'site_container_width' === ( $args['settings'] ?? '' ) ) {
		$args['default'] = '1280px';
	}
	return $args;
} );
```

`knowx_customizer_field_type_map` lists the control types the framework knows; add one only when you also ship the control class. See [Adding a setting, a pattern or a hook](06-adding-settings-patterns-and-hooks.md).

## Naming

Every hook the theme defines starts with `knowx_`. Filters pass a value and expect it back. Actions pass nothing.
