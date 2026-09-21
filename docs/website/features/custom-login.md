# Custom Login

KnowX can restyle the WordPress login screen (`wp-login.php`) so it matches your brand. These options live in the Customizer under **WP Login > Logo** (the Kirki Toolkit plugin must be active).

## Enabling custom login styling

Custom login styling is applied only when it is enabled. In the Customizer WP Login panel:

- **Enable Custom Login** - turn KnowX's login styling on. When off, WordPress uses its default login screen.
- **Enable Custom Login Logo** - replace the default WordPress logo on the login form with your own image.

## Available options

- **Custom Login Logo Image** - the logo image shown above the login form.
- **Custom Login Logo Image Width** and **Height** - dimensions for the logo image.
- **Custom Login Logo Space** - spacing below the logo.
- **Custom Login Logo URL** - the link the login logo points to. By default the WordPress login logo links to wordpress.org; set this to your own site URL.
- **Custom Login Logo Title** - the tooltip and accessible text for the login logo link.
- **Custom Login Page Title** - the browser tab title on the login page.

## Notes

- The logo link, link title, and page title are applied through the standard WordPress login filters (`login_headerurl`, `login_headertext`, `login_title`), so they behave consistently with other login customizations.
- If you leave a value empty, KnowX falls back to the WordPress default for that item.
