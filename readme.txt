=== KnowX ===
Contributors: wbcomdesigns
Tags: blog, education, custom-logo, custom-menu, featured-images, threaded-comments, translation-ready, rtl-language-support, theme-options, wide-blocks, editor-style, block-styles, right-sidebar, full-width-template
Requires at least: 6.9
Tested up to: 7.1
Requires PHP: 8.1
Stable tag: 2.0.0
License: GNU General Public License v3.0 (or later)
License URI: https://www.gnu.org/licenses/gpl-3.0.html

A fast documentation and FAQ theme for one product or many: product cards, section tree, instant search, FAQ and structured data. No plugins required.

== Description ==

KnowX turns WordPress into a documentation site without a custom post type or a plugin. Products are top-level categories, sections are child categories and articles are posts.

* Product cards on the docs home page, with an icon, a short description and an accent colour for each product
* Article pages with a section tree, an "On this page" list, copy-link on headings, copy buttons on code blocks, previous and next links and a "Last updated" date
* Instant search (Ctrl or Cmd + K) that can search the current product or everything
* FAQ page template and pattern built from the core Details block, with FAQ structured data
* Changelog page template and an "applies to version" badge on articles
* schema.org data for the site, articles, breadcrumbs and FAQ
* Light and dark mode with a visitor toggle, a theme colour with quick picks and a page background colour
* Open Sans served from your own site, no external requests, no jQuery
* Works in the block editor with the same colours and fonts as the front end

== Installation ==

1. Go to Appearance > Themes > Add New, search for KnowX and activate it.
2. Add your products under Posts > Categories: create a top-level category for each product and child categories for its sections.
3. Publish articles in those categories. Use the Order field in the editor to sort articles inside a section.
4. Create a page with the template "Docs Home" and set it as your homepage under Settings > Reading.

To see a finished site first, open the sample content in WordPress Playground (blueprint.json in the theme repository, github.com/vapvarun/knowx) or run playground/seed.php from the repository with `wp eval-file` on an empty test site.

== Frequently Asked Questions ==

= Do I need a plugin? =

No. KnowX uses categories, posts, pages and core blocks only.

= How do I add a second product? =

Create another top-level category under Posts > Categories. Pick an icon and an accent colour on the same screen. It appears on the docs home page and in the header Products menu.

= How do I change the order of articles? =

Use the Order field in the editor sidebar. Articles with the same order are sorted by title.

= How do I add an FAQ? =

Create a page, choose the FAQ template and insert the "Frequently asked questions" pattern, or add core Details blocks. Pages with two or more Details blocks also print FAQ structured data.

= Can I turn the structured data off? =

Yes. Add `add_filter( 'knowx_schema_graph', '__return_empty_array' );` to a child theme or a small plugin. The theme prints no meta description when an SEO plugin is active.

= Does it work with the block editor and Site Editor? =

KnowX is a classic theme with block features. The editor shows the same colours and fonts, and the block patterns are in the "KnowX Docs" category.

= Does it support dark mode? =

Yes. Visitors can switch with the toggle in the header, or you can set the default under Appearance > Customize > Dark Mode.

= What happened to Kirki and the old color options? =

Version 2.0.0 no longer needs the Kirki plugin and has fewer color settings, because the other colors are derived from the theme color. Check Appearance > Customize > Colors after updating.

== Resources ==

KnowX bundles the following third-party resources:

Open Sans, Copyright 2020 The Open Sans Project Authors
License: SIL Open Font License 1.1 - https://openfontlicense.org
Source: https://fonts.google.com/specimen/Open+Sans

The theme icons are simple SVG shapes drawn for KnowX and released under the same license as the theme.

KnowX is based on WP Rig (https://github.com/wprig/wprig), released under the GNU General Public License v3.0 (or later).

== Copyright ==

KnowX is distributed under the terms of the GNU GPL.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3.0 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

== Upgrade Notice ==

= 2.0.0 =
Major release. The Customizer is new and earlier choices are not carried over. Google Fonts, login page branding, the page loader and button colour settings from 1.x are removed. Review Appearance > Customize after updating.

== Changelog ==

= 2.0.0 - October 2026 =

Major release. Earlier Customizer choices are not carried over, so review Appearance > Customize after updating.

* New      - Products: top-level categories become product cards with an icon, description and accent colour, plus a header product switcher; a site with one product lists its sections directly.
* New      - Article layout with a section tree, "On this page" list, copy-link on headings, code copy buttons, previous and next links, related articles and a last updated date.
* New      - Instant search overlay (Ctrl or Cmd + K) that can be limited to the current product.
* New      - Search results, archives, the 404 page and the comment form follow the docs style.
* New      - FAQ and Changelog page templates, 17 block patterns (callouts, prerequisites, troubleshooting, API endpoint, compatibility table, next steps and more) and an "applies to version" badge.
* New      - Article Order field in the block editor and in the REST API.
* New      - schema.org data for the site, articles, breadcrumbs and FAQ, and a meta description.
* New      - Light and dark mode with a visitor toggle, five colour palettes and your own colours for links, text, headings, backgrounds, header and footer, with a live preview in the Customizer.
* New      - Footer menu location, a corner style setting and a docs home that switches between cards and sections.
* New      - Switches for comments on articles (off by default; articles end with a "Still need help?" block) and for FAQ structured data, which can also be turned off on a single page.
* New      - Sample content and a WordPress Playground blueprint.
* New      - Articles filed on a product itself, not in a section, appear in an Overview card first on the product page and the docs home.
* New      - The Background control in the Customizer has a Remove image button.
* Improve  - The Kirki plugin and the plugin recommendations are gone; the Customizer is built into the theme and organised in ten short sections with plain labels.
* Improve  - Open Sans is served from your site, and the page loads no jQuery and no icon font.
* Improve  - Tablets and phones: the section tree sits beside the article from 900 px, text lines stay about 72 characters, and the phone header is one row with the article title in the first third of the screen.
* Improve  - The docs home has balanced lists, no half-empty column and no support block without a link; article structured data now names the publisher and an image.
* Improve  - Right-to-left sites mirror correctly, including text alignment, code copy buttons and list arrows.
* Improve  - Menus with child items open with the keyboard on wide screens and close with Escape; the copy button says when copying is not available.
* Improve  - Blog posts print one category list, one edit link and one comment link, images get correct size hints, and an SEO plugin's schema is no longer printed twice.
* Improve  - The docs lists are cached with automatic invalidation and are bounded, so large sites stay fast.
* Improve  - The phone menu is a proper dialog: focus moves into it, stays inside, and Escape closes it and returns focus to the menu button.
* Improve  - Buttons, inputs and tap targets follow the Corners setting, show a clear focus outline and a disabled state, and are at least 40 px on phones.
* Improve  - A long breadcrumb trail on phones scrolls sideways and fades at the start instead of cutting words.
* Improve  - The colour settings say they apply to light mode, since dark mode keeps its own palette.
* Fix      - Saved Customizer colors were overridden by a duplicate set of defaults in the content stylesheet.
* Fix      - Excluding a product now also hides its sections and articles on the Docs Index.
* Fix      - Buttons in articles and pages are no longer underlined.
* Fix      - Body Text line height now applies to paragraphs, and the Headings setting keeps the size of each heading level.
* Fix      - A font from 1.x that the theme no longer loads falls back to the default font, and the Typography control shows it.
* Fix      - Docs Index follows the Order in section value, leaves out products with no articles of their own and names the product above each section.
* Fix      - Docs Home hero colour and image from the Customizer now show, with readable text on a dark colour.
* Fix      - Phones: the menu drawer shows one menu with its Close button, the Products list and the search box stay on screen, tables scroll sideways and nested comments keep their width.
* Fix      - Keyboard focus in the phone header follows the order on screen.
* Fix      - A header image sits above the header bar, and Content Width stays between 1000 and 1920 pixels.
* Fix      - Text set in the Customizer follows the reader's browser font size.
* Fix      - A product with articles but no sections lists them with page numbers, and its header no longer says 0 sections.
* Fix      - A square logo no longer makes the header 150 px tall; logos are shown up to 48 px tall.
* Fix      - The Products menu scrolls when there are many products, and long words in articles wrap instead of being clipped.
* Fix      - Previous and Next page links look like the page numbers, and the site title lines up with the header on every screen size.
* Fix      - Right-to-left sites mirror the desktop menu and the remaining spacing correctly.
* Fix      - The comment reply script loads on articles only when article comments are on.
* Fix      - The copyright line keeps links you add, and theme translations load.
* Security - Customizer values printed into the stylesheet drop characters that could end a rule, and the docs home pattern escapes its data.
* Dev      - PHP 8.1 and WordPress 6.9 minimum, PHPStan, PHPUnit, an accessibility check on every page type and a documented content model.
* Dev      - New filters knowx_overview_articles and knowx_overview_title for the product Overview card.
* Dev      - Removed from 1.x: Google Fonts loading, login page branding, the page loader, button colour settings, a separate font for each heading level, the sub-header and header search switches and the blog grid options.

= 1.7.4 =
* Improvement: Updated global styling for better design consistency.
* Improvement: Refined footer structure for cleaner and more maintainable layout.
* Improvement: Added Knowledge Base page display on theme activation to help users get started quickly.
* Improvement: Removed unwanted white space on full-width pages with no title.
* Improvement: Optimized navigation template for faster performance.
* Performance: Improved caching to reduce repeated processing and speed up page loads.
* Cleanup: Removed unused templates and applied general code quality improvements.

= 1.7.3 =
* Fixed duplicate calls for content.min.css.
* Resolved theme typography component issue.
* Improved block button UI for better consistency.
* Managed Gutenberg editor font and font size for improved styling.

= 1.7.2 =
* Fix: Manage theme issue with WP 6.6
* Enhancement: Add scroll top
* Enhancement: Add blog post box view layout

= 1.7.1 =
* Enhancement: Managed color scheme options apply dynamically
* Enhancement: Added theme.json
* Fix: Theme check warnings fixes
* Fix: Managed sticky sidebar scrolling

= 1.7.0 =
* Fix: Updated customizer options images
* Fix: Managed plugin bulk selection installation and activation
* Fix: Format theme files
* Fix: Added support for Kirki 5.0.0 (the plugin is no longer used since 2.0.0)

= 1.6.0 =
* Fix : Added custom WP login form setting
* Fix : Added missing file, removed unwanted files
* Fix : Removed unwanted images

= 1.5.0 =
* Fix : Implement webfonts locally and removed unnecessary files
* Fix : Added sticky header enable/disable customizer setting

= 1.4.0 =
* Fix : Fixed header glitch on page load
* Improvement: Added setting to enable or disable sub header and search option
* Fix : Removed page mapping option from customizer

= 1.3.1 =
* Fix : Updated screenshot


= 1.3.0 =
* Fix: Removed the Kirki include notice from the Customizer
* Fix : Updated theme screenshot.png
* Fix : Updated gmpg.org link with https

= 1.2.0 =
* Fix : Escaping functions
* Fix:  Styling of the_content area of Full-Width-no-title template
* Fix: Updated tags

= 1.1.0 =
* Fix : Added source js and css files

= 1.0.0 =
* Initial Release
