# Docs home settings

**Included in Free**

These settings are under **Docs Home** and **Docs Index** in the Customizer.

## Docs Home

The hero block at the top, the product cards, the featured and recent articles and the support block.

| Setting | What it does |
|---|---|
| Hero background | Colour or image behind the hero. A featured image on the page is used if you set one. |
| Hero heading and content | The title and text in the hero. If empty, the page title and excerpt are used. |
| Exclude category by ID | Category IDs, separated by commas, hidden from the cards and from both lists (and from Layout Two). Their sub-categories are hidden too. |
| Recent articles list | Recently updated (default) lists the articles changed last, with their date. Newest lists the latest published ones. |
| Show the recent articles list | Hide the recent list on the docs home. |
| Show the support block | Hide the closing support block. |
| Articles in the featured and recent lists | 3, 5, 8 or 10. Default 5. |
| Support section background, heading, content, button text and link | The closing block. The button shows only when both its text and link are set. |

Featured articles are your **sticky posts**. The recent list skips the excluded categories.

With **one product**, the home shows that product's sections as cards instead of a single product card, and the product switcher in the header is hidden. With two or more products you get the product cards.

## Articles

**Customize > Docs > Articles** has one switch for each part of an article page:

| Switch | What it hides |
|---|---|
| Show last updated date | The date on articles and in article lists |
| Show the contents list | "On this page" beside and above the article |
| Show previous and next links | The pager under the article |
| Show More in this section | The related list under the article |
| Show the version badge | The version label on articles and lists |

**Order articles in a section** sets the order inside a section after the page order: newest first (default), by title, or last updated first. Product and section pages also print the date of their newest article, unless the last updated date is turned off.

The date is the last modified date of the post, and it is also sent as `dateModified` in the page's structured data.

## Docs Index

A list of every category with its latest articles.

| Setting | What it does |
|---|---|
| Show page title | Print the page title and content above the list. |
| Posts per category | How many articles to list per category. Default 10. |
| Order posts | By date or by name. |
| Show View All link | A link to the category page. |
| Show category description | Print the category description. |

Up to 100 categories are listed; developers can change the limit with the `knowx_kb_max_categories` filter.
