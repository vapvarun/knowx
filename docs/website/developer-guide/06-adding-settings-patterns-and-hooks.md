# Adding a setting, a pattern or a hook

**Included in Free**

## A Customizer setting

1. Add the field in the matching file under `inc/Customizer_Settings/Fields/` with `\KnowX\KnowX\Customizer_Framework\Field::add( $type, $args )`. The arguments are `settings` (the id), `label`, `description`, `section`, `default` and, for a field that changes the page, `output` or `transport`. The type picks the sanitiser: `switch` becomes 0 or 1, `number` an integer, `color` a hex colour.
2. If the field needs a new section, register it in `inc/Customizer_Settings/Component.php` with `Section::add()`. Keep a section short; the labels and the description are what an owner reads.
3. Read the value with `knowx_mod( 'your_id' )`. It returns the saved value or the field's default. Test a switch with `knowx_is_truthy()`.
4. A colour belongs in `Tokens\Component` (`VARIABLES` for a plain mapping, `build_css()` for derived values) so the front end, the editor and the live preview all get it. Add the setting id to the list in `action_customize_register()` so the preview updates without a reload.
5. Add the setting to the cases in `tests/docker/settings_qa.py`, run it, and regenerate `docs/customizer-inventory.md`.

## A block pattern

1. Create `patterns/your-pattern.php`. The header is `Title`, `Slug: knowx/your-pattern`, `Description`, `Categories: knowx-docs` and `Keywords`.
2. Write the block markup. Every visible string goes through `esc_html_e()` or `__()` with the `knowx` text domain. Block attributes that contain text (a placeholder, a button label) are built with `wp_json_encode()` and `__()` in a PHP block above the markup.
3. Use the colour tokens and the `is-style-knowx-*` group styles; do not write colours in the markup. Keep headings below the page title: start at level 2.
4. WordPress caches the theme's pattern list. After you add a file run `wp_get_theme()->delete_pattern_cache()` (or `wp eval 'wp_get_theme()->delete_pattern_cache();'`).
5. List the pattern in [Block patterns](../templates-and-patterns/02-block-patterns.md) and regenerate `audit/manifest.json`.

## A hook

1. Fire it with `apply_filters( 'knowx_name', $value, ... )` or `do_action( 'knowx_name', ... )`. Put a docblock right above the call: one sentence and a `@param` line for each argument.
2. Add a row to the [hooks reference](03-hooks.md) with the default value.
3. Regenerate `audit/manifest.json` with `python3 tools/build-manifest.py`.

## A stylesheet rule

Use the tokens in `assets/css/src/_custom-properties.css` (colours, `--radius-*`, `--shadow-*`, `--z-*`). Write logical properties (`margin-inline-start`, `inset-inline-end`) so right-to-left needs no extra rules, and add a `@media` rule for 390 px in the same change. Run `npm run build`; the `.min.css` files are committed.
