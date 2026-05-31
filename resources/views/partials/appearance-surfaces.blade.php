{{-- System-wide surfaces, tables, and typography for appearance theme + display mode.
     Included from ui-foundation-styles on every page that loads the design tokens. --}}

/* ── Shared card / surface primitives (light + dark via tokens) ─────────── */
:where(
    .card,
    .ui-surface,
    .dashboard-card,
    .filter-card,
    .kpi-card,
    .kpi,
    .stat-card,
    .panel,
    .updates-surface,
    .msg-card,
    .booking-card,
    .property-card,
    .accommodation-card,
    .empty-state,
    .perm-category,
    .role-card,
    .log-card,
    .surface,
    .properties-section,
    .perm-option,
    .table-wrap,
    .support-table-wrap
) {
    background: var(--app-surface-bg, #fff);
    border-color: var(--app-surface-border, rgba(209, 213, 219, 0.7));
    color: var(--ink-800);
}

:where(
    .card-header h3,
    .card h3,
    .dashboard-card h3,
    .card-title,
    .panel-header h1,
    .panel-header h2,
    .msg-card-header h2
) {
    color: var(--ink-800);
}

:where(.panel-header p, .section-desc, .snapshot-meta) {
    color: var(--ink-500);
}

:where(.card-body, .card-padded, .table-container) {
    color: var(--ink-700);
}

/* Tables */
:where(table) {
    color: var(--ink-700);
}

:where(table th) {
    color: var(--ink-600);
    border-color: var(--ink-200);
}

:where(table td) {
    color: var(--ink-700);
    border-color: var(--ink-200);
}

:where(.data-table th, table thead th) {
    background: var(--app-surface-muted-bg, var(--ink-50));
    color: var(--ink-600);
}

:where(.data-table tr:hover, table tbody tr:hover) {
    background: var(--app-surface-muted-bg, var(--ink-50));
}

/* Form controls on admin / owner pages */
:where(
    .filter-field input,
    .filter-field select,
    .filter-field textarea,
    .form-control,
    input[type="text"],
    input[type="email"],
    input[type="password"],
    input[type="date"],
    input[type="number"],
    input[type="search"],
    input[type="tel"],
    select,
    textarea
) {
    background: var(--app-surface-bg, #fff);
    border-color: var(--app-surface-border, var(--ink-200));
    color: var(--ink-800);
}

:where(.filter-field label, .form-label) {
    color: var(--ink-500);
}

/* KPI / stats */
:where(.kpi-region-title, .kpi-info p, .kpi-info .kpi-sub, .stat-card p) {
    color: var(--ink-500);
}

:where(.kpi-info h3, .stat-card h3, .stat-card .stat-value) {
    color: var(--chrome-icon-color, var(--ink-900));
}

/* Power BI–style blocks */
:where(.pbi-visual) {
    background: var(--app-surface-bg, #fff);
    border-color: color-mix(in srgb, var(--chrome-focus-ring, var(--brand-700)) 28%, transparent);
}

:where(.pbi-visual-body, .pbi-visual-body--flush) {
    background: var(--app-surface-muted-bg, #fafdfb);
    color: var(--ink-700);
}

/* Demographics / pill metrics */
:where(.pill, .metric-pill) {
    background: var(--app-surface-muted-bg);
    border-color: var(--app-surface-border);
    color: var(--ink-700);
}

:where(.pill .value, .metric-pill .value) {
    color: var(--chrome-icon-color, var(--brand-700));
}

/* Muted helper text */
:where(.muted, .text-muted, .help-text) {
    color: var(--ink-500);
}

/* Page shells using legacy body backgrounds */
:where(body.owner-nav-page, body.admin-central-portal) {
    background: var(--app-page-bg) !important;
    color: var(--ink-800);
}

:where(.main-content, .dashboard-layout, .msg-admin-main) {
    color: var(--ink-800);
}

@include('partials.main-content-watermark-styles')

:where(.report-page) {
    background: var(--app-page-bg);
    color: var(--ink-800);
}

:where(.surface-header, .properties-section-header) {
    background: var(--app-surface-muted-bg);
    border-color: var(--app-surface-border);
}

:where(.surface-header h3, .properties-section-header h3) {
    color: var(--ink-900);
}

:where(.surface-body, .properties-grid) {
    color: var(--ink-700);
}

:where(.table-wrap table thead th, .report-page table th) {
    background: var(--app-surface-muted-bg);
    color: var(--ink-600);
    border-color: var(--app-surface-border);
}

:where(.table-wrap table tbody td, .report-page table td) {
    color: var(--ink-700);
    border-color: var(--app-surface-border);
}

:where(.perm-category summary, .role-summary) {
    background: var(--app-surface-muted-bg);
    color: var(--chrome-icon-color, var(--ink-800));
    border-color: var(--app-surface-border);
}

:where(.tenant-filters, .tenants-table-head, .tenant-table-body) {
    background: var(--app-surface-muted-bg);
    border-color: var(--app-surface-border);
    color: var(--ink-700);
}

:where(.tenant-table-body) {
    background: var(--app-surface-bg);
}

:where(.tenant-gcash-notice) {
    background: color-mix(in srgb, var(--chrome-active-bg) 8%, var(--app-surface-bg));
    border-color: color-mix(in srgb, var(--chrome-focus-ring) 25%, var(--app-surface-border));
}

:where(.btn, .btn-filter, .btn-secondary) {
    transition: background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease;
}

:where(.btn.primary, .btn-filter.primary, .btn-primary) {
    background: var(--action-primary-bg, var(--brand-700, #457359));
    border-color: var(--action-primary-border, transparent);
    color: var(--action-primary-text, #fff);
}

:where(.support-table th, .tenants-table-head) {
    background: var(--app-surface-muted-bg, var(--ink-50));
    color: var(--ink-600);
}

/* Messaging tokens → appearance-aware */
html.dark {
    --msg-surface: var(--app-surface-bg);
    --msg-muted: var(--ink-500);
    --msg-border: var(--app-surface-border);
    --msg-bg-chat: var(--app-page-bg);
    --msg-bubble-in: var(--ink-100);
    --msg-accent: var(--chrome-active-bg);
    --msg-accent-hover: var(--chrome-focus-ring);
}

/* ── Dark display mode overrides (legacy pages with hardcoded light colors) ─ */
html.dark :where(
    .card,
    .ui-surface,
    .dashboard-card,
    .filter-card,
    .kpi-card,
    .kpi,
    .stat-card,
    .owner-card,
    .panel,
    .updates-surface,
    .msg-card,
    .booking-card,
    .property-card,
    .accommodation-card,
    .empty-state,
    .perm-category,
    .role-card,
    .log-card,
    .surface,
    .properties-section,
    .perm-option,
    .report-page .card,
    .report-page .table-wrap,
    .table-wrap,
    .support-table-wrap
) {
    background: var(--app-surface-bg) !important;
    border-color: var(--app-surface-border) !important;
    box-shadow: var(--shadow-sm) !important;
    color: var(--ink-800);
}

html.dark :where(.card-header, .card-body, .table-container, .card-padded) {
    border-color: var(--app-surface-border);
    color: var(--ink-800);
}

html.dark :where(.page-header h1, .page-header p) {
    color: var(--ink-900) !important;
}

html.dark :where(.page-header p) {
    color: var(--ink-500) !important;
}

html.dark :where(
    .card-header h3,
    .dashboard-card h3,
    .kpi-info h3,
    .stat-card h3,
    .page-header h2,
    .panel-header h1,
    .panel-header h2,
    .msg-card-header h2
) {
    color: var(--ink-900) !important;
}

html.dark :where(.panel-header p, .panel-header, .section-body) {
    border-color: var(--app-surface-border) !important;
    color: var(--ink-700);
}

html.dark :where(.panel-header h1, .panel-header h2) {
    color: var(--chrome-icon-color, var(--ink-900)) !important;
}

html.dark :where(body) {
    background: var(--app-page-bg) !important;
    color: var(--ink-800) !important;
}

html.dark :where(.main-content) {
    color: var(--ink-800);
}

html.dark :where(.btn, .btn-filter) {
    background: var(--app-surface-bg) !important;
    border-color: var(--app-surface-border) !important;
    color: var(--ink-800) !important;
}

html.dark :where(.btn.primary, .btn-filter.primary, .btn.primary) {
    background: var(--action-primary-bg) !important;
    color: var(--action-primary-text, #fff) !important;
    border-color: var(--action-primary-border) !important;
}

html.dark :where(.support-table th, .support-table td, th, td) {
    border-color: var(--ink-200) !important;
}

html.dark :where(.updates-surface) {
    background: var(--app-surface-bg) !important;
    border-color: var(--app-surface-border) !important;
}

html.dark :where(.msg-card-header) {
    background: var(--app-surface-muted-bg) !important;
    border-color: var(--app-surface-border) !important;
}

html.dark :where(.msg-card-header h2) {
    color: var(--ink-900) !important;
}

html.dark :where(.field input, .field select, .filters select) {
    background: var(--app-surface-muted-bg) !important;
    border-color: var(--ink-300) !important;
    color: var(--ink-800) !important;
}

/* Client dashboard / Tailwind gradient shells */
html.dark body.text-gray-800,
html.dark body.bg-gradient-to-br {
    background: var(--app-page-bg) !important;
    color: var(--ink-800) !important;
}

html.dark .from-green-50 {
    --tw-gradient-from: var(--app-page-bg) var(--tw-gradient-from-position);
    --tw-gradient-to: rgb(15 23 42 / 0) var(--tw-gradient-to-position);
    --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to);
}

html.dark .via-lime-50 {
    --tw-gradient-via: var(--app-page-bg) var(--tw-gradient-via-position);
}

html.dark .to-white {
    --tw-gradient-to: var(--app-page-bg) var(--tw-gradient-to-position);
}

html.dark .bg-gray-100 {
    background-color: var(--app-page-bg) !important;
}

html.dark .dark\:bg-gray-900 {
    background-color: var(--app-page-bg) !important;
}

html.dark :where(.log-card, .filter-input, .btn-reset) {
    background: var(--app-surface-bg) !important;
    border-color: var(--app-surface-border) !important;
    color: var(--ink-800) !important;
}

html.dark :where(.diff-table th, .diff-table td) {
    border-color: var(--ink-200) !important;
}

html.dark :where(.diff-table thead th) {
    background: var(--app-surface-muted-bg) !important;
    color: var(--ink-500) !important;
}

html.dark :where(.diff-cell, .diff-table .diff-key) {
    color: var(--ink-700) !important;
}

html.dark :where(.meta-pill) {
    background: var(--app-surface-muted-bg) !important;
    border-color: var(--app-surface-border) !important;
    color: var(--ink-600) !important;
}

html.dark :where(
    .pbi-kpi-table,
    .pbi-kpi-table tbody td,
    .pbi-chart-panel,
    .pbi-mini-table-wrap,
    .pbi-visual,
    .pbi-visual-body,
    .pbi-visual-body--demographics
) {
    background: var(--app-surface-bg) !important;
    border-color: var(--app-surface-border) !important;
}

html.dark :where(.pbi-kpi-table thead th, .pbi-chart-panel-head) {
    background: var(--app-surface-muted-bg) !important;
    color: var(--chrome-icon-color, var(--ink-500)) !important;
    border-color: var(--app-surface-border) !important;
}

html.dark :where(.pbi-kpi-value, .pbi-mini-table td.pbi-num, .pbi-mini-table-title) {
    color: var(--chrome-icon-color, var(--ink-900)) !important;
}

html.dark :where(.pbi-mini-table th, .pbi-mini-table td, .pbi-mini-table td.pbi-muted) {
    color: var(--ink-600) !important;
    border-color: var(--app-surface-border) !important;
}

html.dark :where(.kpi-info p, .kpi-info .kpi-sub, .kpi-region-title, .stat-card p, .muted) {
    color: var(--ink-500) !important;
}

html.dark :where(.filter-field label) {
    color: var(--ink-500) !important;
}

html.dark :where(
    .filter-field input,
    .filter-field select,
    .filter-field textarea,
    .form-control
) {
    background: var(--app-surface-muted-bg) !important;
    border-color: var(--ink-300) !important;
    color: var(--ink-800) !important;
}

html.dark :where(table th, .data-table th) {
    background: var(--app-surface-muted-bg) !important;
    color: var(--ink-500) !important;
    border-color: var(--ink-200) !important;
}

html.dark :where(table td, .data-table td) {
    color: var(--ink-700) !important;
    border-color: var(--ink-200) !important;
}

html.dark :where(.data-table tr:hover, table tbody tr:hover) {
    background: var(--app-surface-muted-bg) !important;
}

html.dark :where(.report-page) {
    background: var(--app-page-bg) !important;
    color: var(--ink-800) !important;
}

html.dark :where(.surface-header, .properties-section-header) {
    background: var(--app-surface-muted-bg) !important;
    border-color: var(--app-surface-border) !important;
}

html.dark :where(.surface-header h3, .properties-section-header h3) {
    color: var(--ink-900) !important;
}

html.dark :where(tbody tr:nth-child(even)) {
    background: var(--app-surface-muted-bg) !important;
}

html.dark :where(tbody tr:hover) {
    background: color-mix(in srgb, var(--chrome-active-bg) 12%, var(--app-surface-bg)) !important;
}

html.dark :where(tfoot td) {
    background: var(--app-surface-muted-bg) !important;
    border-color: var(--app-surface-border) !important;
    color: var(--ink-900) !important;
}

html.dark :where(.perm-category summary, .role-summary) {
    background: var(--app-surface-muted-bg) !important;
    color: var(--chrome-icon-color, var(--ink-800)) !important;
    border-color: var(--app-surface-border) !important;
}

html.dark :where(.tenant-filters, .tenants-table-head) {
    background: var(--app-surface-muted-bg) !important;
    border-color: var(--app-surface-border) !important;
}

html.dark :where(.tenant-table-body) {
    background: var(--app-surface-bg) !important;
}

html.dark :where(.tenant-row-labels) {
    color: var(--ink-500) !important;
}

html.dark :where(.tenant-gcash-notice__title) {
    color: var(--chrome-icon-color, var(--ink-300)) !important;
}

html.dark :where(.tenant-gcash-notice__body, .tenant-gcash-notice__body strong) {
    color: var(--ink-600) !important;
}

html.dark :where(.tenant-gcash-notice__body strong) {
    color: var(--ink-700) !important;
}

html.dark :where(.tenant-row-hover:hover) {
    background: var(--app-surface-muted-bg) !important;
}

html.dark .bg-sky-50,
html.dark .bg-sky-100 {
    background-color: color-mix(in srgb, var(--chrome-active-bg) 12%, var(--app-surface-muted-bg)) !important;
}

html.dark :where(.pbi-visual) {
    background: var(--app-surface-bg) !important;
    border-color: color-mix(in srgb, var(--chrome-focus-ring) 35%, transparent) !important;
}

html.dark :where(.pbi-visual-header) {
    background: var(--chrome-active-bg, #166534) !important;
    color: #fff !important;
}

html.dark :where(.pbi-visual-body) {
    background: var(--app-surface-muted-bg) !important;
    color: var(--ink-700);
}

html.dark :where(.pbi-visual-title, .pbi-visual-meta) {
    color: #fff;
}

html.dark :where(.tenants-table-head, .border-gray-200) {
    border-color: var(--app-surface-border) !important;
}

html.dark :where(.btn-admin-secondary) {
    background: var(--app-surface-bg) !important;
    border-color: var(--app-surface-border) !important;
    color: var(--ink-800) !important;
}

html.dark :where(.btn-admin-secondary:hover) {
    background: var(--app-surface-muted-bg) !important;
}

html.dark :where(.btn-admin-sm-outline) {
    background: var(--app-surface-bg) !important;
    border-color: var(--app-surface-border) !important;
    color: var(--ink-800) !important;
}

/* Tailwind utility classes used on admin/owner/client pages */
html.dark .bg-white,
html.dark [class*="bg-white/"] {
    background-color: var(--app-surface-bg) !important;
}

html.dark [class*="bg-white\\/"] {
    background-color: var(--app-surface-bg) !important;
}

html.dark .bg-gray-50,
html.dark .bg-slate-50 {
    background-color: var(--app-surface-muted-bg) !important;
}

html.dark .bg-gray-100,
html.dark .bg-slate-100 {
    background-color: var(--ink-100) !important;
}

html.dark .text-gray-900,
html.dark .text-slate-900 {
    color: var(--ink-900) !important;
}

html.dark .text-gray-800,
html.dark .text-slate-800 {
    color: var(--ink-800) !important;
}

html.dark .text-gray-700,
html.dark .text-slate-700 {
    color: var(--ink-700) !important;
}

html.dark .text-gray-600,
html.dark .text-slate-600 {
    color: var(--ink-600) !important;
}

html.dark .text-gray-500,
html.dark .text-slate-500 {
    color: var(--text-muted, var(--ink-600)) !important;
}

html.dark .text-gray-400,
html.dark .text-slate-400 {
    color: var(--text-faint, var(--ink-500)) !important;
}

html.dark .text-gray-300,
html.dark .text-slate-300 {
    color: var(--ink-400) !important;
}

html.dark .border-gray-100,
html.dark .border-gray-200,
html.dark .border-gray-300,
html.dark .border-slate-200 {
    border-color: var(--app-surface-border) !important;
}

html.dark .divide-gray-200 > :not([hidden]) ~ :not([hidden]) {
    border-color: var(--app-surface-border) !important;
}

html.dark .shadow-sm,
html.dark .shadow,
html.dark .shadow-md {
    box-shadow: var(--shadow-sm) !important;
}

@include('partials.appearance-page-backgrounds')
@include('partials.appearance-dark-compat')
@include('partials.appearance-minimal-layouts')
