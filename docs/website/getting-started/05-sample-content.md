# Sample content

KnowX ships a script that builds a complete example docs site, so you can see every template before you write your own articles.

## What it creates

- Two products, Orbit Helpdesk and Orbit API, each with three sections and nine articles
- A Documentation home page set as your homepage, an FAQ page and a Changelog page
- A main menu with those three pages
- Site title, tagline and pretty permalinks

## Try it in WordPress Playground

Open the theme's `blueprint.json` in [WordPress Playground](https://playground.wordpress.net). It installs and activates KnowX and runs the sample content script. Nothing is saved on your own site.

## Run it on a test site

Use an empty test site, never a live one. The script deletes the default "Hello world" post and "Sample Page".

1. Activate KnowX.
2. Run `wp eval-file wp-content/themes/knowx/playground/seed.php`.
3. Open the site. Running the script a second time changes nothing.

The `playground` folder is part of the theme repository on GitHub; it is not included in the zip you install from WordPress.org.

## Remove it

Delete the two product categories (this also removes their sections), the three pages and the menu under Appearance > Menus.
