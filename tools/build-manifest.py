#!/usr/bin/env python3
"""Writes audit/manifest.json: what the theme registers, so new work reuses it instead of duplicating it.

Run from the theme root: python3 tools/build-manifest.py
Everything is read from the code (hooks, meta, templates, patterns, handles, settings); nothing is typed by hand.
"""
import json
import re
from pathlib import Path

root = Path(__file__).resolve().parent.parent
php = [p for p in root.rglob('*.php') if not any(x in p.parts for x in ('vendor', 'node_modules', 'tests', 'dist', 'playground'))]


def rel(p):
    return str(p.relative_to(root))


def find(pattern, flags=0):
    out = {}
    for p in php:
        for m in re.finditer(pattern, p.read_text(errors='ignore'), flags):
            out.setdefault(m.group(1), set()).add(rel(p))
    return {k: sorted(v) for k, v in sorted(out.items())}


def header_value(text, key):
    m = re.search(r'^\s*\*?\s*' + key + r':\s*(.+)$', text, re.M)
    return m.group(1).strip() if m else None


templates = {}
for p in sorted(root.glob('*.php')):
    name = header_value(p.read_text(errors='ignore'), 'Template Name')
    if name:
        templates[rel(p)] = name

patterns = {}
for p in sorted((root / 'patterns').glob('*.php')):
    t = p.read_text(errors='ignore')
    patterns[rel(p)] = {'title': header_value(t, 'Title'), 'slug': header_value(t, 'Slug')}

variations = sorted(p.stem for p in (root / 'styles').glob('*.json'))

settings = sorted({m for p in (root / 'inc' / 'Customizer_Settings' / 'Fields').glob('*.php') for m in re.findall(r"'settings'\s*=>\s*'([^']+)'", p.read_text(errors='ignore'))})

handles = {}
for kind, f in (('styles', 'inc/Styles/Component.php'), ('scripts', 'inc/Scripts/Component.php')):
    handles[kind] = re.findall(r"'(knowx-[a-z-]+)'\s*=>\s*array", (root / f).read_text())

meta = {
    'post_meta': find(r"register_post_meta\(\s*'post',\s*'([^']+)'"),
    'term_meta': find(r"register_term_meta\(\s*'category',\s*'([^']+)'"),
}

manifest = {
    'theme': 'knowx',
    'version': header_value((root / 'style.css').read_text(), 'Version'),
    'generated_by': 'tools/build-manifest.py',
    'components': sorted(re.findall(r'new (\w+)\\Component\(\)', (root / 'inc/Theme.php').read_text())),
    'filters': find(r"apply_filters\(\s*'(knowx_[a-z_]+)'"),
    'actions': find(r"do_action\(\s*'(knowx_[a-z_]+)'"),
    'meta': meta,
    'rest': {'custom_routes': [], 'registered_meta_in_rest': sorted(list(meta['post_meta']) + list(meta['term_meta']))},
    'page_templates': templates,
    'patterns': patterns,
    'pattern_categories': find(r"register_block_pattern_category\(\s*'([^']+)'"),
    'style_variations': variations,
    'asset_handles': handles,
    'customizer_settings': settings,
    'data_helpers': sorted(set(re.findall(r'^function (knowx_[a-z_]+)', (root / 'inc/docs.php').read_text() + (root / 'inc/knowledge-base.php').read_text(), re.M))),
    'options': sorted(set(re.findall(r"(?:get|update)_option\(\s*'(knowx_[a-z_]+)'", ''.join(p.read_text(errors='ignore') for p in php)))),
    'entry_points': {
        'frontend': ['docs home, product, section, article, FAQ, changelog, search overlay'],
        'backend': ['category screen: icon and accent', 'post editor: Order and Applies to version', 'Customizer'],
        'api': ['/wp/v2/categories (knowx_icon, knowx_accent)', '/wp/v2/posts (knowx_version, menu_order)'],
    },
}

out = root / 'audit' / 'manifest.json'
out.write_text(json.dumps(manifest, indent=2, sort_keys=False) + '\n')
print(f'{out.relative_to(root)}: {len(manifest["filters"])} filters, {len(manifest["actions"])} actions, {len(settings)} settings, {len(templates)} templates')
