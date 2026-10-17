# Settings reference

**Included in Free**

Every Customizer setting is a theme modification stored with `set_theme_mod()`. Read one with `knowx_mod( $id )`: it returns the saved value, or the default that is declared on the Customizer field, so a default lives in one place only.

```php
$width = knowx_mod( 'site_container_width' ); // '1170px' until the owner changes it.
if ( knowx_is_truthy( knowx_mod( 'knowx_show_toc' ) ) ) {
	// Print the contents list.
}
```

Switches are stored as `0` or `1`. Older sites may hold `'on'`, `'off'`, `'yes'` or `true`, so test a switch with `knowx_is_truthy()` and never with a plain `if ( $value )`: the string `'off'` is truthy in PHP.

Settings are registered in `inc/Customizer_Settings/Fields/*_Fields.php`, one file for each area. To add one, follow [Adding a setting, a pattern or a hook](06-adding-settings-patterns-and-hooks.md).

The table lists all registered settings, grouped by where the owner finds them under **Appearance > Customize**. For what each one does for the site owner, see the [Customizing](../customizing/01-customizer-overview.md) pages.


## Dark Mode

| Setting ID | Control | Default | Label |
|---|---|---|---|
| `site_color_mode` | radio | `light` | Default mode |
| `site_color_mode_toggle_show` | switch | `0` | Show a light / dark toggle in the header |

## Footer

| Setting ID | Control | Default | Label |
|---|---|---|---|
| `background_setting` | background | none (empty) | Footer Background |
| `site_copyright_text` | textarea | `"Copyright © [current_year] [site_title] \| Powered by [theme_author]"` | Copyright Line |

## Colors

| Setting ID | Control | Default | Label |
|---|---|---|---|
| `site_footer_content_color` | color | `#505050` | Footer Text |
| `site_copyright_background_color` | color | `#ffffff` | Copyright Background |
| `site_copyright_content_color` | color | `#505050` | Copyright Text |
| `site_header_bg_color` | color | `#ffffff` | Header Background |
| `site_primary_color` | color | `#03A9F4` | Theme Color |
| `site_link_color` | color | none | Link Color |
| `site_text_color` | color | none | Text Color |
| `site_heading_color` | color | none | Heading Color |
| `site_surface_color` | color | none | Content Background |

## Layout

| Setting ID | Control | Default | Label |
|---|---|---|---|
| `site_layout` | radio | `wide` | Layout Style |
| `site_container_width` | dimension | `1170px` | Content Width |
| `knowx_scroll_top` | switch | `0` | Back to top button |
| `knowx_corners` | radio | `soft` | Corners |

## Header and Breadcrumbs

| Setting ID | Control | Default | Label |
|---|---|---|---|
| `site_sticky_header` | switch | `1` | Sticky header |
| `sub_header_background_setting` | background | none (empty) | Breadcrumb Bar Background |
| `site_breadcrumbs` | switch | `1` | Show breadcrumbs |

## Docs > Docs Home

| Setting ID | Control | Default | Label |
|---|---|---|---|
| `hero_section_background_setting` | background | none (empty) | Hero Background |
| `hero_section_heading` | text | none | Hero Heading |
| `hero_section_content` | textarea | none | Hero Text |
| `knowx_exclude` | text | none | Hide Categories |
| `support_section_heading` | text | none | Support Heading |
| `support_section_content` | textarea | none | Support Text |
| `support_section_button_text` | text | none | Support Button Text |
| `support_section_button_link_url` | url | none | Support Button Link |
| `knowx_show_recent` | switch | `1` | Show the recent articles list |
| `knowx_show_support` | switch | `1` | Show the support block |
| `knowx_home_layout` | radio | `cards` | Products on the docs home |

## Docs > Docs Index

| Setting ID | Control | Default | Label |
|---|---|---|---|
| `knowx_posts` | number | `10` | Articles per category |
| `knowx_order` | radio | `date` | Order articles |

## Docs > Articles

| Setting ID | Control | Default | Label |
|---|---|---|---|
| `knowx_show_updated` | switch | `1` | Show last updated date |
| `knowx_article_comments` | switch | `0` | Show comments on articles |
| `knowx_faq_schema` | switch | `1` | FAQ structured data |
| `knowx_show_toc` | switch | `1` | Show the contents list (On this page) |
| `knowx_section_sort` | radio | `date` | Order articles in a section |

## Sidebar

| Setting ID | Control | Default | Label |
|---|---|---|---|
| `sidebar_option` | radio | `right` | Sidebar Layout |
| `sticky_sidebar_option` | switch | `1` | Keep the sidebar in view while scrolling |

## Typography

| Setting ID | Control | Default | Label |
|---|---|---|---|
| `site_title_typography_option` | typography | none | Site Title |
| `site_tagline_typography_option` | typography | none | Tagline |
| `headings_typography_option` | typography | none | Headings (H1 to H6) |
| `menu_typography_option` | typography | none | Menu |
| `typography_option` | typography | none | Body Text |

**Page Background Color** is WordPress' own `background_color` setting, relabelled by the Custom_Background component, so read it with `get_background_color()`.

Colour settings that are empty by default (link, text, heading and content background) are derived from the theme colour in CSS, so leaving them empty keeps a readable result in light and dark mode. Typography settings are stored as one array each (family, size, weight, line height, letter spacing, transform and colour); an empty value falls back to the CSS in `_custom-properties.css`.

## Other stored values

| Name | Kind | Purpose |
|---|---|---|
| `knowx_kb_version` | option | The docs data version used by `knowx_kb_cached()`. Not autoloaded. Changes when a published article, a category or a `knowx_` category field changes. |
| `knowx_setup` | option | The button the owner picked in the setup notice: `sample`, `home` or `dismiss`. While it is empty the notice shows on the Dashboard and Themes screens. |
| `knowx_theme_activated` | option | Set by KnowX 1.x, which created the docs home on activation. 2.0 only reads it: a site that has it does not see the setup notice. |
| `knowx_` transients | transient | One transient per cached docs result; the stored value carries the version it was built for. |

Removing the theme does not delete these. To reset every setting for a site, run `wp theme mod remove --all`.
