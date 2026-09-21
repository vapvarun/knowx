# Troubleshooting

Solutions to the most common KnowX setup issues. Each points back to the code behavior that causes it.

## The KnowX Customizer options are not showing

**Cause:** the KnowX Customizer panels are built with the Kirki Toolkit. They register only when the `Kirki` class exists.

**Fix:** install and activate the **Kirki Toolkit** plugin (**Appearance > Install Plugins**, or Plugins > Add New). Reload the Customizer. See [Recommended Plugins](../features/recommended-plugins.md).

## The theme will not activate or shows a compatibility notice

**Cause:** your site is below KnowX's minimum WordPress or PHP version. Below the internal floor, KnowX loads a back-compat fallback and stops initializing.

**Fix:** update to at least WordPress 4.8 and PHP 7.4 (the supported baseline). See [Requirements](../getting-started/requirements.md).

## A duplicate "Knowledge Base" page appeared, or my homepage changed on activation

**Cause:** on first activation KnowX creates a Knowledge Base page and sets it as the front page. This is expected and runs only once (tracked by the `knowx_theme_activated` option).

**Fix:** to use a different homepage, go to **Settings > Reading** and choose your own static front page. You can safely delete the auto-created page if you do not want it.

## Breadcrumbs are not appearing

**Causes and fixes:**

- The sub-header holds the breadcrumbs. Enable **Site Sub Header?** and **Site Breadcrumbs?** in **Customize > General > Site Sub Header**.
- Breadcrumbs are intentionally hidden on the front page. Check an inner page.
- If Yoast SEO is active but its breadcrumb feature is off, enable Yoast breadcrumbs, or KnowX will fall back to its own trail.

## The navigation menu is empty

**Cause:** no menu is assigned to the Primary location, so the navigation area is skipped.

**Fix:** go to **Appearance > Menus**, create a menu, and assign it to the **Primary** location. See [Navigation and Widgets](../features/navigation-and-widgets.md).

## Footer widgets are not showing

**Cause:** footer columns render only when they contain widgets.

**Fix:** add widgets to **Footer 1** to **Footer 4** under **Appearance > Widgets**. If Elementor controls the footer location, the widget footer is replaced by your Elementor footer.

## The knowledge base page shows no categories or posts

**Cause:** the knowledge base templates read your post categories and their posts.

**Fixes:**

- Create categories under **Posts > Categories** and assign published posts to them.
- Check that you have not excluded the categories in **Customize > Knowledge Base**, or in the per-page category exclusion meta box on the page you are editing.
- Increase **Posts per category** in the Customizer if it is set too low.

## Custom login styling is not applied

**Cause:** custom login styling applies only when enabled.

**Fix:** in **Customize > WP Login > Logo**, turn on **Enable Custom Login** (and **Enable Custom Login Logo** for the logo), then set your values. See [Custom Login](../features/custom-login.md).

## Google Fonts look wrong after switching to local hosting

**Cause:** the local font cache can go stale when you change fonts.

**Fix:** in **Customize > Site Footer > Site Performance**, use **Flush Local Fonts Cache**, then reload.

## Sticky header or sticky sidebar is not sticking

**Fix:** confirm the toggles are on. **Enable Sticky Header?** is in **Customize > General > Site Header**; **Sticky Sidebar?** is in **Customize > General > Site Sidebar**. When enabled, KnowX adds the `sticky-header` and `sticky-sidebar-enable` body classes; if a plugin or custom CSS overrides positioning, that can prevent sticking.
