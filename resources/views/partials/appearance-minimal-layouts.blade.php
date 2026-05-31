{{-- Guest + owner minimal redesign surfaces — theme (impasugong/green) + display mode. --}}

/* Impasugong: pink decorative accents + forest green actions */
:where(
    body.client-nav-page,
    body.owner-nav-page
) {
    --guest-accent: var(--ui-accent-color, var(--accent-pink-deep, #B0436E));
    --guest-accent-hover: var(--ui-accent-strong, var(--accent-pink-strong, #D37897));
    --guest-accent-soft: var(--ui-accent-surface, var(--accent-pink-soft, #F9DEE5));
    --guest-accent-border: var(--ui-accent-border, var(--accent-pink-border, #F3C9D6));
    --guest-action-bg: var(--action-primary-bg, var(--brand-700, #457359));
    --guest-action-hover: var(--action-primary-hover, var(--brand-800, #34543f));
    --guest-action-border: var(--action-primary-border, rgba(52, 84, 63, 0.4));
}

/* ── Shared minimal heroes ─────────────────────────────────────────────── */
:where(
    .owner-page-hero__eyebrow,
    .guest-bookings-hero__eyebrow,
    .guest-messages-hero__eyebrow,
    .guest-support-hero__eyebrow,
    .guest-profile-hero__eyebrow
) {
    color: var(--guest-accent);
}

:where(
    .owner-page-hero__title,
    .guest-bookings-hero__title,
    .guest-messages-hero__title,
    .guest-support-hero__title,
    .guest-profile-hero__title
) {
    color: var(--ink-900);
}

html.dark :where(
    .owner-page-hero__eyebrow,
    .guest-bookings-hero__eyebrow,
    .guest-messages-hero__eyebrow,
    .guest-support-hero__eyebrow,
    .guest-profile-hero__eyebrow,
    .explore-stays-hero__eyebrow
) {
    color: var(--ui-accent-color, var(--chrome-icon-color)) !important;
}

html.dark :where(
    .owner-page-hero__title,
    .guest-bookings-hero__title,
    .guest-messages-hero__title,
    .guest-support-hero__title,
    .guest-profile-hero__title,
    .explore-stays-hero__title
) {
    color: var(--ink-900) !important;
}

:where(
    .owner-page-hero__lede,
    .guest-bookings-hero__lede,
    .guest-messages-hero__lede,
    .guest-support-hero__lede,
    .guest-profile-hero__lede
) {
    color: var(--ink-600);
}

html.dark :where(
    .owner-page-hero__lede,
    .guest-bookings-hero__lede,
    .guest-messages-hero__lede,
    .guest-support-hero__lede,
    .guest-profile-hero__lede,
    .explore-stays-hero__lede
) {
    color: var(--text-secondary, var(--ink-700)) !important;
}

:where(
    .owner-page-hero,
    .guest-bookings-hero,
    .guest-messages-hero,
    .guest-support-hero,
    .guest-profile-hero
) {
    border-bottom-color: var(--app-surface-border, rgba(15, 23, 42, 0.08));
}

/* ── Guest / owner minimal panels & cards ──────────────────────────────── */
:where(
    .guest-booking-card,
    .guest-messages-inbox,
    .guest-messages-chat,
    .guest-support-panel,
    .guest-bookings-filters,
    .guest-bookings-empty__card,
    .guest-messages-empty__card,
    .guest-support-empty__card,
    .owner-edit-panel
) {
    background: var(--app-surface-bg, rgba(255, 255, 255, 0.94));
    border-color: var(--app-surface-border, rgba(15, 23, 42, 0.08));
    color: var(--ink-800);
}

:where(
    .guest-messages-inbox__head,
    .guest-messages-chat__head,
    .guest-support-panel__head,
    .guest-booking-card__footer
) {
    background: var(--app-surface-muted-bg, rgba(248, 250, 252, 0.9));
    border-color: var(--app-surface-border, rgba(15, 23, 42, 0.06));
}

:where(
    .guest-messages-inbox__title,
    .guest-messages-chat__title,
    .guest-support-panel__title
) {
    color: var(--ink-700);
}

:where(
    .guest-booking-card__title,
    .guest-booking-card__fact-value,
    .guest-messages-thread__name,
    .guest-messages-chat__title
) {
    color: var(--ink-900);
}

:where(
    .guest-booking-card__location,
    .guest-booking-card__fact-label,
    .guest-messages-thread__preview,
    .guest-messages-thread__time,
    .guest-messages-chat__subtitle,
    .guest-support-field label
) {
    color: var(--ink-500);
}

:where(
    .guest-bookings-filters__tab.is-active,
    .guest-bookings-empty__cta,
    .guest-messages-hero__cta,
    .guest-messages-empty__cta,
    .guest-support-submit,
    .guest-support-empty__cta,
    .guest-booking-card__btn--primary,
    .guest-messages-reply__send,
    .explore-stays-search-btn
) {
    background: var(--guest-action-bg) !important;
    border-color: var(--guest-action-border, transparent) !important;
    color: var(--action-primary-text, #fff) !important;
}

:where(
    .guest-bookings-filters__tab.is-active:hover,
    .guest-bookings-empty__cta:hover,
    .guest-messages-hero__cta:hover,
    .guest-messages-empty__cta:hover,
    .guest-support-submit:hover,
    .guest-support-empty__cta:hover,
    .guest-booking-card__btn--primary:hover,
    .guest-messages-reply__send:hover,
    .explore-stays-search-btn:hover
) {
    background: var(--guest-action-hover) !important;
}

:where(
    .guest-messages-thread__avatar,
    .guest-messages-bubble--mine
) {
    background: var(--guest-accent-strong, var(--chrome-active-bg));
    color: #fff;
}

:where(
    .guest-bookings-filters__tab:hover,
    .guest-messages-filters__tab:hover
) {
    color: var(--guest-accent-hover);
    background: var(--guest-accent-soft);
}

:where(
    .guest-booking-card__btn--ghost,
    .guest-messages-delete,
    .guest-messages-reply__textarea,
    .guest-support-input,
    .guest-support-textarea,
    .guest-support-file
) {
    background: var(--app-surface-bg, #fff);
    border-color: var(--app-surface-border, rgba(15, 23, 42, 0.12));
    color: var(--ink-800);
}

:where(
    .guest-messages-bubble--theirs,
    .guest-messages-chat__placeholder,
    .guest-messages-chat__stream
) {
    background: var(--app-surface-muted-bg, rgba(241, 245, 249, 0.65));
    border-color: var(--app-surface-border, rgba(15, 23, 42, 0.08));
    color: var(--ink-800);
}

:where(.guest-messages-bubble--theirs) {
    background: var(--app-surface-bg, #fff);
}

:where(
    .guest-booking-card:hover,
    .guest-support-ticket:hover
) {
    border-color: color-mix(in srgb, var(--guest-accent) 28%, var(--app-surface-border, rgba(15, 23, 42, 0.08)));
}

/* Owner shell surfaces (override hardcoded rgba in owner-shell-styles) */
body.owner-nav-page .owner-page-hero__title,
body.owner-nav-page .page-header h1 {
    color: var(--ink-900);
}

body.owner-nav-page .owner-page-hero__lede,
body.owner-nav-page .page-header p {
    color: var(--ink-600);
}

body.owner-nav-page .owner-page-hero,
body.owner-nav-page .page-header {
    border-bottom-color: var(--app-surface-border);
}

body.owner-nav-page .panel,
body.owner-nav-page .settings-panel,
body.owner-nav-page .profile-panel,
body.owner-nav-page .owner-dash-block,
body.owner-nav-page .owner-dash-kpi,
body.owner-nav-page .surface,
body.owner-nav-page .owner-units-card,
body.owner-nav-page .owner-edit-panel {
    background: var(--app-surface-bg) !important;
    border-color: var(--app-surface-border) !important;
    color: var(--ink-800);
}

body.owner-nav-page .panel-header,
body.owner-nav-page .settings-panel__head,
body.owner-nav-page .owner-dash-block__head,
body.owner-nav-page .surface-header {
    background: var(--app-surface-muted-bg) !important;
    border-bottom-color: var(--app-surface-border) !important;
}

body.owner-nav-page .owner-dash-kpi__value {
    color: var(--ink-900);
}

body.owner-nav-page .owner-dash-kpi__label {
    color: var(--ink-500);
}

body.owner-nav-page .btn.primary,
body.owner-nav-page .btn-save,
body.owner-nav-page .btn-primary,
body.owner-nav-page .filter-tab.active {
    background: var(--guest-action-bg) !important;
    border-color: var(--guest-action-border, transparent) !important;
    color: var(--action-primary-text, #fff) !important;
}

body.owner-nav-page .btn.primary:hover,
body.owner-nav-page .btn-save:hover,
body.owner-nav-page .btn-primary:hover {
    background: var(--guest-action-hover) !important;
}

/* Guest profile panels */
body.client-nav-page .guest-profile-main .profile-panel,
body.client-nav-page .guest-profile-main .profile-actions {
    background: var(--app-surface-bg) !important;
    border-color: var(--app-surface-border) !important;
}

body.client-nav-page .guest-profile-main .profile-panel__head {
    background: var(--app-surface-muted-bg) !important;
}

html.dark body.client-nav-page .guest-messages-chat__stream {
    background: var(--app-surface-muted-bg);
}

html.dark :where(.guest-bookings-empty__card i, .guest-messages-empty__card i) {
    color: var(--ink-400);
}

/* Guest explore / dashboard property grid */
:where(
    .explore-stays-hero__eyebrow
) {
    color: var(--guest-accent);
}

:where(
    .explore-stays-hero__title,
    .explore-stay-card__title
) {
    color: var(--ink-900);
}

:where(
    .explore-stays-hero__lede,
    .explore-stay-card__location,
    .explore-stay-card__meta
) {
    color: var(--ink-500);
}

html.dark :where(
    .explore-stays-hero__lede,
    .explore-stay-card__location,
    .explore-stay-card__meta
) {
    color: var(--text-muted, var(--ink-600)) !important;
}

:where(
    .explore-stays-filters,
    .explore-stay-card
) {
    background: var(--app-surface-bg);
    border-color: var(--app-surface-border);
}

:where(
    .explore-stays-field input,
    .explore-stays-field select
) {
    background: var(--app-surface-bg);
    border-color: var(--app-surface-border);
    color: var(--ink-800);
}

:where(.explore-stay-card:hover) {
    border-color: color-mix(in srgb, var(--guest-accent) 28%, var(--app-surface-border));
}
