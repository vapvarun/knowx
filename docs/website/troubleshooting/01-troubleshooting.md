# Troubleshooting

**Included in Free**

## A product page or article shows the standard layout

The docs layout is used for posts that belong to a product, meaning a category with a parent chain that ends in a top-level category. A post only in "Uncategorized" is treated as an ordinary post.

## The section tree is missing on an article

The tree needs the post to be in a category. Check that the post is published and has a category.

## Changes to categories or articles do not show on the docs home

The theme refreshes its cached lists whenever you save a post or category. If a page cache plugin is active, clear its cache.

## The search button does nothing

The overlay needs JavaScript. Without it the button opens the normal search page. Check the browser console for errors and make sure no optimisation plugin has removed the theme's script (`docs.min.js`).

## Search finds nothing inside a product

The overlay searches the product you are in. Untick **Only in (product)** to search everything. Only published posts are searched.

## "On this page" is empty

The list shows `h2` and `h3` headings in the article. Add headings with the Heading block.

## The theme colour looks wrong in one product

Products can have their own accent colour. Edit the category under **Posts > Categories** and untick **Use an accent colour**.

## Colours I set do not apply

A product's accent colour wins over the theme colour on that product's pages, and a colour you set in the Customizer wins over the colour scheme. Reset the field to return to the scheme.

## Pretty permalinks give "Not found"

Go to **Settings > Permalinks** and choose **Save Changes** to rebuild the rewrite rules.
