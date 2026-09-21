# Navigation and Widgets

## Navigation menu

KnowX registers one menu location:

- **Primary** - the main navigation menu shown in the site header.

To set it up:

1. Go to **Appearance > Menus**.
2. Build a menu and assign it to the **Primary** location (or use **Manage Locations**).
3. Save.

The header renders the primary menu with a mobile menu toggle for small screens. If no menu is assigned to the Primary location, the navigation area is skipped.

You can style the menu in the Customizer under **Typography > Menu** (font, hover color, active color). See [General and Typography](../customizing/general-typography.md).

## Widget areas

KnowX registers the following widget areas. Add widgets under **Appearance > Widgets**.

| Widget area | ID | Purpose |
|-------------|----|---------|
| Right Sidebar | `sidebar-right` | Main sidebar when the layout is set to right |
| Left Sidebar | `sidebar-left` | Main sidebar when the layout is set to left |
| bbPress Left Sidebar | (bbPress) | Sidebar on bbPress pages, left layout |
| bbPress Right Sidebar | (bbPress) | Sidebar on bbPress pages, right layout |
| Footer 1 | `footer-1` | First footer column |
| Footer 2 | `footer-2` | Second footer column |
| Footer 3 | `footer-3` | Third footer column |
| Footer 4 | `footer-4` | Fourth footer column |

The bbPress sidebars are registered only when bbPress is active. Footer columns render only when they contain widgets. Choose the sidebar position in **Customize > General > Site Sidebar**.

## KnowX Categories widget

KnowX ships a custom widget named **KnowX Categories** - a list or dropdown of your post categories, useful in a knowledge base sidebar. Find it in the widget list as "KnowX Categories" and drop it into any widget area.
