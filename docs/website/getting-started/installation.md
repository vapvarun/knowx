# Installation

You can install KnowX from the WordPress admin or by uploading the theme files manually.

## Install from the WordPress admin

1. Download the KnowX theme ZIP.
2. In your WordPress dashboard, go to **Appearance > Themes > Add New > Upload Theme**.
3. Choose the KnowX ZIP file and select **Install Now**.
4. Select **Activate**.

## Install manually over FTP or SFTP

1. Unzip the KnowX archive.
2. Upload the `knowx` folder to `wp-content/themes/` on your server.
3. In your dashboard, go to **Appearance > Themes** and activate KnowX.

## What happens on activation

The first time you activate KnowX, the theme automatically creates a page titled **Knowledge Base**, assigns it the "Knowledge Base Layout One" template, and sets it as your site's static front page. This gives you a working knowledge base landing page immediately.

This setup runs only once. KnowX records that it has run using the `knowx_theme_activated` option, so re-activating the theme later does not create duplicate pages or change your front-page setting again.

If you prefer a different front page, go to **Settings > Reading** after activation and choose your own page.

## After activation

- If you see a prompt to install recommended plugins, review [Recommended Plugins](../features/recommended-plugins.md). Install **Kirki Toolkit** to unlock the full KnowX Customizer.
- Continue to [First-Time Setup](first-setup.md) to configure your menu, homepage, and branding.
