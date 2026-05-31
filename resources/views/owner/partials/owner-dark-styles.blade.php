{{-- Owner portal dark mode — overrides page-local #fff / static grays (loaded after page CSS via top-navbar). --}}

html.dark body.owner-nav-page {
    color: var(--text-primary, var(--ink-800));
}

/* Surfaces: KPIs, blocks, cards, actions, availability */
html.dark body.owner-nav-page :where(
    .owner-dash-kpi,
    .owner-dash-block,
    .owner-dash-actions,
    .owner-units-kpi,
    .owner-units-block,
    .owner-unit-card,
    .owner-unit-card__btn,
    .owner-avail-card,
    .property-table th,
    .panel,
    .perm-category,
    .role-card,
    .perm-option,
    .booking-card,
    .empty-state,
    .filter-tabs,
    .updates-surface
) {
    background: var(--app-surface-bg) !important;
    border-color: var(--app-surface-border) !important;
    color: var(--ink-800);
}

html.dark body.owner-nav-page :where(
    .owner-dash-block__head,
    .owner-units-block__head,
    .panel-header,
    .role-summary,
    .perm-category summary,
    .booking-header,
    .booking-footer
) {
    background: var(--app-surface-muted-bg) !important;
    border-color: var(--app-surface-border) !important;
}

/* Typography */
html.dark body.owner-nav-page :where(
    .owner-dash-kpi__value,
    .owner-units-kpi__value,
    .owner-dash-block__head h2,
    .owner-dash-block__head h3,
    .owner-units-block__head h2,
    .owner-unit-card__title,
    .owner-unit-card__meta dd,
    .owner-dash-action__title,
    .property-name,
    .info-value,
    .property-table td,
    .owner-avail-card__head h3,
    .panel-header h1,
    .role-summary-title,
    .perm-option-label,
    .booking-card .property-name,
    .empty-state h3
) {
    color: var(--ink-900) !important;
}

html.dark body.owner-nav-page :where(
    .owner-dash-kpi__label,
    .owner-units-kpi__label,
    .owner-dash-block__caption,
    .owner-units-block__count,
    .owner-unit-card__location,
    .owner-unit-card__meta dt,
    .owner-dash-action__desc,
    .property-address,
    .property-table th,
    .info-label,
    .booking-id,
    .booking-date,
    .property-location,
    .owner-avail-card__head p,
    .owner-avail-card__empty,
    .panel-header p,
    .rbac-note,
    .muted,
    .role-summary-meta,
    .perm-option-key,
    .empty-state p,
    .owner-dash-empty
) {
    color: var(--text-muted, var(--ink-600)) !important;
}

html.dark body.owner-nav-page :where(
    .owner-page-hero__lede,
    .page-header p,
    .owner-dash-top .page-header p,
    .owner-units-top .page-header > p
) {
    color: var(--text-muted, var(--ink-600)) !important;
}

html.dark body.owner-nav-page .owner-page-hero__lede strong {
    color: var(--ink-800);
}

/* Tables */
html.dark body.owner-nav-page .property-table th {
    background: var(--app-surface-muted-bg) !important;
    color: var(--text-muted, var(--ink-600)) !important;
    border-color: var(--app-surface-border) !important;
}

html.dark body.owner-nav-page .property-table td {
    color: var(--ink-700) !important;
    border-color: var(--app-surface-border) !important;
}

html.dark body.owner-nav-page .property-table tbody tr:hover {
    background: var(--app-surface-muted-bg) !important;
}

html.dark body.owner-nav-page :where(table th, table td) {
    border-color: var(--app-surface-border) !important;
    color: var(--ink-700);
}

html.dark body.owner-nav-page table th {
    background: var(--app-surface-muted-bg) !important;
    color: var(--text-muted, var(--ink-600)) !important;
}

html.dark body.owner-nav-page table tbody tr:hover {
    background: var(--app-surface-muted-bg) !important;
}

/* Quick actions / hovers */
html.dark body.owner-nav-page .owner-dash-action:hover {
    background: var(--app-surface-muted-bg) !important;
    border-color: var(--app-surface-border) !important;
}

html.dark body.owner-nav-page .owner-dash-action__icon {
    background: var(--app-surface-muted-bg) !important;
    color: var(--green-dark) !important;
}

html.dark body.owner-nav-page .owner-unit-card__btn:hover {
    background: var(--app-surface-muted-bg) !important;
}

html.dark body.owner-nav-page .role-card-body {
    background: var(--app-surface-muted-bg) !important;
}

html.dark body.owner-nav-page .inline-form input,
html.dark body.owner-nav-page .inline-form select,
html.dark body.owner-nav-page .field input,
html.dark body.owner-nav-page .field select {
    background: var(--app-surface-muted-bg) !important;
    border-color: var(--app-surface-border) !important;
    color: var(--ink-800) !important;
}

html.dark body.owner-nav-page .btn.muted {
    background: var(--app-surface-muted-bg) !important;
    color: var(--ink-700) !important;
    border: 1px solid var(--app-surface-border);
}

html.dark body.owner-nav-page .toggle-actions-btn {
    background: var(--app-surface-bg) !important;
    color: var(--green-dark) !important;
    border-color: var(--app-surface-border) !important;
}

html.dark body.owner-nav-page .filter-tab {
    background: var(--app-surface-bg) !important;
    color: var(--text-secondary, var(--ink-700)) !important;
    border-color: var(--app-surface-border) !important;
}

html.dark body.owner-nav-page .filter-tab:not(.active) {
    color: var(--text-muted, var(--ink-600)) !important;
}

html.dark body.owner-nav-page .filter-tab:hover {
    background: var(--app-surface-muted-bg) !important;
}

html.dark body.owner-nav-page .filter-tab.active {
    background: var(--action-primary-bg) !important;
    color: #fff !important;
    border-color: transparent !important;
}

html.dark body.owner-nav-page .info-item {
    background: var(--app-surface-muted-bg) !important;
}

html.dark body.owner-nav-page .business-status-pill {
    background: var(--app-surface-bg) !important;
    color: var(--ink-800) !important;
    border-color: var(--app-surface-border) !important;
}

html.dark body.owner-nav-page .business-status-pill .biz-label,
html.dark body.owner-nav-page .business-status-pill .biz-detail {
    color: var(--text-muted, var(--ink-600)) !important;
}

html.dark body.owner-nav-page h3[style*="green-dark"] {
    color: var(--green-dark) !important;
}

/* Tailwind utility cards on owner pages (updates, users, bookings, messages) */
html.dark body.owner-nav-page [class*="bg-white"] {
    background-color: var(--app-surface-bg) !important;
}

html.dark body.owner-nav-page [class*="bg-slate-50"],
html.dark body.owner-nav-page [class*="bg-slate-100"],
html.dark body.owner-nav-page [class*="bg-emerald-50"]:not(.text-emerald-800) {
    background-color: var(--app-surface-muted-bg) !important;
}

html.dark body.owner-nav-page [class*="border-slate-200"],
html.dark body.owner-nav-page [class*="border-emerald-100"] {
    border-color: var(--app-surface-border) !important;
}

html.dark body.owner-nav-page [class*="text-slate-900"],
html.dark body.owner-nav-page [class*="text-slate-800"],
html.dark body.owner-nav-page [class*="text-slate-700"] {
    color: var(--ink-900) !important;
}

html.dark body.owner-nav-page [class*="text-slate-600"],
html.dark body.owner-nav-page [class*="text-slate-500"] {
    color: var(--text-muted, var(--ink-600)) !important;
}

html.dark body.owner-nav-page [class*="text-slate-400"] {
    color: var(--ink-400) !important;
}

html.dark body.owner-nav-page [class*="text-emerald-800"] {
    color: var(--green-dark) !important;
}

html.dark body.owner-nav-page textarea,
html.dark body.owner-nav-page input:not([type="checkbox"]):not([type="radio"]):not([type="file"]),
html.dark body.owner-nav-page select {
    background-color: var(--app-surface-muted-bg) !important;
    border-color: var(--app-surface-border) !important;
    color: var(--ink-800) !important;
}

html.dark body.owner-nav-page textarea::placeholder,
html.dark body.owner-nav-page input::placeholder {
    color: var(--ink-400) !important;
}

html.dark body.owner-nav-page [class*="border-slate-200/90"].rounded-2xl {
    background-color: var(--app-surface-bg) !important;
}

html.dark body.owner-nav-page [class*="bg-gradient-to-br"][class*="from-teal-600"] {
    /* outgoing bubbles — keep gradient */
    color: #fff !important;
}

html.dark body.owner-nav-page [class*="rounded-tl-md"][class*="border"][class*="text-slate-900"] {
    background: var(--app-surface-muted-bg) !important;
    color: var(--ink-900) !important;
    border-color: var(--app-surface-border) !important;
}

/* ── Messages index split layout (/messages) ───────────────────────────── */
html.dark body.owner-nav-page {
    background-color: var(--app-page-bg) !important;
    color: var(--ink-800) !important;
}

html.dark body.owner-nav-page .messages-index-main .owner-page-hero__eyebrow {
    color: var(--ui-accent-color, var(--chrome-icon-color)) !important;
}

html.dark body.owner-nav-page .messages-index-main .owner-page-hero__title {
    color: var(--ink-900) !important;
}

html.dark body.owner-nav-page .messages-index-main .owner-page-hero__lede {
    color: var(--text-secondary, var(--ink-700)) !important;
}

html.dark body.owner-nav-page .messages-split > aside,
html.dark body.owner-nav-page .messages-split > section {
    background-color: var(--app-surface-bg) !important;
    border-color: var(--app-surface-border) !important;
    --tw-ring-color: color-mix(in srgb, var(--app-surface-border) 80%, transparent);
}

html.dark body.owner-nav-page .messages-split aside > div:first-child,
html.dark body.owner-nav-page .messages-split section > div:first-child {
    background-color: var(--app-surface-muted-bg) !important;
    border-color: var(--app-surface-border) !important;
}

html.dark body.owner-nav-page .messages-split section > div:last-child {
    background-color: var(--app-surface-bg) !important;
    border-color: var(--app-surface-border) !important;
}

html.dark body.owner-nav-page .messages-split .msg-scrollbar[class*="bg-slate-100"] {
    background-color: var(--app-surface-muted-bg) !important;
}

html.dark body.owner-nav-page .messages-split a[class*="border-b"] {
    border-color: var(--app-surface-border) !important;
}

html.dark body.owner-nav-page .messages-split a[class*="hover:bg-slate"]:hover {
    background-color: color-mix(in srgb, var(--app-surface-muted-bg) 88%, var(--ink-200)) !important;
}

html.dark body.owner-nav-page .messages-split a[class*="bg-teal-50"] {
    background-color: color-mix(in srgb, var(--chrome-active-bg, var(--ui-accent-strong)) 14%, var(--app-surface-bg)) !important;
}

html.dark body.owner-nav-page .messages-split .text-slate-900,
html.dark body.owner-nav-page .messages-split .font-bold.text-slate-900 {
    color: var(--ink-900) !important;
}

html.dark body.owner-nav-page .messages-split .text-slate-800,
html.dark body.owner-nav-page .messages-split .text-slate-700,
html.dark body.owner-nav-page .messages-split .font-medium.text-slate-700 {
    color: var(--ink-700) !important;
}

html.dark body.owner-nav-page .messages-split .text-slate-600,
html.dark body.owner-nav-page .messages-split .text-slate-500 {
    color: var(--text-muted, var(--ink-600)) !important;
}

html.dark body.owner-nav-page .messages-split .text-slate-400,
html.dark body.owner-nav-page .messages-split label [class*="text-slate-400"] {
    color: var(--ink-400) !important;
}

html.dark body.owner-nav-page .messages-split label[class*="text-slate-600"] {
    color: var(--text-muted, var(--ink-600)) !important;
}

html.dark body.owner-nav-page .messages-split textarea {
    background-color: var(--app-surface-muted-bg) !important;
    border-color: var(--app-surface-border) !important;
    color: var(--ink-800) !important;
}

html.dark body.owner-nav-page .messages-split textarea::placeholder {
    color: var(--ink-400) !important;
    opacity: 1;
}

html.dark body.owner-nav-page .messages-split .msg-file-input {
    background-color: var(--app-surface-muted-bg) !important;
    border-color: var(--app-surface-border) !important;
    color: var(--ink-700) !important;
}

html.dark body.owner-nav-page .messages-split [class*="border-red-200"][class*="bg-white"] {
    background-color: var(--app-surface-bg) !important;
    color: #fca5a5 !important;
    border-color: color-mix(in srgb, var(--status-danger) 45%, transparent) !important;
}

html.dark body.owner-nav-page .messages-split [class*="border-dashed"][class*="bg-white"] {
    background-color: var(--app-surface-bg) !important;
    border-color: var(--app-surface-border) !important;
}

html.dark body.owner-nav-page .messages-split [class*="bg-slate-100"]:not([class*="text-slate-400"]) {
    background-color: var(--ink-200) !important;
    color: var(--ink-400) !important;
}

html.dark body.owner-nav-page .messages-split button[class*="bg-teal-50"] {
    background-color: color-mix(in srgb, var(--brand-600) 22%, var(--app-surface-muted-bg)) !important;
    color: var(--green-dark) !important;
    border-color: var(--app-surface-border) !important;
}
