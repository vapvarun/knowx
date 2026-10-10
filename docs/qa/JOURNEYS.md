# QA journeys

Run each at 390, 768, 1024 and 1440 px, in light and dark mode, with the sample content (`playground/seed.php`) and again with the large-site seed (1,000+ articles, 500+ categories). Every journey ends with: axe reports no violations, the console is empty, nothing scrolls sideways.

| # | Journey | Expected |
|---|---|---|
| J1 | Open the home page | Hero search, one card per product with its icon, accent and article count, featured and recent lists |
| J2 | Click a product card | Product page lists its sections with six articles each and "View all"; tree on the left (wide screens) |
| J3 | Open a section, go to page 2 | Articles with excerpts; pagination works; tree keeps the section open |
| J4 | Open an article | Title, version badge, last updated, steps, code block with a Copy button, note, previous and next, related list |
| J5 | Click a heading link | URL gets the fragment, "Link copied" is announced, the page jumps to the heading |
| J6 | Use "On this page" | Each item scrolls to its heading; on phones it is a panel above the article |
| J7 | Press Ctrl or Cmd + K, type two letters | Dialog opens with focus in the field; results in 1 s; arrows move; Escape closes and returns focus |
| J8 | Search in a product, untick "Only in" | Result set grows to other products |
| J9 | Search with no match | "No articles found" message, Enter opens the search page with the empty state |
| J10 | Open the FAQ page | Questions open and close with the keyboard; view source shows an FAQPage block |
| J11 | Open the changelog page | One column, one heading per release |
| J12 | Header Products menu | Lists every product, marks the current one, closes with Escape or a click elsewhere |
| J13 | Toggle dark mode, reload | Choice is kept, no flash of light on load |
| J14 | Customizer: pick each colour scheme | Preview updates; contrast stays readable; saving keeps it on the front end |
| J15 | Category screen: set icon and accent | Card and product pages use them; REST returns them |
| J16 | Editor: Order and Applies to version | Order changes the position in the tree; the badge shows on the article |
| J17 | Open a page with no JavaScript | Content, tree, contents and search page link all work |
| J18 | 404 and empty category | Friendly message with a search form |
