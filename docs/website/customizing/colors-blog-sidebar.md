# Colors, Blog, and Sidebar

These options live in the **General** panel of the Customizer under the Site Skin, Site Blog, and Site Sidebar sections (**Appearance > Customize**). The Kirki Toolkit plugin must be active.

## Site Skin (colors)

Found in **General > Site Skin**. Control the site's core colors:

- **Body Background Color**
- **Theme Color** - the primary accent color.
- **Link Color**
- **Link Hover**
- **Button Background Color**
- **Button Background Hover Color**
- **Button Text Color**
- **Button Text Hover Color**
- **Button Border Color**
- **Button Border Hover Color**

These colors are applied dynamically to the front end, so changes preview live.

## Site Blog

Found in **General > Site Blog**:

- **Blog Layout** - the layout used for the blog and archive pages. The default is `default-layout`.
- **Post Per Row** - how many posts appear per row in grid layouts. The default is `3`.
- **Post Box View Layout** - the visual style of each post box.

## Site Sidebar

Found in **General > Site Sidebar**:

- **Sidebar Layout** - place the sidebar on the left or right, or use no sidebar. The default is **right**.
- **bbPress Sidebar Layout** - the sidebar position on bbPress forum pages. The default is **right**. This appears when bbPress is active.
- **Sticky Sidebar?** - keep the sidebar in view as visitors scroll. Enabled by default. When on, KnowX adds a `sticky-sidebar-enable` class to the `body`.

KnowX registers matching widget areas for these layouts: **Right Sidebar**, **Left Sidebar**, and, when bbPress is active, **bbPress Left Sidebar** and **bbPress Right Sidebar**. Populate them under **Appearance > Widgets**.

## Block editor palette

For content built with the block editor, KnowX defines a color palette and font sizes in its `theme.json`:

- Colors: **Base** (`#ffffff`), **Contrast** (`#000000`), **Primary** (`#03a9f4`), **Secondary** (`#41848f`), **Tertiary** (`#F6F6F6`).
- Font sizes: **Small** (13px), **Medium** (20px), **Large** (36px), **Extra Large** (42px).
- Layout widths: content 840px, wide 1200px.

These appear as presets in the block editor's color and typography controls. See [Block Patterns and Styles](../templates-and-patterns/block-patterns-and-styles.md).
