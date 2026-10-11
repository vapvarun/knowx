# Structured data

KnowX prints schema.org data as JSON-LD in the page head:

| Page | Types |
|---|---|
| Front page | `WebSite` with a search action |
| Article | `TechArticle` and `BreadcrumbList` |
| Page or article with two or more Details blocks | `FAQPage` |

The theme also prints a meta description from the excerpt, the category description or the site tagline. It prints neither when an SEO plugin such as Yoast SEO, Rank Math, All in One SEO or SEOPress is active, except the structured data below.

## Turn it off or change it

```php
// No structured data.
add_filter( 'knowx_schema_graph', '__return_empty_array' );

// Your own meta description.
add_filter( 'knowx_meta_description', fn() => 'Your text' );
```

`knowx_schema_graph` receives the list of nodes, so you can also remove one type or add your own. Check a page with Google's Rich Results Test.
