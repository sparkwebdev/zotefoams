# Zotefoams Site Update Plan

**Created:** 2026-04-15  
**Scope:** Plugin updates, WordPress core, PHP version — staging and live  
**Strategy:** Plugins (disabled → low → medium → high impact) → WordPress → PHP

---

## 1. As-Is Status

### Environment Versions

| | Live | Staging |
|---|---|---|
| **WordPress** | 6.9.4 | 6.9.4 |
| **PHP** | 7.4 ⚠️ EOL | 8.2 |

> Live is running PHP 7.4 which reached end-of-life in November 2022 — no security patches. Upgrading PHP on live is a priority but must come last as it's the highest-risk change.

---

### Plugin Status — Live

| Plugin | Version | Status | Available | Impact |
|---|---|---|---|---|
| Advanced Custom Fields PRO | 6.4.3 | Active | 6.8.0.1 | Critical |
| BackWPup | 5.5.2 | Active | 5.6.8 | Low |
| CookieYes \| GDPR Cookie Consent | 3.1.2 | Active | 3.4.0 | Medium |
| Enable Media Replace | 4.1.6 | Active | 4.1.8 | Low |
| Linguise | 2.1.73 | Active | 2.2.40 | High |
| Redirection | 5.5.2 | Active | 5.7.5 | Medium |
| Safe SVG | 2.3.2 | Active | 2.4.0 | Low |
| Site Kit by Google | 1.149.1 | Active | 1.176.0 | Medium |
| Wordfence Security | 8.1.4 | Active | Up to date | Medium |
| WPForms | 1.9.7.2 | Active | 1.10.0.4 | High |
| Yoast Duplicate Post | 4.5 | Active | 4.6 | Low |
| Yoast SEO | 21.0 | Active | 27.4 | High |
| Yoast SEO Premium | 21.0 | Active | 27.4 | High |

---

### Plugin Status — Staging

#### Active Plugins

| Plugin | Version | Status | Available | Impact |
|---|---|---|---|---|
| Advanced Custom Fields PRO | 6.8.0.1 ✅ | Active | — | Critical |
| BackWPup | 5.6.8 ✅ | Active | — | Low |
| CookieYes \| GDPR Cookie Consent | 3.2.10 | Active | 3.4.0 | Medium |
| Enable Media Replace | 4.1.8 ✅ | Active | — | Low |
| Linguise | 2.2.40 ✅ | Active | — | High |
| Redirection | 5.7.5 ✅ | Active | — | Medium |
| Safe SVG | 2.4.0 ✅ | Active | — | Low |
| Site Kit by Google | 1.176.0 ✅ | Active | — | Medium |
| Wordfence Security | 8.1.4 | Active | Up to date | Medium |
| WPForms | 1.9.6 | Active | 1.10.0.4 | High |
| Yoast Duplicate Post | 4.6 ✅ | Active | — | Low |
| Yoast SEO | 27.4 ✅ | Active | — | High |
| Yoast SEO Premium | 21.0 | Active | 27.4 | High |
| Zotefoams Assistant | 0.0.5 | Active | — | Critical (custom) — staging only, not on live |

#### Inactive Plugins (staging only)

None — all inactive plugins cleared.

---

## 2. Deleted mu-plugins Log

All mu-plugins were legacy WP Engine files left over from when the site was hosted on WP Engine. None receive updates and all were dead weight on every page load.

| File/Folder | What it was | Date Deleted | Environment |
|---|---|---|---|
| `wpengine-common-disabled/` | WP Engine platform code (disabled) | 2026-04-15 | Staging |
| `wpengine-security-auditor.php` | WP Engine security scanner | 2026-04-15 | Staging |
| `wpe-cache-plugin/` + `wpe-cache-plugin.php` | WP Engine caching layer | 2026-04-15 | Staging |
| `wpe-wp-sign-on-plugin/` + `wpe-wp-sign-on-plugin.php` | WP Engine SSO for their dashboard | 2026-04-15 | Staging |
| `force-strong-passwords/` + `slt-force-strong-passwords.php` | Force strong passwords (generic plugin, WP Engine bundled) — Wordfence covers this | 2026-04-15 | Staging |
| `0-worker.php` | ManageWP Worker (ManageWP already removed from regular plugins) | 2026-04-15 | Staging |

> The `mu-plugins` folder itself was also deleted as it is now empty and WordPress does not require it.

**⚠️ Permission issue encountered on staging:** The WP Engine folders had `r-x r-x r-x` permissions (read/execute only) — SFTP deletion failed with Error -144. Fix: in Plesk File Manager, change permissions to `755` **recursively** on each folder before removing. Apply the same process on live when the time comes.

---

## 3. Deleted Plugins Log

| Plugin | Date Deleted | Environment | Reason |
|---|---|---|---|
| Akismet Anti-spam: Spam Protection | 2026-04-15 | Staging | No blog/comment use case |
| All in One SEO | 2026-04-15 | Staging | Superseded by Yoast SEO |
| Annual Archive | 2026-04-15 | Staging | Unused, no updates |
| Breadcrumb NavXT | 2026-04-15 | Staging | Inactive, unused |
| Classic Editor | 2026-04-15 | Staging | Inactive, unused |
| Column Shortcodes | 2026-04-15 | Staging | Inactive, unused |
| Contact Form 7 | 2026-04-15 | Staging | Superseded by WPForms |
| Contact Form 7 Redirection Pro | 2026-04-15 | Staging | CF7 dependent, removed with CF7 |
| Dynamic Widgets | 2026-04-15 | Staging | Inactive, unused |
| Elementor | 2026-04-15 | Staging | Custom theme used, not Elementor |
| Elementor Pro | 2026-04-15 | Staging | Custom theme used, not Elementor |
| Email Log | 2026-04-15 | Staging | Inactive, unused |
| Flamingo | 2026-04-15 | Staging | CF7 companion, removed with CF7 |
| FlowPaper | 2026-04-15 | Staging | Inactive, no updates |
| Force Regenerate Thumbnails | 2026-04-15 | Staging | Inactive, utility — not needed |
| GatorLeads Tracker | 2026-04-15 | Staging | Inactive, old tracking service |
| Google Analytics for WordPress (MonsterInsights) | 2026-04-15 | Staging | Superseded by Site Kit by Google |
| Head, Footer and Post Injections | 2026-04-15 | Staging | Inactive, unused |
| LayerSlider | 2026-04-15 | Staging | Inactive, custom theme used |
| Lead Forensics | 2026-04-15 | Staging | Inactive, old tracking service |
| ManageWP - Worker | 2026-04-15 | Staging | Inactive, unused |
| OptinMonster | 2026-04-15 | Staging | Inactive, unused |
| PDF Light Viewer | 2026-04-15 | Staging | Inactive, unused |
| Post Types Order | 2026-04-15 | Staging | Inactive, unused |
| Search & Filter Pro | 2026-04-15 | Staging | Inactive, premium — not in use |
| Team Member – Multi Language Supported Team Plugin | 2026-04-15 | Staging | Inactive, unused |
| UserFeedback Lite | 2026-04-15 | Staging | Inactive, unused |
| WordPress Importer | 2026-04-15 | Staging | Inactive, utility — not needed |
| WP Booklet | 2026-04-15 | Staging | Inactive, unused |
| WP Content Copy Protection & No Right Click | 2026-04-15 | Staging | Inactive, unused |
| UpdraftPlus - Backup/Restore | 2026-04-15 | Staging | BackWPup is the active backup tool |
| WP Engine GeoTarget | 2026-04-15 | Staging | Not on WP Engine hosting |
| WP Mail SMTP | 2026-04-15 | Staging | Inactive, not needed |
| WPForms Lite | 2026-04-15 | Staging | Paid WPForms version is active |

> Deletions on staging only at this stage. Mirror to live will happen as part of the live update process.  
> All inactive plugins on staging have now been cleared.

---

## 3. Staged Upgrade Path

All updates should be done on **staging first**, verified, then repeated on **live**.  
Take a full backup before each phase.

---

### Phase 1 — Staging: Clean up inactive plugins ✅ Complete (2026-04-15)

**Risk:** Zero (inactive plugins have no frontend impact)  
**Goal:** Reduce surface area before upgrading anything active

**Outcome:** 30 plugins deleted. Remaining inactive: UpdraftPlus, WP Engine GeoTarget, WP Mail SMTP, WPForms Lite.

---

### Phase 2 — Active plugins: Low impact (Staging ✅ Complete 2026-04-15)

**Risk:** Low — admin/utility plugins with no frontend output  
**Applies to:** Both staging and live

| Plugin | Live Current | Target | Staging | Notes |
|---|---|---|---|---|
| BackWPup | 5.5.2 | 5.6.8 | 5.6.8 ✅ | Backup plugin, admin-only |
| Enable Media Replace | 4.1.6 | 4.1.8 | 4.1.8 ✅ | Admin media library tool |
| Safe SVG | 2.3.2 | 2.4.0 | 2.4.0 ✅ | Upload sanitisation only |
| Yoast Duplicate Post | 4.5 | 4.6 | 4.6 ✅ | Admin post cloning tool |

---

### Phase 3 — Active plugins: Medium impact (Staging partial ✅ 2026-04-15)

**Risk:** Medium — visible frontend behaviour or analytics/security  
**Applies to:** Staging first, verify, then live

| Plugin | Live Current | Target | Staging | Notes |
|---|---|---|---|---|
| CookieYes | 3.1.2 | 3.4.0 | 3.4.0 ✅ | Cookie banner — clear cache after update |
| Redirection | 5.5.2 | 5.7.5 | 5.7.5 ✅ | No redirects configured on staging — full redirect test on live only |
| Site Kit by Google | 1.149.1 | 1.176.0 | 1.176.0 ✅ | Not connected on staging — full analytics test on live only |
| Wordfence Security | 8.1.4 | latest | 8.1.4 ⏳ | Check firewall rules still active |

> CookieYes changelog notes: colour customisation is now a paid feature — existing colours will continue to work, but flag this.

---

### Phase 4 — Active plugins: High impact

**Risk:** High — affects SEO data, multilingual output, and all contact forms  
**Extra care:** Verify staging thoroughly before touching live

| Plugin | Live Current | Target | Staging Current | Notes |
|---|---|---|---|---|
| Linguise | 2.1.73 | 2.2.40 | 2.2.40 ✅ | ⚠️ Translation not functioning on staging (billing/API key issue, pre-existing). Visual note: language selector popup is wider post-update — monitor on live. |
| WPForms | 1.9.7.2 | 1.10.0.4 | 1.9.6 ✅ | All 5 forms present and rendering on frontend — pass |
| Yoast SEO | 21.0 | 27.4 | 27.4 ✅ | Large version jump — reviewed, scores intact, sitemap good — pass |
| Yoast SEO Premium | 21.0 | 27.4 | 21.0 ✅ | Tested alongside Yoast SEO — pass |

> Yoast SEO: jumping from v21 to v27 on live is significant. Review release notes for breaking changes. Staging is already on v25.3 — update staging to 27.4 first and confirm SEO data is intact.

---

### Phase 5 — Active plugins: Critical impact ✅ Complete (Staging 2026-04-15)

**Risk:** Critical — ACF drives all custom field data across the entire site  
**Do not rush.** Update on staging, run full regression, only then update live.

| Plugin | Live Current | Target | Staging Current | Notes |
|---|---|---|---|---|
| Advanced Custom Fields PRO | 6.4.3 | 6.8.0.1 | 6.8.0.1 ✅ | Passed — note: custom admin CSS removed from `inc/admin.php` (conflicted with new ACF UI) |

> ACF 6.x → 6.8 changelog should be reviewed for any field type or API changes. After updating, check all page templates, blocks, and CPT archives.

---

### Phase 6 — WordPress core

**Risk:** Low — both environments are already on 6.9.4  
**Action:** Monitor for point releases and update staging → live as they land.

| | Current | Notes |
|---|---|---|
| Live | 6.9.4 | In sync with staging |
| Staging | 6.9.4 | In sync with live |

---

### Phase 7 — PHP upgrade (Live only)

**Risk:** High — PHP 7.4 is EOL. Upgrading to 8.2 matches staging.  
**Prerequisite:** All plugin updates above must be complete on live first.

| | Current | Target |
|---|---|---|
| Live | 7.4 (EOL) | 8.2 |
| Staging | 8.2 | Already on target |

**Pre-upgrade checks:**
- Confirm all active plugins are compatible with PHP 8.2 (check via the plugin compatibility data or test on staging)
- Theme PHP compatibility — run PHPCS against PHP 8.2 ruleset
- Take a full site backup immediately before switching
- Coordinate with hosting to switch PHP version
- Have a rollback plan: know how to revert to 7.4 quickly if issues arise

**Key risk areas with PHP 7.4 → 8.2:**
- Deprecated functions and removed features (e.g. `${var}` string interpolation removed in 8.2)
- Stricter type handling
- ACF PRO: confirm the target version (6.8.0.1) is PHP 8.2 compatible ✓

---

## 4. Testing Plan

Test on staging after each phase. Repeat equivalent checks on live after deploying that phase.

### Pre-update baseline (before any changes)

- [ ] Take full backup (staging + live)
- [ ] Record current Wordfence scan result
- [ ] Note Google Analytics baseline for traffic/errors
- [ ] Screenshot homepage, key landing pages, contact page

---

### Phase 1 — Inactive plugin cleanup (staging)

| Test | Expected | Result | Notes |
|---|---|---|---|
| Frontend loads without errors | No PHP errors/warnings | | |
| Admin dashboard functional | No broken admin pages | | |
| Confirm deleted plugins don't appear | Plugins list is clean | | |

---

### Phase 2 — Low impact plugin updates

| Test | Expected | Result | Notes |
|---|---|---|---|
| Upload an SVG file | Accepted and sanitised correctly | ✅ Pass | |
| Replace an existing media file | Works via Edit Media screen | ✅ Pass | |
| Post duplication works | Duplicate Post creates a copy | ✅ Pass | |
| No PHP errors in debug log | Clean log | ✅ Pass | Yoast Premium deprecation notices noted separately — not new |

---

### Phase 3 — Medium impact plugin updates

#### Staging tests

| Test | Expected | Result | Notes |
|---|---|---|---|
| Cookie banner displays on first visit | Banner shows, accepts/rejects work | ✅ Pass | |
| Cookie preferences saved across sessions | Banner doesn't re-appear after consent | ✅ Pass | |
| CookieYes colours intact after update | Existing custom colours still showing | ✅ Pass | |
| Redirection plugin active, no errors | Plugin updated — no redirects configured on staging, skip redirect test | | |
| Site Kit updated, no errors | Plugin updated — not connected on staging, skip analytics test | | |
| Wordfence scan runs clean | No malware/firewall issues | ✅ Pass | |
| WPForms — Register for updates | Form present on page, loads without errors | ✅ Pass | |
| WPForms — Newsletter Signup | Form present on page, loads without errors | ✅ Pass | |
| WPForms — Contact us | Form present on page, loads without errors | ✅ Pass | |
| WPForms — Request a Quote | Form present on page, loads without errors | ✅ Pass | |
| WPForms — Request a Sample | Form present on page, loads without errors | ✅ Pass | |

#### Live tests (after live update)

| Test | Expected | Result | Notes |
|---|---|---|---|
| Cookie banner displays on first visit | Banner shows, accepts/rejects work | | |
| Cookie preferences saved across sessions | Banner doesn't re-appear after consent | | |
| CookieYes colours intact after update | Existing custom colours still showing | | |
| Test known redirects still fire | 301s resolving correctly | | |
| Check Redirection log for new 404 spikes | No unexpected 404s post-update | | |
| Site Kit dashboard shows live data | Analytics data flowing, no auth errors | | |
| Wordfence scan runs clean | No malware/firewall issues | | |
| WPForms — Register for updates | Submit form, confirm submission received | | |
| WPForms — Newsletter Signup | Submit form, confirm submission received | | |
| WPForms — Contact us | Submit form, confirm email notification delivered | | |
| WPForms — Request a Quote | Submit form, confirm email notification delivered | | |
| WPForms — Request a Sample | Submit form, confirm email notification delivered | | |

---

### Phase 4 — High impact plugin updates

| Test | Expected | Result | Notes |
|---|---|---|---|
| All contact forms submit correctly | Submission succeeds, notifications sent | | |
| Form confirmation/redirect works | Post-submit action fires | | |
| Home page in all languages loads | Linguise translations rendering | ⚠️ N/A staging | Translation not working on staging — billing/API key issue, not plugin-related |
| Language switcher functional | Switching language works | ✅ Pass (partial) | Mostly good — language selector popup is wider post-update. Monitor on live. |
| No garbled/missing translated content | Spot check 3–4 key pages per language | ⚠️ N/A staging | Full test on live only |
| Homepage meta title/description correct | Yoast SEO data intact | ✅ Pass | SEO/readability scores unchanged after update |
| XML sitemap accessible (/sitemap_index.xml) | Sitemap renders correctly | ✅ Pass | |
| Structured data / schema intact | Check with Google Rich Results tool | | Deferred to live |
| No 500 errors in error log | Clean PHP/server log | | |

---

### Phase 5 — ACF (Critical)

| Test | Expected | Result | Notes |
|---|---|---|---|
| All custom blocks render on all page types | No broken/empty blocks | | |
| ACF field groups visible in admin | Field groups load in editor | | |
| Product pages (custom CPTs) load correctly | Data renders from ACF fields | | |
| Hero/banner fields display correctly | Images, text, CTAs all populated | | |
| Download / datasheet fields intact | PDFs/files still linked | | |
| Mega menu (if ACF-driven) renders | Navigation works | | |
| No PHP deprecated notices in debug log | Clean log | | |
| Run full site crawl (e.g. Screaming Frog) | No new 404s, broken links | | |

---

### Phase 6 — WordPress core updates

| Test | Expected | Result | Notes |
|---|---|---|---|
| Admin dashboard loads cleanly | No update or compatibility notices | | |
| All plugin compatibility confirmed | No incompatibility warnings | | |
| Frontend pages load | No regressions | | |

---

### Phase 7 — PHP upgrade (Live)

| Test | Expected | Result | Notes |
|---|---|---|---|
| Site loads on PHP 8.2 | No fatal errors | | |
| Admin dashboard accessible | Fully functional | | |
| All contact forms submit | End-to-end form test | | |
| All languages render | Linguise working | | |
| ACF data renders across site | Custom blocks/fields intact | | |
| Wordfence active and scanning | Security plugin functional | | |
| CookieYes banner functioning | GDPR compliance intact | | |
| Check PHP error log | No fatal/deprecated errors | | |
| Performance check | No regression vs PHP 7.4 baseline | | |

---

## 5. Pre-existing Bugs (unrelated to updates)

| Bug | Detail | Environment | Status |
|---|---|---|---|
| Linguise translation not working | Translation not functioning on staging — suspected billing/API key issue | Staging | Unresolved — check Linguise account |
| Bold headline styling discrepancy | Headline bold styling differs between `/news-centre/events/` and `/news-centre/blog/` | Staging | Unresolved — needs CSS investigation |
| Language flag/picker alignment | Language picker flag alignment is off | Staging | Unresolved — possibly related to wider popup noted post-Linguise update |
| ACF admin title bar clash | Custom dark title bar CSS in `inc/admin.php` conflicting with ACF 6.8's new admin UI | Staging | Fixed — removed custom style block from `inc/admin.php` |
| PHP 8.2 deprecation — Yoast SEO Premium | `Deprecated: Creation of dynamic property ...::$normalizedIds` in Yoast SEO Premium v21.0 — dynamic properties deprecated in PHP 8.2 | Staging | Not fatal. Won't show on live (PHP 7.4) but will appear when live upgrades to PHP 8.2. Fix: update Yoast SEO Premium to 27.4 on live as part of Phase 4. |
| Script dependency mismatch — Yoast SEO Premium | `yoast-seo-premium-metabox` depends on `yoast-seo-legacy-components` which no longer exists in Yoast SEO 27.4 — version mismatch between Yoast SEO (27.4) and Premium (21.0) | Staging | Not fatal. Same fix: update Yoast SEO and Yoast SEO Premium together on live. Do not leave a version gap between them. |
| BackWPup session_start() notice | PHP Notice: `session_start(): Ignoring session_start() because a session is already active` — `backwpup/src/Infrastructure/Restore/commons.php:395`. A session is already open when BackWPup's restore commons code tries to start one. Appears on every page load, not just during restore operations. | Staging | Not harmful, no user-facing impact. Logged for awareness — BackWPup plugin bug. |

---

## 6. Live — Outstanding To-Dos

| Item | Detail | Action Required |
|---|---|---|
| `hyperdb` / `hyperdb-1-1` folders in plugins directory | HyperDB is an Automattic database load-balancing drop-in — not a standard WP plugin, won't appear in WP Admin. Possibly a WP Engine legacy leftover or was used for database replication. Two copies (`hyperdb` and `hyperdb-1-1`) suggests a leftover upgrade. | Investigate before live updates: confirm whether it is active and in use (`db.php` drop-in in `wp-content/` would indicate active use), check with hosting provider, and remove if not needed. Do not delete without confirming it's inactive. |
| mu-plugins cleanup | Same WP Engine mu-plugins as staging — require Plesk permission change (755 recursive) before deletion | Mirror staging cleanup — change permissions recursively in Plesk before removing |
| Annual Report viewer folders | `ZotefoamsAR2020_v1_viewer`, `ZotefoamsAR2020_v1_viewer_old`, `ZotefoamsAR2021_v1_viewer`, `ZotefoamsAR2022_v1_viewer` sit outside WordPress — unmanaged, potentially stale. `_old` folder is explicitly superseded. | Audit: check if any are still linked from the site. Remove any that are not. `ZotefoamsAR2023_v1_viewer` and `annual-reports` are confirmed managed — leave. |

---

## 7. Notes & Decisions Log

| Date | Note |
|---|---|
| 2026-04-15 | Document created. Live on PHP 7.4 (EOL) — PHP upgrade is a priority but must come last. |
| 2026-04-15 | Phase 1 complete on staging — 30 inactive plugins deleted. 4 inactive plugins retained (UpdraftPlus, WP Engine GeoTarget, WP Mail SMTP, WPForms Lite). |
| 2026-04-15 | Phase 2 complete on staging — BackWPup, Enable Media Replace, Safe SVG, Yoast Duplicate Post all updated. |
| 2026-04-15 | All remaining inactive plugins deleted from staging (UpdraftPlus, WP Engine GeoTarget, WP Mail SMTP, WPForms Lite). Staging inactive list is now clean. |
| 2026-04-15 | Phase 3 complete on staging — Redirection, Site Kit, CookieYes all updated and passed. Wordfence up to date, no update needed. |
| 2026-04-15 | Bug noted (unrelated to updates): Linguise translation not functioning on staging — suspected billing/API key issue. Plugin will still be updated; full translation test deferred to live. |

