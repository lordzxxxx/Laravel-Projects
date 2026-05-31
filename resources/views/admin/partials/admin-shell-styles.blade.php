</style>
@include('partials.appearance-boot')
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }

:root {
    --green-dark: var(--brand-800, #3A5C48);
    --green-primary: var(--brand-700, #457359);
    --green-medium: var(--brand-500, #799F76);
    --green-soft: var(--brand-200, #CBDFC6);
    --green-white: var(--brand-50, #EDF4EA);
    --cream: var(--brand-50, #F4F8F1);
    --white: var(--app-surface-bg, #FFFFFF);
    --gray-200: var(--ink-200, #E5E7EB);
    --gray-300: var(--ink-300, #D1D5DB);
    --gray-400: var(--ink-400, #94A3B8);
    --gray-500: var(--ink-500, #6B7280);
    --gray-600: var(--ink-600, #475569);
    --gray-700: var(--ink-700, #374151);
    --gray-800: var(--ink-800, #1F2937);
    --gray-900: var(--ink-900, #0F172A);
}

body {
    background: var(--app-page-bg, var(--ink-50, #f4f8f5));
    min-height: 100vh;
    color: var(--ink-800, #1F2937);
    transition: background-color 0.2s ease, color 0.2s ease;
}

.dashboard-layout {
    padding-top: var(--app-content-offset, calc(var(--app-topbar-height, 4rem) + clamp(1rem, 2vw, 1.25rem)));
}

.main-content {
    padding: 28px 36px;
}

.main-content-narrow {
    max-width: 720px;
    margin-left: auto;
    margin-right: auto;
}

.page-header {
    margin-bottom: 20px;
}

/* ── Canonical page title used by every admin page ─────────────────────────
   • size:   1.75rem (28px)
   • weight: 800 (extra bold)
   • color:  slate-900
   • icon:   44px emerald-50 tile with emerald-700 glyph
   Use markup: <h1><span class="page-title-icon"><i class="fa-solid fa-…"></i></span><span>Title</span></h1>
   ───────────────────────────────────────────────────────────────────────── */
.page-header h1 {
    display: flex;
    align-items: center;
    gap: 0.875rem;
    font-family: var(--app-font-display);
    font-size: 1.75rem;
    font-weight: 800;
    color: var(--ink-900, #0F172A);
    line-height: 1.2;
    letter-spacing: -0.015em;
    margin: 0;
}

.page-header h1 .page-title-icon,
.page-header h1 > .icon-wrap {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 44px;
    height: 44px;
    border-radius: var(--radius-lg, 0.75rem);
    background: var(--chrome-icon-bg, #F9DEE5);
    color: var(--chrome-icon-color, #B0436E);
    border: 1px solid var(--chrome-icon-border, #F3C9D6);
    font-size: 18px;
    flex-shrink: 0;
}

.page-header p {
    color: var(--ink-500, #64748B);
    font-size: 0.875rem;
    line-height: 1.6;
    margin: 0.5rem 0 0 3.65rem;
}

@media (max-width: 640px) {
    .page-header h1 { font-size: 1.4rem; }
    .page-header p { margin-left: 0; }
}

.page-header-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 20px;
}

.page-header-row .page-header {
    margin-bottom: 0;
}

.flash {
    background: #ECFDF5;
    border: 1px solid #86EFAC;
    color: #166534;
    padding: 10px 12px;
    border-radius: 10px;
    margin-bottom: 16px;
    font-weight: 600;
}

.card {
    background: var(--app-surface-bg, var(--white));
    border-radius: 14px;
    border: 1px solid var(--app-surface-border, var(--green-soft));
    box-shadow: var(--shadow-md, 0 8px 30px rgba(27, 94, 32, 0.1));
    overflow: hidden;
    color: var(--ink-800);
}

.card-padded {
    padding: 24px;
}

@media (min-width: 768px) {
    .card-padded {
        padding: 28px 32px;
    }
}

.btn-admin-primary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.9rem;
    text-decoration: none;
    border: 1px solid var(--action-primary-border, rgba(27, 94, 32, 0.35));
    cursor: pointer;
    background: var(--action-primary-bg, var(--green-primary));
    color: var(--action-primary-text, #fff);
    box-shadow: var(--shadow-sm, 0 1px 2px rgba(15, 23, 42, 0.06));
    transition: background 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
}

.btn-admin-primary:hover {
    background: var(--action-primary-hover, var(--green-dark));
    box-shadow: 0 4px 14px color-mix(in srgb, var(--chrome-active-bg, #457359) 30%, transparent);
}

.btn-admin-secondary {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 10px 18px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.9rem;
    text-decoration: none;
    border: 2px solid var(--app-surface-border, var(--green-soft));
    background: var(--app-surface-bg, var(--white));
    color: var(--ink-800, var(--green-dark));
    cursor: pointer;
    transition: background 0.15s ease, border-color 0.15s ease, color 0.15s ease;
}

.btn-admin-secondary:hover {
    background: var(--app-surface-muted-bg, var(--green-white));
}

.btn-admin-sm {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 6px 10px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.72rem;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: opacity 0.15s ease;
}

.btn-admin-sm:hover {
    opacity: 0.92;
}

.btn-admin-sm-emerald {
    background: #059669;
    color: #fff;
}

.btn-admin-sm-outline {
    background: var(--app-surface-bg, var(--white));
    color: var(--ink-800, var(--green-dark));
    border: 2px solid var(--app-surface-border, var(--green-soft));
}

.btn-admin-sm-danger {
    background: #b91c1c;
    color: #fff;
}

.btn-admin-sm-amber {
    background: #fff7ed;
    color: #9a3412;
    border: 1px solid #fdba74;
}

.btn-admin-sm-mint {
    background: #ecfdf5;
    color: #166534;
    border: 1px solid #86efac;
}

@include('admin.partials.top-navbar-styles')

@include('admin.partials.central-portal-background-styles')
