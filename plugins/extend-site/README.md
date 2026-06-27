# Extend Site Plugin Framework

A modular WordPress plugin framework for custom content types, Carbon Fields options, Elementor integrations, templates, and utility features.

## Folder Structure

- `includes/Core`: Plugin lifecycle, autoloader, Carbon Fields loading, and asset enqueueing.
- `includes/Admin`: Admin pages, theme options, Carbon Fields field definitions, and helpers.
- `includes/PostType`: Custom post type registration and template routing.
- `includes/ElementorAddon`: Elementor widget integration.
- `includes/Helpers`: Shared plugin helper functions.
- `templates`: Default plugin templates that can be overridden by the active theme.

## Registering A Post Type

1. Create a class in `includes/PostType` extending `BasePostType`.
2. Define `SLUG`, `SINGULAR`, `PLURAL`, `MENU_NAME`, and optional `TEMPLATE_MAP`.
3. Override `register_taxonomies()` when the post type needs taxonomies.
4. Override `get_args()` when the post type needs custom registration args.
5. Register Carbon Fields for the post type when needed.
6. Add the class to `PostTypeManager::$post_types`.

## Theme Overrides

Plugin templates can be overridden in the active theme by placing files at:

- `your-theme/extend-site/{template-path}`
- `your-theme/{template-path}`

The theme override is used first; otherwise the plugin template is used.
