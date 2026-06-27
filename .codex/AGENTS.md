# Codex Project Rules

## Project Scope

- Treat `wp-content` as the Git repository root.
- Do not hard-code the local absolute path or drive letter in instructions, code, or documentation.
- Custom project code is limited to:
  - `themes/themeblank`
  - `plugins/extend-site`

## Project Structure

- Custom theme: `themes/themeblank`
- Custom plugin: `plugins/extend-site`
- Asset source root: `src/`
- Theme asset source:
  - `src/theme/scss`
  - `src/theme/js`
- Plugin asset source:
  - `src/plugins/extend-site/scss`
  - `src/plugins/extend-site/js`
- Built theme assets are output to `themes/themeblank/assets`.
- Built plugin assets are output to `plugins/extend-site/assets`.

## Plugin Architecture

- `plugins/extend-site/extend-site.php` boots the plugin on `plugins_loaded`.
- The plugin uses the `ExtendSite\` namespace and the custom autoloader in `includes/Core/Autoloader.php`.
- New plugin classes should live under `plugins/extend-site/includes` with paths matching the `ExtendSite\` namespace.
- Main plugin boot orchestration happens in `ExtendSite\Core\Plugin`.
- Add new subsystems to the boot flow only when they are real plugin-level features.
- Use `ExtendSite\Constants\Config` for plugin paths, URLs, version, basename, and shared constants.
- Keep Carbon Fields library loading inside `Core\CarbonLoader`; do not manually require Carbon Fields elsewhere.
- Plugin text domain is `extend-site`; use it for plugin strings.

## Theme Architecture

- `themes/themeblank/functions.php` is the theme include manifest.
- Keep theme setup in `includes/theme-setup.php`.
- Keep theme hooks/filters in `includes/theme-hooks.php`.
- Keep theme enqueue logic in `includes/theme-scripts.php`.
- Keep theme sidebar/widget-area registration in `includes/theme-sidebar.php`.
- Keep general theme helpers in `includes/theme-functions.php` and option-related helpers in `includes/theme-helper-options.php`.
- Theme text domain is `themeblank`; use it for theme strings.
- Do not move plugin-owned business logic into the theme unless the user approves it.

## Restricted Areas

Do not edit the following unless the user explicitly asks for it:

- WordPress core outside `wp-content`
- `uploads/`
- Third-party plugins
- Third-party themes
- Vendor/package code
- `node_modules/`
- Carbon Fields core

Before scanning or modifying Node-related files/folders or Carbon Fields core, ask the user first unless the user already approved that specific action.

## Working Workflow

- Always present a short plan before editing files.
- If the task is unclear or the code intent is ambiguous, ask the user before changing code.
- Prefer minimal, focused changes that match the existing project structure.
- Small refactors are allowed when they clearly improve the touched code.
- Ask the user before doing broad or risky refactors.
- After edits, summarize:
  - Files changed
  - Why each change was made
- Do not commit changes unless the user explicitly asks for a commit.

## Search And Scan Performance

- Avoid heavy full-repository scans.
- Prefer narrow searches scoped to the relevant custom paths:
  - `themes/themeblank`
  - `plugins/extend-site`
  - `src`
- Use targeted filename, class name, function name, or text searches instead of broad recursive reads.
- Do not recursively scan `node_modules`, `uploads`, third-party plugins/themes, vendor directories, built assets, or Carbon Fields core.
- Ask the user before running any potentially heavy search or scan.
- When exploring unfamiliar code, inspect directory listings and key entry files first, then expand only into the relevant module.

## WordPress Rules

- Follow the existing project style first, while staying aligned with WordPress Coding Standards.
- Use the text domain `themeblank` for translation functions.
- Escape all output with the appropriate WordPress escaping function, such as:
  - `esc_html()`
  - `esc_attr()`
  - `esc_url()`
  - `wp_kses_post()`
- Sanitize all input with the appropriate WordPress sanitization function, such as:
  - `sanitize_text_field()`
  - `absint()`
  - `sanitize_email()`
- Use nonce and capability checks for forms, AJAX handlers, admin actions, and privileged operations.
- Use prepared queries for custom SQL.
- Do not echo raw user input, post meta, term meta, option values, request data, or database values.
- Keep Carbon Fields usage compatible with the project, but do not modify Carbon Fields core.

## Theme And Template Data

- Theme options are registered in the custom plugin under `plugins/extend-site/includes/Admin/Options`.
- Theme option modules live in `plugins/extend-site/includes/Admin/Options/Modules`.
- New option modules should follow the existing module pattern:
  - Extend `ExtendSite\Admin\Options\OptionBase`.
  - Implement `ExtendSite\Admin\Options\OptionIF`.
  - Define option keys as private class constants with a consistent prefix.
  - Return Carbon Fields definitions from `fields(): array`.
  - Expose typed public getter methods for reading values.
  - Use `self::get()` from `OptionBase` so missing Carbon Fields functions and empty values fall back safely.
- Register new option modules through `ThemeOptions` by adding them to the appropriate group/tab structure.
- Theme templates should read theme option data through module getter methods, preferably via `themeblank_opt(SomeOptions::class)::some_getter()`.
- Use `themeblank_opt()` when theme code depends on option classes from `extend-site`, so the theme does not fatal if the plugin/class is unavailable.
- Template or post-specific fields are registered under `plugins/extend-site/includes/Admin/Fields`.
- Field tab classes should follow the existing pattern:
  - Implement `ExtendSite\Admin\Fields\FieldTabIF`.
  - Define Carbon Fields keys as private class constants with a stable prefix.
  - Return field definitions from `fields(): array`.
  - Expose `get_data(int $post_id): array` for template consumption.
- Theme code should use `themeblank_get_field_tab_data(FieldTab::class, $post_id)` to read field-tab data safely.
- Keep raw `carbon_get_theme_option()` and `carbon_get_post_meta()` calls inside option/field classes when possible, not scattered through templates.
- Always escape option/meta values at output time in templates.
- Use defaults in getter methods instead of handling missing option values repeatedly in templates.

## Custom Post Types And Templates

- Custom post types are implemented in `plugins/extend-site/includes/PostType`.
- New custom post types should follow the existing pattern:
  - Extend `ExtendSite\PostType\BasePostType`.
  - Define `SLUG`, `SINGULAR`, `PLURAL`, and `MENU_NAME` constants.
  - Define taxonomy constants when needed.
  - Override `register_taxonomies()` for taxonomy registration.
  - Override `get_custom_labels()` for translated labels.
  - Register Carbon Fields for the post type on `carbon_fields_register_fields` when needed.
- Add new post type classes to `PostTypeManager::$post_types` so they are loaded by the plugin boot flow.
- Register post type templates through the post type class `TEMPLATE_MAP`.
- Use `single` and `archive` keys in `TEMPLATE_MAP` for single/archive templates.
- Use taxonomy slug keys in `TEMPLATE_MAP` for taxonomy archive templates.
- Template files for plugin-owned post types live under `plugins/extend-site/templates`.
- The template loader first checks the active theme for overrides, then falls back to plugin templates.
- Theme overrides may be placed at either `extend-site/{template-path}` or `{template-path}`, matching `TemplateLoader`.
- Do not bypass `TemplateLoader` with one-off `template_include` logic unless the user approves a broader architecture change.
- Avoid calling `flush_rewrite_rules()` directly in task code; use the existing post type registration flow.

## Enqueue And Assets

- Theme frontend assets are enqueued in `themes/themeblank/includes/theme-scripts.php`.
- Plugin frontend/login/admin assets are enqueued in `plugins/extend-site/includes/Core/Enqueue.php`.
- Use existing handles and version helpers where possible:
  - Theme assets use `themeblank_get_version_theme()`.
  - Plugin assets use `Config::VERSION`.
- Register/enqueue assets conditionally when they are page-specific.
- Use `wp_localize_script()` only for data needed by scripts, such as AJAX URLs or configuration.

## Admin Modules And Breadcrumbs

- Admin framework code lives in `plugins/extend-site/includes/Admin/AdminManager`.
- New admin pages/modules should extend `BaseAdminModule` and be added to `AdminManager::get_modules()`.
- Admin modules must keep nonce and capability checks through the existing `BaseAdminModule` request flow unless a custom flow is required.
- Breadcrumb code lives in `plugins/extend-site/includes/Core/Breadcrumb`.
- Breadcrumb behavior is controlled by the admin module and booted through `Plugin::boot_breadcrumb()`.
- Do not add duplicate breadcrumb renderers, shortcodes, or schema output outside the breadcrumb subsystem.

## Theme Hooks

- Theme hooks and optimization filters belong in `theme-hooks.php`.
- Code insertion options are intentionally rendered in theme hooks; treat them as trusted admin-configured HTML and avoid applying generic escaping that would break their purpose unless the user requests stricter filtering.

## Frontend Rules

- The frontend stack is jQuery plus plain JavaScript.
- Styles are written in SCSS.
- `src/` contains both CSS and JavaScript source files.
- The project uses Gulp.
- Do not run build/test build commands after edits unless the user asks for it.
- Keep frontend changes consistent with the existing file organization and naming conventions.
- Edit source assets in `src/` instead of directly editing built output in `assets/`, unless the user explicitly asks for an output-only change.
- Ask the user before editing built/minified output files when the source file exists.
- For theme frontend work, use `src/theme/scss` and `src/theme/js`.
- For `extend-site` plugin frontend work, use `src/plugins/extend-site/scss` and `src/plugins/extend-site/js`.
- Keep the existing SCSS partial structure, such as `abstracts`, `base`, `components`, `layout`, `utilities`, `page-templates`, and `post-type`.
- Reuse existing SCSS variables, mixins, functions, placeholders, and helpers before introducing new ones.
- Keep JavaScript consistent with the existing jQuery/plain JavaScript style.
- Do not add a new frontend framework or major dependency unless the user approves it first.

## Communication

- Reply to the user in Vietnamese.
- Keep explanations concise and action-oriented.
- Use English for code comments.
- Add comments only when they clarify non-obvious logic.

## Review And Debugging

- Prioritize bugs, regressions, security issues, performance risks, and missing validation.
- For reviews, list findings first with file and line references where possible.
- Avoid unrelated refactors during review/debug tasks.

## Git

- Git root is `wp-content`.
- Do not commit unless the user explicitly requests it.
- Do not revert user changes unless the user explicitly asks for that exact action.
