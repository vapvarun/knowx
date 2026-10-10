# KnowX 1.7.5 - CSS / JS / enqueue audit (read-only)

Theme root: `/Users/varundubey/Local Sites/knowx/app/public/wp-content/themes/knowx` (abbrev `T/`).
Method: read all 28 CSS + 14 JS source files, build-css.js, build-js.js, .stylelintrc, eslint.config.js, all enqueue PHP. Metrics scripts and lint output live in `scratchpad/audit/` (metrics.txt, stylelint.txt, eslint.json, drift.py). Nothing in the theme was modified or rebuilt. Stylelint/ESLint were run read-only (stdout only).
Environment note: local WP is 7.1.3 (jQuery 3.7.1 + jquery-migrate 3.4.1 still a dependency of `jquery`, `T/../wp-includes/script-loader.php:907`).

Paths that the brief names but DO NOT EXIST: `inc/Custom_Js/Component.php`, `inc/Lazyload/Component.php` (no such dirs). Related dead references: `custom.js:181-189` listens for a `knowx_lazy_load` event and `data-lazy-type` that nothing in the theme emits.

---------------------------------------------------------------------
## 1. ENQUEUE MAP
---------------------------------------------------------------------

Version arg rule (`inc/Template_Tags.php:155`, duplicated in `inc/Fonts/Component.php:85`, `inc/Options/Component.php:23` doc only): `WP_DEBUG ? filemtime : theme version (1.7.5)`. Two copies of the same method = duplicate code.

### 1a. Front-end CSS (hook `wp_enqueue_scripts` 10, `inc/Styles/Component.php:68`, files list `:259-302`)

| handle | file | when loaded | deps | ver | media | preload |
|---|---|---|---|---|---|---|
| knowx-global | global.min.css 43,757 B (9,096 gz) | EVERY request, `<head>`, `global=>true` (Styles:262) | none | get_asset_version | all | no |
| (inline) | `body{--color-*:...}` via `wp_add_inline_style('knowx-global')` | prio 20, only if `class_exists('Kirki')` (Dynamic_Style:32,50,196) | knowx-global | n/a | | |
| knowx-site-loader | loaders.min.css 1,699 B | EVERY request (`global=>true`, Styles:289) even though loader markup prints only when `site_loader=='1'` (inc/extra.php:78-80; default is '2' = off) | none | yes | all | no |
| knowx-load-fontawesome | fontawesome.min.css 28,815 B (6,992 gz) | EVERY request (`global=>true`, Styles:293) | none | yes | all | no |
| knowx-fonts | Google Fonts CSS (external, `fonts.googleapis.com/css?family=Open Sans...|Montserrat...`) | EVERY request, `inc/Fonts/Component.php:182` | none | `null` (intentional, phpcs-ignored) | all | preconnect only for fonts.gstatic.com (`Fonts:208-214`) |
| knowx-content | content.min.css 47,925 B (7,537 gz) | registered, NOT enqueued. `preload_callback=__return_false` (Styles:271) so never preloaded. Printed mid-body by `knowx()->print_styles('knowx-content')` in 404, 500, offline, archive, index, single, page, full-width, full-width-container, bbpress, page-knowledgebase-one/two (13 root templates) | none | yes | all | no |
| knowx-sidebar | sidebar.min.css 435 B | registered; printed mid-body by sidebar.php:16 and index/archive/single/page/full-width*/bbpress | none | yes | all | no |
| knowx-widgets | widgets.min.css 3,871 B | registered; printed with sidebar CSS and from `template-parts/content/error-404.php:22` | none | yes | all | no |
| knowx-comments | comments.min.css 2,474 B | registered; printed by comments.php:24; ALSO `<link rel=preload as=style onload="this.rel='stylesheet'">` in wp_head when singular && comments open/has comments (Styles:263-267, 143-168). No `<noscript>` fallback; inline `onload` handler blocks strict CSP | none | yes | all | yes |
| knowx-front-page | front-page.min.css 682 B | registered, preload callback `front-page.php === basename($template)`. Only `optional/front-page.php:16` prints it, and `optional/` is not an active template dir (no `front-page.php` in theme root). NEVER LOADED in the shipped theme | none | yes | all | callback never true |
| knowx-bbpress | bbpress.min.css 3,011 B | `function_exists('is_bbpress')` (Styles:121) - true on EVERY page whenever bbPress is active (should be `is_bbpress()`). Enqueued in `<head>` (bypasses the print_styles pattern) | none | MISSING (WP appends core version) | all | no |
| knowx-rtl | rtl.min.css 18,147 B (4,184 gz) | `is_rtl()` (Styles:126). Enqueued in head, so it loads BEFORE the body-printed content.min.css/widgets.min.css and loses same-specificity ties against them (e.g. `.post-thumbnail+.post-categories` content.css:94 vs rtl.css:115). RTL is partly broken by order | none | MISSING | all | no |
| knowx-amp | knowx-amp.min.css 5,370 B | `knowx()->is_amp()` (Styles:131) | none | MISSING | all | no |
| (editor) | editor/editor-styles.min.css 41,513 B (7,511 gz) | `add_editor_style` (Styles:186) + Google Fonts URL via `add_editor_style` (Fonts:196) | n/a | n/a | | |
| (inline) | `<style>.site-title a,.site-description{color:#xxxxxx}</style>` | Custom_Header wp_head callback (`inc/Custom_Header/Component.php:73,77`) when header text colour != default | | | | |
| (inline) | login page logo/background `<style>` | `login_head`, `inc/login.php:91-...` only if `enable_custom_login` | | | | |

Other CSS-related facts:
- `wp_style_add_data($handle,'precache',true)` (Styles:117) is set for every theme sheet; nothing in the theme consumes `precache` (PWA component only registers service-worker support).
- No `wp-block-library`/`wp-block-library-theme`/`global-styles` dequeue anywhere; core block CSS loads in addition to the 16 KB of `_blocks.css`.
- Admin: `knowx-theme-settings` (admin/theme-settings.min.css 3,837 B) enqueued on EVERY wp-admin screen (`inc/Options/Component.php:80`, hook `admin_enqueue_scripts` at :40, no `$hook_suffix` guard), ver=`filemtime()` with no `file_exists`.
- Customizer: `knowx-customizer` style (knowx-customizer.min.css 880 B) on `customize_controls_enqueue_scripts` (Customizer/Component.php:48), deps `''` (string, should be array), always (Kirki or not); plus inline `.customize-control.knowx-force-hidden-by-mode{display:none !important;}` only if Kirki (:68-71).
- `inc/Options/settings-page.php:9` `wp_enqueue_style('wp-components')` - file only reachable via `add_admin_menu()`, which is commented out (Options:41).

### 1b. Front-end JS (hook `wp_enqueue_scripts` 10, `inc/Scripts/Component.php:66`, list `:178-240`)

All theme scripts: `footer=>true`, `loading=>'async'` (legacy `wp_script_add_data($h,'async',true)` + theme filter `Base_Support/Component.php:150` that injects the attribute; WP core ignores the `async` data key), `precache=>true`.

| handle | file (shipped / without inline map / gz full) | when | deps | notes |
|---|---|---|---|---|
| knowx-superfish | superfish.min.js 26,324 / 4,285 / 8,828 | every page, `global=>true` | NONE (needs jQuery) | |
| knowx-isotope-pkgd | isotope.pkgd.min.js 136,465 / 35,450 / 35,981 | every page | NONE (UMD, wants jQuery) | only used for `.grid-layout` / `.masonry-layout` blog layouts |
| knowx-fitvids | fitvids.min.js 9,390 / 2,083 / 4,359 | every page | NONE | |
| knowx-sticky-kit | sticky-kit.min.js 13,878 / 3,203 / 5,571 | every page | NONE | only for `.sticky-sidebar-enable .sticky-sidebar` |
| knowx-scroll-up | scrollup.min.js 7,847 / 2,048 / 3,390 | `global => (bool) get_theme_mod('knowx_scroll_top', false)` (Kirki field) else merely registered | NONE | NOT a dependency of knowx-custom although custom.js calls `$.scrollUp` |
| knowx-custom | custom.min.js 19,651 / 3,508 / 7,152 | every page | jquery, superfish, isotope, fitvids, sticky-kit | localizes `knowx_data{enable_scrollup}` |
| knowx-navigation | navigation.min.js 35,520 / 4,121 / 11,349 | every non-AMP page (`inc/Accessibility/Component.php:57`) | NONE | localizes `knowxScreenReaderText`; separate registration path from Scripts component; handle not in `get_js_files()` |
| comment-reply | core | singular + threaded comments (Comments/Component.php:77) | | |
| knowx-customizer (script) | customizer.min.js 4,681 / 646 | `customize_preview_init` (Customizer:124), deps customize-preview | | not Kirki dependent |
| knowx-customizer-controls | customizer-controls.min.js 15,136 / 2,665 | `customize_controls_enqueue_scripts` ONLY if `class_exists('Kirki')` (Customizer:51-66), deps customize-controls, jquery, kirki-customizer | | Kirki-only |
| knowx-theme-settings | admin/index.min.js 17,510 / 2,983 | EVERY wp-admin screen (Options:60) | wp-element, wp-components, wp-data | localizes `knowxThemeSettings{nonce,settings}` on every admin page. Mount node `#knowx-settings-page` is only printed by the commented-out menu page, so this loads React packages for nothing |
| knowx-customizer-script | admin/knowx-customizer.min.js 1,335 / 248 | `admin_enqueue_scripts` gated by `$pagenow==='customize.php'` (Options:68-77). `wp-admin/customize.php` never fires `admin_enqueue_scripts` (it does not include admin-header.php), so this handle NEVER LOADS: the "Flush Local Font Files" button does nothing | | dead enqueue |

Missing from `get_js_files()` / never enqueued anywhere: `global.min.js` (global.ts, `window.isWidthMobile`, `window.mobileBreakpoint=55`; grep shows zero consumers) -> shipped-never-enqueued orphan. `optional/dev/dev-proxy-livereload.php` enqueues livereload but sits in `optional/`.

### 1c. Flags
SHIPPED BUT NEVER ENQUEUED: `global.min.js`; `front-page.min.css` (see above); `admin/knowx-customizer.min.js` (enqueue exists, never fires); `FontAwesome.otf`, `fontawesome-webfont.eot/svg/ttf` (see s5); 9 `.webp` + `placeholder.svg` in assets/images (unreferenced); `assets/images/src/*` (84 KB dev duplicates).
ENQUEUED BUT FILE MISSING: none found. All `.min.*` referenced in PHP exist.
DUPLICATE LOADS / DUPLICATION:
- Nav markup printed twice (`template-parts/header/navigation.php:46-55`) with identical `menu_id=primary-menu` -> duplicate `#primary-menu` IDs (invalid HTML; `aria-controls="primary-menu"` ambiguous; CSS `#primary-menu .sub-menu` at _navigation.css:224-230 hits both).
- Open Sans is requested by the theme (`knowx-fonts`) AND by Kirki Typography fields (all default `Open Sans`) when Kirki is active (likely double load; not verified at runtime).
- `Google Fonts` family map: `'Open Sans' => [... '900&display=swap']` (Fonts/Component.php:229) injects `&display=swap` inside a family variant, then `display=swap` is also passed as query arg; after urlencode the 900 weight request is malformed. Verify in browser.
- Montserrat is requested (Fonts:230) but never referenced in any CSS or Kirki default.
- `get_asset_version()` implemented twice (Template_Tags.php:155, Fonts/Component.php:85).
- Button styling declared 3x: `_blocks-style.css:22-51`, `_forms.css:11-57`, `editor/editor-styles.css:130-159`; `.wp-block-image.is-style-knowx-border` 3x (`_blocks.css:281`, `_blocks-style.css:16`, `editor-styles.css:96`).
- knowx-amp.css lines ~118-442 are a copy of widgets.css (diff shows widgets.css minus its 15-line header and 3 rules).
- rtl.css re-imports `_custom-properties, _reset, _typography, _elements, _links, _grid` (rtl.css:5-10), duplicating ~60% of global.min.css inside rtl.min.css (18 KB).
MISSING VERSION ARGS: knowx-bbpress, knowx-rtl, knowx-amp (Styles:122,127,132). `knowx-fonts` null is deliberate.
LOADED ON EVERY PAGE BUT ONLY NEEDED SOMEWHERE:
- FontAwesome 28.8 KB CSS + 77 KB woff2: theme uses 11 glyphs (bars, search, close, angle-up, angle-down, file-text-o, folder-open-o, pencil, comment-o, calendar, map-marker).
- loaders.min.css when loader off (default).
- Isotope (35 KB) only for grid/masonry blog layouts; sticky-kit only for sticky sidebar; fitvids only when iframes present; superfish duplicates CSS :hover + navigation.ts.
- global.min.css carries Knowledge-Base layouts (global.css:330-630, ~4.9 KB min) used by 2 page templates, Bootstrap grid 12 KB min (see s7), TinyMCE/media-modal overrides (global.css:262-327), Elementor shims.
- Admin: theme-settings.min.css + React app on every wp-admin screen.
- Customizer/AMP/RTL sheets are conditional (fine).

Per page-type CSS+JS weight (theme assets only, minified bytes; jQuery/WP core excluded):

| page type | CSS bytes (gz) | JS as shipped (gz) | JS if maps stripped (gz) |
|---|---|---|---|
| Single post/page (comments closed) | global+loaders+FA+content+sidebar+widgets = 126,502 (25.6 K gz) + Google Fonts CSS | superfish+isotope+fitvids+sticky+custom+navigation = 241,228 (73,240 gz) | 52,650 (17,020 gz) |
| Single with comments | +2,474 = 128,976 | same | same |
| Blog index / archive / search | 126,502 | same | same |
| 404 | global+loaders+FA+content+widgets = 126,067 | same | same |
| KB templates (page-knowledgebase-*) | global+loaders+FA+content = 122,196 (content only is printed) | same | same |
| any page + bbPress active | +3,011 | | |
| any page, RTL | +18,147 (4,184 gz) | | |
| scroll-up enabled | | +7,847 (3,390 gz) | +2,048 |
| wp-admin screen (any) | +3,837 | +17,510 shipped + wp-element/components/data | 2,983 |
| Block editor iframe | editor-styles 41,513 (7,511 gz) + Google Fonts | | |
Fonts payload on every page: FA woff2 77,160 B (+ FA woff 98,024 only for no-woff2 browsers), Open Sans + Montserrat from Google.

---------------------------------------------------------------------
## 2. CSS FILE REPORT
---------------------------------------------------------------------
Legend: HC = hard-coded colour literals (lines); !imp = `!important` count; RTL = physical props (count, examples); MQ = media queries; mobile = a rule at <=640px exists; focus = :focus / :focus-visible; RM = prefers-reduced-motion; dark = dark-mode ready. Global facts: ZERO `:focus-visible`, ZERO `prefers-reduced-motion`, ZERO `prefers-color-scheme`/`data-theme`/`color-scheme` in any file; ZERO `--wp--preset--*` consumption; ZERO logical properties except `text-align:end` (content.css:546); `.wp-element-button` / `.wp-block-button__link` and `select` set `outline:0`/`none` with no replacement focus style (_blocks-style.css:38, _forms.css:30,144).
Breakpoint zoo: 380, 480, 600, 767, 768, 781, 782, 960, 991, 992, 1024, 1199, 1200 px; 37.5em (`--narrow-menu-query` = 600px), 40em, 48em (`--content-query`), 60em (`--sidebar-query`); JS uses a 55em (880px) breakpoint (navigation.ts:155, global.ts:19) and `>768` (custom.js:67). Seven different "mobile" thresholds.

Lint state: stylelint 1,870 errors (1,509 are vendored fontawesome.css; `ignoreFiles` only ignores `**/vendor/**`), rest: selector-pseudo-element-colon-notation 826, rule-empty-line-before 721, no-descending-specificity 197, color-hex-length 23, length-zero-no-unit 22, no-duplicate-selectors 10 (list below). No rule forbids hex, `!important`, physical properties or px font sizes.
no-duplicate-selectors hits: _forms.css:155 (`select` twice), _header.css:37, _navigation.css:190 and :651, content.css:488-491 (`.default-layout .entry-layout` listed 4x in one rule) and :702, global.css:94, rtl.css:101.

### Partials (compiled into entries; entry map in s6)
**_custom-properties.css** (87 lines) - `:root` token block. 26 distinct colour literals (they are the token values): #03a9f4 x10, #505050 x3, #222222 x5, #333333 x5, #fff x3, #f7f7f9 x2, #e5e5e5, #e8e8e8, #ffffff, #111111, #6c7781, #444, #555, #666, #2d2d2d, #f8f8f2, plus editor palette #41848f #c0392b #27ae60 #2980b9 #f1c40f #1c2833 #95a5a6 #ecf0f1 #97c0b7 #eee9d1. Defines 60+ vars in three naming families: `--color-*` (theme/editor/header/footer/copyright/heading/link), `--button-*`, `--global-*` (font color/family/size/line-height/border-color/border-radius), plus `--highlight-font-family`, `--content-width:100%`, `--dropdown-symbol-width`, `--form-border-radius`, `--textarea-border-radius`, `--container-width` (defined, never used; the live container width comes from Kirki `.container{max-width}`). Defects: `--global-font-size:14` is unitless and drives `calc(36/var(--global-font-size)*1rem)` etc.; `--font-size-small` = 16/14 = 1.14rem is LARGER than `--font-size-regular` = 0.875rem (line 81-82) and disagrees with `theme.json` small=13px and `editor-font-sizes` small=13; `--global-font-family: "Open Sans", serif` falls back to serif (line 10). Undefined-but-used vars: `--border-color-dark` (content.css:512,608) and `--color-link-active` (content.css:583) -> silently dropped. Defined-never-used: `--container-width`, `--accentColorHover`, `--optionBorderColor`, `--bs-color`. Dark: none.

**_custom-media.css** (11) - 4 `@custom-media` (narrow 37.5em, wide 37.5em, content 48em, sidebar 60em). `--wide-menu-query` unused. Only injected via `config.default.json` preload, so every entry gets it (build-css.js:98-117).

**_reset.css** (358) - normalize.css v8.0.1 (MIT). No colours, no !important, no focus rules except moz focusring (:226-231). Font stacks `monospace`/`inherit` only.

**_grid.css** (1,149) - Bootstrap-3 grid verbatim (container/row/col-xs|sm|md|lg-1..12, push/pull/offset, visible-*/hidden-*, print helpers). !imp 49 (all in visible/hidden helpers), RTL 172 (float:left, left/right offsets, padding-left/right 15px), MQ 768/992/1200/767/print, mobile yes. Only `.container`, `.row`, `.col-md-6` are used in templates (archive.php:48, index.php:53, single.php:49 `post-layout row`; page-knowledgebase-one.php:92-112). ~12 KB minified of global.min.css (and again in rtl.min.css) for three selectors. Float-based, `.container` width 970 then max-width 1170 (lines 17-27). Nothing from it is token-driven.

**_typography.css** (121) - HC: #333333 (:30 headings, ignores `--color-h1..h6`), #eee (:89 pre bg), #666 (:110 abbr border), #fff9c0 (:116 mark bg). !imp 2 (:90,:96 pre). RTL: border-left (:60), padding-left (:65), margin-left (:66,:71). Fonts: hard-coded stacks :90, :104, `font-size:13px` :77. Vars used: --global-font-*, --highlight-font-family, --color-quote-*. MQ: content-query. Mobile: n/a. Focus: none. Dark: no. Duplicate: `blockquote` border-left/colour also in _blocks.css:101-108.

**_elements.css** (81) - HC: #e8e8e8 (:32 hr). RTL: margin-left (:55). `body{background:var(--color-theme-body)}` (:25). Imports _custom-properties (a no-op after dedupe).

**_links.css** (26) - links use --color-link/-hover; `a:focus{outline:thin dotted}` (:19-21) is the ONLY global focus style; `a:hover,a:active{outline:0}` (:23). Dotted thin outline fails WCAG 2.4.11/non-text contrast on many backgrounds; no :focus-visible.

**_accessibility.css** (36) - `.screen-reader-text` + focus reveal. HC: #f1f1f1 (:20), rgba(0,0,0,.6) (:22 shadow), #21759b (:24 text). !imp 3 (legit). RTL: `left:5px` (:29). z-index 100000 (:35). Focus handled. Dark: the reveal uses fixed light colours.

**_header.css** (98) - imports _navigation.css (:4). HC: #fff (:16), rgba(255,255,255,.05)+rgba(189,189,189,.15) (:19), rgba(206,206,206,.05)+rgba(132,132,132,.15) (:38), #333332 (:80 site title, ignores `--color-site-title`), #757575 (:98 tagline, ignores `--color-site-tagline`). RTL: left/right:0 (:25-26), margin-right:15px (:67). z-index 999/999/9999. px font sizes 38px, 14px. MQ 767 only. Duplicate: `.sticky-header .site-header-wrapper.has-sticky-header` declared twice (:22, :37); `.site-header-wrapper` bg `#fff` is overridden by global.css:80 `var(--color-header-bg)` (dead declaration). Focus: none. RM: none.

**_navigation.css** (703) - HC (13): #111 (:18), rgba(0,0,0,.7) (:99 overlay), #fff (:157,162,290,318x2,591,664), rgba(34,36,38,.1) (:233), #000 (:279,317), rgba(0,0,0,.05)+rgba(18,43,70,.1) (:295), rgba(0,0,0,.02) (:325), #222222 (:453), rgba(0,0,0,.1) (:597), #aaaaaa (:613), #aaa (:636), #c2c2c2 (:652), keywords `white` (:118,:183) and `black` (:141). !imp 4 (:14,:17,:77,:692). RTL: 46 physical (float:left/right :23,:258,:545,:555; left/right on dropdown arrows :275,:288,:306,:316,:332-341,:351,:398,:408,:525-535; padding-left/right :170,:221,:242,:386,:468,:513; margin-left :13,:291,:302,:504,:514,:556). MQ: 480, 600, 767, 768, 782 - mobile YES. Focus: only `:focus` via `:focus-within` (:430) for keyboard submenu reveal; no :focus-visible; `.menu-toggle` and `.search-icon` have no visible focus design. z-index 99, 9999, 1, 100, 9, 1. px font sizes 7. Hard-coded FontAwesome `font-family` + glyph (:215-216, :391-392, :641-642). RM none. Dark: header bg tokenised via global.css only; `.menu-toggle` colour #111, mobile panel `white`/`black`. Dead/legacy selectors: `.bp-header-submenu`, `.user-profile-menu` (BuddyPress), `.cart`/`.cart-info`/`.cart-total` (:565-566,:570-571,:611-612,:655) - no cart markup anywhere in PHP (`knowx_site_menu_icon` prints only search); `.mobile-menu-heading .menu-close` OK; `.nav--toggle-sub .dropdown-symbol` rotated-border arrow AND a FontAwesome `::after` arrow (:389-404) are both drawn (two chevron implementations). Duplicates: `.menu-icons-wrapper #searchform:before` twice (:639,:651), `.mobile-menu-heading .menu-close:before/:after` twice (:173,:190).

**_blocks.css** (1,668; 38 KB src, ~23.5 KB min) - 90 core blocks styled. HC: rgba(0,0,0,.1) (:973 submenu shadow) only; heavily tokenised (--global-border-color x20, --color-theme-primary x16, --color-theme-grey x13, --color-link x10 ...). !imp 6 (:376-383 code block, :433, :473). RTL: 18 (float:left :52 drop cap; margin-left/right; border-left-color :102,:108; padding-left :517,:685; text-align:left :321,:1628). MQ: 40em, 48em, 781, 600 (mobile yes). Focus: 1. Lines 898-1598 (~16 KB src / 9.1 KB min / 1.5 KB gz) style FULL-SITE-EDITING blocks that a classic theme never renders (site-title, site-logo, navigation, query loop, post-title, post-author, comments, term, template-part...) - dead for classic output. Fonts: `"Courier New"` (:358,:376), Georgia (:402). Stylelint: 57 problems.

**_blocks-style.css** (50) - imports _custom-properties; `.wp-element-button,.wp-block-button__link` (global, loaded via global.css) with `outline:0`, no focus style (:38), `text-decoration:none !important` (:32). `border-radius:3px` hard-coded (:28) vs `--button-border-radius`. Duplicates button rules in _forms.css and editor-styles.css.

**_forms.css** (183) - HC: #fff (:41,44,146), #aaa/#bbb (:67), #666 (:93 input colour), #111 (:116). !imp 5 (:41-45 Customizer edit-shortcut button). RTL: padding-right 40px (:151,:160), `background-position:right 1rem center` (:159). Mobile: iOS 16px zoom guard (:163-183) - good. Focus: 24 `:focus` selectors with `outline:thin dotted`; `select` has `outline:none` (:144) and no replacement. Table styles (`table`, `td`) also live here (:120-136) and duplicate `_elements.css:79` `table{margin;width}`. Duplicate `select` rule (:138,:155). Not loaded on pages that do not print content.min.css. Dark: `select{background-color:#fff}`, input `color:#666`.

**_media.css** (120) - no colours; RTL: margin-left/right (:32-33,:48,:53). `.gallery` grid; mobile n/a.

**_utility.css** (219) - Tailwind-ish `.flex/.grid/.gap-*/.justify-*/.items-*` utility classes, uses CSS nesting (`&.horiz`). No template uses them (grep of template-parts/ and page-knowledgebase-*.php found 0). Generic names collide with plugins. Compiled into global.min.css. Dead.

### Entry / leaf stylesheets
**global.css** (640 -> global.min 43,757) - imports _custom-properties, _blocks-style, _reset, _grid, _typography, _elements, _utility, _links, _header(+_navigation), _footer, _accessibility. Own HC (14 distinct, 22 uses): rgba(0,0,0,.025) (:63), #505050 (:114), rgba(255,255,255,.5) (:117), #111 (:181,208,398), #f5f5f5 (:273), #e2e2e2 (:274,279,283), #555 (:275), #595959 (:311), #72777c (:321), #21b4db (:335 hero bg, differs from Kirki default rgba(3,169,244,1)), #fff (:344,351,440,637), #222 (:495,609), rgba(236,236,236,.94) (:524), #333 (:540). !imp 14 (`canvas{width:100% !important;height:auto !important}` :43-46 hits every canvas on the site; TinyMCE/media overrides :267-326). RTL 14 (:98 border-right-color, :119-120 `margin-left:-50vw; left:50%` full-bleed hack, :169,177,385,394 search icon, :357-358, :504 `float:right` arrow, :611,623). MQ 480, 991, 767, 960, 1024, narrow-menu - mobile YES. Focus: 6 (`outline:thin dotted #111`). RM none. z-index 99, 1. Dead/unrelated-plugin selectors: `.bp-user .site-sub-header`, `.single-item.groups`, `.group-create` (:106-108), `#user-profile-menu`, `.bp-header-submenu`, `.main-navigation #user-profile-menu` (:75-79,:87-99), `body.elementor-editor-active`, `.elementor-template-full-width` (:32-41), `.wpp-meta` (:516, WP Popular Posts), `#bbp-search-form` (:137,145,153), `.wp-editor-wrap/.mce-*/.qt-dfw/.media-menu-item/.media-router` (:266-327, front-end editors), `canvas` hammer, `p:empty` rules (:48-55, `content:none` on a non-pseudo element is a no-op). KB layouts (:330-630) only for two page templates. Fonts: `'FontAwesome'` (:621). `.knowx-popular-posts ul li a::after{float:right}` (:504). Body-class dependent layout: `.layout-boxed` fixed 1170px (:66-69).

**content.css** (757 -> 47,925) - imports _blocks, _media, _forms. HC (#e8e8e8 :83,:317; #fff :110,:195). !imp 2 (:110 `color:#fff !important`, :201). RTL 29 (alignleft/alignright :332-339, `post-categories left:10px` :98, margin/padding L/R). MQ content/sidebar queries, 768, 60em - mobile via `max-width:60em` only; no <=640 rule. Focus: pagination `:focus`. Dead: `.type-grade/.type-level/.type-quests/.type-badges` (:257-266 gamification CPTs), `.infinite-scroll/.neverending/.infinity-end` (:615-628 Jetpack), `.woo-left-sidebar/.woo-primary-sidebar` (:742,753 no Woo), `.masory-layout` typo (:81), `.default-layout .entry-layout` x4 (:488-491). Block palette helper classes `.has-theme-*-color/background-color` (:363-460, slugs `theme-*`) do not match `theme.json` slugs (primary, base, contrast, tertiary...) - when theme.json is present WP ignores the PHP `editor-color-palette`, so these exist for legacy content only. Font-size: 24px, 11px, 12px; FontAwesome glyph `::before` for meta icons (:157-190). Undefined vars `--border-color-dark`, `--color-link-active`.

**comments.css** (228 -> 2,474) - HC (8): #e8e8e8 (:35), rgba(0,0,0,.1) (:36,37), #fff (:39), #ccc (:87), #000 (:155), #c3c3c3 (:159), hsl(0,0%,95%) (:179), #d54e21 (:212). RTL 16 (avatar `left:-70px` :79, `margin-left:75px/70px` :55,60 - the avatar-outside layout cannot mirror, `text-align:right` :148,216). MQ content-query only, mobile: no <=640 rule (75px comment gutter on 390px wide screens). Focus: 3. Dark: white card bg, black text. Duplicate: `.comment-content a{word-wrap}` at :5 and :106.

**bbpress.css** (181 -> 3,011) - HC: #f9fbff (:69,81), #fff (:108 `!important`), #e3e3e3 (:156). !imp 3. RTL 2. No media queries (no mobile rule; `padding:25px 35px !important` rows overflow at 390px). Focus none. Dead if bbPress absent; loaded sitewide when bbPress active (see s1).

**widgets.css** (304 -> 3,871) - HC (7): #fff (:17,292), #7d7d7d (:66), #808080 (:162,170), #333 (:222,276), #c3c3c3 (:238), #ccc (:293), #eaeaea (:297). !imp 5 (:116-123 collapsing-categories plugin). RTL 10 (:98,137,142,180,205,215,243,245,256,271). No media queries. Focus: 3. Plugin selectors: `.widget_collapscat` (~40 lines), `.knowx-categories-lists` (theme widget, ok), `.widget_calendar`. Fonts: `var(--global-font-family)`. Full copy inside knowx-amp.css.

**sidebar.css** (35 -> 435) - !imp 2 (:31,34 override sticky-kit inline styles). RTL 4. MQ content/sidebar/60em.

**front-page.css** (56 -> 682) - HC: hsla(0,0%,50%,.8) (:25). Loaded by nothing (see s1). Selectors `.post`, `.site-main`, `.site` are global and would hijack every page if ever enabled.

**loaders.css** (217 -> 1,699) - HC: #fff (:33,76). RTL: 22 `left` keyframes (centering, mirrors ok in practice). Hard-coded English `content:"Loading"` (:16, not translatable). Four vendor-prefixed `-moz-/-webkit-/-ms-` animation blocks (:87-98, 102-131, 159-209) that `browserslist > 1%, last 2 versions` + lightningcss make unnecessary. Infinite animation, no `prefers-reduced-motion`. z-index 999991. The overlay is `position:fixed;100%x100%` opaque (`--color-theme-loader`) and is hidden only by `$(window).load` (custom.js:264-268): if JS fails or errors earlier the whole site stays covered. MQ 1200/991/767/480 (mobile yes).

**rtl.css** (136 -> 18,147) - see s1 for load order and duplicated imports. HC: #000 (:29). !imp 4 (`text-align:right !important` on `body` :24 blocks any `.has-text-align-center` etc.). Dead selectors: `.submenu-btn` (:126,133, no markup), `select#pa_color`/`#pa_size`/`select.orderby` (:93-95, WooCommerce). Covers only ~30 of ~170 physical properties in the other files; grid/offsets/blocks/comments/footer/forms are not mirrored.

**knowx-amp.css** (441 -> 5,370) - HC (8): #fff (:35x2,425), rgba(0,0,0,.02) (:40), #7d7d7d (:179), #808080 (:283,291), #333 (:351,409), #c3c3c3 (:369), #ccc (:426), #eaeaea (:430). !imp 5. RTL 17. MQ 768/767. Loaded only when `is_amp()`; lines 118-442 duplicate widgets.css.

**knowx-customizer.css** (48 -> 880) - HC (6): #2271b1 (:2), #135e96 (:3), #ddd (:4), #dde0e2 (:30), #868894 (:35), rgba(0,0,0,.08) (:48). !imp 4. Selectors `.customize-control-kirki-radio-image` (Kirki-only, :17-49). Own vars `--accentColor*`, `--optionBorderColor` (2 of 3 unused). Fully Kirki-dependent -> removable with Kirki.

**admin/theme-settings.css** (296 -> 3,837) - HC (12): #3c8000 (:23), #fff x5, #bbb (:68), #e8e8e8 (:70), #888 (:71), #ef5455 x4, rgba(0,0,0,.6) (:87), #e3e3e3 (:104), #242424 (:113), #000 (:163), #f83939 (:215), #dadadab3 (:242). Uses CSS nesting; styles a dashboard (`.knowx-dashboard-tabs`, `.addon-section`, `.buddy-plugin-row`, `.knowx-feature-*`, `.dashicons`) whose markup does not exist in the repo (grep: zero PHP hits) and a Kirki switch override (:293-296). Effectively dead (the page that would use it is not registered).

**editor/editor-styles.css** (158 -> 41,513) - imports _custom-properties, _typography, _elements, _links, _media, _blocks, _grid. HC: #ffffff (:102), #505050 (:103,110), #333333 (:120) - fixed light editor surface, no token. Font stack hard-coded `"Open Sans", -apple-system...` (:104,111) ignoring the Kirki/typography settings. Duplicates button + image border rules. 41 KB for the editor iframe includes the full Bootstrap grid.

**fontawesome.css** (2,337 -> 28,815) - FA 4.7.0 verbatim; 675 glyph classes, 11 used by the theme (+ any user menu `fa-*`). HC #eee (:64), #fff (:179). `src:` lists eot, woff2, woff, ttf, svg (:9-10). Vendored but linted (1,509 stylelint errors).

### Per-file metric table

| file | lines | HC distinct/uses | !imp | RTL-unsafe | mobile<=640 rule | :focus / :focus-visible | RM | dark | z-index | px font-size |
|---|---|---|---|---|---|---|---|---|---|---|
| _accessibility | 36 | 3/3 | 3 | 1 | n/a | 1/0 | no | no | 100000 | 0 |
| _blocks-style | 50 | 0/0 | 1 | 0 | n/a | 0/0 | no | partial | - | 0 |
| _blocks | 1668 | 1/1 | 6 | 18 | yes (600, 781) | 1/0 | no | partial | - | 0 |
| _custom-media | 11 | 0 | 0 | 0 | - | - | - | - | - | - |
| _custom-properties | 87 | 26/49 (token source) | 0 | 0 | - | - | - | no (single :root) | - | 0 |
| _elements | 81 | 1/1 | 0 | 1 | - | 0/0 | no | partial | - | 0 |
| _footer | 120 | 2/3 | 0 | 3 | yes (37.5em) | 0/0 | no | partial (`.site-footer{background:#fff}` :6) | - | 1 |
| _forms | 183 | 5/8 | 5 | 3 | yes (iOS) | 24/0 | no | no | - | 3 |
| _grid | 1149 | 0 | 49 | 172 | yes (767) | 0/0 | no | n/a | - | 0 |
| _header | 98 | 7/7 | 0 | 3 | yes (767) | 0/0 | no | no | 999,999,9999 | 2 |
| _links | 26 | 0 | 0 | 0 | - | 2/0 | no | yes (tokens) | - | 0 |
| _media | 120 | 0 | 0 | 4 | - | 0/0 | no | yes | - | 0 |
| _navigation | 703 | 13/20 (+3 keywords) | 4 | 46 | yes (480,600,767) | 4/0 | no | no | 99,9999,1,100,9,1 | 7 |
| _reset | 358 | 0 | 0 | 0 | - | moz-focusring | no | n/a | - | 0 |
| _typography | 121 | 4/4 | 2 | 4 | - | 0/0 | no | no | - | 1 |
| _utility | 219 | 0 | 0 | 0 | yes (narrow-menu) | 0/0 | no | n/a | - | 0 |
| bbpress | 181 | 3/4 | 3 | 2 | NO | 0/0 | no | no | - | 4 |
| comments | 228 | 8/9 | 0 | 16 | NO (48em only) | 3/0 | no | no | - | 0 |
| content | 757 | 2/4 | 2 | 29 | partial (60em) | 3/0 | no | no | - | 3 |
| editor/editor-styles | 158 | 3/4 | 0 | 4 | NO | 0/0 | no | no | - | 0 |
| fontawesome | 2337 | 2/2 | 0 | 13 | - | 1/0 | no | n/a | - | 0 |
| front-page | 56 | 1/1 | 0 | 2 | NO | 0/0 | no | no | - | 0 |
| global | 640 | 14/22 | 14 | 14 | yes (480,767) | 6/0 | no | no | 99,1 | 2 |
| knowx-amp | 441 | 8/12 | 5 | 17 | yes (767) | 3/0 | no | no | 100 | 1 |
| knowx-customizer | 48 | 6/6 | 4 | 1 | n/a | 0/0 | no | no | - | 0 |
| loaders | 217 | 1/2 | 0 | 22 | yes (480) | 0/0 | NO (infinite anim) | no | 999991 | 0 |
| rtl | 136 | 1/1 | 4 | 38 (by design) | yes (767) | 0/0 | no | no | - | 0 |
| sidebar | 35 | 0 | 2 | 4 | yes (60em) | 0/0 | no | n/a | - | 0 |
| widgets | 304 | 7/10 | 5 | 10 | NO | 3/0 | no | no | - | 1 |
| admin/theme-settings | 296 | 12/19 | 1 | 9 | yes (380,768) | 2/0 | no | no | - | 10 |

Totals: 143 colour literal uses across 21 files outside `_custom-properties.css` (141 excluding fontawesome), 60 distinct after normalising #fff/#ffffff; 49 more inside the token block. (The Kirki plan cites 168; counts differ because named colours `white/black` x3 and rgba shadows are counted separately here.)

---------------------------------------------------------------------
## 3. TOKEN MIGRATION INVENTORY
---------------------------------------------------------------------

### 3a. Existing token -> customizer setting -> default -> PHP emitter (Dynamic_Style, `inc/Dynamic_Style/Component.php`)
Emission: one rule `body{--x:val;...}` appended to `knowx-global` (prio 20) ONLY when `class_exists('Kirki')` (Dynamic_Style:35, Theme.php:172); `site_custom_colors` mod read with default true (:36) but no such field exists. Each var is written only when the mod is non-empty. Specificity: `body{}` (0,0,1) vs `:root{}` (0,1,0) - the body rule wins by inheritance (closer ancestor), so there is no cascade conflict, but the emitter is hard-gated on Kirki: after Kirki is removed none of the 24 colour mods reach the front end unless the new Output_Builder reproduces them.

| Kirki setting (mod) | default | CSS var emitted | static fallback in `_custom-properties.css` | consumers (file:line) | proposed role |
|---|---|---|---|---|---|
| site_loader_bg | #03A9F4 | --color-theme-loader | #03A9F4 :28 | loaders.css:12 | loader-bg |
| site_title_hover_color | #03A9F4 | --color-site-title-hover | #03A9F4 :29 | _header.css:89, _blocks.css:944 | site-title-hover |
| site_header_bg_color | #ffffff | --color-header-bg | #fff :31 | global.css:80,84,91,98; _blocks.css:970 | header-bg |
| menu_hover_color | #03A9F4 | --color-menu-hover | #03A9F4 :32 | _navigation.css:483,487; _blocks.css:966 | nav-hover |
| menu_active_color | #03A9F4 | --color-menu-active | #03A9F4 :33 | _navigation.css:476 | nav-active |
| body_background_color | #f7f7f9 | --color-theme-body | #f7f7f9 :30 | _elements.css:25 | page-bg |
| site_primary_color | #03A9F4 | --color-theme-primary | #03A9F4 :17 | 28 uses: breadcrumbs, KB links, post-categories, scrollUp hover, cart badge, blocks | primary / accent |
| site_links_color | #03A9F4 | --color-link | #03A9F4 :34 | 19 uses (links, pagination, widgets, blocks, nav a:hover _navigation.css:457) | link |
| site_links_focus_hover_color | #222222 | --color-link-hover | #222222 :35 | 12 uses | link-hover |
| site_buttons_background_color | #03A9F4 | --button-background-color | #03A9F4 :39 | 8 | button-bg |
| site_buttons_background_hover_color | #222222 | --button-background-hover-color | #222222 :40 | 8 | button-bg-hover |
| site_buttons_text_color | #ffffff | --button-text-color | #fff :37 | 8 | button-text |
| site_buttons_text_hover_color | #ffffff | --button-text-hover-color | #fff :38 | 12 | button-text-hover |
| site_buttons_border_color | #03A9F4 | --button-border-color | #03A9F4 :41 | 8 | button-border |
| site_buttons_border_hover_color | #222222 | --button-border-hover-color | #222222 :42 | 8 | button-border-hover |
| site_footer_title_color | #333333 | --color-footer-title | #333333 :44 | _footer.css:27 | footer-heading |
| site_footer_content_color | #505050 | --color-footer-content | #505050 :45 | _footer.css:7 | footer-text |
| site_footer_links_color | #222222 | --color-footer-link | #222222 :46 | _footer.css:31 | footer-link |
| site_footer_links_hover_color | #03A9F4 | --color-footer-link-hover | #03A9F4 :47 | _footer.css:36 | footer-link-hover |
| site_copyright_background_color | #ffffff | --color-copyright-bg | #ffffff :48 | _footer.css:77 | copyright-bg |
| site_copyright_content_color | #505050 | --color-copyright-content | #505050 :49 | _footer.css:78 | copyright-text |
| site_copyright_border_color | #e8e8e8 | --copyright-border-color | #e8e8e8 :50 | _footer.css:79,80 | copyright-border |
| site_copyright_links_color | #222222 | --color-copyright-link | #222222 :51 | _footer.css:85 | copyright-link |
| site_copyright_links_hover_color | #03A9F4 | --color-copyright-link-hover | #03A9F4 :52 | _footer.css:89 | copyright-link-hover |

All 24 Skin/Header/Footer/Typography colour mods use `choices:['alpha'=>true]`, so values can be `rgba()`; any token derivation (hover/dim variants) must accept alpha.

### 3b. Kirki `output` rules (CSS emitted by Kirki itself, not by Dynamic_Style) - these disappear with Kirki and must be re-created
| setting | selector | property source | default | static CSS rule it overrides / conflicts with |
|---|---|---|---|---|
| site_container_width | `.container` max-width (General_Fields.php:41) | dimension | 1170px | `_grid.css:17-27` `.container` 970px / max-width 1170 |
| background_setting (footer, needs `site_footer_bg`) | `.site-footer-wrapper` background | rgba(255,255,255,.8) | `.site-footer{background:#fff}` _footer.css:6 sits on the PARENT; the wrapper is transparent so Kirki's bg paints under it but the parent is opaque white only if `.site-footer-wrapper` is smaller - verify visually; the pair is unreliable and should be unified into one token |
| site_title_typography_option | `.site-title a` | Open Sans 600 38px/1.2 #333333 left | `_header.css:77-80` 38px/700, #333332; `.site-title a{color:inherit}` :85; hover rule :88 (specificity (0,2,1)) still wins over Kirki's (0,1,1) |
| site_tagline_typography_option | `.site-description` | color #757575 | `_header.css:92-98` #757575 (same) |
| h1..h6_typography_option (6) | `h1`..`h6` | color #333333 each, Open Sans | `_typography.css:21-32` `h1-h6{color:#333333}`; unused tokens `--color-h1..h6` (used only inside _blocks.css for drop cap/headings). h4-h6 token values (#444/#555/#666) differ from Kirki default #333 |
| menu_typography_option | `.main-navigation a, ... .menu-toggle` + border-color for `.nav--toggle-small .menu-toggle` | color #222222 | `_navigation.css:448-454` `#222222`; then `.main-navigation a:hover` -> var(--color-link) :456 vs `--color-menu-hover` :479 (two competing hover rules, later wins) |
| typography_option | `body:not(.block-editor-page):not(.wp-core-ui), ... pre` | Open Sans 14px / line-height 1.6 / #505050 | `_typography.css:8-18` body colour var(--global-font-color) #505050; **line-height 1.4 static vs 1.6 Kirki**, so removing Kirki changes body leading |
| hero_section_background_setting | `.knowx-section-hero` | rgba(3,169,244,1) | `global.css:335` `#21b4db` (different blue) + blend mode color-burn |
| hero_section_title_typo / content_typo | `.knowx-section-hero .entry-title`, `.entry-summary` | #ffffff | `global.css:343-352` `#fff` |
| support_section_background_setting | `.knowx-support-section` | rgba(236,236,236,.94) | `global.css:524` same |
| support_section_title_typo / content_typo | `.knowx-support-title`, `.knowx-support-content` | #333333 | `global.css:540` `#333` |
| sub_header_background_setting (+`site_sub_header_bg`) | `.site-sub-header` | rgba(255,255,255,.5) | `global.css:117` same |
| site_sub_header_typography | `.site-sub-header, ... .entry-title ...` | color #333333 | `global.css:114` #505050 and :208 #111 (static disagrees with Kirki default) |
Because Kirki emits `<style id="kirki-inline-styles">` after the theme sheet, every Kirki rule beats the equal-specificity static rule; after Kirki removal the static values (#333332, 1.4 line-height, #21b4db, #505050/#111) become the visible defaults - these are the "silent visual changes" to reconcile.

### 3c. Other PHP-inline CSS that sets colour
| source | CSS | overrides |
|---|---|---|
| `inc/Custom_Header/Component.php:73,77` | `.site-title a,.site-description{color:#<header_textcolor>}` or clip | (0,1,1); beats `_header.css:85` `color:inherit`; also conflicts with Kirki site-title colour (both set the same property) and with live preview `customizer.tsx:76-78` inline style |
| `inc/login.php:91-...` | login logo/bg `<style>`; `body.login{background: <?php echo esc_url($custom_login_background_color) ?>}` | BUG: colour passed through `esc_url`; `$custom_login_choose_theme`, `$custom_login_background_gallery`, `$custom_login_background_color` are never assigned in `login_custom_head()` (undefined variables) |
| `inc/Customizer/Component.php:68` | `.customize-control.knowx-force-hidden-by-mode{display:none !important}` | Kirki only |
| `inc/Editor/Component.php:46-103` | `editor-color-palette` 11 colours (#03a9f4 #41848f #C0392B #27AE60 #2980B9 #F1C40F #1C2833 #95A5A6 #ECF0F1 #97c0b7 #eee9d1) | duplicate of `_custom-properties.css:17-27` and of `theme.json` palette (14 entries, different slugs: base, contrast, primary, secondary, tertiary, red...custom-sun). Three palettes, two slug schemes |

### 3d. Colour literal -> semantic role table (every distinct hard-coded value; use these rows to write the token map)
Group A - surfaces / backgrounds
| literal | where (file:line) | role -> suggested token |
|---|---|---|
| #fff / #ffffff (35 uses) | _footer:6,105; _forms:41,44,146; _header:16; _navigation:157,162,290,318,591,664 + `white` 118,183; theme-settings; bbpress:108; comments:39; content:110,195; editor:102; global:344,351,440,637; amp:35,425; loaders:33,76; widgets:17,292 | card/surface (`--bx-surface`) for: site-footer :6, comments li :39, post-box :195, widget :17/:292, single-post main (global:637 `.single-post .site-main`), mobile menu panel (_navigation:118); on-primary text/foreground (`--bx-on-primary`) for: scrollUp icon :105, hero text global:344/351, KB hover global:440, pill post-categories content:110, cart badge _navigation:664, loader dots loaders:76, mobile heading text :157/:162; search input bg :591, select bg _forms:146 |
| #f7f7f9 | _custom-properties:30,66 | page-bg (body), block-bg-subtle |
| rgba(0,0,0,.025) | global:63 | boxed-layout body wash |
| rgba(255,255,255,.5) | global:117 | sub-header bg overlay |
| rgba(236,236,236,.94) | global:524 | KB support section bg |
| #21b4db | global:335 | KB hero bg fallback (should equal primary) |
| #f9fbff | bbpress:69,81 | bbPress header/form bg (light tint) |
| #f5f5f5 | global:273 | TinyMCE tab bg |
| #eee | _typography:89 | code/pre bg |
| #fff9c0 | _typography:116 | mark highlight |
| #eaeaea | widgets:297, amp:430 | calendar pad |
| black / #000 | _navigation:141 (mobile heading bg), 279,317; rtl:29 (blockquote border); comments:155 | on-dark surface / strong border / reply link text |
| #2d2d2d / #f8f8f2 | _custom-properties:67-68 | code block bg/text tokens (already tokens) |
Group B - text
| #333333 (7) | _typography:30; editor:120; global:540; amp:351,409; widgets:222,276 | heading text (maps to `--color-h1`, footer title token; Kirki h1-h6 default) |
| #505050 (3 + token) | global:114; editor:103,110 | body text (maps `--global-font-color`) |
| #222222 / #222 | _navigation:453; global:495,609 | nav link / list-link text (`--color-link-hover` default too) |
| #111111 / #111 | _forms:116; _navigation:18; global:181,208,398 | strong text / focus outline colour |
| #666666 | _forms:93; _typography:110 | input text, abbr border |
| #333332 | _header:80 | site title (should use `--color-site-title`) |
| #757575 | _header:98 | tagline (should use `--color-site-tagline`) |
| #7d7d7d, #808080 | widgets:66,162,170; amp:179,283,291 | muted text, dashed separators |
| #555 / #595959 / #72777c | global:275,311,321 | TinyMCE tab/button text |
| #21759b / #f1f1f1 | _accessibility:20,24 | skip-link focus reveal (WP default) |
| #d54e21 | comments:212 | required asterisk (error) |
| #6c7781 | _custom-properties:55 | quote citation (token) |
Group C - borders / dividers / shadows
| #e8e8e8 (5) | _elements:32; comments:35; content:83,317; theme-settings:70 | divider/border-subtle (`--copyright-border-color` default too) |
| #e5e5e5 | _custom-properties:36 | `--global-border-color` default |
| #e2e2e2 | global:274,279,283 | TinyMCE borders |
| #e3e3e3 | bbpress:156; theme-settings:104 | widget list divider |
| #ccc / #c3c3c3 / #c2c2c2 / #aaa / #bbb / #aaaaaa / #dddddd | comments:87,159; widgets:238,293; amp:369,426; _navigation:613,636,652; _forms:67 | input/button borders, placeholder, avatar ring (consolidate to `--border` and `--border-strong`) |
| rgba(0,0,0,.1) | _footer:104 (scrollUp bg), _navigation:597, comments:36-37, _blocks:973 | elevation shadow / translucent scrim |
| rgba(0,0,0,.05)+rgba(18,43,70,.1) | _navigation:295 | dropdown shadow |
| rgba(189,189,189,.15)+rgba(255,255,255,.05) | _header:19 | header shadow |
| rgba(132,132,132,.15)+rgba(206,206,206,.05) | _header:38 | sticky-header shadow |
| rgba(0,0,0,.02) | _navigation:325; amp:40 | dropdown caret shadow |
| rgba(0,0,0,.7) | _navigation:99 | mobile overlay scrim |
| rgba(0,0,0,.6) | _accessibility:22; theme-settings:87 | focus shadow |
| rgba(34,36,38,.1) | _navigation:233 | mobile menu row divider |
| hsla(0,0%,50%,.8) | front-page:25 | card shadow (dead file) |
| hsl(0,0%,95%) | comments:179 | comment notes bg |
Group D - brand / accent (all equal #03A9F4 = `--color-theme-primary`) - see 3a; plus palette #41848f, #c0392b, #27ae60, #2980b9, #f1c40f, #1c2833, #95a5a6, #ecf0f1, #97c0b7, #eee9d1 (editor palette, theme.json, `_custom-properties.css:18-27`, `Editor/Component.php`).
Group E - admin-only literals (not part of the front-end token map): knowx-customizer.css (#2271b1, #135e96, #ddd, #dde0e2, #868894), theme-settings.css (#3c8000, #ef5455, #f83939, #888, #242424, #dadadab3, #bbb, #e3e3e3).

Collisions to resolve when mapping: (1) three palettes with different slugs (theme.json vs `Editor/Component.php` vs CSS vars); (2) `--color-link` is used for nav hover (`_navigation:457`) while `--color-menu-hover` exists - both apply at equal specificity, last wins (:483); (3) `.knowx-section-hero` fallback #21b4db != primary; (4) heading tokens `--color-h1..h6` exist but `h1-h6` use literal #333; (5) `--color-site-title`/`-tagline` exist but header uses literals; (6) `--copyright-border-color` and `--color-header-bg` break the `--color-*` naming; (7) `--button-*` family vs `--color-*`.

Suggested `--bx-*` grouping from this inventory: surface/surface-subtle/surface-elevated, text/text-muted/text-on-primary/heading, border/border-strong, primary(+hover/active/contrast), link(+hover), button(bg/text/border + hover x3), header(bg/shadow), nav(link/hover/active), footer(bg/title/text/link/link-hover), copyright(bg/text/border/link/link-hover), scrim, shadow-sm/md, focus-ring, code(bg/text), quote(border/citation), danger (#d54e21), loader-bg, plus the 11 palette tokens. Every `#fff` surface above needs a dark-mode counterpart; every `#fff` on-primary text needs a contrast pair.

---------------------------------------------------------------------
## 4. JS FILE REPORT
---------------------------------------------------------------------
Build facts (`build-js.js`): esbuild per-file, `minify:true`, target es6, `bundle:true` except `.cjs`, `sourcemap: isProd ? false : 'inline'` (:108), `external` includes `@wordpress/*`, `jquery`, `react`. Output name `*.min.js` for every source. I decoded each shipped file's inline source map: `sourcesContent` is byte-identical to the current `src` for all 13 outputs, so there is NO source/built drift (s6).
Lint: ESLint on `assets/js/src/**` = 1,047 errors, no warnings; ~80% `prettier/prettier`; vendored files (fitvids, scrollup, sticky-kit, superfish) are linted but should be ignored (`ignores` does not list them; the `assets/js/libs/**` ignore points at a directory that does not exist). `isotope.pkgd.cjs` is not matched by the lint glob.

### custom.js (270 lines -> custom.min.js)
- Purpose: header/mobile-nav/search/fitvids/isotope/sticky-sidebar/scrollUp glue; jQuery IIFE.
- Deps: jQuery; plugins `$.fn.superfish`, `$.fn.isotope`, `$.fn.fitVids`, `$.fn.stick_in_parent`, `$.scrollUp`; global `knowx_data` (localized). WP packages: none.
- Selectors expected / verified in PHP: `.site-loader` (inc/extra.php:80, only when loader==1); `body.sticky-header`, `.sticky-header .site-header-wrapper` (class from Kirki_Option/Component.php:67-69 - NOT gated on Kirki being active); `.site-header-wrapper` (header.php:29); `.search-icon` (inc/extra.php:98 AND searchform.php:12 - same class for open and close); `.site-header .top-menu-search-container` ok; `#primary-menu a` ok (duplicated id); `.cart a.menu-icons-wrapper` MISSING; `.bp-icon-wrap` MISSING; `a.user-link` MISSING (BuddyPress remnants); `.site-sub-header a`, `.site-wrapper a` ok; `.primary-menu-container #primary-menu` ok; `.knowx-mobile-menu` MISSING (:83); `.mobile-menu-heading .close-menu` MISSING - markup uses `.menu-close` (:84 dead, `focusin()` with no handler is a no-op); `.menu-toggle`, `.mobile-menu-close` (footer.php:49), `.menu-close` ok; `.grid-layout/.masonry-layout/.entry-layout/.grid-sizer` ok (archive.php, index.php, entry-layout.php); `.sticky-sidebar-enable .sticky-sidebar` ok; `iframe[src*="youtube|vimeo"]` generic.
- Security: no eval/innerHTML. `scrollText` HTML literal is constant but MALFORMED: `'<i class="fa fa-angle-up"><i>'` (:231) - second tag is not closed. No injection surface.
- A11y: scroll-to-top link has no text/aria-label/title (`scrollTitle:false`) -> unnamed link; search toggle (`.search-icon` anchor with an icon-only `<span class="fa fa-search">`, inc/extra.php:98) has no accessible name, no `aria-expanded`, no `aria-controls`, no Esc handling; mobile nav toggle in custom.js adds `body.mobile-menu-opened` only - it never updates `aria-expanded`, while navigation.ts updates `aria-expanded` + `.nav--toggled-on` on the same button. Closing via overlay/`.menu-close`/Esc (`e.keyCode===27`, :131, deprecated) leaves `aria-expanded="true"` and `.nav--toggled-on` set, so the next click toggles the ARIA state the wrong way (desync). No focus trap or focus return for the off-canvas menu. `$(document).mouseup` closes search with `fadeOut()` but never resets focus.
- Bugs / quality: (1) `$(window).load(fn)` (:264) - removed in jQuery 3; works only because WP still loads jquery-migrate 3.4.1; when Migrate is dropped from core the loader never hides, header class, sticky sidebar and isotope never initialise. (2) `headerClass()` registers a NEW `$(document).scroll` handler on every call and is called on ready, on EVERY resize event (:254-257) and on load -> unbounded handler leak. (3) `headerScroll()` runs un-throttled on every scroll event and reads layout (`.height()`) then writes `.site` padding. (4) The `.menu-toggle` click handler is bound twice (:94-103 and :105-115, copy-paste). (5) race: all dependencies are `async` and none are declared on knowx-scroll-up, so `custom.js` can run before superfish/isotope/fitvids/sticky-kit; `desktopMenuToggle()` throws (`$.fn.superfish` undefined) at width > 768 and aborts the ready callback before `mobileNav()`, `fitVids()`, `tableDataAtt()` run. WP core ignores the `async` data key; only the theme filter (Base_Support:150) injects the attribute, and async scripts ignore dependency order. (6) `knowx_data` read without existence check (:248). (7) `tableDataAtt` maps every `tbody td` on every table in the page using a global `thead th` list, wrong with multiple tables. (8) `$(window).width() > 768` vs CSS `min-width:768px`, vs navigation.ts 55em. (9) dead code: lazy-load hook :181-190; `isotope` run on `$(window).load` while CSS expects `.grid-sizer`.
- Kirki: indirect - depends on theme mods only (`knowx_scroll_top` via Scripts:178, body classes from Kirki_Option). No `Kirki` identifier.
- console.log: none.

### navigation.ts (529 -> navigation.min.js 35,520 / 4,121 real)
- Purpose: submenu toggles (`.dropdown` -> `button.dropdown-toggle`), small-nav toggle state, keyboard blur-close.
- Deps: none (vanilla TS, global `knowxScreenReaderText` localized in Accessibility/Component.php:66).
- Selectors verified: `.nav--toggle-sub`, `.nav--toggle-small`, `ul.sub-menu`, `.menu-toggle`, `.dropdown` (Nav_Menus/Component.php:104). Block-navigation selectors (`wp-block-navigation__submenu-container`, `.wp-block-navigation-submenu__toggle`) are dead in a classic theme.
- Security: `thisDropdownButton.innerHTML = dropdown.innerHTML` (:322) copies markup the same script/PHP generated - low risk. No eval, no postMessage.
- A11y: `aria-expanded` kept in sync across toggles (good); BUG: labels inverted - opening sets `aria-label = expand` (:458) and closing sets `collapse` (:431-434); the toggle label is `Expand` after it is already open. Parent links with children: if `isMobileWidth()` (<=55em = 880px) the click is `preventDefault`ed (:269-279) although the desktop hover submenu applies from 768px, so between 768-880px the parent link can not be followed. No Escape-closes-submenu handler; Tab handling only on last/first focusable. `.menu-toggle` aria-label is static "Open menu" (navigation.php:41). `if(!menuToggles)` (:345) NodeList is always truthy.
- Duplicated responsibility with custom.js (mobile toggle) and with CSS `:hover`/`:focus-within`/superfish (three mechanisms open the same submenu).
- Dead: block-navigation branches; `limitOpenSubmenus` param unused by callers except default.
- console: none. Kirki: none.

### global.ts (29 -> global.min.js) - ORPHAN
Sets `window.mobileBreakpoint=55`, `window.isWidthMobile`. Never enqueued (not in Scripts get_js_files, no other enqueue), no consumers in the repo. Delete or wire up.

### customizer.tsx (81 -> customizer.min.js)
Live preview for `blogname`, `blogdescription`, `header_textcolor` via `textContent`/inline style (safe). Dependency: `customize-preview`. Selectors `.site-title a`, `.site-description` exist. Not Kirki-dependent. Does not preview any Kirki colour (Kirki handled via `transport:auto`), so after Kirki removal the new framework needs its own postMessage/CSS-var live preview. Comments contain German ("Cast zu HTMLElement", :45).

### customizer-controls.js (211 -> customizer-controls.min.js) - KIRKI DEPENDENT
Purpose: re-implement Kirki's `active_callback` visibility on the client. Depends on: `[data-kirki-setting]` / `data-kirki-setting-link` attributes (:15-17), global `window.kirkiControlDependencies` (:25,29), script handles `kirki-customizer` / `kirki_field_dependencies` (Customizer/Component.php:51-58), and the CSS class `knowx-force-hidden-by-mode` whose rule is inlined only under Kirki (Customizer:68-71). Without Kirki the file early-returns and is not enqueued. Replace with the BuddyX Customizer_Framework `Active_Callback` (the plan's step 1). `var` + `==` loose equality throughout (103 ESLint errors). No security issues.

### admin/knowx-customizer.js (12 lines) - KIRKI DEPENDENT + DEAD
Click handler on `.button.knowx-flush-font-files` (a raw HTML button emitted by Kirki `Custom` field `site_flush_local_font`, Site_Performance.php:52) posting `action=knowx_regenerate_fonts_folder` to `ajaxurl` with NO nonce and no response handling. Server side `knowx_regenerate_fonts_folder` (Webfont loader:751) checks only capability (CSRF flush possible). The script never loads at all (see s1b). `ajaxurl` is a global not declared (ESLint no-undef).

### admin/index.jsx + api.js + settingsFields.json (164 + 12 + 64)
React settings page using `wp.element`, `wp.components` (TabPanel, TextControl, SelectControl, FormToggle, SnackbarList), debounced POST to `/wp-json/my-theme/v1/settings` (`my-theme` = WP Rig placeholder namespace; `apiRoot` built from `window.location.origin + '/wp-json/...'`, breaks plain permalinks and subfolder installs; should use `wp.apiFetch` / rest_url). Nonce `X-WP-Nonce` ok; server `permission_callback` = `manage_options` (good). Data: `settingsFields.json` contains placeholder fields ("Page 1", "Option 1", "Select one"). Settings are never read anywhere else in the theme (`knowx_theme_settings` only stored). Mount `#knowx-settings-page` exists only in `settings-page.php`, which is reachable only through the commented-out `add_admin_menu`. Result: a 17.5 KB bundle + React deps shipped and loaded on every admin page for no UI. `console.error` on failure (:59) is the only console usage in the theme. Not Kirki dependent (but `theme-settings.css:293-296` has a Kirki switch rule).

### Vendored (src files)
- fitvids.js (86 lines): FitVids 1.1, WTFPL, jQuery. `innerHTML` with a constant string to inject a `<style id="fit-vids-style">` into `<head>` at plugin call (:26) - CSP-unfriendly, native `aspect-ratio` replaces it. Lint-noisy.
- scrollup.js (7 lines, already minified): scrollUp v2.4.1, MIT, jQuery. Creates `<a id="scrollUp" href="#top">` with no accessible name; uses jQuery `animate` (no reduced-motion).
- sticky-kit.js (9 lines, minified): Sticky-kit 1.1.3 (2015), MIT, jQuery. Unmaintained.
- superfish.js (274 lines): Superfish 1.7.10 (2018), MIT/GPL, jQuery, optional hoverIntent (`$.fn.hoverIntent` absent -> falls back; `hoverIntent` not declared as a dep although WP ships it).
- isotope.pkgd.cjs (11 lines minified bundle): Isotope PACKAGED 3.0.6 (see s5). Built with `bundle:false` (`.cjs`), not linted.

### Cross-cutting JS findings
- Every file but customizer-controls/admin depends on global jQuery that is not declared as a dep for superfish/isotope/fitvids/sticky-kit/scrollup/navigation (works only because jQuery is in the head).
- No `console.log`/`debugger` leftovers (only `console.error` in admin/index.jsx:59).
- Dead code summary: `global.ts`; `.close-menu`, `.knowx-mobile-menu`, `.bp-icon-wrap`, `.user-link`, `.cart a.menu-icons-wrapper` selectors; lazy-load event; block-navigation branches in navigation.ts; duplicate click binding; `admin/*` UI.
- Duplicated mobile-nav logic between custom.js (class on body) and navigation.ts (aria + `.nav--toggled-on`).
- Kirki dependency map: customizer-controls.js (hard), admin/knowx-customizer.js (hard, also dead), customizer.tsx (none), custom.js/Scripts (soft: `knowx_scroll_top` mod, sticky-header/sticky-sidebar body classes), admin/index.jsx (none).

---------------------------------------------------------------------
## 5. VENDORED LIBRARIES
---------------------------------------------------------------------
| lib | version | licence | wp.org GPL-compat | src / shipped size | used by | still needed? | native replacement |
|---|---|---|---|---|---|---|---|
| Isotope PACKAGED | 3.0.6 (2018) | "GPLv3 for open source use or commercial" (header `isotope.pkgd.cjs:3-4`) | Compatible with a GPL-3.0-or-later theme; confirm the header wording is acceptable for the declared "GPL v3 (or later)" license and list it in readme (it is, readme.txt:72-75) | 35,450 min / 136,465 shipped (35,981 gz) | `.grid-layout`, `.masonry-layout` blog layouts (custom.js:154-167) | Only for 2 of 3 blog layouts, but loaded on every page | CSS Grid (`grid-template-columns:repeat(auto-fill,...)`) for grid; CSS columns or `grid-template-rows:masonry` (limited) for masonry. Load conditionally if kept |
| Superfish | 1.7.10 (2018) | MIT/GPL dual | yes | 4,285 / 26,324 | desktop menu (custom.js:68) | No: CSS already shows submenus on `:hover`/`:focus-within` (_navigation.css:415-433) and navigation.ts toggles classes; superfish adds a 600 ms delay and a third mechanism | CSS `:hover`/`:focus-within` + existing navigation.ts |
| Sticky-kit | 1.1.3 (2015) | MIT | yes | 3,203 / 13,878 | sticky sidebar (custom.js:194-206) | No | `position:sticky; top:var(--header-h)` |
| FitVids | 1.1 | WTFPL | yes (FSF GPL-compatible, listed readme.txt:67-70) | 2,083 / 9,390 | video wrappers (custom.js:171-179) | No | `aspect-ratio:16/9; width:100%` on `iframe[src*=youtube|vimeo]`; core `responsive-embeds` support |
| jQuery.scrollUp | 2.4.1 | MIT | yes | 2,048 / 7,847 | optional (knowx_scroll_top) | Optional | `<a href="#top">` + CSS `scroll-behavior:smooth` + small IntersectionObserver; native `scroll-behavior` needs `prefers-reduced-motion` guard |
| Font Awesome | 4.7.0 | Font SIL OFL 1.1, CSS MIT (readme.txt:41-47 says "Icons CC BY 4.0", which describes FA5+, not 4.7 - correct the attribution text) | yes | fontawesome.css 42,083 -> 28,815 min (6,992 gz); `assets/fonts` 1,085,661 B on disk | 11 glyphs (see s1c) + any user menu icons | Mostly no | 11 inline SVGs / CSS masks (~1.5 KB) or WP Dashicons (not on front end). If kept: ship woff2 only |
| normalize.css | 8.0.1 | MIT | yes | _reset.css | all | keep or replace with a modern reset (`@layer reset`) | |
| Bootstrap 3 grid | (copied) | MIT | yes | _grid.css 12 KB min | `.container`, `.row`, `.col-md-6` | No | CSS Grid / flex (the 3 used classes need ~10 lines) |
| TGM-Plugin-Activation | 2.6.1 | GPL-2.0+ | yes | external/class-tgm-plugin-activation.php | Kirki/WPP recommendation | Removed by the plan |
| Kirki | plugin (6.0.4) | GPL | n/a | not bundled | all customizer | being removed | |
Fonts (served): Open Sans + Montserrat from Google (external request; privacy + render-blocking). Montserrat unused. Local-font option `knowx_webfont_loader` exists (`site_load_google_font_locally`, a Kirki mod) and downloads to `assets/fonts` / `assets/css/src`.

FontAwesome files in `assets/fonts` (6): eot 165,742; svg 444,379; ttf 165,548; woff 98,024; woff2 77,160; FontAwesome.otf 134,808. Referenced by `@font-face`: eot, woff2, woff, ttf, svg. NOT referenced anywhere: FontAwesome.otf (desktop install file). Needed for browsers in `> 1%, last 2 versions`: woff2 (+ optional woff). eot (IE<=8), svg (iOS<=4) and ttf are obsolete. Savings from deleting eot/svg/ttf/otf = 910,477 B (84% of the folder; the theme zip includes them, `dist/knowx-1.7.5.zip`).

---------------------------------------------------------------------
## 6. BUILD OUTPUT DRIFT
---------------------------------------------------------------------
Build logic (`build-css.js`): every non-underscore `*.css` under `assets/css/src` (recursive) -> `assets/css/<rel>.min.css` via lightningcss `bundleAsync` (minify unless `--dev`, custom-media draft on, targets from `.browserslistrc` = `> 1%, last 2 versions`), preload snippet `_custom-media.css` injected after top-level imports (config.default.json:27), `~theme/` URL rewriting, plus block CSS from `assets/blocks/*` (directory does not exist). `.min.css` have no source maps (`*.map` is gitignored anyway). `build-js.js`: esbuild per-file to `<rel>.min.js` (see s4).

CSS entries -> built (all 14 present): admin/theme-settings, bbpress, comments, content, editor/editor-styles, fontawesome, front-page, global, knowx-amp, knowx-customizer, loaders, rtl, sidebar, widgets. Partials (`_*.css`) correctly have no output.
Drift test (script `scratchpad/audit/drift.py`): resolve `@import`s of each source, extract all class/id tokens, compare with the `.min.css`: 0 selectors missing in any built file; built-only tokens are decimal artefacts. `:root` var values in global.min.css equal `_custom-properties.css` (spot-checked). Timestamps: everything is `2026-10-10 10:22` (checkout time, no information) except `sidebar.min.css` at 10:16 - size 435 B matches the 707 B source minified. Verdict: CSS up to date.
JS entries -> built (13 of 13 present, plus `settingsFields.json` bundled into admin/index). Decoded inline source maps: every `sourcesContent` equals the current src byte for byte -> up to date.
Orphan built files (built file with no source): none. Source with no enqueue: `global.ts` -> `global.min.js` (s1).
BUILD DEFECT (serious): all 13 committed `.min.js` (and the shipped `dist/knowx-1.7.5.zip`, verified by `unzip -p`) contain inline base64 source maps because they were built without `NODE_ENV=production` (`build-js.js:108` `sourcemap: isProd ? false : 'inline'`). Frontend JS is 241,228 B (73,240 gz) instead of 52,650 B (17,020 gz) - 4.6x - plus leaks full original sources. `npm run bundle` is `cross-env NODE_ENV=production`, so the release process or the committed artefacts did not go through it. Add a post-build assertion (no `sourceMappingURL=data:` in release JS) and rebuild with prod before release.
Other build notes: `.stylelintrc` has no `ignoreFiles` for `fontawesome.css`; eslint `ignores` lists a non-existent `assets/js/libs/**`; `package.json` lists `@parcel/css`, `autoprefixer`, `cssnano`, `postcss-custom-media` (devDependencies unused by build-css.js, which uses lightningcss); `build-css.js` silently ignores a missing preload file; font URLs rely on `../fonts` / `../images` resolving relative to `assets/css/*.min.css` (works for top-level outputs; `editor/` and `admin/` outputs would break if they referenced images - they do not).

---------------------------------------------------------------------
## 7. WEIGHT / LIGHTWEIGHT OPPORTUNITIES
---------------------------------------------------------------------
Biggest files (shipped):
| file | bytes | note |
|---|---|---|
| fonts/fontawesome-webfont.svg | 444,379 | unused by modern browsers |
| js/isotope.pkgd.min.js | 136,465 | 100 KB is the inline map; 35 KB code |
| fonts/fontawesome-webfont.eot/.ttf/.otf | 165,742 / 165,548 / 134,808 | obsolete formats |
| fonts/fontawesome-webfont.woff / .woff2 | 98,024 / 77,160 | woff2 needed |
| css/content.min.css | 47,925 | 16 KB of it is FSE block CSS (~9.1 KB min) + forms/tables + grid-free |
| css/global.min.css | 43,757 | 12 KB Bootstrap grid, 4.9 KB KB layouts, 5 KB utilities+nav legacy |
| css/editor/editor-styles.min.css | 41,513 | includes full grid + all blocks |
| js/navigation.min.js | 35,520 | 31 KB inline map |
| css/fontawesome.min.css | 28,815 | 675 glyph classes for 11 used |
| js/superfish.min.js | 26,324 | 22 KB inline map |
| css/rtl.min.css | 18,147 | repeats reset/grid/typography |

Unused / removable CSS (estimates from minified-equivalent measurement):
1. `_grid.css` ~12.0 KB min (1.8 KB gz) for 3 classes (global + rtl + editor = ~36 KB of repeated bytes across sheets).
2. FSE-only block rules `_blocks.css:898-1598` ~9.1 KB min (1.5 KB gz), loaded on content pages + editor.
3. FontAwesome CSS 28.8 KB (7.0 KB gz) -> ~1.5 KB of SVG/mask CSS; plus 77 KB woff2 request removed from every page.
4. `_utility.css` (219 lines, unused utility classes) ~2 KB in global.min.css.
5. KB layout blocks `global.css:330-630` ~4.9 KB min only for 2 templates -> move to a `knowledge-base.css` printed by those templates.
6. Plugin-specific: BuddyPress (`.bp-user`, `.single-item.groups`, `.group-create`, `#user-profile-menu`, `.bp-header-submenu`, `.user-profile-menu`, `bp-icon-wrap` JS) ~20 rules; WooCommerce (`.cart*`, `.woo-*-sidebar`, `select.orderby`, `#pa_color/#pa_size` in rtl.css) ~15 rules; Elementor (global.css:32-41, loaders.css:215-218); bbPress sheet (3 KB, loaded sitewide when plugin active); Mailchimp `.mc4wp-form` (bbpress.css:111,170); WP Popular Posts `.wpp-meta` (global.css:516); Collapsing Categories `.widget_collapscat` ~740 B x2 (widgets + amp, with 5 `!important`); gamification `.type-grade/level/quests/badges` (content.css:257-266); Jetpack infinite scroll (content.css:615-628); front-end TinyMCE/media modal overrides global.css:266-327 (1.2 KB min); `canvas !important` hammer; `p:empty` no-op rules.
7. Duplicates: knowx-amp.css copy of widgets (~3.8 KB min), editor/global/forms button triplication, rtl.css re-imports (~13 KB min of repeated reset/typography/grid).
8. `loaders.css` vendor-prefixed keyframes/animation (about 40% of file) plus the whole sheet when the loader is off (default) -> print only when `site_loader=='1'`.
9. Dead stylesheet `front-page.min.css` + `knowx-customizer.min.css` after Kirki + `admin/theme-settings.min.css`/`admin/index.min.js` (dead admin UI).
Render-blocking: `knowx-global`, `knowx-site-loader`, `knowx-load-fontawesome` (3 requests in head, 74 KB), `knowx-fonts` (external Google CSS in head, then font files from gstatic), `knowx-rtl`/`knowx-bbpress`/`knowx-amp` in head; plus in-body `<link rel=stylesheet>` for content/sidebar/widgets (mid-body render-blocking by design, 52 KB). FA font is `font-display`-less (`@font-face` at fontawesome.css:7 has no `font-display`) so icons render invisible until loaded (FOIT). The site-loader overlay adds a full-screen opaque blocker until `window.load` (hides content, hurts LCP/CLS if JS fails). Scripts are footer + async but without dependency control (race, s4).
Prefer: one consolidated `global.css` of ~15 KB min after items 1-6, FA replaced by SVG, Google Fonts self-hosted with `font-display:swap` and woff2 preload, JS reduced to navigation.ts + a ~1 KB header/scroll script (no jQuery) -> removes jQuery as a dependency on the front end (WP itself still ships it for others).

Image inventory (`assets/images`, 172 KB total, 84 KB of it `src/` duplicates): 9 PNG layout pickers (700-1,700 B each) referenced ONLY by Kirki radio-image fields (General_Fields.php:21-22, Blog_Fields.php:21-23, Sidebar_Fields.php); 9 matching `.webp` files unreferenced; `placeholder.svg` unreferenced; `arrow-down.svg` (_forms.css:157), `search.svg` (global.css:175,392), `right-arrow-angle.svg` (global.css:505) are the only front-end images (200-413 B each). After Kirki removal the 18 picker images are dead unless the new Customizer_Framework radio-image controls reuse them. `screenshot.png` 90,814 B (wp.org requires 1200x900 <=? ok).

Other weight/safety items: `FontAwesome.otf`, `*.svg` font; `dist/knowx-1.7.5.zip` is gitignored but present; `node_modules` (3.4 MB+) and `vendor/` are gitignored; Lighthouse CI config present (.lighthouserc.cjs).

---------------------------------------------------------------------
## 8. TOP FINDINGS (priority order)
---------------------------------------------------------------------
1. Release JS ships inline source maps: 241 KB vs 52.6 KB (4.6x), verified in `assets/js/*.min.js` and `dist/knowx-1.7.5.zip` (build-js.js:108; s6).
2. All theme scripts are `async` with no dependency ordering and no jQuery/superfish/etc. deps (Scripts/Component.php:178-240, Base_Support:150); `custom.js` can run before superfish and throw inside `$(document).ready`, killing mobile nav, fitVids and table attrs (custom.js:66-87, 239-252).
3. Kirki-gated colour pipeline: Dynamic_Style (24 colour mods) and all `output` rules (typography, hero, support, footer bg, container width) exist only with Kirki active (Dynamic_Style:35, Kirki_Option:32); static fallbacks disagree with Kirki defaults (line-height 1.4 vs 1.6, hero #21b4db vs #03a9f4, sub-header text #505050/#111 vs #333, site title #333332 vs #333).
4. No dark-mode readiness and no focus-visible: 143 hard-coded colour literals in 21 files (60 distinct), ~35 `#fff` surfaces, zero `:focus-visible`, `.wp-element-button`/`select` outline removed with no replacement (_blocks-style.css:38, _forms.css:144), zero `prefers-reduced-motion` (infinite loader animation, jQuery animations).
5. `$(window).load()` (custom.js:264) is removed in jQuery 3 and only works through jquery-migrate; loader hide, sticky sidebar, isotope all hang off it. Loader overlay never hides if that handler fails.
6. Layout bloat on every page: Bootstrap-3 grid (12 KB min) for 3 classes; FontAwesome 28.8 KB + 77 KB woff2 for 11 glyphs; FSE-block CSS (9 KB) unused by a classic theme; 6 FA font files (910 KB obsolete) in the zip.
7. Dead/Kirki-only assets after removal: customizer-controls.js, knowx-customizer.css, admin/knowx-customizer.js (already never enqueued, Options:68 depends on admin_enqueue_scripts which customize.php does not fire), 9 PNG pickers, EZ_Customizer; dead admin React page loaded on every wp-admin screen (Options/Component.php:40,60-98).
8. RTL is structurally wrong: rtl.min.css (18 KB) is enqueued in head (loads before in-body content.min.css so equal-specificity RTL overrides lose), re-imports reset/grid/typography, covers ~30 of ~170 physical-property declarations; no logical properties anywhere; `body{text-align:right !important}` (rtl.css:24).
9. Accessibility defects in markup+JS: duplicate `#primary-menu` IDs (navigation.php:46-55), icon-only unnamed search toggle and scrollUp link, nav labels inverted (navigation.ts:431-434,458), `aria-expanded` desync with custom.js body-class toggle, no focus trap, search form has no `<label>` and a control named `submit` (searchform.php:12-14), 55em vs 768px breakpoint mismatch blocking parent links 768-880px.
10. Undefined/misleading tokens and version bugs: `--border-color-dark`, `--color-link-active` undefined (content.css:512,583,608), `--font-size-small` larger than regular (custom-properties:81), `--global-font-family` falls back to `serif`, three competing palettes (theme.json slugs vs `theme-*` CSS/PHP), `is_bbpress` function_exists check loads bbPress CSS sitewide (Styles:121), missing version args on bbpress/rtl/amp, `login.php` uses undefined vars and `esc_url` for a colour, Google Fonts URL contains `900&display=swap` inside a family variant and an unused Montserrat request.
