# KnowX libraries: what ships, why, and whether it earns its place

Rule (user, 2026-10-10): WordPress is always present, so the theme must not re-implement what WordPress core already provides. A library stays only if it gives site owners or readers something core and native browser features do not, at a size that is justified on a phone.

Verdicts: **Keep**, **Replace** (native or core feature does it), **Remove** (not needed). Work group in brackets.

## 1. WordPress core first (what we do not rebuild)

| Need | Core provides | KnowX decision |
|---|---|---|
| `<body>` hook | `wp_body_open()` (WP 5.2) | Use core; custom shim removed (W02, W03) |
| Translations | Automatic translation loading for themes hosted on wordpress.org | Custom textdomain loader removed (W02) |
| Version gate | `Requires at least` / `Requires PHP` in `style.css` block activation | `back-compat.php` and version checks removed (W02) |
| Script loading | `wp_enqueue_script( ..., array( 'strategy' => 'defer' ) )` (WP 6.3) | Replace the custom `async` filter (W10) |
| Image lazy loading, `srcset`, `sizes`, `fetchpriority` | Core adds them to content and thumbnails | No lazy-load library; remove custom `sizes` filters unless proven needed (W16) |
| Prefetch / prerender | Speculative loading in core (WP 6.8) | Do not ship a custom Speculation Rules component (W16) |
| Current-menu `aria-current` | Core nav walkers set it | Duplicate filter removed (W02) |
| Fonts | `theme.json` `fontFace` self-hosting | Replace the webfont downloader and Google loader (W12) |
| Accordions (FAQ) | Core Details block | No accordion JS (W14) |
| Search | REST `/wp/v2/search` | Instant-search overlay uses it, no search library (W14) |
| Background colour / image | Core `custom-background` | Do not duplicate in own settings (W08) |
| Embeds sizing | `responsive-embeds` support | FitVids removed (W10) |
| Logo, title, home page, menus, widgets | Core Customizer panels | Not rebuilt in our settings (W08) |

## 2. Front-end libraries shipped today

| Library | Where it is used | Size shipped | Why it was there | Verdict |
|---|---|---|---|---|
| ~~Isotope 3.0.6~~ (removed in W10) | Blog `grid-layout` and `masonry-layout` (`custom.js:154-167`), loaded on every page | 35 KB min | Masonry and filtered grids | **Done**: grid is flex, masonry is CSS columns (`content.css`) |
| ~~Superfish 1.7.10~~ (removed in W10) | Desktop menu (`custom.js:68`) | 4 KB min | Dropdown hover delay | **Done**: CSS `:hover` shows submenus; `navigation.ts` toggles them for keyboard and touch |
| ~~Sticky-kit 1.1.3~~ (removed in W10) | Sticky sidebar (`custom.js:194-206`) | 3 KB min | Sticky sidebar before CSS could | **Done**: `position: sticky` (sidebar and header); the full docs layout in W14 |
| ~~FitVids 1.1~~ (removed in W10) | Responsive video wrappers (`custom.js:171-179`) | 2 KB min | Fluid iframes | **Done**: `aspect-ratio` on video iframes (`_media.css`) |
| ~~jQuery.scrollUp 2.4.1~~ (removed in W10) | Back-to-top button | 2 KB min | Scroll-to-top | **Done**: 60-line `theme.js` back-to-top with a reduced-motion guard |
| ~~jQuery~~ (no longer a dependency, W10) | `custom.js`, all of the above | core | Dependency of every library above | **Done**: the front end loads no jQuery (checked) |
| ~~Font Awesome 4.7~~ (removed in W12) | About 11 icon glyphs, 1.1 MB | 1.1 MB | Icons | **Done**: CSS-mask SVG files and inline SVG; readme attribution to update in W17 |
| normalize.css 8.0.1 (MIT) | `_reset.css` | small | Cross-browser reset | **Keep** or fold into a modern `@layer reset` (W11) |
| Bootstrap 3 grid (MIT, copied) | Only `.container`, `.row`, `.col-md-6` | 12 KB min | Layout | **Remove**: those classes need about ten lines of CSS grid (W11) |
| TGM-Plugin-Activation 2.6.1 (GPL-2.0+) | Recommends plugins on every admin load | 3,759 lines | Plugin nag | **Remove**: no required or recommended plugin (W07) |
| ~~Open Sans + Montserrat from Google Fonts~~ (removed in W12) | Body and headings | external request | Typography | **Done**: Open Sans variable woff2 self-hosted (about 90 KB), declared in `theme.json`, one preload |

Result after W10: no jQuery and no third-party JavaScript on the front end; the theme ships 6.4 KB of minified JS in total (it was 284 KB with source maps and 60 KB without). After W12: icons are SVG and the only font is self-hosted Open Sans; no external request.

## 3. Kirki

Plugin, not bundled. Removed entirely (W07); the in-house Customizer framework replaces it.

## 4. Build tools (development only, never shipped)

| Tool | Why | Verdict |
|---|---|---|
| esbuild | Bundles and minifies JS/TS | Keep: fast, no config |
| lightningcss | Bundles, minifies, nests and prefixes CSS (declared explicitly in W02; it used to arrive only through `@wordpress/scripts`) | Keep |
| stylelint + `@wordpress/stylelint-config`, eslint + `@wordpress/eslint-plugin`, `typescript-eslint` | Lint CSS and JS to WordPress standards | Keep |
| simple-git-hooks + lint-staged | Pre-commit gate | Keep |
| browser-sync, create-cert | Live reload dev server | Keep, dev convenience |
| sharp, svgo | Image and SVG optimisation in the build | Keep |
| archiver, fs-extra, del, mkdirp, rimraf, fast-glob, commander, inquirer, ansi-colors, fancy-log, deepmerge | Build scripts | Keep |
| typescript | `npm run lint:ts` type-checks the two TypeScript files (`tsconfig.json`) | Keep |
| ~~jest, jest-expect-message~~ (removed in W18) | There are no JS unit tests; the browser journeys cover the scripts | **Done**: removed with `jest.config.cjs` and the unused `playwright.config.ts` |
| chokidar-cli, `eslint-plugin-import`, `globals` | Watch scripts and the ESLint config | Keep |
| PHP: phpcs + WPCS + PHPCompatibility, phpstan + WordPress stubs, phpunit + Brain Monkey, rector, php-cs-fixer | Coding standard, static analysis, unit tests | Keep |

Removed because nothing used them: `postcss-custom-media`, `axe-core`, `minimist`, `replace-in-file`, `wp-pot-cli` (the template is made with `wp i18n make-pot`), `eslint-config-standard`, `eslint-plugin-node`, `@eslint/js` and `glob`. Removed in W02: `@wordpress/block-editor`, `blocks`, `components`, `create-block`, `data`, `scripts`, `server-side-render`, `react`, `react-dom`, `@parcel/css`, `cssnano`, `esbuild-loader`, `esm`, `import-fresh`, `npm-run-all`, `tiny-lr`, `vinyl`, `wp-cli` (1,145 packages).

## 5. How a library earns its place

1. Core or a native browser feature cannot do it.
2. It is GPL-compatible and listed with its licence in `readme.txt`.
3. It loads only on pages that need it, deferred, with a version argument.
4. A reader on a phone benefits more than the bytes cost (checked against the budgets in the plan: JS under 20 KB gzip, CSS under 40 KB gzip per page).
5. A site owner never has to install or configure anything extra.
