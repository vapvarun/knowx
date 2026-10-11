# Templates and template parts

**Included in Free**

Which file WordPress loads for each kind of page, which parts it prints, and how to override or extend them from a child theme.

## Which template loads when

| Page | File | Notes |
|---|---|---|
| Home page set to a page with the **Docs Home** template | `page-knowledgebase-one.php` | Hero with search, product cards or sections, featured and recent articles, support block |
| Page with the **Docs Index** template | `page-knowledgebase-two.php` | Every category with its latest articles |
| Page with the **FAQ** or **Changelog** template | `page-faq.php`, `page-changelog.php` | Narrow column through `template-parts/docs/simple-page.php` |
| Page with **Full Width, No Title** or **Page Without Sidebar** | `full-width.php`, `full-width-container.php` | No sidebar |
| Any other page | `page.php` | Optional sidebars |
| Article that belongs to a product | `single.php` then `template-parts/docs/single.php` | Section tree, article, "On this page", previous and next, related, help block |
| Post outside any product | `single.php` | Standard layout with the sidebars |
| Product page and section page | `category.php` | Sections with first articles, or a paginated list |
| Search, tag, date and author archives | `search.php`, `archive.php` | Same article list as a section, with product and section above each title |
| Posts page | `index.php` | |
| Not found | `404.php` | |

## What a page prints

Every template follows the same frame:

```
header.php
  header/custom_header   (only with a header image)
  header/branding        logo or site title and tagline
  header/navigation      Primary menu (desktop and mobile copies)
  header/search-trigger  the search button
  header/product-switcher  the Products menu (two or more products)
do_action( 'knowx_sub_header' )     breadcrumb bar
do_action( 'knowx_before_content' ) opens .site-wrapper
  ... the template's own content ...
do_action( 'knowx_after_content' )  closes .site-wrapper
footer.php
  footer/info            footer menu and copyright line
```

An article page adds, inside the content:

```
docs/single
  docs/sidebar   the product's section tree (a collapsible panel under 900px)
  docs/article   title, version badge, updated date, content
  docs/help      "Still need help?" using the support button link
```

The tree, the contents list and the neighbour links are built by `knowx_product_tree()`, `knowx_render_tree()`, `knowx_toc_items()` and `knowx_article_neighbours()`. See the [Functions reference](09-functions-reference.md).

## Override from a child theme

Copy a file into the child theme at the same path and edit it. WordPress loads the child's copy first.

| To change | Copy |
|---|---|
| The article layout | `template-parts/docs/article.php` |
| The section tree column | `template-parts/docs/sidebar.php` |
| The end-of-article help block | `template-parts/docs/help.php` |
| One article in a list | `template-parts/docs/list-item.php` |
| The header pieces | `template-parts/header/*.php` |
| The footer line | `template-parts/footer/info.php` |
| A whole page type | the template file, for example `category.php` |

Keep the data calls (`knowx_*` functions) and change only the markup. The helpers return plain arrays, so a part rarely needs more than a loop.

## Add content without copying a file

Prefer a hook to a copy: a copy stops receiving fixes from theme updates.

```php
// Add a notice above every article page.
add_action( 'knowx_before_content', function () {
	if ( is_singular( 'post' ) ) {
		echo '<p class="notice">Docs for version 3 only.</p>';
	}
} );
```

The actions `knowx_sub_header`, `knowx_before_content` and `knowx_after_content` fire on every template listed above. See the [Hooks reference](03-hooks.md) for the filters.

## Add a page template

1. Create `page-my-template.php` in the child theme with the header comment `Template Name: My template`.
2. Print the frame: `get_header();`, `do_action( 'knowx_sub_header' );`, `do_action( 'knowx_before_content' );`, your content, `do_action( 'knowx_after_content' );`, `get_footer();`.
3. Load the stylesheets the page needs with `knowx()->print_styles( 'knowx-content', 'knowx-docs' );` before the content.

Use `page-faq.php` as the smallest example.

## Page containers

`header.php` wraps the content in a `.container` unless `knowx_template_uses_container()` returns false. That is the case for `full-width.php` and the Docs Home template only, and the function has no filter. For a full-bleed page of your own, either use the **Full Width, No Title** template and build your sections inside it, or copy `header.php` and `footer.php` into the child theme and change the check.
