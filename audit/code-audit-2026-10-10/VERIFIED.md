# Independent verification of the agent audit (2026-10-10)

Each claim re-checked against the source or the built zip by the main session.

| Claim | Result |
|---|---|
| Source maps inline in release JS | CONFIRMED (`custom.min.js` in `dist/knowx-1.7.5.zip` contains `sourceMappingURL=data:`; min.js total 284 KB) |
| Scripts load `async` | CONFIRMED (`Scripts/Component.php` 'loading' => 'async'; knowx-custom deps include superfish, isotope, fitvids, sticky-kit) |
| `wp_body_open()` never called | CONFIRMED (shim only fires `knowx_wp_body_open`) |
| PHP 8.0 syntax under 7.4 header and 7.0 gate | CONFIRMED (union types in `Options`, `Fonts`; `mixed` in `Blocks`) |
| Webfont loader includes remote CSS as PHP | CONFIRMED (`include $local_path`, line 238); file is `require`d on every request (`functions.php:78`), no callers outside the file, its AJAX hook is live |
| Cron `'monthly'` event | CONFIRMED (`CLEANUP_FREQUENCY = 'monthly'`, not a core schedule) |
| Dynamic_Style only with Kirki | CONFIRMED (`class_exists( 'Kirki' )`, line 40) |
| `setPostViews()` writes on every view | CONFIRMED (`single.php:42`) |
| TGMPA required on every request | CONFIRMED (`functions.php:26`) |
| `$(window).load()` in custom.js | CONFIRMED (line 264) |
| Duplicate `#primary-menu` | CONFIRMED (`navigation.php` lines 56 and 62, same `menu_id`) |
| Two exclusion settings | CONFIRMED (`knowx_exclude` in Layout Two, `knowx_exclude_posts` in Layout One) |
| 4 settings never read | CONFIRMED (`site_load_google_font_locally`, `site_preload_local_font`, `site_sub_header_bg`, `site_footer_bg`) |
| Hero colour mismatch | CONFIRMED (`global.css:335` `#21b4db`; Kirki default `#03A9F4`) |
| Number of settings | CORRECTED: 90 Kirki fields, 6 are `Custom` dividers, so 84 real settings (earlier docs said 85) |
| `site_loader` default | CORRECTED: default `'2'`, rendered only when `== '1'`, so the loader is OFF by default |
| FontAwesome "11 glyphs" | NOT CONFIRMED: grep of PHP/JS found 4 `fa-` names; recount from templates and CSS in W12 |
| bbPress CSS loaded sitewide | PARTIAL: `function_exists( 'is_bbpress' )` guards the block at `Styles/Component.php:121`; confirm the inner condition in W11 |
| `rtl.min.css` ordering/size | PARTIAL: enqueue confirmed (line 127); size and ordering effects to be measured in W11 |
