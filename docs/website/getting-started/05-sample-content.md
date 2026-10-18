# Sample content

KnowX can build a complete example docs site for you, so you can see every template before you write your own articles. Nothing is created until you ask for it: activating the theme changes no page, menu or setting.

## Add sample docs

After you activate KnowX, the Dashboard and **Appearance > Themes** show a **Set up your docs site** notice with three buttons:

| Button | What it does |
|---|---|
| **Add sample docs** | Creates two products, Orbit Helpdesk and Orbit API, each with three sections and nine articles; a Documentation home page; an FAQ page; a Changelog page; and a main menu with those three pages. |
| **Create an empty docs home** | Creates only the Documentation home page and a menu item for it, so you can add your own products straight away. |
| **No thanks** | Hides the notice. Nothing is created. |

Either setup button also:

- Makes the Documentation page your homepage, but only if your site shows its latest posts there. A homepage you already chose is kept.
- Adds the menu only if no menu is assigned to the main menu location. A menu you already use is kept as it is; add the new pages to it under **Appearance > Menus**.
- Reuses a docs home, FAQ or Changelog page that already exists, so clicking again never makes copies.

The notice appears once. After you pick a button it does not come back. Sites updated from KnowX 1.x already have a docs home and do not see it.

Changed your mind after **No thanks**? The same two setup buttons stay on **Appearance > KnowX** until the site has a docs home.

## Try it in WordPress Playground

Open the theme's `blueprint.json` in [WordPress Playground](https://playground.wordpress.net). It installs and activates KnowX and adds the same sample docs. Nothing is saved on your own site.

## Run it from the command line

On an empty test site (never a live one), `wp eval-file wp-content/themes/knowx/playground/seed.php` adds the sample docs plus what only a demo needs: the "Orbit Docs" site title, tags, two comments and pretty permalinks. It also deletes the default "Hello world" post and "Sample Page". The `playground` folder is in the theme repository on GitHub; it is not in the zip you install from WordPress.org.

## Remove it

Delete the two product categories (this also removes their sections), the 18 articles, the three pages and the menu under **Appearance > Menus**.

## Start your own articles from a layout

When you add a new post or page, WordPress offers ready layouts from the theme. Pick one, or close the window to start from a blank page.

| New post (an article) | New page |
|---|---|
| Getting started steps | Docs home |
| Troubleshooting | Frequently asked questions |
| API endpoint | Changelog entry |

All of them, and the smaller callouts, tables and lists, are also in the block inserter under **Patterns > KnowX Docs**. See [Block patterns](../templates-and-patterns/02-block-patterns.md).
