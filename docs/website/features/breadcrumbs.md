# Breadcrumbs

KnowX includes a built-in breadcrumb trail so visitors can see where they are in your knowledge base and navigate back up.

## Where breadcrumbs appear

Breadcrumbs render inside the sub-header on inner pages (they are hidden on the front page). Two Customizer toggles control them, both under **Customize > General > Site Sub Header**:

- **Site Sub Header?** - the sub-header must be enabled for breadcrumbs to show. On by default.
- **Site Breadcrumbs?** - show or hide the breadcrumb trail. On by default.

## Yoast SEO integration

If **Yoast SEO** is active and its breadcrumb feature is enabled (Yoast's `breadcrumbs-enable` setting), KnowX defers to Yoast and renders the Yoast breadcrumb trail instead of its own. This keeps a single, consistent breadcrumb source when Yoast is managing SEO.

When Yoast breadcrumbs are not available, KnowX renders its own breadcrumb trail.

## bbPress

On bbPress pages, KnowX suppresses the default bbPress breadcrumb (via the `bbp_no_breadcrumb` filter) so the theme's breadcrumb trail is used instead of a duplicate.

## Customizing the trail (developers)

KnowX's breadcrumb system exposes filters for adjusting the trail, including `knowx_breadcrumb_trail_args`, `knowx_breadcrumb_trail_items`, `knowx_breadcrumb_trail_labels`, and `knowx_breadcrumb_trail`. See [Hooks Reference](../developer-guide/hooks.md).
