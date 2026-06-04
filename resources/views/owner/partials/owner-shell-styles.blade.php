{{-- Owner portal — shared minimal layout (all /owner/* and owner-nav shared views) --}}
body.owner-nav-page {
    min-height: 100dvh;
    display: flex;
    flex-direction: column;
    font-family: var(--app-font-sans);
    color: var(--ink-800, #1f2937);
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    background-color: var(--app-page-bg, #f8fafc);
    background-image: var(--owner-page-bg-image, var(--communal-bg-overlay-light));
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
}

/* Main shells — full width, viewport height */
body.owner-nav-page .main-content.with-owner-nav,
body.owner-nav-page .main-content.with-owner-nav.owner-app-main,
body.owner-nav-page .profile-main.owner-app-main,
body.owner-nav-page main.with-owner-nav.owner-app-main,
body.owner-nav-page .landing-settings-main.with-owner-nav,
body.owner-nav-page .tenant-updates-shell.with-owner-nav,
body.owner-nav-page .page-shell.with-owner-nav {
    width: 100% !important;
    max-width: none !important;
    margin: 0 !important;
    padding-top: var(--owner-content-offset, var(--app-content-offset, calc(4rem + clamp(1.25rem, 2vw, 1.875rem)))) !important;
    padding-left: clamp(1rem, 2.5vw, 2rem) !important;
    padding-right: clamp(1rem, 2.5vw, 2rem) !important;
    padding-bottom: clamp(1.25rem, 2.5vw, 2rem) !important;
    min-height: calc(100dvh - var(--owner-content-offset, var(--app-content-offset, 6rem))) !important;
    display: flex;
    flex-direction: column;
    --owner-section-gap: clamp(0.75rem, 1.25vw, 1.25rem);
    gap: var(--owner-section-gap);
    flex: 1 0 auto;
    align-content: flex-start;
    box-sizing: border-box;
}

body.owner-nav-page .page-shell.with-owner-nav {
    max-width: min(72rem, 100%);
    margin-left: auto !important;
    margin-right: auto !important;
}

.owner-page-top {
    display: flex;
    flex: 0 0 auto;
    width: 100%;
    flex-shrink: 0;
    flex-wrap: wrap;
    align-items: flex-end;
    justify-content: space-between;
    gap: 0.75rem 1.25rem;
    margin-bottom: 0;
    padding-bottom: clamp(0.75rem, 1.25vw, 1.25rem);
    border-bottom: 1px solid var(--app-surface-border, rgba(15, 23, 42, 0.08));
}

.owner-page-hero {
    flex: 0 0 auto;
    width: 100%;
    min-width: min(100%, 16rem);
    margin: 0 0 0;
    padding-bottom: clamp(0.75rem, 1.25vw, 1.25rem);
    border-bottom: 1px solid var(--app-surface-border, rgba(15, 23, 42, 0.08));
}

.owner-page-hero--flush {
    border-bottom: none;
    padding-bottom: 0;
}

.owner-page-hero__eyebrow {
    margin: 0 0 0.25rem;
    font-size: 0.6875rem;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--ui-accent-color, var(--accent-pink-deep, #B0436E));
}

.owner-page-hero__title {
    margin: 0 0 0.2rem;
    font-family: var(--app-font-display, inherit);
    font-size: clamp(1.375rem, 2.75vw, 1.875rem);
    font-weight: 700;
    line-height: 1.15;
    letter-spacing: -0.03em;
    color: var(--ink-900, var(--gray-900, #0f172a));
}

.owner-page-hero__lede {
    margin: 0;
    max-width: 40rem;
    font-size: 0.875rem;
    line-height: 1.45;
    color: var(--text-secondary, var(--ink-600, #4b5563));
}

/* Legacy page-header — minimal when hero partial not used */
body.owner-nav-page .page-header {
    margin-bottom: 0;
    flex: 0 0 auto;
    width: 100%;
    min-width: min(100%, 16rem);
    padding-bottom: clamp(0.75rem, 1.25vw, 1.25rem);
    border-bottom: 1px solid var(--app-surface-border, rgba(15, 23, 42, 0.08));
}

body.owner-nav-page .page-header .page-title-icon {
    display: none;
}

body.owner-nav-page .page-header h1 {
    margin: 0 0 0.35rem;
    font-family: var(--app-font-display, inherit);
    font-size: clamp(1.375rem, 2.75vw, 1.875rem);
    font-weight: 700;
    line-height: 1.15;
    letter-spacing: -0.03em;
    color: var(--ink-900, var(--gray-900, #0f172a));
}

body.owner-nav-page .page-header > p,
body.owner-nav-page .page-header p {
    margin: 0;
    max-width: 40rem;
    font-size: 0.875rem;
    line-height: 1.55;
    color: var(--gray-600, #4b5563);
}

body.owner-nav-page .owner-page-top .page-header {
    border-bottom: none;
    padding-bottom: 0;
}

/* Content grows to fill viewport */
body.owner-nav-page .owner-page-body,
body.owner-nav-page .owner-dash-body,
body.owner-nav-page .settings-grid,
body.owner-nav-page .owner-units-body {
    flex: 1 1 auto;
    min-height: 0;
    width: 100%;
}

/* Unified surface cards */
body.owner-nav-page .panel,
body.owner-nav-page .settings-panel,
body.owner-nav-page .profile-panel,
body.owner-nav-page .owner-dash-block,
body.owner-nav-page .owner-dash-kpi,
body.owner-nav-page .surface,
body.owner-nav-page .card:not(.property-card),
body.owner-nav-page .booking-card,
body.owner-nav-page .owner-units-card {
    background: var(--app-surface-bg, rgba(255, 255, 255, 0.94));
    border: 1px solid var(--app-surface-border, rgba(15, 23, 42, 0.08));
    border-radius: 0.75rem;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
}

body.owner-nav-page .panel-header,
body.owner-nav-page .settings-panel__head,
body.owner-nav-page .profile-panel__head,
body.owner-nav-page .owner-dash-block__head,
body.owner-nav-page .surface-header {
    background: rgba(248, 250, 252, 0.9);
    border-bottom: 1px solid rgba(15, 23, 42, 0.06);
}

body.owner-nav-page .settings-panel__head h2,
body.owner-nav-page .profile-panel__head h2,
body.owner-nav-page .owner-dash-block__head h2,
body.owner-nav-page .panel-header h1,
body.owner-nav-page .panel-header h2 {
    font-size: 0.8125rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    color: var(--gray-700, #374151);
}

body.owner-nav-page .btn.primary,
body.owner-nav-page .btn-save,
body.owner-nav-page .btn-primary,
body.owner-nav-page .filter-tab.active {
    background: var(--action-primary-bg, var(--brand-700, #457359));
    color: var(--action-primary-text, #fff);
    border-color: var(--action-primary-border, transparent);
}

body.owner-nav-page .btn.primary:hover,
body.owner-nav-page .btn-save:hover,
body.owner-nav-page .btn-primary:hover {
    background: var(--action-primary-hover, var(--brand-800, #34543f));
}

body.owner-nav-page .flash-alerts,
body.owner-nav-page .profile-flash {
    flex-shrink: 0;
}

@media (max-width: 768px) {
    body.owner-nav-page {
        background-attachment: scroll;
    }
}

@include('owner.partials.owner-responsive-styles')
