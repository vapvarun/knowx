# Knowledge Base Homepage

KnowX's core feature is a knowledge base landing page that groups your post categories and articles into a browsable layout. Two templates are available.

## Choosing a layout

Assign one of these templates to a page (edit the page, then set **Template** in Page Attributes):

- **Knowledge Base Layout One** - hero section, categories with posts, and a support call-to-action section.
- **Knowledge Base Layout Two** - categories with posts, without the hero and support sections.

On first activation, KnowX creates a **Knowledge Base** page with Layout One and sets it as your front page.

## How content is sourced

Both templates read your standard WordPress **post categories** and the posts inside them. To build your knowledge base:

1. Create categories under **Posts > Categories** (for example, Getting Started, Billing, Troubleshooting).
2. Publish articles as posts and assign each to a category.
3. The template lists each category with its posts.

## Layout One options

Configure in **Appearance > Customize > Knowledge Base > Knowledge Base Layout One**:

**Hero section**
- Background control, heading text, and content text.
- Separate typography controls for the hero title and hero content.

**Category display**
- **Exclude category by ID** - hide specific categories.
- **Posts per category** - how many articles to list under each category.
- **Order posts** - the sort order for posts within a category.
- **Show page title**, **Show View All link**, and **Show category description** toggles.

**Support section** (call to action)
- Background control, heading, and content.
- Typography for the heading and content.
- **Button Text** and **Button Link URL** for the call-to-action button.

## Layout Two options

Configure in **Appearance > Customize > Knowledge Base > Knowledge Base Layout Two**:

- **Show page title**, **Show View All link**, and **Show category description** toggles.
- **Exclude category by ID**.
- **Posts per category**.
- **Order posts**.

## Per-page category exclusion

When editing a page that uses a knowledge base template, KnowX adds a meta box that lets you exclude specific categories from that page's output (stored in the `knowx-exclude-cats` meta field). This is separate from the site-wide Customizer exclusion and applies only to the page you are editing.
