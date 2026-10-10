# KnowX 1.7.5 - PHP/inc audit (read-only), Kirki removal prep

Theme root: `/Users/varundubey/Local Sites/knowx/app/public/wp-content/themes/knowx` (paths below relative to it).
Method: every file in scope read in full. Template files (`*.php` at root, `template-parts/`) grepped for theme_mod reads so the settings map has real call sites.
Environment facts: Kirki plugin is NOT installed on the local knowx site (`wp-content/plugins` holds only index.php) and KnowX is not the active theme there (`template`=twentytwentyfive), so there is no live `theme_mods_knowx` row to inspect. Stored-value shapes below are therefore derived from code + Kirki behaviour, flagged "unverified" where relevant. Plan file already exists: `plans/2026-10-10-kirki-removal-and-tokens.md`.

Count note: the Fields files declare 90 `new \Kirki\Field\*` objects. 6 are `Custom` (non-settings: custom-skin-divider, custom-knowledge-divider-one..four, site_flush_local_font) = 84 real settings; `bbpress_sidebar_option` (Sidebar_Fields.php:29-45) is registered only when bbPress is active. The plan says 85; my count is 84 (83 always + 1 conditional).

---------------------------------------------------------------------
## 0. PER-FILE TABLE

Legend: K = Kirki coupling. "mods" = theme_mod IDs.

| file | purpose | K / customizer coupling | mods | defects |
|---|---|---|---|---|
| functions.php | bootstrap, autoload, includes | L25-27 require TGMPA + kirki-utils; L78 loads webfont loader; L81 extra.php; L90 login.php | none | L13 min PHP '7.0' but style.css says 7.4 and code needs 8.0 (see §4); L7 docblock "PHP 5.2"; L26 TGMPA (3.7k lines) parsed on every request incl. front end though only used in admin; L72-75 `is_plugin_active()` include comment "used below" but never used (dead; loads wp-admin/includes/plugin.php every request); L66-70 theme boots on file include (before init) so `class_exists('Kirki')` in Theme.php depends on plugin load order; wp-cli/knowx-commands.php never required anywhere (dead) |
| external/kirki-utils.php | `knowx_defaults()` | name implies Kirki; holds the only defaults table | site-layout, site-loader, site-sticky-header, site-sub-header, site-search, site-breadcrumbs, sidebar-option, bbpress-sidebar-option, sticky-sidebar, blog-layout-option, post-per-row (dash keys, not mod ids) | L37-41 returns '' for unknown key; callers pass 'site-cart' (extra.php:90) and 'post-box-view-layout' (entry-layout.php:11) which do not exist. Defaults disagree with Kirki field defaults: post_per_row '3' here vs '4' Blog_Fields.php:33; site_loader '2' here and in field. Defaults are strings '1', Kirki sub-header field default is 'on' (Sub_Header_Fields.php:18); only 11 of 84 settings have a defaults entry |
| external/require_plugins.php | TGMPA recommended plugins | Kirki Toolkit, Collapsing Categories, WP Popular Posts (all required=false) L61-87 | none | L66,73,82 stray bare string `'knowx'` array element (positional entry, ignored/garbage); `tgmpa_register` L36; only hooked in admin by TGMPA itself |
| inc/Theme.php | component registry | L162-163 registers Kirki + Kirki_Option components always; L156 EZ_Customizer commented out; L172 `class_exists('Kirki')` gates Dynamic_Style | none | L172 evaluated once at theme load; if Kirki loads after theme (mu-plugin order, activation in same request) Dynamic_Style never registers; L31 typed property (PHP 7.4 ok); Options registered before Kirki so no ordering issue |
| inc/Kirki/Component.php | 'kirki/config' filter | L11 `use Kirki`; L36 filter `kirki/config`; L44-51 `Kirki::add_config('knowx_kirki',...)` | none | L44 condition inverted: calls `Kirki::add_config` only when Kirki class does NOT exist = fatal if ever reached, never runs otherwise; L43-53 filter callback returns nothing (null) so it nulls the config filter value; config id 'knowx_kirki' is never used by any field (no `config_id`) so fields go to Kirki 'global' config; dead component. L29,37 leftover TODOs |
| inc/Kirki_Option/Component.php | panels/sections + 3 body_class filters | L32 gate `class_exists('Kirki')`; L80-309 `\Kirki\Panel/Section` x 11 sections, 5 panels; L34 `add_filter('init', ...)` (add_action misuse) loads field files | site_layout, sticky_sidebar_option, site_sticky_header | L45 `'layout-' . get_theme_mod('site_layout')` unsanitised into class (stored value could be junk; Kirki radio-image sanitises but framework replacement must); L56,67 truthy tests on switch value (legacy 'off' string => true); body_class filters run even without Kirki (good) but then use defaults only. Priority: every section priority 30/31 identical (order by registration). Panels/sections created on `init` (works but Kirki 5 prefers `customize_register`) |
| inc/Kirki_Option/Fields/General_Fields.php | layout, width, loader, scroll top | 5 Kirki fields | site_layout, site_container_width, site_loader, site_loader_bg, knowx_scroll_top | L56 `default '2'` for a Checkbox_Switch (invalid shape); L77 active_callback `site_loader == '1'` but default is '2' so loader_bg control hidden until toggled; L32-46 output `.container{max-width}` only; L92 default '' |
| inc/Kirki_Option/Fields/Header_Fields.php | sticky header, header bg | 2 fields | site_sticky_header, site_header_bg_color | L18 default '1' (string) while read via truthy |
| inc/Kirki_Option/Fields/Sub_Header_Fields.php | sub header | 6 fields incl. Background + Typography with output | site_sub_header, site_sub_header_bg, sub_header_background_setting, site_sub_header_typography, site_breadcrumbs, site_search | L28-44 `site_sub_header_bg` toggle has no PHP consumer; Background output (L60-64) is unconditional so toggle only hides the control; L18,110,130 defaults 'on'/'off' strings; L40 active_callback value `true` vs L70 `'1'` mixed |
| inc/Kirki_Option/Fields/Skin_Fields.php | 10 colours + divider | 11 fields | body_background_color, site_primary_color, site_links_color, site_links_focus_hover_color, site_buttons_* (6) | all consumed via Dynamic_Style CSS vars only; no `output` so no colour at all without Kirki (see §2); L57-63 Custom divider has a 'settings' id that is not a real setting |
| inc/Kirki_Option/Fields/Blog_Fields.php | blog layout | 3 fields | blog_layout_option, post_per_row, post_box_view_layout | L33 default '4' vs knowx_defaults '3' (kirki-utils.php:35): control shows 4, frontend uses 3 until saved; L44 operator `'!=='` (JS strict) works only via customizer-controls.js; L56 default '' |
| inc/Kirki_Option/Fields/Sidebar_Fields.php | sidebar layouts | 3 fields | sidebar_option, bbpress_sidebar_option, sticky_sidebar_option | L29 gated on `function_exists('is_bbpress')` at `init` priority 10 (bbPress defines it on `plugins_loaded`, ok); L52 default '1' |
| inc/Kirki_Option/Fields/Site_Performance.php | local fonts | 3 fields (2 switch + 1 Custom button) | site_load_google_font_locally, site_preload_local_font, site_flush_local_font | NONE of these three is read by any PHP in the theme: Webfont loader functions `knowx_get_webfont_url`/`knowx_load_preload_local_fonts` are never called. Dead settings. L52 hard-coded button HTML with untranslated string "Flush Local Font Files" |
| inc/Kirki_Option/Fields/Typography_Fields.php | 13 settings | 10 Typography with `output`, 3 Color | site_title_typography_option, site_title_hover_color, site_tagline_typography_option, h1..h6_typography_option, menu_typography_option, menu_hover_color, menu_active_color, typography_option | all value-to-CSS conversion is Kirki output + Kirki Webfonts (see §2). L242-245 second output with property border-color on `.nav--toggle-small .menu-toggle`. L240 selector list. All default font Open Sans |
| inc/Kirki_Option/Fields/Footer_Fields.php | footer + copyright | 12 fields | site_footer_bg, background_setting, site_footer_*_color (4), site_copyright_text, site_copyright_* (5) | L28 setting id `background_setting` is generic/unprefixed (collision risk); L15-24 `site_footer_bg` toggle has no PHP consumer (same as sub header); L108 default text has "©" |
| inc/Kirki_Option/Fields/Knowledge_Base_Fields.php | KB layout one/two | 23 fields | hero_section_background_setting, hero_section_heading, hero_section_content, hero_section_title_typo, hero_section_content_typo, knowx_exclude_posts, support_section_background_setting, support_section_heading, support_section_content, support_section_title_typo, support_section_content_typo, support_section_button_text, support_section_button_link_url, knowx_page_title, knowx_view_all, knowx_cat_description, knowx_exclude, knowx_posts, knowx_order (+4 Custom dividers) | L131 `knowx_exclude_posts` and L302 `knowx_exclude` Text fields: no sanitize_callback, comma list of IDs goes to query (template concatenation, see §4); L309 `knowx_posts` Number no default/min => 0/negative possible; layout-two radios yes/no strings |
| inc/Kirki_Option/Fields/WP_Login_Fields.php | login logo | 9 fields | enable_custom_login, enable_custom_login_logo, custom_login_logo_image, ..._width, ..._height, custom_login_logo_space, custom_login_logo_url, custom_login_logo_title, custom_login_page_title | L53,77,... 7 fields use transport `postMessage` but no JS handler exists (customizer.tsx only handles blogname/blogdescription/header_textcolor) and the login page is not previewable: preview never updates; L63 `enable_custom_login_logo == false`; label "Disable Logo?" inverse semantics |
| inc/Customizer/Component.php | core postMessage + customizer scripts | L50 `class_exists('Kirki')` gate for controls JS; L53-58 detects `kirki-customizer` / `kirki_field_dependencies` handles; L68 inline style | blogname, blogdescription, header_textcolor (core) | L85 `get_setting('header_textcolor')` fatal if null (theme supports custom-header, so ok but unguarded); L83-85 no null guard; L111 empty 'theme_options' section registered with zero controls (dead); L44 closure on controls enqueue (cannot be unhooked); when Kirki absent `customizer-controls.min.js` not loaded |
| inc/Dynamic_Style/Component.php | CSS variables from colour mods | L40 `class_exists('Kirki')` gate; reads 24 mods; inline style on 'knowx-global' | site_custom_colors (undefined anywhere), 24 colour mods (see §1) | L41 `site_custom_colors` not a registered setting, default true => dead toggle; L70 `$light_attrs` var named light but only one block; values echoed raw into inline CSS via wp_add_inline_style with no `sanitize_hex_color`/rgba validation (L72 etc.): CSS injection by any user able to set theme_mod (customizer-capable only; low). 24x duplicated `get_theme_mod` (each mod read twice: L44-67 and again L71...). Whole feature disabled if Kirki missing. Runs on every front-end request (uncached string building, fine) |
| inc/Options/Component.php | React settings page + REST | admin script `knowx-theme-settings`, customizer admin JS L68-78 | option `knowx_theme_settings` | L60-66 enqueues `index.min.js` + css on EVERY admin screen (no hook-suffix check); L64,75,84 `filemtime` of file => warning if missing; L87-96 localises option with nonce on every admin page; L41 add_menu commented => settings page unreachable; L123 REST namespace `my-theme/v1` (template placeholder); L141 return type `WP_REST_Response\|WP_Error` = PHP 8.0 syntax; L168-184 `sanitize_text_field($value)` on non-scalar values returns '' silently; L150 `update_option` autoloaded array; `inc/Options/settings-page.php` unreachable. Also L68-78 duplicate of Customizer enqueue (`knowx-customizer-script` via `$pagenow==='customize.php'` on admin_enqueue_scripts which never fires on customize.php; should be customize_controls_enqueue_scripts) => dead |
| inc/Options/settings-page.php | settings page markup | none | none | dead (render_settings_page only called from commented menu) |
| inc/Activation/Component.php | first-activation home page | `after_switch_theme` | option knowx_theme_activated, show_on_front, page_on_front | L50 `update_option('knowx_theme_activated', true)` autoloaded bool; L38-52 forcibly overwrites show_on_front/page_on_front on any site's first activation without checking existing front page (destructive); L72 `wp_insert_post` returns WP_Error possible (`$page_id ?` truthy check ok for 0 but WP_Error object is truthy => treated as ID); L68 meta `_wp_page_template` page-knowledgebase-one.php; no theme_mod seeding; no starter content |
| inc/Fonts/Component.php | Google fonts enqueue, font collections, downloader | L47 init register_font_collection; `knowx_google_fonts` filter | none (does NOT read typography mods) | L229-230 hard-coded Open Sans + Montserrat always enqueued, independent of typography mods => double load alongside Kirki Webfonts; L229 variant string `'900&display=swap'` injects `&display=swap` then L274 adds `display=swap` again (duplicate param); L229 uses css v1 `|` syntax; L158-171 'local-fonts' collection references non-existent `my-local-font.woff2` and wrong collection schema (family/file instead of font_families settings) - invalid; L209-212 resource hint array `'crossorigin'` positional (WP expects `'crossorigin' => true`); L331 return type `WP_Error\|string` = PHP 8; L288-311 downloader unreachable from UI (only wp-cli, itself unloaded); L297/479 `get_local_font_path` checks uploads/fonts but download writes into theme dir => never "exists"; L422 `'sslverify' => false`; L395-415 writes into `get_stylesheet_directory()` (child theme / theme dir: overwritten on update, perms); L435,467 `$wp_filesystem` may be null/false if creds needed => fatal; L405 `$font_name_match` unused; L67-75, 85-91 duplicate of Template_Tags::get_version/get_asset_version and `template_tags()` returns empty array so these are not exposed (the Template_Tags versions are the callable ones) = dead duplicates |
| inc/Styles/Component.php | CSS registry | handle `knowx-global` is target for dynamic inline CSS | none | L122 bbPress CSS enqueued on every page when bbPress active (`function_exists('is_bbpress')` always true) and no version arg; L111-114 preload logic: `$preloading_available` only consulted when preloading disabled; L175 inline `onload=` handler (CSP-hostile); L186 add_editor_style; knowx-global is `global=>true` must exist for Dynamic_Style inline CSS to attach |
| inc/Scripts/Component.php | JS registry | none | knowx_scroll_top | L177 `(bool) get_theme_mod('knowx_scroll_top', false)`: legacy `'off'` string => true (scrollup enabled); L225 localises bool => JS gets "1"/"" (custom.js:248 `== true` ok); L181-232 every script `'loading'=>'async'` incl. `knowx-custom` which depends on jquery+superfish+isotope+sticky-kit: async scripts execute out of order => race condition (jQuery is not async but custom.min.js can run before superfish/isotope); `async` with dependencies is invalid; L114 trailing whitespace only |
| inc/Base_Support/Component.php | theme supports, patterns | none | none | L86-87 `add_theme_support('custom-header'/'custom-background')` bare, later overridden by Custom_Header/Background components on after_setup_theme (default prio) => ordering-sensitive duplicates; L48 script_loader_tag regex fails on tags with inline `before/after` data (`></script>` not adjacent); L205 `require` of pattern file each init; patterns/footer-small.php L18 hard-coded wbcomdesigns link `rel=nofollow` |
| inc/Base_Support/patterns/footer-small.php | block pattern | none | none | hard-coded external credit link; unused in classic theme |
| inc/Blocks/Component.php | register blocks from assets/blocks | none | none | `assets/blocks` does not exist => component returns at L66 every init (dead); L166,194 `mixed` param type = PHP 8.0 (fatal parse on 7.4); L62-70 `knowx_log` action never defined/hooked |
| inc/Editor/Component.php | editor supports | none | none | L67 palette hard-coded `#03a9f4`, ignores `site_primary_color`; palette slugs theme-* not linked to CSS vars |
| inc/Accessibility/Component.php | nav a11y script | none | none | L82 prints no-js removal inline on every page (needed); L101-103 IE11 skip-link script obsolete; L64 async on navigation script |
| inc/AMP/Component.php | AMP | none | none | L42 body_class `knowx-amp`; fine; unused otherwise |
| inc/PWA/Component.php | service worker support | none | none | none |
| inc/Comments/Component.php | comments helpers | none | none | `filter_comment_form_defaults` (L89) never hooked => dead; `the_comments` template tag; L121 var typo `$poll_inverval` |
| inc/Nav_Menus/Component.php | primary menu | none | none | L103 loose `in_array` |
| inc/Image_Sizes/Component.php | sizes attrs | none | none | `knowx()->is_right_sidebar_active()` called per image (is_active_sidebar cached) - hardcoded sidebar assumption ignores `sidebar_option` mod (uses right sidebar widgets active, not layout setting) |
| inc/Post_Thumbnails/Component.php | thumbnails | none | none | none |
| inc/Custom_Background/Component.php | custom bg support | `knowx_custom_background_args` | core background_color | L45 default-color 'ffffff' conflicts with `body_background_color` mod (#f7f7f9) - two competing body background systems |
| inc/Custom_Header/Component.php | custom header + wp_head_callback | core header_textcolor | header_textcolor | L77 `esc_attr` for colour; overlaps with `site_title_typography_option` colour output (specificity fight) |
| inc/Custom_Logo/Component.php | logo support | none | custom_logo | none |
| inc/Localization/Component.php | textdomain | none | none | none |
| inc/Excerpts/Component.php | excerpt_more | none | none | L44 `get_the_title()` placed unescaped in sprintf (title may contain markup) |
| inc/Jetpack/Component.php | Jetpack supports | none | none | L76 'stylesheet' => 'knowx-content' handle; ok |
| inc/EZ_Customizer/Component.php + .json | JSON-driven customizer | `wp_remote_get` of own URI | ga_id (never output) | DEAD (commented in Theme.php:156). L86 loopback HTTP request on every request if enabled; L88 `json_decode(..., FILE_USE_INCLUDE_PATH)` wrong arg (assoc flag by accident); L115 `$args` undefined; setting `ga_id` has no sanitize_callback and no consumer. JSON still copied by config.default.json:60 export list |
| inc/Template_Tags.php | `knowx()->x()` proxy | none | none | L137-161 get_version/get_asset_version here; L156 `WP_DEBUG` bare constant (undefined constant error if not defined, WP defines it); filemtime of missing file warns |
| inc/extra.php | helper functions + sub header, loader, footer text, meta box | theme_mod reads | site_sub_header, site_breadcrumbs, site_search, site_loader, site_cart (undefined), site_copyright_text | L34-35,41,47 `!empty()` on switch value; L78-79 `== '1'`; L89-91 `(int)`; L90 reads undefined `site_cart` + `knowx_defaults('site-cart')`; L86-110 `knowx_site_menu_icon` has no caller (dead); L97,99 echo apply_filters raw (phpcs ignored); L125-137 copyright text from mod echoed (L13 info.php) with only token replacement, relies on Kirki Textarea kses sanitisation (framework must apply `wp_kses_post`); L132,145 `esc_html('..','knowx')` 2nd arg ignored; L139 hard-coded untranslated fallback; L161-167 transient `knowx_category_count` never expires and no invalidation hook (`edit_category`, `create_category`, `delete_category`) => stale; L223-232 `knowx_metabox_callback` never registered with `add_meta_box` => the KB per-page exclusion field cannot be edited (dead UI); L234-250 save handler: `$_POST` used raw (L238 not unslashed), `get_post_type()` uses global not `$post_id` (L244), no revision/autosave of other post types guard beyond type check, `save_post` fires for every type; L257 global unprefixed `setPostViews()`, non-atomic read-modify-write, writes meta on every single view for every visitor incl. bots (DB write per page view, defeats page caching, hot row); L273 unprefixed `custom_bbp_no_breadcrumb` ; L27 add_action before function defined inside conditional (works) |
| inc/login.php | login page logo/title | L12-14 whole file skipped if `!class_exists('Kirki')` | enable_custom_login, enable_custom_login_logo, custom_login_logo_image/_width/_height/_space/_url/_title, custom_login_page_title | L12 gate means login customisations vanish without Kirki although they are plain theme_mods; L71 `login_custom_head` hooked on `init` reading mods (theme_mods readable at init, ok); L94 `true === $enable_custom_login_logo` strict: legacy '1'/'on' => no effect; L122,133,142 undefined variables `$custom_login_choose_theme`, `$custom_login_background_*` (3 dead branches, PHP notices/warnings under 8.x "undefined variable" warnings); L136 `esc_url()` on a colour value (breaks it); L105 esc_url inside CSS url() ok; L109-115 `esc_attr` on dimension values in `<style>` (no CSS-context escaping; values like `84px;...`), L20 `knowx_is_login_page` unused; function names `login_custom_head` unprefixed global; `login_headerurl` returns raw mod without esc_url (core escapes) |
| inc/Webfont/class-knowx-webfont-loader.php | WPTT-style local Google Fonts | `customize_save_after` L782; AJAX L769 | options knowx_font_url, site opts knowx_local_font_files, ast_downloaded_font_files | see §4 (many) ; whole class never instantiated except via flush paths; no caller of `knowx_get_webfont_url` / `knowx_load_preload_local_fonts` |
| inc/widgets/category-widget.php | KnowX Categories widget | none | none | L41 typo id `knowx-caegoriest`; L90 `strip_tags`; L44 title echoed unescaped inside label (title filtered widget_title ok) ; global-scope class + function `knowx_register_widgets` |
| inc/class-knowx-breadcrubms.php (filename typo) | Breadcrumb Trail clone | none | none | L914 misplaced paren: `esc_url( get_day_link( Y ), m, d )` => day link broken/ months dropped; L942 `esc_url()` wraps HTML returned by `get_archives_link` (destroys markup); L1073-1098 `$post` possibly undefined after loop; L1241,1298 methods without visibility; L244 http://schema.org microdata; L337 `echo` and L92 `knowx_get_breadcrumb` returns null when echo true; extra.php:68 wraps output in `.knowx-breadcrumbs` and L78 `before` adds a second `.knowx-breadcrumbs` (double wrapper); items concatenated with unescaped `$term->name`, display names |
| inc/Component_Interface.php, Templating_Component_Interface.php, functions.php, wordpress-shims.php | plumbing | none | none | shims L11-19 `knowx_wp_body_open` wrapper: header.php:27 calls it instead of core `wp_body_open()` so plugins hooking `wp_body_open` (analytics, skip links) are never fired; nobody hooks `knowx_wp_body_open` |
| inc/back-compat.php | insufficient-version guard | none | none | `KNOWX_MINIMUM_PHP_VERSION` 7.0 wrong; L46 `WP_CLI::error` at file load for CLI |
| wp-cli/knowx-commands.php | WP-CLI helpers | none (L440 theme_mod nav_menu_locations) | nav_menu_locations | Not loaded by functions.php (dead); L577 reads `font-dir`/`css-dir` but docblock documents `--dir`; L183 `file_put_contents` of arbitrary user path (CLI, ok); L524 `date()` not gmdate; L27 `dev_setup` installs 5 plugins; docblock L59-96 duplicated orphan above menu_export |
| config/config.default.json | build config | L60-64 export list includes `inc/Kirki/**`, `inc/Kirki_Option/**`, `external/kirki-utils.php`, EZ json | none | must be updated on removal; version "1.7.5" duplicated here |
| config/config.json | local override | none | none | `https:true` vs default false (dev only) |
| config/themeConfig.js | merges configs | none | none | none |

---------------------------------------------------------------------
## 1. SETTINGS MAP

Common facts:
- No field sets `config_id`, `option_type`, `sanitize_callback`, or `capability`. Everything relies on Kirki global config (theme_mod, edit_theme_options) and Kirki's per-field-class sanitizer. Framework replacement must supply these explicitly.
- Transport: only 5 settings declare `auto` (site_container_width, sub_header_background_setting, background_setting, hero_section_background_setting, support_section_background_setting). 7 WP Login settings use `postMessage` with no preview JS. All others default `refresh`.
- `output` = Kirki auto-CSS map (server-rendered by Kirki's CSS module; disappears without Kirki).
- "via defaults()" = read through `knowx_defaults()`.
- Stored shape for Checkbox_Switch: Kirki sanitises to boolean (stored `true`/`false`) in current Kirki; older Kirki/Customizer saves may leave `'1'`/`''`, and the declared `choices` 'on'/'off' and defaults 'on'/'off' suggest legacy `'on'`/`'off'` strings could exist on sites upgraded from Kirki 3. UNVERIFIED (no DB row available). Every toggle must be read through one normalising helper (truthy-bool: true,1,'1','on','yes','true' => true; false,0,'0','off','no','false','' => false).

### 1a. Toggles (14 Checkbox_Switch) - the legacy 'on'/'off' risk table

| id | default (field / defaults()) | read at | evaluation | misbehaves on legacy value? |
|---|---|---|---|---|
| site_loader | '2' / '2' | extra.php:78-79 (called header.php:26) | `== '1'` loose | 'on' => false (loader never shows); '2' default => off; true => on; plan assumes default "on" but code shows OFF by default for unsaved sites |
| site_sticky_header | '1' / '1' | Kirki_Option/Component.php:67-68 | truthy | 'off' => TRUE (sticky wrongly on) |
| site_sub_header | 'on' / '1' | extra.php:34-35 | `!empty()` | 'off' => TRUE; also active_callback `== true` |
| site_sub_header_bg | 'off' / none | none (active_callback only Sub_Header_Fields.php:67) | no consumer | toggle is cosmetic |
| site_breadcrumbs | 'on' / '1' | extra.php:40-41 | `!empty()` | 'off' => TRUE |
| site_search | 'on' / '1' | extra.php:46-47 `!empty()` AND extra.php:89 `(int)` | mixed | 'on' => `(int)'on'` = 0 (hidden) in menu icon fn (dead fn) but shown in sub header; 'off' => shown in sub header |
| sticky_sidebar_option | '1' / '1' | Kirki_Option/Component.php:55-56 | truthy | 'off' => TRUE |
| post_box_view_layout | '' / lookup of missing key => '' | template-parts/content/entry-layout.php:11-13 | truthy | 'off' => TRUE |
| knowx_scroll_top | '' / none | Scripts/Component.php:177 `(bool)` | bool cast | 'off' => TRUE (scroll-up script enqueued global, line 213) |
| site_load_google_font_locally | '' | NO READER | - | dead setting |
| site_preload_local_font | '' | NO READER | - | dead setting |
| site_footer_bg | 'off' | NO READER (active_callback only Footer_Fields.php:47) | - | cosmetic toggle |
| enable_custom_login | '' | login.php:68-70 | truthy | 'off' => TRUE |
| enable_custom_login_logo | '' | login.php:94 | `!empty() && true ===` | '1'/'on' => FALSE (never hides logo) |

### 1b. Colour settings (24 Color, alpha:true) - all read ONLY by Dynamic_Style/Component.php (isset+!empty), emitted as CSS var, gated by `class_exists('Kirki')` (L40) and undefined `site_custom_colors` (L41). No `output`, no sanitize, no active_callback, transport refresh. Not read through knowx_defaults (default is the Kirki field default, absent mod => `get_theme_mod` returns false => no var emitted => CSS fallback in global.css).

| id | field default | CSS var emitted (Dynamic_Style line) |
|---|---|---|
| site_loader_bg (active_callback site_loader=='1', General_Fields.php:73-79) | #03A9F4 | --color-theme-loader (L72) |
| site_header_bg_color | #ffffff | --color-header-bg (L82) |
| site_title_hover_color | #03A9F4 | --color-site-title-hover (L77) |
| menu_hover_color | #03A9F4 | --color-menu-hover (L87) |
| menu_active_color | #03A9F4 | --color-menu-active (L92) |
| body_background_color | #f7f7f9 | --color-theme-body (L97) |
| site_primary_color | #03A9F4 | --color-theme-primary (L102) |
| site_links_color | #03A9F4 | --color-link (L107) |
| site_links_focus_hover_color | #222222 | --color-link-hover (L112) |
| site_buttons_background_color | #03A9F4 | --button-background-color (L117) |
| site_buttons_background_hover_color | #222222 | --button-background-hover-color (L122) |
| site_buttons_text_color | #ffffff | --button-text-color (L127) |
| site_buttons_text_hover_color | #ffffff | --button-text-hover-color (L132) |
| site_buttons_border_color | #03A9F4 | --button-border-color (L137) |
| site_buttons_border_hover_color | #222222 | --button-border-hover-color (L142) |
| site_footer_title_color | #333333 | --color-footer-title (L147) |
| site_footer_content_color | #505050 | --color-footer-content (L152) |
| site_footer_links_color | #222222 | --color-footer-link (L157) |
| site_footer_links_hover_color | #03A9F4 | --color-footer-link-hover (L162) |
| site_copyright_background_color | #ffffff | --color-copyright-bg (L167) |
| site_copyright_content_color | #505050 | --color-copyright-content (L172) |
| site_copyright_border_color | #e8e8e8 | --copyright-border-color (L177) |
| site_copyright_links_color | #222222 | --color-copyright-link (L182) |
| site_copyright_links_hover_color | #03A9F4 | --color-copyright-link-hover (L187) |

Gotcha: Kirki returns the field default for unsaved mods only through Kirki's own `get_theme_mod` filter (`theme_mod_{id}`); plain `get_theme_mod('x')` in Dynamic_Style (no default arg) returns false for unsaved => no var. After Kirki removal the framework must register `theme_mod_{id}` defaults or pass defaults, otherwise vars and any default-dependent logic change.

### 1c. Non-colour settings

| id | Kirki class | type | default | transport | output? | active_callback | sanitize | WHERE read | via defaults()? | evaluation |
|---|---|---|---|---|---|---|---|---|---|---|
| site_layout | Radio_Image | boxed/wide | 'wide' | refresh | no | no | Kirki choice check | Kirki_Option/Component.php:45 (body class) | yes 'site-layout' | string concat into class |
| site_container_width | Dimension | css len | '1170px' | auto | `.container{max-width}` | no | Kirki dimension | none in PHP (output only) | no | - |
| site_loader | Checkbox_Switch | toggle | '2' | refresh | no | no | bool | extra.php:78 | yes | `== '1'` |
| knowx_scroll_top | Checkbox_Switch | toggle | '' | refresh | no | no | bool | Scripts/Component.php:177 | no | `(bool)` |
| site_sticky_header | Checkbox_Switch | toggle | '1' | refresh | no | no | bool | Kirki_Option/Component.php:67 | yes | truthy |
| site_sub_header | Checkbox_Switch | toggle | 'on' | refresh | no | no | bool | extra.php:34 | yes | !empty |
| site_sub_header_bg | Checkbox_Switch | toggle | 'off' | refresh | no | site_sub_header==true | bool | none | no | - |
| sub_header_background_setting | Background | array | rgba(255,255,255,0.5) etc | auto | `.site-sub-header` | site_sub_header_bg=='1' | Kirki bg | none (output only) | no | output applied regardless of toggle (verify Kirki default-skip) |
| site_sub_header_typography | Typography | array | color #333333 rest '' | refresh | list of 7 selectors | site_sub_header==true | Kirki typo | none | no | - |
| site_breadcrumbs | Checkbox_Switch | toggle | 'on' | refresh | no | site_sub_header==true | bool | extra.php:40 | yes | !empty |
| site_search | Checkbox_Switch | toggle | 'on' | refresh | no | site_sub_header==true | bool | extra.php:46, extra.php:89 | yes | !empty / (int) |
| blog_layout_option | Radio_Image | choice | 'default-layout' | refresh | no | no | choice | index.php:24, archive.php:19, single.php:19 | yes | string |
| post_per_row | Select | 12/6/4/3 | '4' (defaults() says '3') | refresh | no | blog_layout_option !== 'default-layout' | choice | index.php:25, archive.php:20, single.php:20, entry-layout.php:10 | yes | concat 'col-md-'. |
| post_box_view_layout | Checkbox_Switch | toggle | '' | refresh | no | no | bool | entry-layout.php:11 | yes (missing key) | truthy |
| sidebar_option | Radio_Image | none/left/right/both | 'right' | refresh | no | no | choice | Sidebars/Component.php:183; index.php:22; archive.php:17; page.php:22; single.php:17; sidebar.php:22 (no default) | yes (all but sidebar.php) | `==` string |
| bbpress_sidebar_option | Radio_Image | choice | 'right' | refresh | no | no | choice | Sidebars/Component.php:207; bbpress.php:22 | yes | `==` |
| sticky_sidebar_option | Checkbox_Switch | toggle | '1' | refresh | no | no | bool | Kirki_Option/Component.php:55 | yes | truthy |
| site_load_google_font_locally | Checkbox_Switch | toggle | '' | refresh | no | no | bool | none | no | dead |
| site_preload_local_font | Checkbox_Switch | toggle | '' | refresh | no | site_load_google_font_locally==1 | bool | none | no | dead |
| site_footer_bg | Checkbox_Switch | toggle | 'off' | refresh | no | no | bool | none | no | cosmetic |
| background_setting | Background | array | rgba(255,255,255,0.8) | auto | `.site-footer-wrapper` | site_footer_bg=='1' | Kirki bg | none | no | output regardless |
| site_copyright_text | Textarea | html | 'Copyright (c) [current_year]...' | refresh | no | no | Kirki textarea (kses) | extra.php:125 (render template-parts/footer/info.php:13) | no | !empty |
| hero_section_background_setting | Background | array | rgba(3,169,244,1) | auto | `.knowx-section-hero` | no | Kirki bg | none (output) | no | note page-knowledgebase-one overrides with featured image |
| hero_section_heading | Text | text | '' | refresh | no | no | Kirki text | page-knowledgebase-one.php:18 | no | |
| hero_section_content | Textarea | text | '' | refresh | no | no | Kirki textarea | page-knowledgebase-one.php:19 | no | |
| hero_section_title_typo | Typography | array | color #ffffff | refresh | `.knowx-section-hero .entry-title` | no | typo | none (output) | no | |
| hero_section_content_typo | Typography | array | #ffffff | refresh | `.knowx-section-hero .entry-summary, ... p` | no | typo | none | no | |
| knowx_exclude_posts | Text | csv ids | none | refresh | no | no | Kirki text only (not numeric csv) | page-knowledgebase-one.php:70-71 (after esc_attr) | no | truthy |
| support_section_background_setting | Background | array | rgba(236,236,236,.94) | auto | `.knowx-support-section` | no | bg | none | no | |
| support_section_heading | Text | | '' | refresh | no | no | | page-knowledgebase-one.php:21 | no | |
| support_section_content | Textarea | | '' | refresh | no | no | | :22 | no | |
| support_section_title_typo | Typography | | #333333 | refresh | `.knowx-support-title` | no | | none | no | |
| support_section_content_typo | Typography | | #333333 | refresh | `.knowx-support-content, ... p` | no | | none | no | |
| support_section_button_text | Text | | '' | refresh | no | no | | :24 | no | |
| support_section_button_link_url | URL | | '' | refresh | no | no | Kirki esc_url_raw | :25 | no | |
| knowx_page_title | Radio | yes/no | 'yes' | refresh | no | no | choice | page-knowledgebase-two.php:29 | no | `== 'yes'` string (NOT toggle) |
| knowx_view_all | Radio | yes/no | 'no' | refresh | no | no | choice | page-knowledgebase-two.php:105 | no | `== 'yes'` |
| knowx_cat_description | Radio | yes/no | 'no' | refresh | no | no | choice | page-knowledgebase-two.php:64 | no | `== 'yes'` |
| knowx_exclude | Text | csv ids | none | refresh | no | no | text | page-knowledgebase-two.php:48-49 | no | truthy |
| knowx_posts | Number | int | none | refresh | no | no | Kirki number | page-knowledgebase-two.php:70-71 | no | truthy; unvalidated min |
| knowx_order | Radio | date/name | 'date' | refresh | no | no | choice | page-knowledgebase-two.php:76 | no | `== 'name'` |
| site_title_typography_option | Typography | array | Open Sans 600 38px ... | refresh | `.site-title a` | no | typo | none | no | |
| site_tagline_typography_option | Typography | | Open Sans regular 14px | refresh | `.site-description` | no | | none | no | |
| h1_ ... h6_typography_option (6) | Typography | | Open Sans 500, sizes 30/24/22/20/18/16px | refresh | `h1`..`h6` | no | | none | no | |
| menu_typography_option | Typography | | Open Sans 500 14px lh1.6 ls .02em | refresh | 2 outputs (selector list; `.nav--toggle-small .menu-toggle` border-color) | no | | none | no | |
| typography_option (body) | Typography | | Open Sans regular 14px #505050 | refresh | `body:not(.block-editor-page):not(.wp-core-ui), ... pre` | no | | none | no | |
| enable_custom_login | Checkbox_Switch | toggle | '' | refresh | no | no | bool | login.php:68 | no | truthy |
| enable_custom_login_logo | Checkbox_Switch | toggle | '' | refresh | no | enable_custom_login==true | bool | login.php:85,94 | no | strict `true ===` |
| custom_login_logo_image | Image | url/id | '' | postMessage | no | login on and logo disable==false | Kirki image | login.php:86,102,105 | no | |
| custom_login_logo_image_width / _height / custom_login_logo_space | Dimension | css len | 84px / 84px / 0px | postMessage | no | same | dimension | login.php:87-89, 108-115 | no | |
| custom_login_logo_url | URL | | '' | postMessage | no | same | esc_url_raw | login.php:27 | no | |
| custom_login_logo_title | Text | | '' | postMessage | no | same | | login.php:40 | no | |
| custom_login_page_title | Text | | '' | postMessage | no | enable_custom_login==true | | login.php:55 | no | |

Typography-array reads: none in PHP. Typography/Background/Dimension arrays are consumed exclusively by Kirki output + Kirki Webfonts (fonts). 15 Typography + 4 Background + 1 Dimension(container) = 20 fields with Kirki `output` that vanish when Kirki is removed (the migration must re-implement via Output_Builder or tokens).
Active_callback comparison operators used: `==`, `!==` with values `'1'`, `1`, `true`, `false`, `'default-layout'` (mixed types, Kirki JS loose compare; customizer-controls.js normalises 'on'/'1'/1/true).
Mods read that are NOT registered settings: `site_custom_colors` (Dynamic_Style:41), `site_cart` (extra.php:90).
Setting ids in field files not read anywhere in PHP: site_container_width, site_sub_header_bg, site_footer_bg, all *_typography*/*_typo, all *_background_setting, site_load_google_font_locally, site_preload_local_font, header/body/links colours except via Dynamic_Style.

---------------------------------------------------------------------
## 2. KIRKI COUPLING LIST

| # | where | what | silent behaviour if Kirki missing |
|---|---|---|---|
| 1 | functions.php:25-27 | requires `external/require_plugins.php` (TGMPA) + `kirki-utils.php` | TGMPA nags admin to install Kirki (required=false so recommended) |
| 2 | external/require_plugins.php:63-69 | TGMPA entry slug 'kirki'; L36 `tgmpa_register` | - |
| 3 | external/kirki-utils.php:3 | `knowx_defaults()` (misnamed; not Kirki API) | none |
| 4 | inc/Theme.php:162 | `new Kirki\Component()` | - |
| 5 | inc/Theme.php:163 | `new Kirki_Option\Component()` (own gate) | |
| 6 | inc/Theme.php:172-174 | `class_exists('Kirki')` => Dynamic_Style | all 24 colour CSS vars (colour customisation) silently stop working |
| 7 | inc/Kirki/Component.php:11,36,44-51 | `use Kirki`, filter `kirki/config`, `Kirki::add_config` | broken inverted logic (fatal path) |
| 8 | inc/Kirki_Option/Component.php:32-35 | `class_exists('Kirki')` gate on panels/fields | Customizer panels/controls entirely absent: no UI at all |
| 9 | inc/Kirki_Option/Component.php:80-309 | `\Kirki\Panel`, `\Kirki\Section` | |
| 10 | Fields/*.php (11 files) | `\Kirki\Field\{Color,Typography,Checkbox_Switch,Text,Custom,Radio_Image,Radio,Dimension,Background,Textarea,URL,Select,Number,Image}` (90 instantiations) | |
| 11 | Fields (`output`, `transport=auto`) | Kirki CSS module: Typography(15), Background(4), Dimension(1) front-end CSS | no typography, no background, no container width: falls back to static CSS; fonts for typography not loaded |
| 12 | Kirki Webfonts module (implicit) | loads Google fonts for typography `font-family`/`variant` values | none; theme also loads Open Sans+Montserrat itself (Fonts/Component.php:229-230) |
| 13 | inc/Customizer/Component.php:50-72 | `class_exists('Kirki')`, handles `kirki-customizer`, `kirki_field_dependencies` | dependency/visibility JS not loaded |
| 14 | assets/js/src/customizer-controls.js:15,17,25,29 | `[data-kirki-setting]`, `data-kirki-setting-link`, `window.kirkiControlDependencies` | compiled as assets/js/customizer-controls.min.js; depends on Kirki internals |
| 15 | inc/login.php:12-14 | `class_exists('Kirki')` return => login customisation off | login logo/title mods ignored though plain theme_mods |
| 16 | Typography/Background `output` 'element' only (no property) | relies on Kirki mapping of array keys to CSS properties | |
| 17 | Kirki `theme_mod_{id}` default filter (implicit) | unsaved mods return field default only when Kirki active | after removal `get_theme_mod('x')` without default returns false |
| 18 | config/config.default.json:62-64 | export includes inc/Kirki/**, inc/Kirki_Option/**, kirki-utils.php | build copies files that must be removed |
| 19 | wp.org / readme / docs mention of Kirki (not audited here) | | |
| 20 | strings 'kirki' in class slugs: `get_slug()` 'kirki' (Kirki/Component.php:28), 'kirki_option' (Kirki_Option/Component.php:25) | | |
| TGMPA | external/class-tgm-plugin-activation.php (vendor, skipped), `tgmpa()` call L109, plugin list incl. Collapsing Categories & WordPress Popular Posts | removal drops these admin notices; templates that use WPP/CC need own fallbacks |

No other `kirki_*` function calls, `kirki/*` filters besides `kirki/config`, and no `Kirki::get_option`/`kirki_get_option` usage exist.

Behaviours that silently depend on Kirki being active:
1. Dynamic_Style colours (Theme.php:172, Dynamic_Style:40).
2. All typography (sizes, line heights, colours, families), site title/tagline, headings, menu, body.
3. Backgrounds for sub header, footer, KB hero, KB support section; container max-width.
4. Google font loading for chosen families (Kirki).
5. Login page customisations (login.php:12).
6. Customizer UI + control dependency JS.
7. `theme_mod` default values for unsaved fields.

---------------------------------------------------------------------
## 3. DEAD / DUPLICATE CODE

1. `inc/EZ_Customizer/*` (commented Theme.php:156); json still in export list.
2. `inc/Kirki/Component.php` (inverted dead logic, wrong config id).
3. `inc/Options/` admin settings page: menu commented (Component.php:41), `render_settings_page`, `settings-page.php`, REST route `my-theme/v1/settings` still live (rest_api_init) and writable by manage_options, `index.min.js` enqueued on all admin pages. `knowx-customizer-script` enqueue (L68-78) on `admin_enqueue_scripts` with `$pagenow==='customize.php'` never fires.
4. `inc/Blocks/Component.php`: `assets/blocks` does not exist.
5. `inc/Webfont/class-knowx-webfont-loader.php`: `knowx_get_webfont_url`, `knowx_load_preload_local_fonts`, `knowx_webfont_loader_instance` have zero callers; settings `site_load_google_font_locally`, `site_preload_local_font` unread; only the flush AJAX + customize_save_after + cron remain.
6. `inc/Fonts/Component.php`: `download_all_google_fonts` / `download_google_fonts_to_local` (only wp-cli, itself unloaded); `get_version`/`get_asset_version` duplicates (L67-91) of Template_Tags L137-161 and not exposed; `knowx_register_fonts` 'local-fonts' collection references nonexistent file; `get_local_font_path`, `get_relative_path` only used by dead downloader.
7. `wp-cli/knowx-commands.php`: never required (functions.php has no include).
8. `inc/Comments/Component.php::filter_comment_form_defaults` unhooked.
9. `inc/extra.php`: `knowx_site_menu_icon` (no callers), `knowx_metabox_callback` (never registered; dead UI for `knowx-exclude-cats`), `knowx_save_meta` effectively dead; `custom_bbp_no_breadcrumb` fine; `setPostViews` called single.php:42 but nothing displays `post_views_count` in audited files (WPP plugin may).
10. `inc/login.php`: three dead branches with undefined variables (L122-151), `knowx_is_login_page` unused.
11. `inc/Customizer/Component.php:111-117` empty `theme_options` section.
12. `inc/Dynamic_Style`: `site_custom_colors` never registered.
13. `inc/Template_Tags.php::get_theme_asset` - no callers found (grep: none).
14. `knowx_wp_body_open` shim (wordpress-shims.php) - WP 5.2+ has `wp_body_open`; min WP is 4.5 per functions.php but readme says 4.8.
15. `Styles/Component.php::print_styles` used by sidebar.php:16; ok. `Accessibility` IE11 script obsolete.
16. Duplicate Kirki default tables: field `default` values vs `knowx_defaults()` (differ: post_per_row 4 vs 3).
17. Duplicate copyright-default blocks in `knowx_footer_custom_text` (extra.php:125-151 two near-identical branches); and Custom_Background `default-color` vs `body_background_color`.
18. Duplicate `add_theme_support('custom-header'/'custom-background')` (Base_Support:86-87 + Custom_* components).
19. Unreachable: Sidebars/Component.php:197,221 `is_right_sidebar_active() && is_right_sidebar_active()` (duplicate operand; 'both' layout never checks left).
20. `sidebar.php:12` returns when EITHER sidebar inactive (`!left || !right`), so sidebar never renders unless both are active - logic bug (should be `&&`).
21. Hooks never needed: `knowx_wp_body_open`, `knowx_log` (no listener), `knowx_search_*_data_attrs` only AMP.

---------------------------------------------------------------------
## 4. SECURITY / CORRECTNESS

### High
1. **Remote CSS `include`d as PHP** - Webfont loader L225-240 `get_local_stylesheet_contents()` does `include $local_path` on a `.css` file whose contents come straight from `wp_remote_get($this->remote_url)` (L265) with no validation. Any `<?php` in the response (MITM, compromised host, or hostile `$url` passed to the helper functions) executes. Currently unreachable because `knowx_get_webfont_url` is never called, but the class is loadable. Must not be ported.
2. **Arbitrary file write by extension** - L309-353: file names/URLs taken from parsed remote CSS (`basename(wp_parse_url($url,PATH))`) and moved into uploads with no host allow-list (fonts.gstatic.com) or extension allow-list (woff2/woff); a `.php` URL in the CSS would be written inside uploads/knowx-local-fonts/. `download_url()` uses safe HTTP (mitigates SSRF to internal hosts) but no host pinning.
3. **Infinite recursion** - Webfont loader `get_styles()` (L184) <-> `write_stylesheet()` (L499-501): when the remote fetch fails (empty CSS), `write_stylesheet` sees `!$this->css` and calls `get_styles()` again, which calls `write_stylesheet()` ... stack overflow / memory exhaustion. Also leaves a 0-byte css file so the next request refetches remotely every time.
4. **Recursive delete of possibly the whole uploads dir** - L610-618 `get_fonts_folder()` appends subfolder only `if get_subfolder_name()`; `knowx_local_fonts_directory_name` filter returning '' makes folder = `wp-content/uploads/` and `knowx_delete_fonts_folder()` (L648-653, `$fs->delete(..., true)`) removes all uploads. Also base path hard-coded `wp_content_dir() . '/uploads/'` (L571) ignoring `UPLOADS`/`wp_upload_dir()`; runs from cron and from `customize_save_after`.
5. **PHP version gate wrong -> fatals on 7.4** (style.css/readme say 7.4.0, functions.php:13 says 7.0, composer platform 8.1): `Options/Component.php:141` union return `WP_REST_Response|WP_Error`; `Fonts/Component.php:331` `WP_Error|string`; `Blocks/Component.php:166,194` `mixed`. Parse errors on PHP 7.4. Also `Theme.php:31` typed property needs 7.4 (fails on 7.0-7.3 even though gate allows them). Trailing comma in call args Options L84 requires 7.3.
6. **Async + dependency race** - Scripts/Component.php:181-232 `knowx-custom` (jQuery plugin consumer) loaded `async` with deps superfish, isotope, fitvids, sticky-kit also async: execution order not guaranteed; intermittent "$.fn.superfish is not a function".

### Medium
7. CSRF on destructive AJAX: Webfont L769 `wp_ajax_knowx_regenerate_fonts_folder` has capability check (`edit_theme_options`, L753) but no nonce; JS (admin/knowx-customizer.js:4-10) posts no nonce. `wp_send_json_error` for no-priv fine.
8. `update_option('knowx_font_url')` written on front-end request path (L154) => DB write on page view; autoloaded option holding a remote URL if local write failed -> permanent fallback until monthly cleanup, which never runs (item 9).
9. Cron `'monthly'` (Webfont L112, L634): no `cron_schedules` filter in theme or WP core ('monthly' is not a core schedule) => `wp_schedule_event` fails every request; cleanup never scheduled; `wp_next_scheduled` + schedule attempt runs in constructor each time class is instantiated. Also hooks `knowx_delete_fonts_folder` only registered when constructed (so cron event, if it existed, would not fire on requests where class not built).
10. Wrong option namespace reuse: `get_site_option('ast_downloaded_font_files')` (L287, 366) is Astra's key; collides with Astra on same network. Font options are network-wide (site option) on multisite while font URL is per-site.
11. `WP_Filesystem()` can return false/null when credentials needed (FTP/SSH): `get_filesystem()->mkdir()` (L296) fatal on null; Fonts L430-435, 462-467 same. Never `request_filesystem_credentials`.
12. `sslverify => false` Fonts/Component.php:422 (font download).
13. Post meta box handler `knowx_save_meta` extra.php:234-250: raw `$_POST['knowx_nonce']` passed to `wp_verify_nonce` without `wp_unslash`/`sanitize_text_field` (L238); `sanitize_text_field($_POST['knowx-cats'])` without `wp_unslash` (L248) so slashes/escapes mutate; `get_post_type()` (L244) uses global post instead of `$post_id` (wrong in quick edit/REST, can skip or mis-save); no revision check; hooked on all `save_post`. Meta never rendered (dead) but handler still runs with attacker-reachable input only if nonce valid.
14. Unescaped output of stored values: extra.php:137,150 copyright HTML (kses done by Kirki Textarea only; framework must `wp_kses_post` on save AND output); Dynamic_Style CSS values raw into `wp_add_inline_style` (no `sanitize_hex_color`/regex; `</style>` break out is stripped by WP? `wp_add_inline_style` strips `</style>` tags but not CSS injection); login.php:105 `esc_url` in url() ok, L109-115 `esc_attr` for CSS values (wrong context), L136 esc_url on colour; login.php:28,41 raw mod returned for header url/title (core escapes later, ok); page-knowledgebase-two.php:49,71 `esc_attr()` used as "sanitiser" on ids/numbers then concatenated into queries (see template; not in audited scope but relevant to migration); extra.php:44 etc fine.
15. Options REST endpoint: `update_settings` only checks `manage_options` (ok) but `sanitize_theme_settings` treats arrays as '' (data loss) and accepts arbitrary keys (unbounded option growth). Namespace `my-theme/v1`.
16. Activation: overwrites `show_on_front` + `page_on_front` on first activation (Activation/Component.php:46-47) even when the site already has a front page; creates a page `knowledge-base` (no existence check by slug, duplicates after DB resets); returns WP_Error treated as id (L72-74).
17. `setPostViews` (extra.php:257-268): unauthenticated write on every single view (L42 single.php), race condition lost updates, defeats full-page caching semantics, bots inflate; `$count == ''` and 0 handling re-adds meta.
18. Breadcrumbs: L914 wrong parenthesis; L942 `esc_url()` around HTML; L1073+ undefined `$post` after empty loop; schema microdata http; `$term->name` etc output unescaped.
19. Login.php undefined variables (L122-151) => PHP 8 warnings "Undefined variable" if branches ever evaluated (they short-circuit on `true === $undefined` => warning each login page load when `enable_custom_login` is on).
20. functions.php:72-75 + L26 loads TGMPA and wp-admin/includes/plugin.php on every front-end request.
21. Fonts: `wp_resource_hints` crossorigin array shape (L209-212) invalid; duplicate `display=swap` param; fonts not preloaded.
22. Deprecated/legacy: `esc_html('x','knowx')` extra args (extra.php:132,145); `date()` in CLI; `FILE_USE_INCLUDE_PATH` misuse (EZ:88); `add_filter('init')` (Kirki_Option:34); `wp_localize_script` used for non-translation data (works).
23. Sidebar logic bugs: sidebar.php:12 (`||`), Sidebars:197/221 (right twice).
24. header.php:27 uses `knowx_wp_body_open()` instead of `wp_body_open()`.
25. Transient `knowx_category_count` never expires/invalidates.
26. `phpcs:ignore` blanket escapes on `echo apply_filters(...)` extra.php:97,99 (filters receive data, attr output from AMP only, fine).
27. eval/unserialize/create_function: none found. `$_GET`/`$_POST` direct uses: extra.php:235-248, back-compat.php:57,92 (isset only), searchform.php:9 (empty check only).

---------------------------------------------------------------------
## 5. PERFORMANCE / LARGE-SITE

1. Per-request remote HTTP: EZ_Customizer (dead) loopback; Webfont loader `get_styles()` on empty-cache path performs remote CSS fetch and font downloads synchronously on a front-end request (download_url default 300 s timeout x N files) - not called currently.
2. Webfont: DB write via `update_option` on page request (L154); `get_option('knowx_font_url')` autoloaded.
3. Every request: parses 3.7k-line TGMPA (functions.php:26), loads `wp-admin/includes/plugin.php` (L73-75), `glob()` of assets/blocks on `init` (Blocks:73, dir missing so `is_dir` short-circuits), `get_theme_mod` x ~50 (cached by WP), Dynamic_Style builds string.
4. Admin: `knowx-theme-settings` JS/CSS + `filemtime()` + localised option on every admin page (Options:59-96).
5. Scripts: ~7 JS files + 8 CSS handles loaded globally incl. isotope, sticky-kit, fontawesome (full) on every page; all `async`.
6. `knowx_categorized_blog()` runs `get_categories(['hide_empty'=>1])` (loads all category objects) to `count()` (extra.php:163-164): unbounded on sites with thousands of terms; result cached in a never-expiring transient (stale), call on each post meta render (transient hit thereafter).
7. KB templates (page-knowledgebase-*.php): `knowx_posts` unvalidated numeric, `get_post_meta`/queries per category (N+1 per category, not audited in depth; Layout Two loops categories running a query each, unbounded categories - flag for follow-up).
8. `setPostViews` write per view (extra.php:257) - hot row, cache-busting object cache invalidation on `post_views_count` for each view.
9. Image sizes filters call `is_active_sidebar` per image (cached internally).
10. Options autoload: `knowx_theme_settings` (Options:150 update_option default autoload yes), `knowx_theme_activated`, `knowx_font_url`. theme_mods_knowx is a single autoloaded row (all 84 mods, includes Typography arrays - ~15 KB) fine.
11. Font loading: Open Sans + Montserrat requested from Google on every page even when typography mods choose other families (and Kirki loads its own set) => duplicated requests; weights 100..900 lists.
12. Inline `onload` stylesheet preload pattern and per-handle `<link rel=preload>`: cosmetic.

---------------------------------------------------------------------
## 6. HOOKS INVENTORY

### 6a. add_action / add_filter registered by the theme (name | file:line)
Kirki/Component.php:36 `kirki/config` (filter).
Kirki_Option/Component.php:33 `init` -> add_panels_and_sections; :34 `init` (via add_filter) -> add_fields; :36,:37,:38 `body_class` x3.
Customizer/Component.php:41 `customize_register`; :42 `customize_preview_init`; :44 `customize_controls_enqueue_scripts` (closure).
Dynamic_Style/Component.php:32 `wp_enqueue_scripts` prio 20.
Options/Component.php:40 `admin_enqueue_scripts`; :41 (commented) `admin_menu`; :42 `rest_api_init`.
Activation/Component.php:30 `after_switch_theme`.
Fonts/Component.php:45 `wp_enqueue_scripts`; :46 `after_setup_theme`; :47 `init`; :48 `wp_resource_hints`.
Styles/Component.php:68 `wp_enqueue_scripts`; :69 `wp_head`; :70 `after_setup_theme`.
Scripts/Component.php:66 `wp_enqueue_scripts`.
Sidebars/Component.php:48 `widgets_init`; :49 `body_class`.
Base_Support/Component.php:42 `init` prio -999; :43 `after_setup_theme` prio 1; :44 `wp_head`; :45 `body_class`; :46 `embed_defaults`; :47 `theme_scandir_exclusions`; :48 `script_loader_tag`; :49 `init`; :50 `init`.
Accessibility/Component.php:40 `wp_enqueue_scripts`; :41 `wp_head`; :42 `wp_print_footer_scripts`; :43 `nav_menu_link_attributes`; :44 `page_menu_link_attributes`.
AMP/Component.php:41 `after_setup_theme`; :42 `body_class`; :43 `knowx_search_slide_toggle_data_attrs`; :44 `knowx_search_field_toggle_data_attrs`.
Comments/Component.php:49 `wp_enqueue_scripts`.
Nav_Menus/Component.php:46 `after_setup_theme`; :47 `walker_nav_menu_start_el`.
Image_Sizes/Component.php:34 `wp_calculate_image_sizes`; :35 `get_header_image_tag`; :36 `wp_get_attachment_image_attributes`.
Post_Thumbnails/Component.php:35,:36 `after_setup_theme`.
Custom_Background/Component.php:33; Custom_Header/Component.php:39; Custom_Logo/Component.php:35; PWA/Component.php:34; Jetpack/Component.php:37; Localization/Component.php:48 (prio 1); Editor/Component.php:34 - all `after_setup_theme`.
Excerpts/Component.php:33 `excerpt_more`.
Blocks/Component.php:42 `init`.
extra.php:12 `knowx_before_content`; :23 `knowx_after_content`; :27 `knowx_sub_header`; :217 `elementor/theme/register_locations`; :251 `save_post`; :276 `bbp_no_breadcrumb`.
login.php:34 `login_headerurl`; :47 `login_headertext`; :62 `login_title`; :74 `init`; :71 `login_head` prio 150 (conditional, inside init callback).
Webfont loader:128 `knowx_delete_fonts_folder` (per instance); :769 `wp_ajax_knowx_regenerate_fonts_folder`; :782 `customize_save_after`.
widgets/category-widget.php:161 `widgets_init`.
external/require_plugins.php:36 `tgmpa_register`.
back-compat.php:59 `admin_notices`; :61 `after_switch_theme`; :85 `load-customize.php`; :96 `template_redirect` (only loaded when requirements fail).
Template-level: offline.php:13 `add_filter('has_nav_menu','__return_false')`.

### 6b. Fired by the theme (apply_filters / do_action)
Actions: `knowx_before_content` / `knowx_after_content` (all templates incl. index/archive/single/page/404/500/full-width-container/bbpress/KB/optional/*), `knowx_sub_header` (index.php:29, archive.php:26, single.php:26, page.php:26, full-width-container.php:18, bbpress.php:25, page-knowledgebase-two.php:20), `knowx_wp_body_open` (wordpress-shims.php:18), `knowx_log` (Blocks:64,69,77,87,123,128).
Filters: `knowx_search_slide_toggle_data_attrs` (extra.php:97), `knowx_search_field_toggle_data_attrs` (:99), `knowx_theme_author` (extra.php:129,142), `knowx_footer_copyright_text` (:137,150), `knowx_google_fonts` (Fonts:238), `knowx_css_files` (Styles:308), `knowx_preloading_styles_enabled` (Styles:247), `knowx_js_files` (Scripts:244), `knowx_custom_background_args` (Custom_Background:43), `knowx_custom_header_args` (Custom_Header:48), `knowx_custom_logo_args` (Custom_Logo:44), `knowx_customizer_settings` (EZ:88, dead), `knowx_local_font_file_name` (Webfont:536), `knowx_local_google_fonts_format` (:548,:702), `knowx_local_fonts_base_path` (:571), `knowx_local_fonts_base_url` (:585), `knowx_local_fonts_directory_name` (:599), `knowx_attr_{ctx}` (breadcrumbs:62), `knowx_attr_{ctx}_output` (:35), `knowx_breadcrumb_trail_args` (:84,:196), `knowx_breadcrumb_trail_object` (:86), `knowx_breadcrumb_trail` (:331), `knowx_breadcrumb_trail_labels` (:376), `knowx_breadcrumb_trail_post_taxonomy` (:396), `knowx_breadcrumb_trail_items` (:484). Core filters re-applied: `post_type_archive_title`, `widget_title`, `widget_categories_args`, `widget_categories_dropdown_args`, `navigation_widgets_format`.
Cron: `knowx_delete_fonts_folder` (event never actually scheduled, see §4.9).

---------------------------------------------------------------------
## 7. MIGRATION RISKS (things a token/Customizer_Framework port could miss)

1. **Default values are not centralised**: only 11 settings have `knowx_defaults()` entries; the rest rely on Kirki returning its field default via `theme_mod_*` filter. Framework must register every default (and fix mismatches: post_per_row 4 vs 3; site_loader '2'; Checkbox defaults 'on'/'1'/'').
2. **Toggle storage shape**: legacy 'on'/'off' strings would flip meaning under truthy reads (see §1a). Provide one `Customizer_Framework::is_on()` helper and apply to all 14 toggles; add a one-time migration or tolerant reads. Never use `== '1'` (site_loader) or `true ===` (enable_custom_login_logo).
3. **site_loader default**: effective frontend default is OFF (`'2' != '1'`) for unsaved sites; plan §3.3 states "loader on". Verify with a DB snapshot before choosing the new default.
4. **Typography/Background/Dimension output**: 20 fields have no PHP reader; their CSS exists only through Kirki. Selector lists to preserve: see Typography_Fields.php outputs, KB hero/support, `.container`, `.site-sub-header`, `.site-footer-wrapper`. Menu typography has a second output for border-color. Output must emit only when not default (avoid overriding stylesheet), and honour the toggle gating (`site_sub_header_bg`, `site_footer_bg`) which Kirki did NOT.
5. **Colours via CSS variables** in Dynamic_Style: 24 var names are a contract with `assets/css/*.css` (168 hard-coded hex per plan). Keep names or map to `--bx-*` tokens. `site_custom_colors` switch is undefined; drop.
6. **Google fonts**: Fonts component loads Open Sans/Montserrat statically; Kirki loads chosen families. A value-driven loader must replace both and must also handle the three Performance settings (currently dead) and the flush AJAX (JS `.knowx-flush-font-files` in admin/knowx-customizer.js, loaded only via the dead `$pagenow` enqueue).
7. **Customizer JS**: `assets/js/customizer-controls.min.js` (Kirki internals) and `assets/js/customizer.min.js` (core title/desc preview) and `admin/knowx-customizer.min.js`. `knowx-customizer.min.css` + inline `.knowx-force-hidden-by-mode`.
8. **Body classes depending on mods**: `layout-{site_layout}`, `sticky-sidebar-enable`, `sticky-header` (Kirki_Option:45,56,67), sidebar classes `has-sidebar-left/right/both` and bbPress variants (Sidebars:182-228), `knowx-amp`, `hfeed`. These are registered in Kirki_Option component and must survive its deletion/rename.
9. **Inline output**: `<style>` in `login_head` (login.php:91-153), Custom_Header `wp_head_callback` (L65-78), Dynamic_Style inline on handle `knowx-global`; `knowx-global` must stay `global=>true` in Styles.
10. **JS localised data**: `knowx_data.enable_scrollup` (Scripts:227-231, consumed custom.js:248 `== true`), `knowxScreenReaderText` (Accessibility:66), `knowxThemeSettings` (Options:89). `ajaxurl` used by flush JS.
11. **Template reads** (outside inc/): index/archive/single/page/bbpress/sidebar/entry-layout/KB pages read mods directly; sidebar.php:22 reads `sidebar_option` without default; page-knowledgebase-one.php:18-25,70-71; page-knowledgebase-two.php:29-105 (string radios yes/no). Provide helpers and keep ids.
12. **Admin pages / REST**: `my-theme/v1/settings` REST route + React page are orphaned; decide remove vs keep. Activation hook creates page & overwrites front page; migration may want to only seed content on fresh installs (Starter_Content).
13. **Activation / theme_mod migrations**: none exist today (no version option, no upgrade routine, `knowx_theme_activated` only). Port needs a version-stamped migration routine (`knowx_db_version`) for any key rename or shape normalisation.
14. **Build config**: `config/config.default.json:60-64` export list; `composer.json` platform 8.1 vs header 7.4; `phpstan`, `tests/phpunit` referencing Kirki components (not audited).
15. **bbPress conditional field** registered at `init` via `function_exists('is_bbpress')` (Sidebar_Fields.php:29): framework must register at `customize_register` and still guard.
16. **Kirki-registered default filter side effect**: some reads use `get_theme_mod('x')` without default (Dynamic_Style, login.php, KB templates, entry-layout missing key). With Kirki gone these return false/'' not the former default (e.g. `knowx_page_title` default 'yes' => `== 'yes'` fails on unsaved sites, KB layout two page title hidden; `knowx_order` 'date'; `site_copyright_text` falls to hard-coded fallback; hero/support bg colours disappear).
17. **Recommended plugins**: TGMPA entries for Collapsing Categories and WordPress Popular Posts; templates rely on them (guard needed).
18. **Selective refresh**: only blogname/blogdescription partials exist; all other changes full-refresh; Kirki `auto` transport gave live preview for 5 settings that will regress to refresh unless re-implemented.
19. **Preload/CSP**: inline `onload` preload (Styles:175) and inline scripts (Accessibility:101, widget script) matter if a CSP/Playground target is planned.
20. **i18n**: hard-coded strings: extra.php:139, Site_Performance.php:52 button value, WP_Login/Customizer description strings, footer-small pattern.

---------------------------------------------------------------------
## TOP DEFECTS (ranked)
1. Webfont loader `include`s remote-derived CSS file as PHP (L238).
2. Webfont loader writes remote-named files with no host/extension allow-list (L309-353).
3. Webfont loader infinite recursion on failed fetch (get_styles <-> write_stylesheet).
4. Webfont delete can wipe entire uploads dir with empty subfolder filter (L610-653).
5. PHP 8.0-only syntax (union types, `mixed`) with a declared PHP 7.4 minimum, and a wrong 7.0 gate in functions.php:13.
6. All 24 colour vars, all typography/background/container output, login customisations and the Customizer UI disappear silently without Kirki; unsaved mods lose their defaults (Theme.php:172, Dynamic_Style:40, login.php:12, Kirki_Option/Component.php:32).
7. Toggle reads mis-handle legacy 'on'/'off' strings (truthy/`== '1'`/`true ===`/(int)); site_loader default '2' makes loader off by default and contradicts plan.
8. Async scripts with dependency chain (Scripts/Component.php:181-232).
9. Sidebar logic bugs: sidebar.php:12 `||`, Sidebars:197/221 duplicate operand; `knowx_wp_body_open` instead of `wp_body_open` (header.php:27).
10. Dead/broken settings & UI: meta box never registered (extra.php:223), site_load_google_font_locally/site_preload_local_font/site_sub_header_bg/site_footer_bg unread, WP Login postMessage with no preview JS, Kirki Component inverted `class_exists`, cron 'monthly' undefined, setPostViews write-per-view, Activation overwrites front page.
