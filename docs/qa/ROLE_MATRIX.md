# Role matrix

What each kind of user can do with the docs features. Test each cell in the browser with a real account; the theme adds no capability of its own, it uses core capabilities.

| Action | Visitor | Subscriber | Author | Editor | Administrator |
|---|---|---|---|---|---|
| Read docs, use search, toggle dark mode | yes | yes | yes | yes | yes |
| Comment on an article | when comments are open | yes | yes | yes | yes |
| Set "Applies to version" and Order on an own post | no | no | yes | yes | yes |
| Set "Applies to version" and Order on any post | no | no | no | yes | yes |
| Edit product icon, accent colour, description (`manage_categories`) | no | no | no | yes | yes |
| Change Customizer settings (`edit_theme_options`) | no | no | no | no | yes |
| Read icon, accent and version through REST | yes (public meta) | yes | yes | yes | yes |
| Write icon, accent through REST | no | no | no | yes | yes |
| Write version through REST | no | no | own posts | yes | yes |

Check: the icon and accent fields are absent for an Author on the category screen (the screen is not available), and a REST write from an Author to a term returns 403.
