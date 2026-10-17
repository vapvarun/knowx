# Docs home settings

**Included in Free**

These settings are under **Customize > Docs** in the Customizer: **Docs Home**, **Articles** and **Docs Index**.

## Docs Home

The hero block at the top, the product cards, the featured and recent articles and the support block.

| Setting | What it does |
|---|---|
| Hero Background | Colour or image behind the hero; **Remove image** clears it. A featured image on the page is used if you set one. |
| Hero Heading and Hero Text | The title and text in the hero. If empty, the page title and excerpt are used. |
| Products on the docs home | Product cards (default), or each product with its sections. |
| Hide Categories | Category IDs, separated by commas, kept off the docs home cards and lists and off the Docs Index. Find the ID in the address bar when you edit a category. Sub-categories are hidden too. |
| Show the recent articles list | Hide the recent list on the docs home. |
| Show the support block | Hide the closing support block. |
| Support Heading, Support Text, Support Button Text and Support Button Link | The closing block. It is shown only when you set a button text and link, or your own support text, so a reader is never left at a dead end. The button shows only when both its text and link are set. |

Featured articles are your **sticky posts**. The recent list shows the articles changed last, with their date, five at a time, and skips the excluded categories. With three or more of each, the two lists sit side by side and are the same length; with fewer they stack. On wide screens the header search button steps aside on the docs home because the hero has the search box (Ctrl or Cmd + K still works).

With **one product**, the home shows that product's sections as cards instead of a single product card, and the product switcher in the header is hidden. With two or more products you get the product cards.

## Articles

**Customize > Docs > Articles** has four switches. The previous and next links, the related list and the version label appear when there is something to show, so they have no switch. The related list ("More in this section") only shows below 900 px, where the section tree is collapsed.

| Switch | What it hides |
|---|---|
| Show last updated date | The date on articles and in article lists |
| Show the contents list | "On this page" beside and above the article |
| Show comments on articles | Off by default. Articles end with a "Still need help?" block that uses your support button link (Docs Home settings); turn this on for community-answered docs. |
| FAQ structured data | Pages with two or more Details blocks are marked up as a FAQ for search engines. You can also switch it off for one page in the **Structured data** box of the page editor. |

**Order articles in a section** sets the order inside a section after the page order: newest first (default), by title, or last updated first. Product and section pages also print the date of their newest article, unless the last updated date is turned off.

The date is the last modified date of the post, and it is also sent as `dateModified` in the page's structured data.

## Docs Index

A list of every category with its latest articles.

| Setting | What it does |
|---|---|
| Posts per category | How many articles to list per category. Default 10. |
| Order posts | By date or by name. |

Up to 100 categories are listed; developers can change the limit with the `knowx_kb_max_categories` filter.
