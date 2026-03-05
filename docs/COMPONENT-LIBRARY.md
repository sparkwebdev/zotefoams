# Component Library

An admin-only front-end showcase page for browsing and previewing the theme's ACF flexible content components with realistic dummy data, rendered using the real templates and styles.

**URL:** `/component-library` (requires logged-in user with `edit_posts` capability)

## How It Works

The component library renders real component templates (`template-parts/components/*.php`) without needing any ACF data in the database. It achieves this through a global variable injection mechanism that intercepts the safe field retrieval function.

### Architecture Overview

```
Browser → /component-library
           ↓
  inc/component-library.php        (rewrite rule + access control)
           ↓
  page-component-library.php       (template: UI + render loop)
           ↓
  inc/component-library-data.php   (dummy data definitions)
           ↓
  $GLOBALS['zotefoams_preview_fields'] = $fields;
  include template-parts/components/{slug}.php
           ↓
  inc/acf-helpers.php              (intercepts get_sub_field via safe wrapper)
```

### The Preview Mode Mechanism

The core trick is a 3-line check at the top of `zotefoams_get_sub_field_safe()` in `inc/acf-helpers.php`:

```php
if (isset($GLOBALS['zotefoams_preview_fields'][$field_name])) {
    return $GLOBALS['zotefoams_preview_fields'][$field_name];
}
```

When the component library sets `$GLOBALS['zotefoams_preview_fields']` to an associative array of field names and values, every call to `zotefoams_get_sub_field_safe()` inside the included template returns the dummy value instead of querying ACF. After the template is included, the global is unset.

This works because all component templates use `zotefoams_get_sub_field_safe()` for field retrieval. As part of building the component library, 14 templates that previously used raw `get_sub_field()` were migrated to the safe wrapper — an improvement that also added type validation and sanitisation to those fields.

### Prerequisite: Safe Wrapper Migration

For preview mode to work, **every** field access in a component must go through `zotefoams_get_sub_field_safe()`. The migration involved:

- Replacing `get_sub_field('field_name')` with `zotefoams_get_sub_field_safe('field_name', $default, $type)`
- Adding the `'html'` type to `zotefoams_validate_field_value()` for rich text fields that need HTML preserved (uses `wp_kses_post()` instead of `sanitize_text_field()`)
- Choosing the correct type for each field: `'string'`, `'html'`, `'array'`, `'int'`, `'bool'`, `'url'`, `'image'`

This migration is a standalone improvement — it adds input validation and sanitisation regardless of component library usage.

## File Structure

| File | Purpose |
|---|---|
| `inc/component-library.php` | Registers rewrite rule (`^component-library/?$`), whitelists query var, restricts access to `edit_posts` users, loads template |
| `inc/component-library-data.php` | Dummy data definitions: one function per component returning field arrays, plus the master registry |
| `page-component-library.php` | Front-end template: selector UI, toggle options, render loop, stress test logic, SVG wireframes |
| `inc/acf-helpers.php` | Contains the 3-line preview mode check (+ `'html'` type addition) |

## UI Features

### Component Selection

Three side-by-side dropdowns allow selecting up to three components simultaneously. Each dropdown option shows:

- An SVG wireframe sketch of the component layout
- The component name
- A "has variant" badge where applicable

Selections are URL-based (`?c1=text_block&c2=split_text&c3=data_map`), so they're shareable and bookmarkable.

### Toggle Options

Three toggles in the toolbar, all persisted via URL parameters:

| Toggle | URL param | Default | Effect |
|---|---|---|---|
| Show containers | `containers=0` | On | When off: removes borders, backgrounds, and margins from preview wrappers; sets body background to white |
| Show variants | `variants=0` | On | When off: hides variant renders (components with alternative configurations) |
| Enable test mode | `testmode=1` | Off | Renders 4 iterations of the first slot at increasing stress levels; hides dropdowns 2 and 3 |

Toggle state is preserved across dropdown navigation by including the current toggle values in all dropdown links (`$toggle_args`).

### Test Mode (Stress Testing)

When enabled, renders 4 versions of the selected component:

1. **Normal** — unmodified dummy data
2. **Stress level 1** — mild: short title suffixes, 1 extra paragraph, 1 extra repeater item, `_value` fields x2
3. **Stress level 2** — moderate: longer titles, 2 extra paragraphs, 2 extra items, values x10
4. **Stress level 3** — extreme: very long titles, 3 extra paragraphs, 3 extra items, values x100

The stress function (`zotefoams_cl_stress_data()`) recursively walks the data array and intelligently skips:

- ACF image arrays (detected by `url` + `sizes` keys)
- ACF link arrays (detected by `url` + `target` keys)
- Pure numeric values
- Very short strings (≤2 characters, e.g. prefixes/suffixes)
- Control field keys matching `behaviour|variant|position|theme|mode|type|layout|style|target|prefix|suffix|from_top|from_left`
- URLs and boolean strings

## Adding a New Component

### 1. Define dummy data

In `inc/component-library-data.php`, add a function returning the component's fields:

```php
function zotefoams_cl_data_my_component() {
    return [
        'my_component_title'   => 'Example Title',
        'my_component_content' => '<p>Rich text content here.</p>',
        'my_component_image'   => zotefoams_cl_image(800, 600, 'Preview'),
        'my_component_button'  => zotefoams_cl_link('Learn More', '#'),
        'my_component_items'   => [
            ['item_title' => 'Item 1', 'item_text' => 'Description'],
            ['item_title' => 'Item 2', 'item_text' => 'Description'],
        ],
    ];
}
```

Use `zotefoams_cl_image($w, $h, $label)` for images and `zotefoams_cl_link($title, $url)` for link fields. For components with `behaviour` fields, force `'manual'` mode to avoid database queries.

### 2. Register in the registry

Add an entry to `zotefoams_component_library_registry()`:

```php
'my_component' => [
    'label' => 'My Component',
    'group' => 'Text',          // Text, Media, Columns & Grids, Carousels, Navigation, Data, Misc
    'data'  => 'zotefoams_cl_data_my_component',
],
```

For components with variants:

```php
'my_component' => [
    'label' => 'My Component',
    'group' => 'Media',
    'data'  => 'zotefoams_cl_data_my_component',
    'variants' => [
        'Dark Theme' => 'zotefoams_cl_data_my_component_dark',
    ],
],
```

### 3. Add an SVG wireframe (optional)

In `page-component-library.php`, add an entry to `zotefoams_cl_wireframes()`. Wireframes use an 80x52 viewBox with simple SVG shapes.

### 4. Ensure safe wrapper usage

The component template must use `zotefoams_get_sub_field_safe()` for all field access. If it uses raw `get_sub_field()`, migrate those calls.

### 5. Flush permalinks

After adding the component, visit **Settings > Permalinks > Save** in wp-admin to flush rewrite rules (only needed the first time the component library is set up).

## Known Limitations

- **Adjacent sibling CSS selectors**: The `.cl-preview` wrapper div around each component means CSS selectors like `.theme-dark + .theme-dark` (used for removing duplicate borders between same-themed components) won't work in the library. On live pages these selectors work correctly since components render without wrapper divs.
- **Components with database queries**: Components using `get_posts()` or similar queries (e.g. `news_feed`, `document_list` in `'latest'` mode) won't return real data. Use `'manual'` behaviour mode in dummy data to bypass queries.
- **Shared state bugs**: Components using JavaScript that doesn't scope to individual instances (e.g. carousel navigation) will exhibit cross-instance control issues. These are real bugs documented in DEVELOPMENT-TRACKER.md.

## Converting to a Plugin

The component library is currently embedded in the theme but is designed with minimal coupling, making plugin extraction straightforward.

### What would move to the plugin

| Theme file | Plugin equivalent |
|---|---|
| `inc/component-library.php` | Plugin's main file (rewrite rules, access control) |
| `inc/component-library-data.php` | Plugin data file (dummy definitions) |
| `page-component-library.php` | Plugin template (loaded via `template_include` filter) |
| SVG wireframes (in template) | Stays with the template |

### What stays in the theme

| File | Reason |
|---|---|
| `inc/acf-helpers.php` | The preview mode check and `'html'` type are general improvements, not component-library-specific |
| `template-parts/components/*.php` | Component templates remain in the theme |
| `functions.php` | Remove the `require` line for `component-library.php` |

### Plugin structure

```
zotefoams-component-library/
├── zotefoams-component-library.php    # Plugin header, hooks, access control
├── includes/
│   └── data.php                       # Dummy data definitions + registry
├── templates/
│   └── component-library.php          # Front-end template
└── readme.txt                         # Plugin metadata
```

### Key changes for plugin conversion

1. **Plugin header**: Add standard WordPress plugin header to the main file
2. **Template path**: Change `locate_template()` to use the plugin's own `templates/` directory for the page template, but keep using `locate_template()` for component includes (so they still load from the theme)
3. **Theme dependency check**: Add an activation check that the Zotefoams theme is active (or a child theme of it)
4. **Data extensibility**: Consider adding a filter hook so the registry can be extended:
   ```php
   $registry = apply_filters('zotefoams_cl_registry', $registry);
   ```
5. **Asset loading**: The template already uses `wp_head()` / `wp_footer()` which load the theme's CSS/JS, so no changes needed there
6. **Deactivation**: Add a `deactivate` hook to flush rewrite rules on plugin deactivation

### Preview mode dependency

The plugin would still depend on the 3-line preview mode check in `inc/acf-helpers.php`. This check should remain in the theme since it's a general-purpose feature (any code can set the global to inject preview data). The plugin's readme should note this as a requirement.

Alternatively, the plugin could add the check itself via a filter or by monkey-patching, but this is fragile and unnecessary — the 3-line check is a legitimate theme improvement that has no side effects when the global is not set.
