# KnowX 1.7.5 - Templates audit (B). READ-ONLY. Theme root: .../themes/knowx
Line numbers are from the files as read on 2026-10-10. "KB1" = page-knowledgebase-one.php, "KB2" = page-knowledgebase-two.php.

## 1. KNOWLEDGE BASE TEMPLATES

### 1.1 Queries inventory
| # | File:line | Call | Args | Bounded? | Notes |
|---|---|---|---|---|---|
| Q1 | KB1:82 | get_categories | parent=0, hide_empty=0, exclude=$exclude, orderby=name, order=asc | NO (no `number`) | 1 query; top-level only; every top-level cat rendered as one <ul><h2>. 500 top-level cats = 500 <ul>, no pagination. Empty cats shown (hide_empty=0). |
| Q2 | KB1:97 | wpp_get_mostpopular | limit=5 | yes | Plugin call. WPP default post_type is "post,page" so Pages leak in; no `cat` arg so exclusions ignored. WPP prints its own wrapper/empty message. |
| Q3 | KB1:116 | get_posts | numberposts=5 | yes | No category exclusion, no post_type/ignore_sticky args (default post). get_posts => no_found_rows, cheap. |
| Q4 | KB2:59 | get_categories | hide_empty=0, exclude, orderby=name, order=asc | NO | ALL categories (flat, children included), no `number`. |
| Q5 | KB2:99 | get_posts (inside foreach cat) | post_type=post, tax_query term_id include_children=false, posts_per_page = knowx_posts or -1, orderby name|date | NO by default (-1) | N queries for N categories. |
| Q6 | KB2:65-66 | category_description() x2 per cat | - | - | Called twice per cat (condition + output); filters run twice. |

Per-item template-tag calls (get_the_title/get_permalink/get_category_link) read primed caches (get_posts primes post + term caches) so no extra SQL in Q5 loop; get_category_link is a pure rewrite call.

### 1.2 Behaviour at scale
- KB1 at 500 categories: 1 SQL for cats, 1 for recent, WPP (own cached query). Output = 500 single-item <ul>. Cheap SQL, unusable UI (no search-within, no cap, no "show more"). Zero posts listed per category (KB1 shows headings only - see 1.4g).
- KB2 at 2000 posts / 500 cats: 1 + 500 get_posts queries (each with tax_query JOIN on term_relationships, ORDER BY post_date/post_name, LIMIT -1 by default) + up to ~2000+ <li>. Posts in several categories are repeated. Page easily >1 MB HTML and 500+ queries; no object-cache/transient wrapper. Fails big-site checklist items 1 (pagination), 3 (N+1 - per-category query), 10 (cache).
- KB2 `orderby => 'name'` (line 77) sorts by post_name (slug), not title. Fix: `'orderby' => 'title'`.
- KB2 `posts_per_page`: `get_theme_mod('knowx_posts')` (line 70) - '0' is falsy => -1 (unlimited); negative numbers accepted by Kirki Number field => unlimited. Cast with `max(1,(int))`.
- KB2 parent-category posts: `include_children=false` (line 91), so a parent with all posts in children shows only a header (+ "View All" if enabled) - intentional but undocumented.

### 1.3 Exclusion handling
- KB1 resolves exclusion at 67-74: per-page meta `knowx-exclude-cats` first, else theme_mod `knowx_exclude_posts`. KB2 at 45-52: meta first, else theme_mod `knowx_exclude` (DIFFERENT key, same label "Exclude category by ID": Kirki fields Knowledge_Base_Fields.php:131 and :302). Setting one never affects the other template.
- Value is passed through `esc_attr()` (KB1:69,71; KB2:47,49) - wrong function for a query arg (HTML-escape instead of intval list). Should be: `wp_parse_id_list( $raw )`.
- 'exclude' only removes the listed term; its CHILD categories still appear in KB2 (flat list). Use `exclude_tree` for hierarchical exclusion, or filter children.
- Per-page meta UI is NOT REGISTERED: `knowx_metabox_callback()` (inc/extra.php:223) is defined but `add_meta_box` is called nowhere in the theme (grep: only the definition). The save handler `knowx_save_meta` (extra.php:239-250) therefore only fires if the meta is posted by some other form. Result: "knowx-exclude-cats" is unreachable from the editor except via the Custom Fields panel. Also `knowx_save_meta` uses `get_post_type()` (global) not `$post_id`, `wp_verify_nonce($_POST[...])` without `wp_unslash`/`sanitize_text_field`, and no `wp_is_post_revision` guard.
- Recent Articles (KB1:116) honours NO exclusions. Popular Articles (KB1:97) honours NO exclusions. Neither list is category-scoped, so excluded categories' posts appear in both. KB2 has no Popular/Recent.

### 1.4 Verification of reported bugs
(a) CONFIRMED. KB1:116 `get_posts( array( 'numberposts' => 5 ) )` ignores `$exclude` (computed at line 67-74 but only used by Q1). Fix:
```php
$exclude_ids = wp_parse_id_list( $exclude );
$related = get_posts( array(
  'numberposts'         => 5,
  'category__not_in'    => $exclude_ids,   // note: also hides posts in excluded child cats only if they are listed
  'ignore_sticky_posts' => true,
  'no_found_rows'       => true,
) );
```
For WPP pass `'cat' => implode( ',', array_map( fn( $i ) => '-' . $i, $exclude_ids ) )` and `'post_type' => 'post'`.
(b) CONFIRMED. KB1:121-123 opens `<ul>`/`<li>`/`</ul>` inside the foreach (lines 118-125) => N one-item lists (invalid semantics, screen reader announces "list 1 item" x5). Fix: open `<ul>` before the foreach (line 117), close after (line 126).
(c) CONFIRMED. KB2:106: `esc_html( 'View All &raquo;', 'knowx' )` - second arg is ignored by esc_html(), string is NOT translatable, and `&raquo;` is double-handled (esc_html keeps existing entities, so it renders OK but is untranslatable and un-extractable). Fix: `esc_html__( 'View All &raquo;', 'knowx' )` (better: drop entity, add arrow via CSS ::after for RTL).
(d) CONFIRMED. KB1:85 and KB2:62 echo `get_category_link()` (unescaped URL in href), `$category->name` in `title="..."` (unescaped attribute) and as link text (unescaped HTML) under a blanket `// phpcs:ignore`. Term names may contain `"` or HTML-like text (names stored encoded via sanitize_term but `get_categories` returns 'display'?-raw context, `"` is stored as `&quot;`; risk is low but it is a Theme Review hard fail pattern). Fix: `esc_url( get_category_link( $category ) )`, `esc_attr( $category->name )`, `esc_html( $category->name )`; also `title` attr is redundant (duplicates link text, a11y noise) - remove.
(e) CONFIRMED. KB1:149 `'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.'` is the fallback support text. Also KB1:158 `<a href="<?php esc_url( '#' ); ?>">` has NO `echo` => empty href (links to current page). Also Kirki placeholder text (Knowledge_Base_Fields.php:~176) says "Can not find what you are looking for?" while template fallback (KB1:142) says "Can't find what you're looking for?" - mismatch. Fix: real helpful default copy ("Still need help? Browse the categories above or search our articles.") and button `href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"`, or hide button when no URL configured.
(f) CONFIRMED. KB1:104-108 prints "No data found." when `wpp_get_mostpopular` missing. On Playground/fresh install this is what is shown. Better: fall back to native popular list: `get_posts(['meta_key'=>'post_views_count','orderby'=>'meta_value_num','numberposts'=>5])` (theme already writes `post_views_count`, extra.php:257 setPostViews) or `comment_count` ordering; and if still empty hide the whole column. Note meta_key orderby is an unindexed postmeta sort - acceptable at LIMIT 5 only with meta_key index (postmeta has meta_key index); at 2000+ posts OK.
(g) CONFIRMED. KB1:76 `'parent' => 0`; KB2:53-58 no parent arg. KB1 additionally lists headings only (no posts), KB2 lists posts. Inconsistent by design but undocumented; Kirki labels do not mention it.

### 1.5 Further KB defects
KB1
- 28 `</div><!--close .container-->` closes the container opened by header.php:48; then 63 opens a new .container, 134 closes it, 137 opens another, 161 `</div>` (commented ".knowx-support-section") actually closes the .container and 162 `</div>` (commented ".container") closes the support section - comments are swapped but markup balanced. footer.php:17-19 then closes ANOTHER `</div>` (body class check fails for KB template) => one stray `</div>` closing #page early, footer.php:47 closes a non-existent div. Invalid DOM (footer rendered outside #page).
- 29 `$post->ID` relies on global; 29-34 builds `<div style="background-image: url(' ...url... ')">` with padding spaces inside the quotes; no alt/srcset; full-size image. Use `get_the_post_thumbnail_url( null, 'full' )` and `esc_url`.
- 31/37/45/140/147/155: theme_mods echoed through esc_html - OK escaping, but hero content `echo esc_html( $hero_content )` strips line breaks/links (field is Textarea) - use `wpautop( esc_html() )` or `wp_kses_post`.
- 36-46: heading structure: hero `<h1>` is OUTSIDE `<main>` (hero precedes `<main id="primary">` at 61). Skip link target #primary skips the hero h1 and search form. Category headings are `<h2>`, popular/recent `<h3>`, support `<h2>` (OK order).
- Page content (`the_content()`) is never output in KB1 - editor content ignored; no `have_posts()/the_post()` loop; `the_excerpt()` at 49 used outside a loop (works only through global $post).
- 53 `get_search_form()` -> duplicate id="s"/"searchform" if site search icon/sub-header also render (see searchform).
- 94-129 no empty state for Recent list; if no posts the "Recent Articles" heading renders empty.
- 118 `foreach ( $related as $post )` overwrites global $post (WP.GlobalVariablesOverride) - use `$recent_post` with setup_postdata.
- 122 `title="<?php the_title(); ?>"` unescaped attribute; use `the_title_attribute()`; redundant title anyway.
- 121 trailing whitespace `<ul> `.
- 16, 14 `knowx()->print_styles('knowx-content')`: KB1 does not print sidebar/widgets styles (fine) but KB-specific CSS (.knowx-support-section etc.) must come from global.
- 67 `get_post_meta( get_the_ID(), ... )` OK.
KB2
- 20 `knowx_sub_header` action (breadcrumbs+search) shown; KB1 omits it (hero has its own search).
- 29 `get_theme_mod('knowx_page_title') == 'yes'` - NO default arg. Kirki default is 'yes' (Knowledge_Base_Fields.php:263+) but without Kirki / before first save get_theme_mod returns false => title hidden. After Kirki removal this flips behaviour; pass default `'yes'`.
- 64 `knowx_cat_description == 'yes'`, 105 `knowx_view_all == 'yes'` (Kirki default 'no'): without Kirki false == 'yes' false => fine (same as default).
- 43-112 markup: `<ul class="cat-list">` contains `<li class="cat-name"><h2>` then raw `<li class="post-name">` siblings - valid, but the cat-description `<div>` (line 66) is a direct child of `<ul>` => INVALID HTML (div inside ul). Wrap in `<li>`.
- 26-41 `page_header` + `entry-page` renders page title+content+comments_template, THEN categories - OK.
- 99 `get_posts` per category with `suppress_filters` default true: breaks WPML/Polylang language filtering and any `pre_get_posts` visibility rules. Add `'suppress_filters' => false` only if multilingual is a goal.
- 102 `get_the_title( $single_post->ID )` esc_attr/esc_html applied - OK. No `<li>` empty state: a category with 0 posts shows only its heading.
- 106 `esc_html( 'View All &raquo;', 'knowx' )` (see c). `title` attr duplicates text.
- Missing empty state for 0 categories (can't happen: Uncategorized exists unless deleted/renamed; with all excluded the container is empty).
- Fix proposal (scale): cap with a theme setting (`number` on get_categories, default 20 + "load more"/pagination), fetch all posts in ONE query (`'category__in' => $term_ids`, `posts_per_page => -1`, `fields => 'ids'`... then group in PHP by `wp_get_object_terms`/`get_object_term_cache`) or use a transient keyed by (page ID, settings hash, `wp_cache_get_last_changed('posts')`), invalidated on `save_post`/`edited_category`.

### 1.6 Strings hard-coded / lorem
- KB1:149 lorem ipsum (e).
- KB1:106 "No data found." (translatable, ok but poor UX).
- KB1:142 default heading "Can't find what you're looking for?" translatable.
- KB1:158 "Contact Support" button with dead href.
- Footer fallback copyright (extra.php ~150) is a hard-coded English literal 'Copyright © [current_year] [site_title] | Powered by [theme_author]' - not translatable (outside scope but visible on KB pages).

## 2. ESCAPING / SANITIZATION / I18N (cross-file)
| File:line | Issue |
|---|---|
| page-knowledgebase-one.php:85, two.php:62 | unescaped `get_category_link`, `$category->name` x2 (phpcs:ignore blanket) |
| KB1:31 | `esc_url()` OK but padded with spaces |
| KB1:122 | `the_title()` in attribute unescaped; `the_permalink()` OK (core escapes) |
| KB1:158, searchform.php:12 | `esc_url('#')` without echo (no output) - dead call |
| KB2:106 | esc_html with text domain arg (untranslatable) |
| header.php:29 | `echo esc_url('#primary')` - esc_url on fragment returns '#primary' OK but pointless; fine |
| comments.php:39,47 | `get_the_title()` unescaped inside span with phpcs:ignore; use `esc_html( get_the_title() )` |
| comments.php:44-45 | `esc_html( _nx() )` fine; phpcs-ignore comment on its own line (45) is misplaced |
| comments.php:36-40 | `$comment_count` int compare `1 ===` OK |
| template-parts/content/entry_content.php:15-27, entry-full-width.php:14-27, content-bbpress.php:14-27, entry_actions.php:17-30,36-48 | `sprintf( wp_kses( __('...%s...') ), get_the_title() )` - the title is injected AFTER kses and unescaped (more-link text is output raw). Use `esc_html( get_the_title() )` as the %s arg. |
| template-parts/content/entry_meta.php:78 | `echo get_avatar(...)` unescaped (core returns safe HTML; acceptable but not annotated). `$size = '26'` assignment inside call (WPCS: assignment in argument). get_avatar with author EMAIL: use `get_avatar( get_the_author_meta('ID'), 26 )` (avoids leaking email into args/filters). |
| entry_meta.php:77,79,81,98,100,117-118 | translators comments misplaced: 77 precedes `echo get_avatar` not the `_x`; line 79 `_x('By %s')` has NO comment immediately above (comment must be on the line directly before the gettext call - WPCS reports MissingTranslatorsComment). 81 correct for 82. 97 and 100 OK. 117 comment is above `esc_html_x( ' %s' ...)` but string ' %s' is a leading-space placeholder-only string: not translatable meaningfully (translators cannot move anything). Replace with plain `echo $time_string` after esc. |
| entry_meta.php:11 | `$post = get_post();` global override (phpcs flag) |
| entry_taxonomies.php:44 | `__( '%s:', 'knowx' )` placeholder-only string; translators comment OK but untranslatable value (taxonomy label). |
| entry_taxonomies.php:10 | `$post` used from global (works via load_template) - add explicit `$post = get_post()` |
| entry_taxonomies.php:21 | `foreach ( $taxonomies as $taxo )` - global-override annotation unnecessary |
| entry-attachment.php:49 | `$class =` assignment as argument; echo of `_navigation_markup` with phpcs:ignore (should wrap `wp_kses_post`) |
| error-404.php:45 | translators comment OK; `convert_smilies(':)')` output into string then passed through widget args - OK |
| page_header.php:14,error.php:44,error-offline.php:14 | HTML entities (`&rsquo;`, `&#8217;`) inside translatable strings: translators see entities; prefer real ’ characters |
| page_header.php:30,52,53 | `single_post_title()`, `the_archive_title/description` echo core-filtered values; acceptable |
| page_header.php:41-42 | `get_search_query()` escaped by core - OK |
| footer/info.php:13 | `echo knowx_footer_custom_text()` - returns raw theme_mod `site_copyright_text` (user text, str_replace placeholders) with blanket phpcs:ignore. Must be `wp_kses_post( ... )` at output. Also extra.php `esc_html( 'KnowX WordPress Theme', 'knowx' )` misuse (not translatable). |
| navigation.php:43,46,54 | `echo $amp_state` (raw script block), `$amp_attrs` - static literals; acceptable but annotated; amp-only. |
| branding.php:34 | `echo esc_html( $knowx_description )` OK (misleading phpcs comment) |
| searchform.php:9-11 | reads `$_GET['s']` directly (empty() only; no unslash/sanitize). Output goes via `get_search_query()` + esc_attr - safe but use `get_search_query()` alone / `is_search()`. Placeholder is the previous query text instead of a label. |
| single.php:42 | `setPostViews( get_the_ID() )` writes post meta on every pageview (see 5 / 7). |
| Text domains | All gettext calls in scope use 'knowx'. No wrong domains found. Strings w/o gettext: none in scope, except searchform `type="text"`/ARIA none; `fa fa-close` icon link has no text. |
| Untranslated | extra.php fallback copyright string; "Menu" ok; navigation.php:45 aria-label static "Open menu" (translatable, but state never toggles to "Close menu") |

## 3. ACCESSIBILITY
- Skip link: header.php:29 present, target `#primary` exists in all templates, but `full-width.php` etc. fine; KB1 hero (h1+search) sits before `#primary` so is skipped. 404/500/offline: `#primary` present.
- Landmarks: `<header id="masthead">` OK (inside .container, not role=banner issue). `<main id="primary">` present in all main templates. `<aside id="secondary">` is rendered TWICE with the same id in `both` sidebar mode (archive.php:31 & :78, index.php:34 & :82, page.php:31 & 63, single.php:31 & 79, bbpress.php:30 & 59) => duplicate ID and two unlabelled `complementary` landmarks (needs aria-label "Left sidebar"/"Right sidebar" or unique ids). footer.php:32 `role="complementary"` on footer widget div - redundant/incorrect (use `<aside aria-label>`/no role). footer.php:22 `<footer id="colophon">` has no role needed.
- Menus: navigation.php:38 `<nav aria-label="Main menu">` good. Two `display_primary_nav_menu( array('menu_id'=>'primary-menu') )` calls (56 and 60) => DUPLICATE id="primary-menu"; button `aria-controls="primary-menu"` (45) ambiguous. Menu toggle `aria-expanded="false"` static (JS must update) and `aria-label="Open menu"` never changes. Close control is `<a href="#" class="menu-close">` (54) - should be `<button type="button">`; `href="#"` jumps to top. Mobile heading `<h3 class="menu-title">` inside nav creates out-of-order heading (h3 before any h2) - use `<p>`/`<span>`. Duplicate full menu in DOM (mobile + desktop) doubles tab stops / SR content.
- Search: searchform.php: no `<label>` (placeholder only), `type="text"` not `type="search"`, no `role="search"`, id="s"/id="searchform" duplicated when multiple forms render (sub header + hero + overlay), icon link `<a href="<?php esc_url('#') ?>" class="search-icon"><span class="fa fa-close">` has empty href (no echo), no accessible name, focusable, `name="submit"` shadows form.submit(). Submit value "Go" is weak. Placeholder is the previous query (not a label) and low-contrast risk.
- extra.php (outside scope but rendered): `knowx_site_menu_icon` `<a href="#" id="overlay-search" class="search-icon"><span class="fa fa-search">` icon-only link without label.
- Headings: single/page: h1 from page_header.php:58 (`the_title('<h1 class="entry-title">')`); entry_header.php:22 skips title when singular (OK, no dup). Archive: h1 via the_archive_title, post titles h2. Home (blog) front page: index.php:46 skips page_header on front page => h1 is the site title (branding.php:20) - OK. Blog posts page (not front): page_header 26 h1 single_post_title. Comments: `<h2 class="comments-title">` OK. Widgets in 404 use h2. KB1: h1(hero) -> h2 cats -> h3 popular/recent -> h2 support: OK order but hero outside main. KB2: page_header h1 (optional) + h2 per category.
- Page with `knowx_page_title=no` in KB2: NO h1 at all (page_header suppressed 29-31 and entry-page does not print title) -> missing h1.
- Post thumbnails: entry_thumbnail.php:32 `<a class="post-thumbnail" aria-hidden="true">` wraps a focusable link: needs `tabindex="-1"`; alt is set to the post title (40-44, 51-55) on an aria-hidden link (fine) but the singular thumbnail (27) uses attachment alt via core - OK.
- Pagination: `the_posts_pagination` with screen_reader_text - OK. Comments navigation via core - OK. Post navigation via core (`the_post_navigation`) - OK.
- Lists: KB1 recent one-item <ul>s (1.4b); KB2 div inside ul (1.5); KB2 `<ul class="cat-list">` containing `<li><h2>` OK.
- entry_content: `<p><?php the_excerpt() ?></p>` (30-32) - the_excerpt already wraps in <p> => nested <p> (invalid).
- Focus order: mobile menu markup duplicates; `.mobile-menu-close` empty <div> in footer.php:49 (click target without role).
- Images alt: header image `the_header_image_tag()` alt empty by default (acceptable decorative); logo via the_custom_logo (core alt). KB1 hero background-image - decorative.
- Language: `<html class="no-js">` ok; viewport meta `initial-scale=1.0` (no user-scalable=no; 1.7.5 changelog "pinch-to-zoom works" consistent).

## 4. WORDPRESS.ORG THEME REVIEW RISKS
BLOCKERS
1. `wp_body_open()` is NEVER called. header.php:27 calls `knowx_wp_body_open()` whose shim (inc/wordpress-shims.php:17-18) only does `do_action( 'knowx_wp_body_open' )`; nothing hooks it, and core `wp_body_open` never fires. Required since WP 5.2. Fix: `wp_body_open();` in header.php directly right after `<body>`; remove the shim. Also order: `knowx_site_loader()` (header.php:26) prints markup BEFORE body_open.
2. Plugin dependency via TGMPA: external/require_plugins.php + external/class-tgm-plugin-activation.php loaded at functions.php:26-27 (Kirki, Collapsing Categories, WordPress Popular Posts - `required => false`). Allowed for recommended-only, but the migration goal (remove Kirki) should drop Kirki; theme options built on a plugin are rejected ("Themes must not require a plugin to function"). Currently with Kirki missing, `get_theme_mod(...)` defaults are lost (see section 5).
3. Plugin-territory features: view counter `setPostViews()` (inc/extra.php:257, called single.php:42) writes post meta on front-end and counts every hit (bots, admins, cached pages); custom login form (inc/login.php, functions.php:90); popular-posts dependence. Reviewers flag view counting and login customizing as plugin territory. Remove or move to a plugin.
4. functions.php:12-13 minimum versions (WP 4.5, PHP 7.0) contradict style.css `Requires at least: 4.8` (style.css:8) and `Requires PHP: 7.4.0` (style.css:10). Theme.json v3 requires WP 6.6+ (theme.json:3); `Requires at least` must be >= the real minimum. functions.php:73-75 `include_once ABSPATH . 'wp-admin/includes/plugin.php'` on front end (unneeded: `is_plugin_active` never used in scope).
5. functions.php:5 docblock says "parseable by PHP 5.2" while using namespaces/arrow-free but `: bool` return types in optional/dev file.
6. optional/dev/dev-proxy-livereload.php:37,45 enqueues `//localhost:35729/livereload.js` driven by request headers/cookie `knowx_dev` - a remote-controllable script injection vector on production if the file is ever loaded. Verify no `require` loads it (none found in functions.php/inc), and EXCLUDE `optional/` from the release zip. Quote: `wp_enqueue_script( 'knowx-livereload', '//localhost:35729/livereload.js?snipver=1', array(), null, false );`
7. Directory contents shipped: build tooling (node_modules, vendor, tests, dist, plans, scripts, composer.lock, package-lock.json, rector.php, phpstan*.neon, playwright.config.ts...) - Theme Check rejects dev files; ensure release zip excludes them.
8. theme.json: `"$schema": "https://schemas.wp.org/trunk/theme.json"`, `"version": 3` OK for WP 6.6+. `customTemplates` (theme.json:304-343: blank, no-title, full-width, blog-alternative, 404) and `templateParts` (344-365: header, footer, comments, post-meta) reference files that DO NOT EXIST (theme has no templates/ or parts/ dir; this is a classic theme). Dead/misleading and reviewers flag it; remove for a classic theme or provide the block templates. `settings.spacing.blockGap: true` (82) valid. `settings.typography.fontSizes` lacks `fluid` (ok). `styles.blocks.core/list.css` (189) requires WP 6.2+ (custom css per block; allowed). Block style keys such as `core/post-title`, `core/comment-*` are block-theme blocks (irrelevant without templates but valid). `appearanceTools: true` (5) enables lots of UI. Palette (7-78) 14 colors including duplicated semantic (Black #1c2833 vs Contrast #000000; Blue/Primary). Slug `custom-daylight`/`custom-sun` fine.
9. style.css header: `Tags: translation-ready` only (style.css:14). `translation-ready` is an allowed *feature* tag; at least one *subject* tag is NOT required but WP.org recommends feature+subject; allowed set OK, count (1) < 15 max. Missing recommended `Template`... n/a. `Tested up to: 6.8.2` (style.css:9, readme.txt:5): stale vs current WP (7.x as of 2026-10) - update after testing (and "6.8.2" format is accepted). `Requires PHP: 7.4.0` ok. `License: GNU General Public License v3.0 (or later)` (style.css:11) - WP.org wants the name to be a recognised SPDX-ish string, e.g. "GNU General Public License v2 or later" / "GPL-3.0-or-later"; the long form is accepted but "v3.0 (or later)" with parentheses sometimes fails the license check - recommend `License: GNU General Public License v3 or later`. Description ("Free theme for a knowledge base.") fine. `Theme Name`, `Author`, `Version`, `Text Domain` present. Author lists two people with comma and no spaces: "wbcomdesigns,vapvarun".
10. readme.txt: sections present (Description, Copyright, Customizations, Changelog); missing `== Frequently Asked Questions ==`, `== Upgrade Notice ==`, `== Resources ==` list of images/fonts per theme-review policy (screenshot.png license, assets/images/*.png/webp origin not declared). `Stable tag: trunk` (readme.txt:7) is invalid for themes; use version. `Contributors: wbcomdesigns`. Description is one line. Changelog 1.7.5 uses the house format (ok); older entries differ. readme.txt:51 contains an em dash in the scrollup credit (third-party text, fine). Bundled libs declared: Font Awesome 4 (OFL fonts, CC BY 4.0 icons - OK), scrollup MIT, Superfish MIT/GPL, doubleTapToGo MIT, sticky-kit MIT, FitVids WTFPL (GPL-compatible), Isotope "GPLv3 for open source use" (OK for GPL theme but must state it's v3 only - GPLv3 vs "GPL-3.0-or-later" fine; Isotope 3.0.6 is MIT-or-commercial in the repo's licence file since 3.0.x? verify: Isotope 3.x is GPLv3 free for open source - mention only), TGM-Plugin-Activation GPL-2.0+. Not declared: `assets/images/*` (own?), `assets/fonts/*.otf` etc., webfont loader, EZ_Customizer JSON, `placeholder.svg`, screenshot source. screenshot.png is 1200x900 (OK, <= 1 MB at ~91 KB).
11. Required template tags check: wp_head (header.php:22) yes; wp_footer (footer.php:50) yes; body_class (header.php:25) yes; post_class (entry*.php) yes; `wp_link_pages` (entry_content.php:34, entry-full-width.php:29, entry-page.php:34, content-bbpress.php:29) yes; comment_form (comments.php:65) yes; comments_template (entry.php:40, entry-page.php:42, entry-attachment.php:55) yes; pagination `the_posts_pagination` (pagination.php:10) yes; `the_post_navigation` (entry.php:30) yes; `wp_body_open` NO (blocker); `language_attributes` yes; `bloginfo('charset')` yes; title-tag support (Base_Support/Component.php:57) yes; `add_editor_style`/editor-styles yes; `wp_nav_menu` via Nav_Menus; `the_custom_logo` yes; `the_privacy_policy_link` yes (info.php:17); `$content_width` not verified in scope (check inc). `get_search_form` yes.
12. Hard-coded links (policy: only one credit link allowed, must be to theme/author): footer credit built in extra.php (wbcomdesigns.com) filterable - acceptable; header.php:20 gmpg profile link OK; error-404/500 none. `https://codex.wordpress.org` only in comments.
13. Forbidden/flagged functions in scope: none of `eval/base64/file_get_contents` in templates. Outside scope but render-time: inc/Webfont/class-knowx-webfont-loader.php:265 `wp_remote_get` + :634 `wp_schedule_event` (phpcs-ignored PluginTerritory), inc/Fonts/Component.php:351,418 remote font download + filesystem write, inc/EZ_Customizer/Component.php:86 `wp_remote_get( get_theme_file_uri() . '/inc/EZ_Customizer/themeCustomizeSettings.json' )` (loopback HTTP to self at runtime; should be `file_get_contents`/`wp_json_file_decode`).
14. Escaping blanket `phpcs:ignore` at KB1:85/KB2:62 (see 1.4d) = automatic Theme Check failure.
15. Customizer: all options live in Kirki; WP.org requires sanitize_callback on every setting - Kirki supplies it, so replacing Kirki with core Customizer API requires `sanitize_callback` everywhere (Theme Review blocker if forgotten).
16. Accessibility-ready tag not claimed; do not add it unless 3. is fixed.
17. Child-theme/Elementor: `elementor_theme_do_location` checks (404.php:20, archive.php:24, footer.php:23, header.php:32, single.php:24) are guarded with function_exists - OK.
18. `functions.php:16` uses `$GLOBALS['wp_version']`, `phpversion()` fine. `require` of `external/kirki-utils.php` (defines `knowx_defaults`) - the name is Kirki-agnostic but file lives under "external" and will be deleted with Kirki: MOVE `knowx_defaults()` before deleting.

## 5. THEME-MOD COUPLING (reads inside scope + helpers they call)
Kirki registers defaults on the setting; without Kirki `get_theme_mod( $name )` with no 2nd arg returns **false**, so any "default = X" that exists only in the Kirki field is lost.
| theme_mod / helper | Read at | Evaluation | Kirki default | Result without Kirki / unset |
|---|---|---|---|---|
| hero_section_heading | KB1:18,36 | `! empty()` | '' | falls to page title: OK |
| hero_section_content | KB1:19,43 | `! empty()` | '' | the_excerpt: OK |
| support_section_heading | KB1:21,139 | `! empty()` | '' | default text |
| support_section_content | KB1:22,146 | `! empty()` | '' | LOREM IPSUM |
| support_section_button_text / _link_url | KB1:24-25,153 | both `! empty()` | '' | fallback dead '#' button |
| knowx_exclude_posts | KB1:70-71 | truthy | none | '' ok |
| knowx_exclude | KB2:48-49 | truthy | none | '' ok |
| knowx-exclude-cats (post meta) | KB1:67, KB2:45 | `! empty()` | - | metabox not registered |
| knowx_page_title | KB2:29 | `== 'yes'` (loose) | 'yes' | **false => title HIDDEN without Kirki** (regression risk) |
| knowx_cat_description | KB2:64 | `== 'yes'` | 'no' | off |
| knowx_view_all | KB2:105 | `== 'yes'` | 'no' | off |
| knowx_posts | KB2:70-71 | truthy then esc_attr string | none | -1 unlimited |
| knowx_order | KB2:76 | `== 'name'` | 'date' | date desc |
| sidebar_option | archive.php:17, index.php:22, page.php:22, single.php:17 (with `knowx_defaults('sidebar-option')`='right'); sidebar.php:22 (NO default) | string `== 'left'/'right'/'both'` loose | (Sidebar_Fields) | sidebar.php has no fallback (dead file) |
| blog_layout_option | archive.php:19, index.php:24, single.php:19 | string into class via esc_attr | default 'default-layout' (knowx_defaults) | ok |
| post_per_row | archive.php:20, index.php:25, single.php:20, entry-layout.php:10 | string concat `'col-md-' . value`, not validated/(int) | '3' | injectable class suffix from DB (esc_attr'd later at archive:49 only for $post_layout; `$post_per_row` esc_attr at :49 yes, in entry-layout.php:22 passed to post_class -> escaped by core) |
| post_box_view_layout | entry-layout.php:11,13 | truthy; `knowx_defaults('post-box-view-layout')` does NOT exist => '' | Kirki Blog_Fields:53 | box layout off unless saved |
| bbpress_sidebar_option | bbpress.php:22 | loose `==` | right | ok |
| site_loader | extra.php:78 (header.php:26) | `== '1'` string; default '2' | | loader off |
| site_sub_header / site_breadcrumbs / site_search | extra.php (knowx_sub_header) | `! empty()` with defaults '1' | | ok |
| site_search, site_cart | extra.php knowx_site_menu_icon | `(int)` | | ok |
| site_copyright_text | footer/info.php via extra.php | `! empty()` -> raw HTML | has default string | fallback literal untranslatable |
| header/footer checks | footer.php:16-17, header.php:45-46 | `get_body_class()` in_array 'page-template-full-width' (computed twice per request, runs `body_class` filters twice) |
| `knowx_defaults()` | external/kirki-utils.php | array lookup; unknown key returns '' | | MUST be relocated before removing external/ |
| options | functions/inc Options/Component.php:150 `update_option('knowx_theme_settings')` (not read in scope) |

## 6. DEAD / UNUSED FILES
Proof: `grep get_template_part` over theme (excluding optional/node_modules/vendor) yields these paths only: content-bbpress; content/entry_{actions,content,footer,header,meta,summary,taxonomies,thumbnail,title}; content/entry + {attachment, full-width, layout, page, search, $post_type}; content/error + {404,500,offline}; content/page_header; content/pagination; footer/info; header/{branding,custom_header,navigation}.
- All 25 template-parts are reachable. Special notes:
  - `entry-attachment.php` only via optional/attachment.php:28 (i.e. DEAD in production: no `attachment.php` in root). Attachment pages therefore fall to index.php -> `entry` + `get_post_type()` => `entry-attachment.php` is actually reached via `get_template_part('template-parts/content/entry', get_post_type())` = 'attachment'. So it IS used by index.php/single.php at runtime. 
  - `entry-search.php` is requested (`entry, 'search'` in an optional file) but does not exist; falls back to entry.php. `entry-post.php`/`entry-page.php`: `entry, get_post_type()` => `entry-post.php` does not exist (falls back to entry.php, fine); `entry-page.php` is used by page.php:48.
  - `entry_summary.php` used when is_search (entry.php:17, entry-layout.php:34).
  - Template-parts/content/error-404/500/offline reachable from 404.php/500.php/offline.php (500.php/offline.php only load under PWA/service-worker plugin).
- `sidebar.php`: never loaded by root templates (they call `knowx()->display_left_sidebar()` directly). Only optional/* call `get_sidebar()`. Logic is also wrong (sidebar.php:12 `! left_active || ! right_active` returns when EITHER is inactive). DEAD + buggy.
- `optional/` (11 files + dev/): none are loaded by WP (not in theme root). All use `get_sidebar()`. Duplicates of root templates: optional/archive.php, page.php, single.php (superseded), category.php, search.php, singular.php, attachment.php, front-page.php, custom-page-template.php (Template Name: "Custom Page Template" - would appear in dropdown only if moved), custom-post-template.php (same), dev/dev-proxy-livereload.php (not required anywhere). Recommendation: delete `optional/` from the release (WP-Rig scaffolding); keep nothing.
- `full-width.php` ("Full-Width-No-Title") and `full-width-container.php` ("Page-Without-Sidebar") are selectable page templates; full-width-container.php:36 closes `</div><!-- .container -->` AND footer.php:19 closes it again (body class `page-template-full-width-container` != `page-template-full-width`) => stray `</div>`.
- bbpress.php: loaded for bbPress via the plugin template hierarchy (bbpress.php is honoured by bbPress). Header comment copy-pasted "The main template file".
- archive.php:67 `if ( ! is_singular() )` always true (dead check); page.php:53 `! is_singular()` inside a page branch always false (dead pagination block); single.php:47 / 69 `blog/archive/search` body-class branch is dead on singular (never true) and `! is_singular()` always false.
- archive.php:46 `in_array('archive', $classes)` always true on archives => the else branch (60-64) dead.
- navigation.php:10-12 empty `if` with commented `//return;`; :34 `$nav_class` computed but literal duplicated in markup (38).
- No front-page.php/home.php/search.php/category.php/tag.php/author.php/date.php/singular.php in root: all fall to archive.php/index.php (search uses index.php; index.php:51 handles 'search' class). Search results page therefore has h1 from page_header and layout grid.
- Block-theme leftovers: theme.json customTemplates/templateParts (no files).

## 7. PLAYGROUND / SAMPLE-CONTENT READINESS
Needs plugin / remote / cron / FS at render:
- TGMPA (functions.php:26 -> external/require_plugins.php) prompts to install Kirki/Collapsing Categories/WPP: admin-only nag; in Playground installs fetch from wp.org (works only if blueprint allows; not at render).
- Kirki: all Customizer UI + defaults. Without it: no theme options; KB2 title hidden (5), default fonts, colors depend on Kirki-output CSS (inc/Dynamic_Style) - verify.
- WPP: KB1 "Popular Articles" = "No data found." (1.4f).
- inc/Activation/Component.php:46-72 `wp_insert_post` + `update_option('show_on_front','page')` + `page_on_front` on activation (switch_theme hook): creates the Knowledge Base page. In Playground this runs on theme activation: fine, but it overwrites `show_on_front`.
- Webfont loader (inc/Webfont/class-knowx-webfont-loader.php:265 `wp_remote_get`, :154 `update_option`, :634 `wp_schedule_event`, cleanup via WP_Filesystem) and inc/Fonts/Component.php:351/418 download Google fonts + write to uploads at render/first load: Playground has no/limited network and virtual FS; fails silently => fallback fonts. Cron never fires in Playground reliably.
- inc/EZ_Customizer/Component.php:86 `wp_remote_get( get_theme_file_uri() . '/inc/EZ_Customizer/themeCustomizeSettings.json' )` HTTP loopback to itself (customizer only) - fails in Playground; replace with local file read.
- inc/Template_Tags.php:184-196 `WP_Filesystem()` get_contents of asset at render (inline CSS) - works in Playground (direct FS) but extra I/O each request without caching.
- single.php:42 `setPostViews()` DB write per view (also each Playground load).
- extra.php:157-167 `knowx_categorized_blog()` `set_transient('knowx_category_count')` with `get_categories(hide_empty=1)` (loads ALL term objects just to count - at 500 cats heavy; use `wp_count_terms`); transient never invalidated (grep) .
- Zero-content render per template:
  * Front page default (blog index, no posts): index.php -> have_posts false -> error.php with page_header ("Nothing Found" h1 for is_home) and "Ready to publish your first post?" for admins; search form.
  * KB1 with zero posts: hero (h1 = page title, summary = excerpt), search form, a single "Uncategorized" h2 category link, "No data found." popular, empty "Recent Articles" heading, support section with LOREM IPSUM + dead "Contact Support" button.
  * KB2 with zero posts: page title+content, "Uncategorized" `<ul>` with heading only.
  * 404: heading + search + recent posts widget (empty), Most Used Categories (Uncategorized if has count; wp_list_categories `show_count`, hides empty by default => "No categories" text), archives dropdown, tag cloud (empty).
  * archive/search with no results: error.php.
  * page: page_header + entry-page; comments area absent if closed.
  * 500/offline: only with service-worker plugin; otherwise unreachable.
- Sample content needed for Playground blueprint: posts across >=5 categories (with category descriptions), a page assigned to "Knowledge Base Layout One"/"Two" template, WPP replaced by native fallback (see 1.4f), a menu assigned to `primary` location (navigation renders nothing but toggle if no menu), widgets in footer-1..4, custom copyright text.

## PER-FILE TABLE (file | purpose | reads | defects)
| File | Purpose | Reads | Defects |
|---|---|---|---|
| 404.php | 404 wrapper | elementor_theme_do_location | none significant; no sidebar |
| 500.php | PWA 500 | - | only reachable with PWA plugin |
| archive.php | archives/categories/tags | sidebar_option, blog_layout_option, post_per_row (+defaults) | :46 dead else; :67 dead check; duplicate #secondary (30,78); get_body_class used as logic; `==` loose |
| bbpress.php | bbPress pages | bbpress_sidebar_option | stray header comment; :48 dead is_singular check; no h1 guarantee |
| comments.php | comments | - | :39,:47 unescaped title; phpcs comment placement :45 |
| footer.php | footer + closing | get_body_class, sidebars footer-1..4 | :17-19 stray `</div>` for non-matching templates; :32 role=complementary on div; :49 empty div .mobile-menu-close |
| full-width.php | page template, no title | - | no knowx_sub_header; relies on body class |
| full-width-container.php | page template | - | :36 extra `</div>` vs footer.php:19 |
| header.php | doc head + masthead | get_body_class | :26 loader before body_open; :27 wp_body_open missing (blocker); :45 get_body_class again; trailing no newline |
| index.php | fallback/blog | sidebar_option, blog_layout_option, post_per_row | same as archive (dead checks, dup #secondary) |
| offline.php | PWA offline | - | filter `has_nav_menu` false added globally for request; only with PWA |
| page.php | pages | sidebar_option | :53 dead pagination branch; page_header always before loop |
| page-knowledgebase-one.php | KB layout 1 | hero_*, support_*, knowx_exclude_posts, meta | see section 1 |
| page-knowledgebase-two.php | KB layout 2 | knowx_page_title, cat_description, view_all, exclude, posts, order | see section 1 |
| searchform.php | search form | $_GET['s'] | no label/role/type=search; dup ids; dead `esc_url('#')`; name=submit |
| sidebar.php | (unused) sidebar | sidebar_option (no default) | :12 wrong boolean; never loaded |
| single.php | single post | same as archive + setPostViews | :42 write on view; dead blog/archive branch; layout branch unreachable |
| template-parts/content-bbpress.php | bbPress content | - | title unescaped in more-link |
| content/entry_actions.php | comment/edit links | - | :28,:47 get_the_title unescaped into sprintf after kses |
| content/entry_content.php | content/excerpt | - | :30-32 `<p>` around the_excerpt (nested p); :15 unescaped title |
| content/entry_footer.php | footer wrapper | - | duplicates categories/edit with entry_meta/knowx_posted_on |
| content/entry_header.php | header (thumb, cats, title, meta) | - | :19 `the_category` + entry_meta knowx_posted_on prints categories again (dup x3 with entry_taxonomies) |
| content/entry_meta.php | meta line | - | :11 global override; :78 get_avatar by email; :79 missing translators comment; :118 ' %s' string; knowx_posted_on duplicates comments/edit; `get_the_time('U') !== get_the_modified_time('U')` compare strings |
| content/entry_summary.php | excerpt | - | none |
| content/entry_taxonomies.php | terms | - | :44 `%s:`; uses global $post implicitly; renders every public taxonomy (N term-list queries per entry in archive? only singular via entry_footer) |
| content/entry_thumbnail.php | thumb | - | :32 aria-hidden focusable link; :34 global $wp_query |
| content/entry_title.php | title | - | none |
| content/entry-attachment.php | attachment | - | :49 echo with phpcs ignore; `$class =` in call |
| content/entry-full-width.php | content only | - | unescaped title in more link |
| content/entry-layout.php | grid card | post_per_row, post_box_view_layout | :11 default key missing; empty class string |
| content/entry-page.php | page content | - | :41-43 comments_template inside .entry-content (comments nested in content div); featured media figure without caption |
| content/entry.php | post | - | :29 get_post_type_object()->has_archive (null deref if type unregistered) |
| content/error-404.php | 404 body | - | the_widget x3 with default instances (heavy on 404: recent posts, archives, tag cloud) |
| content/error-500.php | 500 | - | PWA only |
| content/error-offline.php | offline | - | entity in translatable string |
| content/error.php | no results | - | entity strings; `is_home()` check misses front page |
| content/page_header.php | headings | - | entities in strings; else branch outputs h1 `entry-title` for every singular |
| content/pagination.php | pagination | - | none (no `mid_size` issue) |
| footer/info.php | copyright | site_copyright_text | unescaped output |
| header/branding.php | logo/title | - | fine |
| header/custom_header.php | header image | - | fine |
| header/navigation.php | nav | - | duplicate id, dup menu, `<a href=#>` close, dead if |
| optional/* (11) + optional/dev/* | scaffolding | none loaded | all dead; get_sidebar() uses buggy sidebar.php; dev livereload injection |
| theme.json | settings | - | customTemplates/templateParts reference missing files; WP>=6.6 vs Requires 4.8 |
| style.css | header | - | Tested up to stale; License string; requires mismatch |
| readme.txt | wp.org readme | - | Stable tag trunk; no FAQ/Resources; stale tested-up-to |

## TOP FIXES BEFORE KIRKI REMOVAL (ordering)
1. Add `wp_body_open()`; fix stray `</div>` in KB1/full-width-container; remove optional/ and sidebar.php.
2. Escape KB1/KB2 category output; fix `esc_html` misuse; wrap cat-description in li; one `<ul>` for Recent.
3. Honour exclusions in Recent/Popular; unify `knowx_exclude` / `knowx_exclude_posts`; register the metabox or drop meta.
4. Bound KB2 (cap categories, single grouped post query or transient); `orderby=title`.
5. Move `knowx_defaults()` out of external/; give every `get_theme_mod()` a default so Kirki removal does not hide KB2 page title.
6. Replace lorem/dead "#" button/"No data found." with real defaults and native popular fallback.
