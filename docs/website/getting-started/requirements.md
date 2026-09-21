# Requirements

Confirm your WordPress environment meets these minimums before installing KnowX. The values below come from the theme's `style.css` header and `readme.txt`.

## Minimum requirements

| Requirement | Version |
|-------------|---------|
| WordPress   | 4.8 or later |
| PHP         | 7.4.0 or later |
| Tested up to | WordPress 6.8.2 |

If your site runs an older WordPress or PHP version, KnowX loads a compatibility fallback (`inc/back-compat.php`) that shows an admin notice and prevents the theme from fully initializing, so update your environment first.

## Recommended plugins

KnowX registers three optional plugin recommendations through TGM Plugin Activation. None are required; the theme works without them, and installing them is prompted from **Appearance** after activation.

- **Kirki Toolkit** - powers the theme's Customizer controls. Install this to see the full set of KnowX Customizer options. Without Kirki, the KnowX-specific Customizer panels do not appear.
- **Collapsing Categories** - useful for building expandable category lists on knowledge base pages.
- **WordPress Popular Posts** - lets you surface your most-viewed articles.

See [Recommended Plugins](../features/recommended-plugins.md) for details.

## Optional integrations detected automatically

KnowX adds extra behavior only when these plugins are active:

- **bbPress** - registers bbPress-specific left and right sidebars and a forum breadcrumb rule.
- **Elementor** - registers Elementor core theme locations so Elementor can control the header and footer.
- **Jetpack** - loads a Jetpack integration component.

These are detected in code; you do not need to configure anything to enable the detection.
