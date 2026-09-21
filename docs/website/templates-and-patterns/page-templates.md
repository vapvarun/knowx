# Page Templates

KnowX ships four selectable page templates. To use one, edit a page, open the **Page Attributes** panel (or the **Template** dropdown in the block editor sidebar), and choose the template.

## Knowledge Base Layout One

Template file: `page-knowledgebase-one.php`

A knowledge base landing page that displays a hero section, your post categories with their articles, and an optional support (call-to-action) section. This is the template KnowX assigns to the auto-created homepage on activation.

Configure it in the Customizer under **Knowledge Base > Knowledge Base Layout One**, where you can set:

- **Hero section** - background, heading, content, and typography for the title and content.
- **Category display** - exclude categories by ID, posts per category, post order, and toggles for the page title, a "View All" link, and the category description.
- **Support section** - background, heading, content, typography, and a button with custom text and link URL.

See [Knowledge Base Homepage](../features/knowledge-base-homepage.md) for the full walkthrough.

## Knowledge Base Layout Two

Template file: `page-knowledgebase-two.php`

A simpler knowledge base landing page that displays your categories and posts without the hero and support sections. Configure it in the Customizer under **Knowledge Base > Knowledge Base Layout Two**:

- Toggle the page title, "View All" link, and category description.
- Exclude categories by ID, set posts per category, and choose the post order.

## Full-Width-No-Title

Template file: `full-width.php`

A full-width page template that removes the page title and renders the content edge to edge. Use it for custom landing pages or pages built with blocks or a page builder.

## Page-Without-Sidebar

Template file: `full-width-container.php`

A full-width content template kept inside the site container, with the sidebar removed. Use it when you want the standard page framing (sub-header, container) but no sidebar column.

## Default page and post templates

Pages without a selected template use `page.php`, and single posts use `single.php`. Both include the sub-header, breadcrumbs, content, and the configured sidebar. Archives use `archive.php`, and the blog index uses `index.php`.
