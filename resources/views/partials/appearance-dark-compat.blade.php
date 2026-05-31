{{-- Dark display mode: fix hardcoded light surfaces, nav text, guest/owner shells, messaging. --}}

/* Nav links — idle text was dark slate on dark bar */
html.dark .portal-nav-minimal__link,
html.dark .portal-nav-minimal__action--text {
    color: var(--ink-700) !important;
}

html.dark .portal-nav-minimal__link:hover {
    color: var(--ui-accent-color, var(--chrome-icon-color)) !important;
}

html.dark :is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) .navbar.portal-nav-minimal > .nav-links a {
    color: var(--ink-600) !important;
}

html.dark :is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) .navbar.portal-nav-minimal .user-display,
html.dark :is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) .navbar.portal-nav-minimal .imp-notify-btn {
    background: var(--app-surface-bg) !important;
    border-color: var(--app-surface-border) !important;
    color: var(--ink-700) !important;
}

html.dark :is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) .navbar.portal-nav-minimal > .nav-links a:hover {
    color: var(--ui-accent-color, var(--chrome-icon-color)) !important;
}

/* Guest / owner minimal cards & panels (hardcoded white in page CSS) */
html.dark body.client-nav-page :where(
    .guest-booking-card,
    .guest-bookings-filters,
    .guest-bookings-empty__card,
    .guest-messages-inbox,
    .guest-messages-chat,
    .guest-messages-empty__card,
    .guest-messages-chat__placeholder,
    .guest-messages-bubble--theirs,
    .guest-support-panel,
    .guest-support-empty__card,
    .guest-support-ticket,
    .explore-stays-filters,
    .explore-stay-card,
    .explore-stays-empty,
    .client-guest-surface
),
html.dark body.owner-nav-page :where(
    .panel,
    .settings-panel,
    .profile-panel,
    .owner-dash-block,
    .owner-dash-kpi,
    .surface,
    .owner-units-card,
    .owner-edit-panel,
    .owner-units-table-wrap,
    .owner-availability-card
) {
    background: var(--app-surface-bg) !important;
    border-color: var(--app-surface-border) !important;
    color: var(--ink-800) !important;
}

html.dark body.client-nav-page :where(
    .guest-messages-inbox__head,
    .guest-messages-chat__head,
    .guest-support-panel__head,
    .guest-booking-card__footer,
    .guest-messages-chat__stream
),
html.dark body.owner-nav-page :where(
    .panel-header,
    .settings-panel__head,
    .owner-dash-block__head,
    .surface-header
) {
    background: var(--app-surface-muted-bg) !important;
    border-color: var(--app-surface-border) !important;
}

/* Titles / body copy using --gray-900 fallbacks */
html.dark body.client-nav-page :where(
    .guest-bookings-hero__title,
    .guest-bookings-hero__lede,
    .guest-messages-hero__title,
    .guest-messages-hero__lede,
    .guest-support-hero__title,
    .guest-support-hero__lede,
    .guest-profile-hero__title,
    .guest-profile-hero__lede,
    .guest-booking-card__title,
    .guest-booking-card__location,
    .guest-booking-card__fact-value,
    .guest-messages-thread__name,
    .guest-messages-chat__title,
    .guest-messages-chat__subtitle,
    .guest-support-panel__title,
    .explore-stays-hero__title,
    .explore-stay-card__title,
    .explore-stay-card__location,
    .explore-stay-card__meta
),
html.dark body.owner-nav-page :where(
    .owner-page-hero__title,
    .owner-page-hero__lede,
    .page-header h1,
    .page-header p,
    .owner-dash-kpi__value,
    .owner-dash-kpi__label
) {
    color: var(--ink-900) !important;
}

html.dark body.client-nav-page :where(
    .guest-booking-card__fact-label,
    .guest-messages-thread__preview,
    .guest-messages-thread__time,
    .guest-support-field label,
    .explore-stays-hero__lede
),
html.dark body.owner-nav-page .owner-page-hero__lede,
html.dark body.owner-nav-page .page-header p {
    color: var(--ink-500) !important;
}

/* Form fields on guest pages */
html.dark body.client-nav-page :where(
    .guest-messages-reply__textarea,
    .guest-support-input,
    .guest-support-textarea,
    .guest-support-file,
    .guest-booking-card__btn--ghost,
    .guest-messages-delete,
    .explore-stays-field input,
    .explore-stays-field select
),
html.dark body.client-nav-page .guest-messages-bubble--theirs,
html.dark body.client-nav-page .guest-messages-reply__textarea {
    background: var(--app-surface-muted-bg) !important;
    border-color: var(--app-surface-border) !important;
    color: var(--ink-800) !important;
}

html.dark body.client-nav-page .guest-bookings-filters__tab {
    color: var(--ink-600) !important;
}

html.dark body.client-nav-page .guest-bookings-filters__tab:hover {
    color: var(--ui-accent-color) !important;
    background: var(--ui-accent-surface) !important;
}

/* Messaging UI (legacy msg-* pages) */
html.dark :where(
    .msg-card,
    .msg-sidebar,
    .msg-thread,
    .msg-compose,
    .msg-bubble-in,
    .msg-list-item,
    .msg-toolbar
) {
    background: var(--app-surface-bg) !important;
    border-color: var(--app-surface-border) !important;
    color: var(--ink-800) !important;
}

html.dark :where(
    .msg-card-header,
    .msg-sidebar-header,
    .msg-thread-header
) {
    background: var(--app-surface-muted-bg) !important;
    border-color: var(--app-surface-border) !important;
}

html.dark :where(
    .msg-card-header h2,
    .msg-thread-title,
    .msg-list-item strong,
    .msg-bubble-in
) {
    color: var(--ink-900) !important;
}

html.dark :where(.msg-list-item, .msg-meta, .msg-preview, .msg-time) {
    color: var(--ink-500) !important;
}

html.dark :where(.msg-list-item.is-active, .msg-list-item:hover) {
    background: var(--app-surface-muted-bg) !important;
}

html.dark :where(.msg-filter-tab) {
    color: var(--ink-600) !important;
    background: transparent !important;
    border-color: var(--app-surface-border) !important;
}

html.dark :where(.msg-filter-tab.active) {
    background: var(--action-primary-bg) !important;
    color: #fff !important;
}

html.dark :where(.msg-input, .msg-compose textarea, .msg-compose input) {
    background: var(--app-surface-muted-bg) !important;
    border-color: var(--app-surface-border) !important;
    color: var(--ink-800) !important;
}

html.dark :where(.msg-bubble-out) {
    background: var(--chrome-active-bg) !important;
    color: #fff !important;
}

/* Tables — catch pages without .data-table */
html.dark :where(
    table,
    .support-table,
    .tenants-table,
    .owner-units-table,
    .users-table
) {
    color: var(--ink-700);
}

html.dark :where(table th, .support-table th, .tenants-table-head th) {
    background: var(--app-surface-muted-bg) !important;
    color: var(--ink-500) !important;
    border-color: var(--ink-200) !important;
}

html.dark :where(table td, .support-table td) {
    color: var(--ink-700) !important;
    border-color: var(--ink-200) !important;
}

html.dark :where(table tbody tr:hover, .support-table tbody tr:hover) {
    background: var(--app-surface-muted-bg) !important;
}

html.dark :where(.tenant-row-labels, .tenant-row-hover td) {
    color: var(--ink-600) !important;
}

/* Appearance preference cards on profile */
html.dark .appearance-theme-option .appearance-theme-card,
html.dark .appearance-mode-option > span {
    background: var(--app-surface-bg) !important;
    border-color: var(--app-surface-border) !important;
}

html.dark .appearance-theme-name {
    color: var(--ink-900) !important;
}

html.dark .appearance-theme-desc,
html.dark .appearance-prefs .appearance-label {
    color: var(--ink-500) !important;
}

html.dark .appearance-mode-option input:checked + span {
    background: var(--ui-accent-surface, var(--chrome-surface-bg)) !important;
    color: var(--ui-accent-color, var(--chrome-icon-color)) !important;
    border-color: var(--chrome-focus-ring) !important;
}

/* Admin flash + pills on dark */
html.dark .flash {
    background: color-mix(in srgb, var(--status-success) 18%, var(--app-surface-bg)) !important;
    border-color: color-mix(in srgb, var(--status-success) 35%, transparent) !important;
    color: #86efac !important;
}

html.dark .pill.open {
    background: color-mix(in srgb, var(--status-success) 22%, var(--app-surface-muted-bg)) !important;
    color: #86efac !important;
}

html.dark .pill.resolved {
    background: color-mix(in srgb, var(--status-info) 22%, var(--app-surface-muted-bg)) !important;
    color: #7dd3fc !important;
}

html.dark .btn-admin-sm-mint,
html.dark .btn-admin-sm-amber {
    background: var(--app-surface-muted-bg) !important;
    border-color: var(--app-surface-border) !important;
    color: var(--ink-700) !important;
}

/* Owner pages without late-loaded dark sheet — Tailwind white cards */
html.dark body.owner-nav-page [class*="bg-white"] {
    background-color: var(--app-surface-bg) !important;
}

html.dark body.owner-nav-page .text-slate-800 {
    color: var(--ink-800) !important;
}

/* Owner shell hardcoded surfaces */
html.dark body.owner-nav-page .panel,
html.dark body.owner-nav-page .settings-panel,
html.dark body.owner-nav-page .profile-panel,
html.dark body.owner-nav-page .owner-dash-block,
html.dark body.owner-nav-page .owner-dash-kpi,
html.dark body.owner-nav-page .surface,
html.dark body.owner-nav-page .card:not(.property-card),
html.dark body.owner-nav-page .owner-units-card {
    background: var(--app-surface-bg) !important;
    border-color: var(--app-surface-border) !important;
}

html.dark body.owner-nav-page .owner-page-hero__title,
html.dark body.owner-nav-page .page-header h1 {
    color: var(--ink-900) !important;
}
