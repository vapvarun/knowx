# Frequently Asked Questions

## Is KnowX free?

Yes. KnowX is a free WordPress theme released under the GNU General Public License v3.0 (or later).

## Is KnowX a block (Full Site Editing) theme?

No. KnowX is a classic PHP template theme built on WP Rig. You customize it through the Customizer and standard menus and widgets, not the Site Editor. It ships a small `theme.json` only to define the block editor's color palette, font sizes, and layout widths.

## Why are the KnowX Customizer options missing?

The KnowX Customizer panels (General, Typography, Knowledge Base, WP Login, Site Footer) are provided through the Kirki Toolkit plugin. Install and activate **Kirki Toolkit** and they will appear. See [Recommended Plugins](../features/recommended-plugins.md).

## Do I have to install the recommended plugins?

No. All three (Kirki Toolkit, Collapsing Categories, WordPress Popular Posts) are optional. The theme works without them, but without Kirki you lose the KnowX Customizer options.

## Where do knowledge base articles come from?

From your standard WordPress posts and categories. Create categories under **Posts > Categories** and assign your articles to them. The knowledge base templates group posts by category.

## KnowX created a "Knowledge Base" page and made it my homepage. Why?

On first activation, KnowX creates a Knowledge Base page (using the Knowledge Base Layout One template) and sets it as your static front page so you have a working landing page immediately. This runs only once. Change it any time under **Settings > Reading**.

## How do I switch between the two knowledge base layouts?

Edit the page, open Page Attributes (or the Template dropdown in the block editor), and choose **Knowledge Base Layout One** or **Knowledge Base Layout Two**. See [Knowledge Base Homepage](../features/knowledge-base-homepage.md).

## How many navigation menus does KnowX have?

One location: **Primary**, shown in the header. Assign a menu under **Appearance > Menus**.

## How many footer widget areas are there?

Four: Footer 1 through Footer 4. Each renders only if it has widgets. There are also Right Sidebar and Left Sidebar areas, plus bbPress sidebars when bbPress is active.

## Does KnowX work with page builders?

KnowX detects **Elementor** and registers Elementor theme locations for the header and footer, so Elementor can control those areas. The Full-Width templates are also good bases for page-builder content.

## Does KnowX support breadcrumbs?

Yes, built in. If Yoast SEO's breadcrumbs are enabled, KnowX uses Yoast's trail; otherwise it renders its own. Toggle breadcrumbs in **Customize > General > Site Sub Header**. See [Breadcrumbs](../features/breadcrumbs.md).

## Can I customize the WordPress login screen?

Yes. Under **Customize > WP Login > Logo** you can replace the login logo, its link and title, and the login page title. See [Custom Login](../features/custom-login.md).

## Can I host Google Fonts locally?

Yes. In **Customize > Site Footer > Site Performance**, enable "Load Google Fonts Locally", optionally preload them, and flush the local font cache when needed.
