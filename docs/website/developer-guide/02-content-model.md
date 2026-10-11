# Content model

KnowX uses only what WordPress already has. It adds no post type and no taxonomy.

| Docs concept | WordPress object | Notes |
|---|---|---|
| Product | Top-level category | Icon and accent colour are category term meta. The category description is the card text. |
| Section | Child category of a product | Sections can nest. Sections are listed by name. |
| Article | Post | Ordered inside a section by the editor's **Order** field, then by title. |
| Featured article | Sticky post | Shown on the Docs Home template. |
| FAQ | Page using the FAQ template, with core Details blocks | Two or more Details blocks produce FAQPage structured data. |
| Changelog | Page using the Changelog template | One heading per release. |

## Meta

| Key | Object | Type | REST | Purpose |
|---|---|---|---|---|
| `knowx_icon` | category | string (icon slug) | yes | Product icon, one of the 12 shipped icons. |
| `knowx_accent` | category | string (hex) | yes | Accent colour used as the brand colour on that product's pages. |
| `knowx_order` | category | integer | yes | Product order. Lowest first, then by name. Used by the docs home, the Products menu and the search filter. |
| `knowx_start` | category | string (URL) | yes | Where the **Start here** button on a product page goes. Empty means the first article. |
| `knowx_version` | post | string | yes | "Applies to version" badge on the article. |
| `knowx_faq_schema` | post, page | string (`off`) | yes | `off` keeps FAQ structured data off for this page, even when the Customizer switch is on. |

Article order is not meta: it is the post's own `menu_order`, which the theme exposes in the block editor (the **Order in section** box) and in the REST API as `menu_order` on `/wp/v2/posts`. The site-wide settings are theme modifications, listed in the [Settings reference](10-settings-reference.md).

Where each can be edited:

| Entry point | What |
|---|---|
| Front end | Everything above is printed by the docs templates. |
| Admin | **Posts > Categories** (icon, accent, product order, start link, description), the post editor sidebar (Order in section, Docs > Applies to version) and the **Structured data** box on pages and posts. |
| REST API | `/wp/v2/categories` (`knowx_icon`, `knowx_accent`, `knowx_order`, `knowx_start`) and `/wp/v2/posts` (`knowx_version`, `menu_order`). The theme registers no custom routes. |

Reading in code:

```php
$icon    = get_term_meta( $product_id, 'knowx_icon', true );
$version = knowx_article_version( $post_id );
$product = knowx_current_product(); // Array with id, name, url, description, count, icon, accent.
```

## Templates

| File | Purpose |
|---|---|
| `page-knowledgebase-one.php` (Docs Home) | Hero search, product cards, featured and recent articles. |
| `page-knowledgebase-two.php` | All sections with their latest articles. |
| `category.php` | Product home (sections with first articles) and section page (paginated articles). |
| `single.php` and `template-parts/docs/single.php` | Article with section tree, "On this page", previous/next and related articles. Posts outside any product keep the standard layout. |
| `page-faq.php`, `page-changelog.php` | Narrow single-column pages. |
| `full-width.php`, `full-width-container.php` | Pages without the container or sidebar. |

## Structured data

Printed once in the head as JSON-LD by `Docs\Component::render_schema()`:

- Front page: `WebSite` with a `SearchAction`.
- Article: `TechArticle` and `BreadcrumbList`.
- Page with two or more Details blocks: `FAQPage` built from the visible questions and answers.

Filter `knowx_schema_graph` to change the graph; return an empty array to turn it off (for example when an SEO plugin prints its own).

## Scale

The product list, section tree and counts are cached with a key that includes the posts and terms "last changed" values, so any edit rebuilds them. Queries are bounded: `knowx_kb_max_categories` (default 100 products, five times that many sections) and `knowx_tree_max_posts` (default 500 articles per product tree; sections link to their full paginated archive).

## Extension points

`knowx_show_product_switcher`, `knowx_schema_graph`, `knowx_kb_max_categories`, `knowx_tree_max_posts`.
