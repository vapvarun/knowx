# Instant search

The magnifier button in the header opens a search box on top of the page. Visitors can also press **Ctrl + K** (Windows and Linux) or **Cmd + K** (Mac) anywhere on the site.

## How it works

- Results appear while typing, after two characters, from the WordPress REST API (`/wp/v2/posts?search=`).
- On a product page or an article, the box is limited to that product. Visitors can untick **Only in (product)** to search everything.
- The arrow keys move through the results, Enter on the search field opens the normal search page and Escape closes the box.
- Without JavaScript the button is a normal link to the search page.

## Notes for developers

The overlay is a native `dialog` element, so focus handling comes from the browser. The script is `assets/js/src/docs.js` and the markup is `template-parts/docs/search-dialog.php`. Search results use the post titles only; to change what is searched, filter the REST query with `rest_post_query`.
