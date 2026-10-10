#!/usr/bin/env python3
"""Settings QA for KnowX in the Docker stack (tests/docker/docker-compose.yml).

For every Customizer setting: apply a non-default value, fetch nine page types, and record
  - whether the page responds 200 (no fatal),
  - whether the rendered HTML changed against the baseline (a setting that changes nothing is reported),
  - whether PHP wrote anything to debug.log.
Then the setting is put back. Usage:  python3 settings_qa.py  (stack up, theme active, sample content seeded)
Writes docs/qa/SETTINGS_QA.md.
"""
import json
import re
import subprocess
import sys
import urllib.request
from pathlib import Path

HERE = Path(__file__).resolve().parent
BASE = 'http://localhost:8089'
PAGES = {
    'docs home': '/',
    'product': '/category/orbit-api/',
    'section': '/category/orbit-api-guides/',
    'article': '/get-an-api-key/',
    'plain post': '/plain-post/',
    'blog index': '/?page_id=BLOG',
    'date archive': '/2026/',
    'tag archive': '/tag/api/',
    'search': '/?s=webhook',
    'faq': '/faq/',
    'docs index': '/?page_id=INDEX',
}

TYP = {'font-family': 'System', 'font-size': '19px', 'variant': '700', 'line-height': '1.7', 'letter-spacing': '1px', 'text-transform': 'uppercase', 'color': '#aa3300'}
BG = {'background-color': '#334455'}

# setting -> list of values to try (one run per value)
TESTS = [
    ('site_color_scheme', ['cool', 'minimal', 'monochrome', 'pastel', 'vibrant', 'warm']),
    ('site_primary_color', ['#aa2255']),
    ('background_color', ['ffeedd']),
    ('site_color_mode', ['dark', 'auto']),
    ('site_color_mode_toggle_show', [1]),
    ('site_layout', ['boxed']),
    ('site_container_width', ['1000px']),
    ('site_loader', [1]),
    ('knowx_scroll_top', [1]),
    ('site_title_typography_option', [TYP]),
    ('site_tagline_typography_option', [TYP]),
    ('headings_typography_option', [TYP]),
    ('menu_typography_option', [TYP]),
    ('typography_option', [TYP]),
    ('site_sticky_header', [0]),
    ('site_header_bg_color', ['#ffeecc']),
    ('site_sub_header', [0]),
    ('sub_header_background_setting', [BG]),
    ('site_breadcrumbs', [0]),
    ('site_search', [1]),
    ('knowx_home_layout', ['sections']),
    ('knowx_show_updated', [0]),
    ('knowx_show_toc', [0]),
    ('knowx_show_pager', [0]),
    ('knowx_show_related', [0]),
    ('knowx_show_version', [0]),
    ('knowx_recent_by', ['newest']),
    ('knowx_home_count', ['3']),
    ('knowx_show_recent', [0]),
    ('knowx_show_support', [0]),
    ('blog_layout_option', ['grid-layout', 'masonry-layout']),
    ('post_per_row', ['6']),
    ('post_box_view_layout', [1]),
    ('sidebar_option', ['left', 'both', 'none']),
    ('sticky_sidebar_option', [0]),
    ('hero_section_background_setting', [BG]),
    ('hero_section_heading', ['Hero heading QA']),
    ('hero_section_content', ['Hero content QA']),
    ('knowx_exclude', ['CATEXCL']),
    ('support_section_background_setting', [BG]),
    ('support_section_heading', ['Support heading QA']),
    ('support_section_content', ['Support content QA']),
    ('support_button', [[('support_section_button_text', 'Support button QA'), ('support_section_button_link_url', 'https://example.com/help')]]),
    ('knowx_page_title', ['no']),
    ('knowx_view_all', ['yes']),
    ('knowx_cat_description', ['yes']),
    ('knowx_posts', [1]),
    ('knowx_order', ['name']),
    ('knowx_section_sort', ['title', 'updated']),
    ('background_setting', [BG]),
    ('site_footer_content_color', ['#ff0000']),
    ('site_copyright_text', ['Copyright QA [current_year] [site_title]']),
    ('site_copyright_background_color', ['#001122']),
    ('site_copyright_content_color', ['#ffffff']),
]

# Settings whose effect is only visible on some page types or after JavaScript: asserted separately.
NOTES = {
    'site_color_mode_toggle_show': 'adds the toggle button',
    'knowx_posts': 'only the Docs Index template',
    'knowx_order': 'only the Docs Index',
    'knowx_section_sort': 'only section pages, and only when the articles differ in the sorted field',
}


def wp(*args, stdin=None):
    r = subprocess.run([str(HERE / 'wp'), *args], capture_output=True, text=True)
    return r.stdout.strip()


def fetch(path):
    try:
        with urllib.request.urlopen(BASE + path, timeout=30) as r:
            return r.status, r.read().decode('utf8', 'replace')
    except urllib.error.HTTPError as e:
        return e.code, e.read().decode('utf8', 'replace')


def norm(html):
    html = re.sub(r'ver=\d+', 'ver=N', html)
    html = re.sub(r'(nonce|_wpnonce)[^>]{0,60}value="[^"]+"', r'\1', html)
    html = re.sub(r'\b[0-9a-f]{10}\b', 'H', html)
    html = re.sub(r'search-field-\d+', 'search-field-N', html)
    html = re.sub(r'comment_post_ID[^>]*', 'cp', html)
    return html


RAW = {}
BASECNT = {}


def snapshot(pages):
    out = {}
    for name, path in pages.items():
        status, html = fetch(path)
        RAW[name] = html
        out[name] = (status, norm(html))
    return out


# What the front end must contain after a setting is applied: (page, regex, should_be_present)
EXPECT = {
    ('site_color_scheme', 'cool'): ('docs home', r'--color-surface:#F8FAFC', True),
    ('site_color_scheme', 'warm'): ('article', r'--color-theme-primary:#C2410C', True),
    ('background_color', 'ffeedd'): ('docs home', r'--color-theme-body:#ffeedd', True),
    ('site_primary_color', '#aa2255'): ('docs home', r'--color-theme-primary:#aa2255', True),
    ('site_color_mode', 'dark'): ('docs home', r'var mode="dark"', True),
    ('site_color_mode', 'auto'): ('docs home', r'var mode="auto"', True),
    ('site_color_mode_toggle_show', 1): ('docs home', r'color-mode-toggle', True),
    ('site_layout', 'boxed'): ('docs home', r'layout-boxed', True),
    ('site_container_width', '1000px'): ('docs home', r'1000px', True),
    ('site_loader', 1): ('docs home', r'class="site-loader"', True),
    ('knowx_scroll_top', 1): ('docs home', r'"enableScrollup":"?1"?|enableScrollup\W+(true|1)', True),
    ('site_title_typography_option', None): ('docs home', r'text-transform:\s*uppercase', True),
    ('headings_typography_option', None): ('article', r'text-transform:\s*uppercase', True),
    ('menu_typography_option', None): ('docs home', r'text-transform:\s*uppercase', True),
    ('typography_option', None): ('article', r'text-transform:\s*uppercase', True),
    ('site_sticky_header', 0): ('docs home', r'sticky-header', False),
    ('site_header_bg_color', '#ffeecc'): ('docs home', r'--color-header-bg:#ffeecc', True),
    ('site_sub_header', 0): ('article', r'site-sub-header', False),
    ('site_breadcrumbs', 0): ('article', r'knowx-breadcrumbs', False),
    ('site_search', 1): ('article', 'COUNT_UP', True),
    ('knowx_home_layout', 'sections'): ('docs home', r'class="knowx-home-product"', True),
    ('knowx_show_updated', 0): ('article', r'Last updated', False),
    ('knowx_show_toc', 0): ('article', r'class="knowx-toc', False),
    ('knowx_show_pager', 0): ('article', r'class="knowx-pager"', False),
    ('knowx_show_related', 0): ('article', r'class="knowx-related"', False),
    ('knowx_show_version', 0): ('article', r'class="knowx-version"', False),
    ('knowx_recent_by', 'newest'): ('docs home', r'Recent Articles', True),
    ('knowx_show_recent', 0): ('docs home', r'class="knowx-recent-posts"', False),
    ('knowx_show_support', 0): ('docs home', r'class="knowx-support-section"', False),
    ('blog_layout_option', 'grid-layout'): ('blog index', r'grid-layout', True),
    ('blog_layout_option', 'masonry-layout'): ('blog index', r'masonry-layout', True),
    ('post_per_row', '6'): ('blog index', r'col-md-6', True),
    ('sidebar_option', 'left'): ('blog index', r'id="secondary-left"', True),
    ('sidebar_option', 'both'): ('blog index', r'id="secondary"', True),
    ('sidebar_option', 'none'): ('blog index', r'<aside id="secondary', False),
    ('hero_section_heading', 'Hero heading QA'): ('docs home', r'Hero heading QA', True),
    ('hero_section_content', 'Hero content QA'): ('docs home', r'Hero content QA', True),
    ('knowx_exclude', 'CATEXCL'): ('docs home', r'Orbit API</h2>', False),
    ('support_section_heading', 'Support heading QA'): ('docs home', r'Support heading QA', True),
    ('support_section_content', 'Support content QA'): ('docs home', r'Support content QA', True),
    ('support_button', None): ('docs home', r'href="https://example.com/help"[^>]*>Support button QA', True),
    ('knowx_page_title', 'no'): ('docs index', r'<h1 class="entry-title">', False),
    ('knowx_view_all', 'yes'): ('docs index', r'View All', True),
    ('knowx_cat_description', 'yes'): ('docs index', r'cat-description', True),
    ('knowx_posts', 1): ('docs index', r'cat-list', True),
    ('site_footer_content_color', '#ff0000'): ('docs home', r'#ff0000', True),
    ('site_copyright_text', None): ('docs home', r'Copyright QA ' + '20', True),
    ('site_copyright_background_color', '#001122'): ('docs home', r'#001122', True),
    ('site_copyright_content_color', '#ffffff'): ('docs home', r'--color-copyright-content:#ffffff', True),
    ('background_setting', None): ('docs home', r'#334455', True),
    ('hero_section_background_setting', None): ('docs home', r'#334455', True),
    ('support_section_background_setting', None): ('docs home', r'#334455', True),
    ('sub_header_background_setting', None): ('article', r'#334455', True),
}


def check_expect(key, value):
    ek = (key, value) if (key, value) in EXPECT else (key, None)
    if ek not in EXPECT:
        return None
    page, rx, present = EXPECT[ek]
    if rx == 'COUNT_UP':
        return 'ok' if RAW[page].count('class="searchform"') > BASECNT[page] else 'FAIL: %s search form count did not rise' % page
    if rx == 'COUNT':
        return 'ok' if RAW[page].count('class="searchform"') < BASECNT[page] else 'FAIL: %s search form count did not drop' % page
    found = re.search(rx, RAW[page]) is not None
    return 'ok' if found == present else 'FAIL: %s %s %r' % (page, 'lacks' if present else 'still has', rx)


def debug_log_size():
    return int(wp('eval', 'echo file_exists(WP_CONTENT_DIR."/debug.log") ? filesize(WP_CONTENT_DIR."/debug.log") : 0;') or 0)


def setmod(key, value):
    payload = json.dumps(value).replace("\\", "\\\\").replace("'", "\\'")
    out = wp('eval', "set_theme_mod('%s', json_decode('%s', true)); echo 'ok';" % (key, payload))
    if out != 'ok':
        raise SystemExit('could not set %s: %s' % (key, out))


def main():
    index_id = wp('post', 'list', '--post_type=page', '--name=docs-index', '--field=ID')
    if not index_id:
        index_id = wp('post', 'create', '--post_type=page', '--post_title=Docs Index', '--post_status=publish', '--porcelain')
        wp('post', 'meta', 'update', index_id, '_wp_page_template', 'page-knowledgebase-two.php')
    blog_id = wp('post', 'list', '--post_type=page', '--name=blog', '--field=ID')
    if not blog_id:
        blog_id = wp('post', 'create', '--post_type=page', '--post_title=Blog', '--post_status=publish', '--porcelain')
    wp('option', 'update', 'page_for_posts', blog_id)
    pages = {k: v.replace('INDEX', index_id).replace('BLOG', blog_id) for k, v in PAGES.items()}
    cat_id = wp('term', 'get', 'category', '--by=slug', 'orbit-api', '--field=term_id')

    wp('eval', 'knowx_kb_bump_version();')
    base1 = snapshot(pages)
    base2 = snapshot(pages)
    BASECNT.update({n: RAW[n].count('class="searchform"') for n in pages})
    noisy = {k for k in pages if base1[k] != base2[k]}
    print('baseline noise on:', sorted(noisy) or 'none')
    log0 = debug_log_size()

    rows = []
    for key, values in TESTS:
        for value in values:
            v = cat_id if value == 'CATEXCL' else value
            if isinstance(value, list):
                for k2, v2 in value:
                    setmod(k2, v2)
            else:
                setmod(key, v)
            wp('eval', 'knowx_kb_bump_version();')
            snap = snapshot(pages)
            changed = [k for k in pages if k not in noisy and snap[k][1] != base1[k][1]]
            bad = [k for k in pages if snap[k][0] != base1[k][0]]
            log1 = debug_log_size()
            verdict = check_expect(key, value if not isinstance(value, (dict, list)) else None)
            rows.append((key, json.dumps(value)[:34], changed, bad, log1 - log0, verdict))
            log0 = log1
            print(f'{key:36} {json.dumps(value)[:22]:24} changed={len(changed):2} status_diff={len(bad)} log+{rows[-1][4]} {verdict}')
            sys.stdout.flush()
        if isinstance(values[0], list):
            for k2, _ in values[0]:
                wp('theme', 'mod', 'remove', k2)
        else:
            wp('theme', 'mod', 'remove', key)

    wp('eval', 'knowx_kb_bump_version();')
    lines = ['# Settings QA', '', 'Generated by `tests/docker/settings_qa.py` on a fresh WordPress in Docker with the sample content. Each setting was changed alone, nine page types were fetched, and the rendered HTML was compared with the defaults.', '',
             '| Setting | Value tried | Pages that changed | Expected markup | Status differences | debug.log |', '|---|---|---|---|---|---|']
    for key, val, changed, bad, log, verdict in rows:
        lines.append(f'| `{key}` | `{val}` | {", ".join(changed) or "**none**"} | {verdict or "-"} | {", ".join(bad) or "-"} | {"+%d bytes" % log if log else "-"} |')
    fails = [r for r in rows if r[5] and r[5].startswith('FAIL')]
    lines += ['', f'Expected-markup failures: {len(fails)}.']
    unchanged = [r[0] for r in rows if not r[2]]
    lines += ['', f'Rows with no change in any page: {", ".join(sorted(set(unchanged))) or "none"}.']
    out = HERE.parent.parent / 'docs' / 'qa' / 'SETTINGS_QA.md'
    out.write_text('\n'.join(lines) + '\n')
    print('wrote', out)


if __name__ == '__main__':
    main()
