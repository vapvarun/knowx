# Performance and accessibility

**Included in Free**

## Performance

- No jQuery and no icon font. All JavaScript is about 9 KB minified and loads deferred.
- One self-hosted variable font, preloaded.
- Colours come from CSS variables, so changing the theme colour does not add a stylesheet.
- Lists (products, section tree, counts) are cached and refresh when you publish or edit a post or category. They are bounded, so a site with thousands of articles stays fast.
- WordPress core adds lazy loading for images and speculative loading for links.

On a test site with 4,590 articles and 546 categories, pages took 21 to 35 ms on a warm cache.

## Accessibility

- Skip link, visible focus, keyboard-operable menus, tree, search overlay and FAQ.
- Colours meet WCAG AA contrast in light and dark mode, including every colour scheme.
- Links and buttons have large touch targets; motion is reduced for visitors who ask for it.
- Landmarks have unique names; icons are hidden from screen readers and every icon-only button has a label.
- Right-to-left languages mirror correctly.

Every page type is scanned with axe in light and dark at phone and desktop widths before a release.
