@include('partials.typography-system')

:root {
    /* Typography */
    --app-font-sans: 'Inter', ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Roboto, Arial, 'Noto Sans', sans-serif;
    --app-font-display: 'Space Grotesk', var(--app-font-sans);
    --client-nav-font: var(--app-font-sans);

    /* Ink / neutral scale (cool slate) */
    --ink-900: #0F172A;
    --ink-800: #1E293B;
    --ink-700: #334155;
    --ink-600: #475569;
    --ink-500: #64748B;
    --ink-400: #94A3B8;
    --ink-300: #CBD5E1;
    --ink-200: #E2E8F0;
    --ink-100: #F1F5F9;
    --ink-50:  #F7F9F7;

    /* Brand / forest-sage scale (matches Love Impasugong photo greens) */
    --brand-900: #2A4434;
    --brand-800: #34543F;
    --brand-700: #457359;
    --brand-600: #56856A;
    --brand-500: #799F76;
    --brand-300: #A8C4A2;
    --brand-200: #CBDFC6;
    --brand-100: #E6F0E3;
    --brand-50:  #F4F8F1;

    /* Cultural accents — pink family (secondary brand color) */
    --accent-rust:  #D37897;
    --accent-gold:  #E09FB3;
    --accent-cream: #F9DEE5;

    /* Pink usage tokens (paired for contrast) */
    --accent-pink:        #D37897; /* base pink */
    --accent-pink-strong: #C25C82; /* fills with white text (AA for bold) */
    --accent-pink-deep:   #B0436E; /* text/icons on light surfaces */
    --accent-pink-soft:   #F9DEE5; /* soft pink background */
    --accent-pink-border: #F0C3D2; /* hairline pink borders */

    /* Semantic / status */
    --status-success: #15803D;
    --status-warning: #B45309;
    --status-danger:  #B91C1C;
    --status-info:    #0E7490;

    /* Elevation */
    --shadow-sm: 0 1px 2px rgba(15, 23, 42, 0.04);
    --shadow-md: 0 1px 2px rgba(15, 23, 42, 0.04), 0 8px 24px -16px rgba(15, 23, 42, 0.18);
    --shadow-lg: 0 1px 2px rgba(15, 23, 42, 0.04), 0 18px 48px -28px rgba(15, 23, 42, 0.18);

    /* Radii */
    --radius-sm:  0.5rem;
    --radius-md:  0.625rem;
    --radius-lg:  0.75rem;
    --radius-xl:  1rem;
    --radius-2xl: 1.5rem;

    /* Primary actions (buttons, submit) — forest green on all themes */
    --action-primary-bg: var(--brand-700);
    --action-primary-hover: var(--brand-800);
    --action-primary-border: rgba(52, 84, 63, 0.4);
    --action-primary-text: #ffffff;

    /* Decorative accents (eyebrows, nav chrome, highlights) — theme via chrome-* */
    --ui-accent-color: var(--accent-pink-deep);
    --ui-accent-strong: var(--accent-pink-strong);
    --ui-accent-surface: var(--accent-pink-soft);
    --ui-accent-border: var(--accent-pink-border);

    /* Chrome (nav, avatar, page icons) — Impasugong default */
    --chrome-active-bg: var(--accent-pink-strong);
    --chrome-active-border: rgba(176, 67, 110, 0.45);
    --chrome-focus-ring: var(--accent-pink-strong);
    --chrome-avatar-bg: var(--accent-pink-strong);
    --chrome-surface-bg: var(--accent-pink-soft);
    --chrome-surface-border: var(--accent-pink-border);
    --chrome-icon-bg: var(--accent-cream);
    --chrome-icon-color: var(--accent-pink-deep);
    --chrome-icon-border: #F3C9D6;

    --app-page-bg: var(--ink-50);
    --app-surface-bg: rgba(255, 255, 255, 0.88);
    --app-surface-border: rgba(209, 213, 219, 0.7);
    --app-surface-muted-bg: rgba(248, 250, 252, 0.78);

    /* Legacy aliases (admin shell, guest pages, reports) — follow ink + surfaces */
    --gray-50: var(--ink-50);
    --gray-100: var(--ink-100);
    --gray-200: var(--ink-200);
    --gray-300: var(--ink-300);
    --gray-400: var(--ink-400);
    --gray-500: var(--ink-500);
    --gray-600: var(--ink-600);
    --gray-700: var(--ink-700);
    --gray-800: var(--ink-800);
    --gray-900: var(--ink-900);
    --cream: var(--brand-50);
    --green-white: var(--brand-50);
    --white: #ffffff;
    --green-primary: var(--brand-700);
    --green-dark: var(--brand-800);
    --green-soft: var(--brand-200);
    --green-medium: var(--brand-500);

    /* Top nav — follows appearance chrome */
    --nav-brand-color: var(--chrome-icon-color);
    --nav-brand-muted: var(--accent-pink);

    /* Premium tourism brand heading — fixed forest-green identity (theme-independent) */
    --nav-heading-font: var(--app-font-display);
    --nav-heading-color: var(--brand-800, #34543F);
    --nav-heading-accent: var(--brand-800, #34543F);
    --nav-bar-bg: rgba(255, 255, 255, 0.9);
    --nav-bar-border: rgba(229, 231, 235, 0.95);
    --nav-bar-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
    --nav-bar-overlay: linear-gradient(180deg, rgba(255, 255, 255, 0.48) 0%, rgba(255, 255, 255, 0.3) 42%, rgba(255, 255, 255, 0.38) 100%), rgba(15, 23, 42, 0.05);
    --nav-link-idle-bg: rgba(255, 255, 255, 0.55);
    --nav-link-idle-border: rgba(15, 23, 42, 0.1);
    --nav-link-idle-color: var(--ink-600);

    /* Premium app navbar (SaaS typography + rhythm) */
    --app-topbar-height: 84px;
    --app-topbar-height-mobile: 3.5rem;
    --nav-link-font-size: 0.8125rem;
    --nav-link-font-weight: 500;
    --nav-link-font-weight-active: 600;
    --nav-link-padding-x: 0.6875rem;
    --nav-link-padding-y: 0.4375rem;
    --nav-link-gap: 0.375rem;
    --nav-link-min-height: 2.375rem;
}

:root,
:root[data-theme="impasugong"] {
    --ui-accent-color: var(--accent-pink-deep);
    --ui-accent-strong: var(--accent-pink-strong);
    --ui-accent-surface: var(--accent-pink-soft);
    --ui-accent-border: var(--accent-pink-border);
    --chrome-active-bg: var(--accent-pink-strong);
    --chrome-active-border: rgba(176, 67, 110, 0.45);
    --chrome-focus-ring: var(--accent-pink-strong);
    --chrome-avatar-bg: var(--accent-pink-strong);
    --chrome-surface-bg: var(--accent-pink-soft);
    --chrome-surface-border: var(--accent-pink-border);
    --chrome-icon-bg: var(--accent-cream);
    --chrome-icon-color: var(--accent-pink-deep);
    --chrome-icon-border: #F3C9D6;
    --nav-brand-color: var(--accent-pink-deep);
    --nav-brand-muted: var(--accent-pink);
    --action-primary-bg: var(--brand-700);
    --action-primary-hover: var(--brand-800);
    --action-primary-border: rgba(52, 84, 63, 0.4);
}

:root[data-theme="green"] {
    --ui-accent-color: var(--brand-700);
    --ui-accent-strong: var(--brand-700);
    --ui-accent-surface: var(--brand-50);
    --ui-accent-border: var(--brand-200);
    --chrome-active-bg: var(--brand-700);
    --chrome-active-border: rgba(69, 115, 89, 0.45);
    --chrome-focus-ring: var(--brand-700);
    --chrome-avatar-bg: var(--brand-700);
    --chrome-surface-bg: var(--brand-50);
    --chrome-surface-border: var(--brand-200);
    --chrome-icon-bg: var(--brand-50);
    --chrome-icon-color: var(--brand-700);
    --chrome-icon-border: var(--brand-100);
    --nav-brand-color: var(--brand-800);
    --nav-brand-muted: var(--brand-600);
    --action-primary-bg: var(--brand-700);
    --action-primary-hover: var(--brand-800);
    --action-primary-border: rgba(52, 84, 63, 0.4);
}

html.dark {
    color-scheme: dark;

    /* Type on dark surfaces — light grey hierarchy (bright → soft) */
    --ink-900: #F8FAFC;
    --ink-800: #E2E8F0;
    --ink-700: #CBD5E1;
    --ink-600: #94A3B8;
    --ink-500: #8B9CB3;
    --ink-400: #78859A;
    --ink-300: #334155;
    --ink-200: #1E293B;
    --ink-100: #151C28;
    --ink-50:  #0F172A;

    --text-primary: var(--ink-800);
    --text-secondary: var(--ink-700);
    --text-muted: var(--ink-600);
    --text-faint: var(--ink-500);

    --app-page-bg: #0F172A;
    --app-surface-bg: rgba(30, 41, 59, 0.92);
    --app-surface-border: rgba(51, 65, 85, 0.85);
    --app-surface-muted-bg: rgba(21, 28, 40, 0.88);

    --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.2);
    --shadow-md: 0 1px 2px rgba(0, 0, 0, 0.2), 0 8px 24px -16px rgba(0, 0, 0, 0.45);
    --shadow-lg: 0 1px 2px rgba(0, 0, 0, 0.2), 0 18px 48px -28px rgba(0, 0, 0, 0.5);

    --nav-bar-bg: rgba(30, 41, 59, 0.92);
    --nav-bar-border: rgba(51, 65, 85, 0.85);
    --nav-bar-shadow: 0 8px 24px rgba(0, 0, 0, 0.35);
    --nav-bar-overlay: linear-gradient(180deg, rgba(15, 23, 42, 0.55) 0%, rgba(15, 23, 42, 0.35) 42%, rgba(15, 23, 42, 0.45) 100%), rgba(0, 0, 0, 0.12);
    --nav-link-idle-bg: rgba(30, 41, 59, 0.65);
    --nav-link-idle-border: rgba(148, 163, 184, 0.22);
    --nav-link-idle-color: var(--ink-700);
    --nav-brand-color: var(--chrome-icon-color);
    --nav-brand-muted: var(--chrome-icon-color);

    /* Lift forest-green brand heading for dark backgrounds */
    --nav-heading-color: #BCD9C6;
    --nav-heading-accent: var(--nav-heading-color, #BCD9C6);

    --cream: var(--app-surface-muted-bg);
    --green-white: var(--app-surface-muted-bg);
    --white: var(--app-surface-bg);
    --green-primary: var(--brand-600);
    --green-dark: #BCD9C6;
    --green-soft: var(--app-surface-border);
    --green-medium: var(--brand-500);
}

html.dark[data-theme="green"] {
    --chrome-active-bg: var(--brand-600);
    --chrome-avatar-bg: var(--brand-600);
}

html.dark[data-theme="impasugong"] {
    --ui-accent-color: #F9DEE5;
    --ui-accent-strong: #E09FB3;
    --ui-accent-surface: rgba(249, 222, 229, 0.14);
    --ui-accent-border: rgba(240, 195, 210, 0.45);
    --chrome-active-bg: #D37897;
    --chrome-active-border: rgba(211, 120, 151, 0.5);
    --chrome-focus-ring: #E09FB3;
    --chrome-avatar-bg: #D37897;
    --chrome-surface-bg: rgba(249, 222, 229, 0.12);
    --chrome-surface-border: rgba(240, 195, 210, 0.35);
    --chrome-icon-bg: rgba(249, 222, 229, 0.15);
    --chrome-icon-color: #F9DEE5;
    --chrome-icon-border: rgba(240, 195, 210, 0.4);
    --action-primary-bg: var(--brand-600);
    --action-primary-hover: var(--brand-700);
}

body {
    background-color: var(--app-page-bg);
    color: var(--ink-800);
}

/* Dark display mode — default copy to light grey */
html.dark body {
    color: var(--text-primary, var(--ink-800));
}

html.dark :where(
    .main-content,
    .client-guest-main,
    .owner-app-main,
    .dashboard-layout,
    .portal-public-main
) {
    color: var(--text-primary, var(--ink-800));
}

html.dark :where(
    h1, h2, h3, h4, h5, h6,
    .owner-page-hero__title,
    .page-header h1,
    .card-header h3,
    .panel-header h1,
    .panel-header h2
) {
    color: var(--ink-900);
}

html.dark :where(
    p,
    .owner-page-hero__lede,
    .page-header p,
    .panel-header p,
    .section-desc,
    .snapshot-meta,
    li,
    dd,
    dt
) {
    color: var(--text-secondary, var(--ink-700));
}

html.dark :where(
    .muted,
    .table-note,
    .owner-page-hero__eyebrow,
    label,
    .form-label,
    .filter-field label
) {
    color: var(--text-muted, var(--ink-600));
}

html.dark :where(input, textarea, select) {
    color: var(--text-primary, var(--ink-800));
}

html.dark :where(input::placeholder, textarea::placeholder) {
    color: var(--ink-400);
    opacity: 1;
}

html.dark :where(table th) {
    color: var(--text-muted, var(--ink-600));
}

html.dark :where(table td) {
    color: var(--text-secondary, var(--ink-700));
}

/* Flash alerts */
.alert-stack {
    display: grid;
    gap: 10px;
    margin-bottom: 16px;
}
.alert {
    border-radius: var(--radius-lg);
    border: 1px solid;
    padding: 10px 14px;
    font-size: 0.9rem;
    font-weight: 600;
}
.alert-success { background: #ecfdf5; border-color: #86efac; color: var(--status-success); }
.alert-error   { background: #fef2f2; border-color: #fecaca; color: var(--status-danger); }
.alert-warning { background: #fffbeb; border-color: #fcd34d; color: var(--status-warning); }
.alert-info    { background: #eff6ff; border-color: #bfdbfe; color: var(--status-info); }

/* Reusable status badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    border-radius: 999px;
    padding: 6px 12px;
    font-size: 0.76rem;
    font-weight: 700;
}
.status-badge.pending { background: #fef3c7; color: var(--status-warning); }
.status-badge.confirmed, .status-badge.open, .status-badge.active { background: #dcfce7; color: var(--status-success); }
.status-badge.completed, .status-badge.resolved { background: #dbeafe; color: var(--status-info); }
.status-badge.cancelled, .status-badge.inactive { background: #fee2e2; color: var(--status-danger); }

.payment-badge {
    display: inline-flex;
    align-items: center;
    border-radius: 999px;
    padding: 6px 12px;
    font-size: 0.76rem;
    font-weight: 700;
}
.payment-badge.neutral { background: var(--ink-100); color: var(--ink-700); }
.payment-badge.pending-review { background: #fff7ed; color: var(--status-warning); }
.payment-badge.paid { background: #ecfdf5; color: var(--status-success); }

/* Loading button state */
[data-loading-button][disabled] {
    opacity: 0.75;
    cursor: wait;
}

/* Shared modern surfaces */
.ui-surface {
    background: var(--app-surface-bg);
    border: 1px solid var(--app-surface-border);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-md);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}

.ui-section-muted {
    background: var(--app-surface-muted-bg);
    border: 1px solid var(--app-surface-border);
    border-radius: var(--radius-lg);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
}

.ui-heading {
    color: var(--ink-900);
    font-weight: 700;
    letter-spacing: 0.01em;
}

/* ── Canonical page title used system-wide (admin / owner / client / profile) ─
   Markup:
     <div class="page-header">
         <h1>
             <span class="page-title-icon"><i class="fa-solid fa-..."></i></span>
             <span>Page Title</span>
         </h1>
         <p>Description...</p>
     </div>
   The same selector also catches the legacy `.page-title` wrapper used by the
   profile page. `:where(...)` keeps specificity at 0 so per-page overrides win
   if a page truly needs a different title look.
   ───────────────────────────────────────────────────────────────────────── */
:where(.page-header, .page-title, .panel-header) h1 {
    display: flex;
    align-items: center;
    gap: 0.875rem;
    font-family: var(--app-font-display);
    font-size: 1.75rem;
    font-weight: 800;
    color: var(--ink-900);
    line-height: 1.2;
    letter-spacing: -0.015em;
    margin: 0;
}

:where(.page-header, .page-title) h1 .page-title-icon,
:where(.page-header, .page-title) h1 > .icon-wrap {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 44px;
    height: 44px;
    border-radius: var(--radius-lg);
    background: var(--chrome-icon-bg);
    color: var(--chrome-icon-color);
    border: 1px solid var(--chrome-icon-border);
    font-size: 18px;
    flex-shrink: 0;
}

:where(.page-header, .page-title) p {
    color: var(--ink-500);
    font-size: 0.875rem;
    line-height: 1.6;
    margin: 0.5rem 0 0 3.65rem;
}

@media (max-width: 640px) {
    :where(.page-header, .page-title) h1 { font-size: 1.4rem; }
    :where(.page-header, .page-title) p { margin-left: 0; }
}

@include('partials.appearance-surfaces')
