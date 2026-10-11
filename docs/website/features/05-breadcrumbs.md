# Breadcrumbs

**Included in Free**

The trail shows where a reader is, for example Home > Acme App > Getting started > Create your account. For an article it starts at the product, then the section, then the article.

## Settings

Under **Header and Breadcrumbs** in the Customizer, **Show breadcrumbs** must be on. It is on by default. The trail is hidden on the front page.

## Developers

The same trail feeds the `BreadcrumbList` structured data. See [Structured data](04-structured-data.md). Breadcrumbs are built in `inc/breadcrumbs.php` (`knowx_get_breadcrumb_items()`).
