# Plugin Review – Critical Issues + Solutions (High Priority)

## 🚨 1. Analytics – Consolidate, Enqueue Properly & Add Consent Gating

GA and LinkedIn scripts are hardcoded in `inc/analytics.php` while ACF fields (`google_analytics_measurement_id`, `linkedin_partner_id`) also exist but are likely unused. Scripts load unconditionally for all visitors with no consent check.

**Problems:**
- Hardcoded scripts bypass the enqueue system (no dependency management, cache busting, or version control)
- ACF fields exist but aren't driving the output — duplication and no real admin control
- Tracking fires without user consent — legal risk (GDPR / UK compliance)

**Fix:**
- Audit whether ACF fields are wired up (likely not)
- Remove hardcoded scripts, use ACF options as single source of truth
- Load via `wp_enqueue_script()` + `wp_add_inline_script()`, only if IDs are set
- Integrate a consent tool (e.g. CookieYes, Complianz) and gate script loading on opt-in

**Outcome:**
- One clean analytics system, fully configurable via admin
- Properly enqueued, consent-compliant tracking

---

## 📋 TODO – Fix Mailchimp Script Loading

Mailchimp scripts are currently injected via `wp_footer` with raw `<script>` tags and rely on jQuery being available implicitly. This is fragile and can break depending on load order or other plugins.

**Fix:**
- Replace inline `<script>` output with proper `wp_enqueue_script()`
- Declare `['jquery']` as a dependency (do not assume it's already loaded)
- Move inline config to `wp_add_inline_script()`
- Use full `https://` URL (no protocol-relative URLs)
- Optionally only load on pages where the form exists

**Outcome:**
Reliable script loading, correct dependency handling, and improved compatibility with other plugins and optimisations.

---

## 🚨 2. ACF Data Not Validated
Example:
$ga_tracking_id = get_field(...)

Impact:
- Potential XSS if admin compromised

Solution:
- Sanitize on save:

  add_filter('acf/update_value/name=ga_tracking_id', function($value) {
      return sanitize_text_field($value);
  });

- Escape on output (already partially done):
  esc_attr(), esc_js(), esc_html()
