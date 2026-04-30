## Purpose

This file defines how the agent should operate within this repository to maximize efficiency, minimize token usage, and prevent unnecessary context expansion.

The agent must behave as a scoped executor, not an autonomous planner unless explicitly instructed.

---

# Core Operating Rules

## 1. Do Not Redesign Unless Explicitly Instructed

Execute only the requested task.

Do NOT:

- Propose unrelated improvements
- Refactor beyond defined scope
- Rewrite architecture
- Modify unrelated files

Do:

- Follow instructions exactly
- Make reasonable assumptions based on existing patterns. Only ask for clarification if multiple architectural choices would significantly affect the system.

---

## 2. Strict File Scope Enforcement

Only read or modify files that are:

- Explicitly listed in the task, OR
- Direct dependencies of those files

Do NOT scan the entire repository unless explicitly instructed.

If additional files are required, ask first.

---

## 3. Minimize File Reads

Before reading a file, confirm it is necessary.

Avoid:

- Re-reading files already processed
- Reading unrelated modules
- Broad directory scans

Prefer targeted access.

---

## 4. Output Format Rules

Default output format:

- Minimal explanation
- Provide diffs or full file replacements only
- No verbose reasoning
- Prefer unified diffs when modifying existing files.

Only explain decisions if explicitly requested.

---

## 5. Execution Mode vs Planning Mode

The agent operates in two distinct modes.

### Execution Mode (Default)

Execute predefined instructions exactly.

Do NOT:

- Redesign
- Expand scope
- Suggest unrelated improvements

### Planning Mode (Explicit Only)

Entered only when user explicitly requests:

Examples:

- "Create implementation plan"
- "Design architecture"
- "Propose refactor"

Planning mode ends when plan is approved.

---

## 6. Avoid Repeated Analysis

Once a file has been analyzed, do not re-analyze unless:

- The file has changed, OR
- The user requests review

---

## 7. Prefer Incremental Changes

Break large tasks into smaller steps.

Avoid large multi-system changes in one execution.

---

## 8. Token Efficiency Priority

Always prefer:

- Smaller scope
- Fewer file reads
- Minimal output verbosity
- Direct execution

Avoid unnecessary reasoning output.

---

# Safe Execution Workflow

Follow this sequence:

1. Identify task scope
2. Identify required files
3. Read only required files
4. Execute change
5. Output minimal result

Do NOT:

- Explore beyond scope
- Scan unrelated directories
- Suggest unrelated improvements

---

# File Modification Rules

When modifying files:

- Preserve existing structure unless instructed otherwise
- Avoid unnecessary formatting changes
- Do not modify unrelated code

---

# When to Ask for Clarification

Ask before proceeding if:

- Scope is unclear
- Multiple architectural options exist
- Required files are not specified
- Task may affect many systems

---

# Default Assumptions

Unless told otherwise:

- Prioritize minimal, safe changes
- Avoid architectural redesign
- Maintain backward compatibility
- Prefer minimal diffs over full file rewrites when possible.

---

# Efficiency Priority Hierarchy

Highest priority:

1. Correctness
2. Scope compliance
3. Token efficiency
4. Minimal output verbosity

Lowest priority:

- Code style improvements not related to task
- Optional optimizations

---

# Summary

The agent is a precise executor.

Not a redesigning architect unless explicitly instructed.

Scope discipline and token efficiency are critical.

---

# Project-Specific Rules

These rules apply specifically to the Zotefoams theme and override general defaults where they conflict.

## File Scope

For most tasks, work will be concentrated in:

- `template-parts/components/<slug>.php` — component templates
- `src/components/<slug>.js` — component JavaScript modules
- `src/sass/design/components/_<slug>.scss` — component styles
- `inc/` — theme functions (setup, assets, ACF config, helpers, etc.)
- `functions.php` — entry point (modular includes only)
- `acf/acf-json/` — ACF field group JSON (rarely modified directly)

Other files commonly involved:

- `header.php`, `footer.php`, `page.php` — core template files
- `docs/DEVELOPMENT-TRACKER.md` — issue tracking and change history

See `docs/BUILD.md` for build commands and asset pipeline details.

## PHP Conventions

- Always use `zotefoams_get_sub_field_safe()` — never call `get_sub_field()` directly
- Always use `zotefoams_get_field_safe()` — never call `get_field()` directly in components
- Never call `wp_get_attachment_image_src()` directly — use `Zotefoams_Image_Helper`
- Use `Zotefoams_Button_Helper::render()` for all button/link output
- Use `Zotefoams_Theme_Helper::get_wrapper_classes()` for component wrapper class generation
- Sanitize output: `esc_html()` for plain text, `wp_kses_post()` for HTML/rich text, `esc_url()` for URLs, `esc_attr()` for attributes

## JavaScript Conventions

- ES modules only — no jQuery, no global scripts
- All component JS lives in `src/components/` and is imported via `src/main.js`
- Use `ZotefoamsReadyUtils.ready()` for DOM-ready initialisation
- No inline `<script>` tags in component templates

## ACF

- Field group JSON is in `acf/acf-json/` — source of truth for field definitions
- `zotefoams_get_sub_field_safe()` contains a `$GLOBALS['zotefoams_preview_fields']` intercept used by the `zotefoams-component-library` plugin for preview rendering — do not remove it

## Adding a Component

All steps are required:

1. PHP template — `template-parts/components/<slug>.php`
2. JavaScript module — `src/components/<slug>.js` (import in `src/main.js`)
3. SASS partial — `src/sass/design/components/_<slug>.scss` (import in design manifest)
4. ACF flexible content layout — defined in `acf/acf-json/`
