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
| `knowx_version` | post | string | yes | "Applies to version" badge on the article. |

Where each can be edited: Posts > Categories (icon, accent, description), the post editor sidebar (Order, Docs > Applies to version), and the REST API (`/wp/v2/categories`, `/wp/v2/posts`).

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
