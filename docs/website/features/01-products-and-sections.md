# Products, sections and articles

KnowX organises documentation with the categories and posts WordPress already has. There is no custom post type and no plugin.

| Docs concept | WordPress object |
|---|---|
| Product | Top-level category |
| Section | Child category of a product (they can nest) |
| Article | Post |
| Featured article | Sticky post |

## Add a product

1. Go to **Posts > Categories** and add a category with no parent. Write a short description; it is shown on the product card.
2. Choose an **icon** from the list and, if you want one, tick **Use an accent colour** and pick a colour. Buttons and links on that product's pages use it. If you choose nothing, a site with two or more products gives each product its own icon and colour from the theme's palette automatically, so the cards look different out of the box. With one product the theme colour is used.
3. Add child categories for the sections and publish articles in them.

WordPress' stock "Uncategorized" category is never shown as a product. The product appears on the docs home page and in the **Products** menu in the header. The menu is hidden when the site has a single product.

## Order of articles

Use the **Order** field in the editor sidebar. Articles with the same order are sorted by title. On a section page, **Customize > Knowledge Base > Order posts** decides what happens after the Order field: by date or by name.

## What readers see

- **Docs home:** product cards with an icon, the description and the article count, then featured (sticky) and recent articles.
- **Product page:** every section with its first six articles and a link to see all of them.
- **Section page:** the articles of the section with a short excerpt and a page number list.
- **Article page:** the section tree on the left, the article in the middle and "On this page" on the right. On tablets and phones the tree and the contents are collapsible panels above the article.

## Version badge

In the editor sidebar, the **Docs** box has an **Applies to version** field. Whatever you type is shown as a badge under the title, for example "Version 3.2".

## Large sites

The lists are cached and refresh when you publish, edit or delete a post or change a category. A product tree lists up to 500 articles; the section links always reach the full list. You can change the limits with the `knowx_kb_max_categories` and `knowx_tree_max_posts` filters.

## Search results, tags, dates and authors

Search results and the tag, date and author archives use the same article list as a section page. Search results and these archives print the product and section above each title, so a reader can tell two articles with the same name apart. The blog layout and sidebar settings apply to the posts page only.

## Order, start link and search filter

On **Posts > Categories**, a top-level category has two more fields next to its icon and colour:

| Field | What it does |
|---|---|
| Product order | Lowest number first, then by name. Applies to the docs home, the product switcher and the search filter. |
| Start here link | Where the **Start here** button on the product page goes. Empty opens the first article. |

When there are two or more products, search results show **All products** and one button per product. Choosing a product limits the results to that product and its sections.

**Customize > Knowledge Base > Layout One > Products on the docs home** switches between product cards and each product listed with its sections.
