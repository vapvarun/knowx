# Block Patterns and Styles

KnowX adds a small set of block editor extras so you can build content that matches the theme.

## Block pattern category

KnowX registers a block pattern category named **KnowX**. Patterns provided by the theme appear under this category in the block editor's pattern inserter.

## Block patterns

KnowX registers one block pattern:

- **Footer Small** (`knowx/footer-small`) - a compact footer layout you can insert into a page or a footer area.

To use it, open the block inserter, switch to the **Patterns** tab, find the **KnowX** category, and insert the pattern.

## Block styles

KnowX registers one custom block style:

- **Borders** on the **Image** block (`core/image`) - adds a bordered image style. Select an image block, open the **Styles** panel, and choose **Borders**.

## Block editor presets

KnowX's `theme.json` defines the palette and typography presets available in the editor:

- **Colors**: Base (`#ffffff`), Contrast (`#000000`), Primary (`#03a9f4`), Secondary (`#41848f`), Tertiary (`#F6F6F6`).
- **Font sizes**: Small (13px), Medium (20px), Large (36px), Extra Large (42px).
- **Spacing units**: `%`, `px`, `em`, `rem`, `vh`, `vw`, with block gap, margin, and padding controls enabled.
- **Layout**: content width 840px, wide width 1200px.

KnowX also declares editor support for wide alignment (`align-wide`), block styles (`wp-block-styles`), and editor styles, so block content in the editor previews closely to the front end.
