# Functions reference

**Included in Free**

Template-facing helpers are plain functions prefixed `knowx_`, loaded from `functions.php`. Use them in a child theme template or a plugin. These are the public helpers. Functions that are not listed here are internal and may change.

Two other ways in: the `knowx()` function returns the template tags object (`knowx()->display_primary_nav_menu()`, `knowx()->display_left_sidebar()` and so on), and the [hooks](03-hooks.md) change behaviour without a copy of the code.


## Settings and small helpers

Defined in `inc/helpers.php`.

| Function | Returns | What it does |
|---|---|---|
| `knowx_mod( $id )` | `mixed` | Returns a theme setting with its registered default. The default is the one declared on the Customizer field, so it lives in one place. |
| `knowx_is_truthy( $value )` | `bool` | Whether a setting value means "on". Accepts booleans, integers and the strings 1, on, yes, true and enable (case insensitive). Unlike wp_validate_boolean(), the string "off" is false. |
| `knowx_search_form_label()` | `string` | Accessible name for the next search form on the page. Every search landmark needs its own name: the first is the site search, the others are numbered. |

## Products, sections and articles

Defined in `inc/docs.php`.

| Function | Returns | What it does |
|---|---|---|
| `knowx_product_icons()` | `array[]` | Icons a product can choose, as slug => array( label, SVG path data on a 24px grid ). |
| `knowx_default_product_looks()` | `array[]` | Colours and icons a product gets when its owner has not picked any. Chosen by category id, so a product keeps the same look as products are added. Used only when the site has two or more products; with one product the theme colour applies. |
| `knowx_icon_svg( $slug, $size = 24 )` | `string` | Prints-ready inline SVG for a product icon. |
| `knowx_products()` | `array[]` | Products: top-level categories that hold articles, with their icon, accent colour and article count. |
| `knowx_current_product_id()` | `int` | Id of the product the current request belongs to: the top-level ancestor of the category or article. |
| `knowx_current_product()` | `array|null` | The current product's data from knowx_products(). |
| `knowx_article_count_label( $count )` | `string` | Text for an article count, such as "12 articles". |
| `knowx_tree_max_posts()` | `int` | Upper limit for articles listed in one product's tree. |
| `knowx_product_tree( $product_id )` | `array` | Section tree of a product: sections (child categories) with their articles. Two bounded queries (all descendant terms, all articles) however large the product is. Articles are ordered by the editor's Order field, then by title. |
| `knowx_tree_flat( $tree )` | `array[]` | Articles of a tree in reading order, for previous and next links. |
| `knowx_tree_open_ids( $tree, $post_id, $term_id )` | `int[]` | Section ids that must be open: the current section and everything above it. |
| `knowx_render_tree( $tree, $post_id = 0, $term_id = 0 )` |  | Prints the section tree: collapsible sections (no JavaScript needed) listing their articles. |
| `knowx_toc_items( $set = null )` | `array[]` | Contents items collected by knowx_add_heading_anchors() for the current article. |
| `knowx_add_heading_anchors( $content )` | `string` | Gives h2 and h3 headings an id and a link to themselves, and records them for "On this page". |
| `knowx_render_toc( $items )` |  | Prints the "On this page" list. |
| `knowx_article_version( $post_id )` | `string` | The "applies to version" label of an article. |
| `knowx_block_text( $block )` | `string` | Plain text of a parsed block and everything inside it. |
| `knowx_faq_from_blocks( $blocks )` | `array[]` | Question and answer pairs from the core Details blocks of a parsed post. |
| `knowx_schema_graph()` | `array[]` | Structured data (schema.org) for the current page, as a JSON-LD graph. Home: WebSite with a search action. Article: TechArticle and BreadcrumbList. Any page with two or more Details blocks: FAQPage. |
| `knowx_schema_logo_url()` | `string` | The site logo, or the site icon, as an absolute URL for structured data. |
| `knowx_seo_plugin_active()` | `bool` | Whether an SEO plugin (Yoast, Rank Math, All in One SEO, SEOPress, The SEO Framework) prints the meta description and the site, breadcrumb and article schema itself. |
| `knowx_meta_description()` | `string` | Text for the meta description of the current page. Article or page: its excerpt. Category: its description. Front page: the page excerpt or the site tagline. Empty when the page has nothing to describe, or when an SEO plugin is active and prints its own. |
| `knowx_term_updated( $term_id )` | `array` | The newest last-modified date among the articles of a product or section, children included. |
| `knowx_entry_trail()` | `string[]` | Product and section names above an article card, for lists that mix products (search, tag, date, author). |
| `knowx_article_neighbours( $flat, $tree )` | `array` | Previous, next and related articles for the current article. |
| `knowx_start_url( $product, $flat )` | `string` | Where "Start here" points on a product page: the owner's link, else the first article. |
| `knowx_home_view()` | `array` | Everything the Docs Home template needs from the Customizer, in one call: hero and support texts, the support button, products, featured and recent articles, the column class, the updated-date switch and the single-product tree. |

## Docs data and cache

Defined in `inc/knowledge-base.php`.

| Function | Returns | What it does |
|---|---|---|
| `knowx_kb_excluded_ids()` | `int[]` | Category ids hidden from both Knowledge Base layouts. |
| `knowx_kb_version()` | `string` | Version stamp of the docs data. It changes whenever a post, a category or category meta changes. Stored in an option on purpose: the "last changed" values of the object cache are rebuilt on every request when the site has no persistent object cache, so a transient keyed on them never hits. |
| `knowx_kb_bump_version()` |  | Invalidates every cached docs result. Hooked to the post and term actions in the Docs component. |
| `knowx_kb_invalidate( $reset = false )` |  | Refreshes the docs cache after a content change, without writing the option once per hook. The first call in a request bumps the version at once; later calls only queue one more bump for shutdown, so a save that fires many hooks costs two writes at most. |
| `knowx_kb_cached( $key, $callback )` | `array` | Returns cached data and rebuilds it whenever any post or term changes. |
| `knowx_kb_map_terms( $terms )` | `array[]` | Maps category terms to the fields the templates print. |
| `knowx_kb_map_posts( $posts )` | `array[]` | Maps posts to the fields the templates print. |
| `knowx_kb_max_categories()` | `int` | Upper limit for categories printed by a layout. |
| `knowx_kb_recent_posts( $limit = 5 )` | `array[]` | Recently updated posts outside the excluded categories. |
| `knowx_kb_featured_posts( $limit = 5 )` | `array[]` | Sticky posts outside the excluded categories, shown as featured articles. Owners mark an article sticky in the editor to feature it. |
| `knowx_kb_post_query( $exclude, $args = array( )` | `array` | Builds the shared get_posts() arguments. |
| `knowx_kb_sections()` | `array[]` | Categories with their latest posts (Layout Two). Reads the Posts per category (default 10) and Order posts settings. |

## Breadcrumbs

Defined in `inc/breadcrumbs.php`.

| Function | Returns | What it does |
|---|---|---|
| `knowx_get_breadcrumb_items()` | `array[]` | Builds the breadcrumb trail for the current request. The same items feed the visible trail and the BreadcrumbList schema. |
| `knowx_build_breadcrumb_items()` | `array[]` | Builds the breadcrumb items before the `knowx_breadcrumb_items` filter runs. |
| `knowx_the_breadcrumb()` |  | Prints the breadcrumb trail. |
| `knowx_get_breadcrumb( $display = true )` | `string\|void` | Deprecated since 2.0.0: the 1.x breadcrumb tag, kept so child themes keep working. Use `knowx_the_breadcrumb()`. |

## Layout helpers

Defined in `inc/extra.php`.

| Function | Returns | What it does |
|---|---|---|
| `knowx_content_top()` |  | Opens the site wrapper that holds the content and the sidebars. |
| `knowx_content_bottom()` |  | Closes the site wrapper. |
| `knowx_sub_header()` |  | Prints the bar with the breadcrumbs under the header. |
| `knowx_categorized_blog()` | `bool` | Whether the site has more than one category in use. |
| `knowx_posted_on()` |  | Prints the author, date and category line of a post. |
| `knowx_template_uses_container()` | `bool` | Whether header.php should wrap the page content in a `.container`. Full width pages and the Knowledge Base Layout One page build their own full-bleed sections, so the container opened in header.php and closed in footer.php is skipped for them. |
