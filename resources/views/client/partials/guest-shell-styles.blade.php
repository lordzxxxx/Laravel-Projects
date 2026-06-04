{{-- Guest / client portal — communal photo background + main content shell (matches /dashboard). --}}
body.client-nav-page {
    min-height: 100dvh;
    display: flex;
    flex-direction: column;
    font-family: var(--app-font-sans);
    color: var(--ink-800, #1f2937);
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    background-color: var(--app-page-bg, #f8fafc);
    background-image: var(--client-guest-bg-image, var(--communal-bg-overlay-light));
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
}

body.profile-page.client-nav-page,
body.client-nav-page.msg-thread-page {
    background-color: var(--app-page-bg, #f8fafc);
    background-image: var(--client-guest-bg-image, var(--communal-bg-overlay-light));
}

.client-guest-main {
    position: relative;
    z-index: 1;
    flex: 1 0 auto;
    width: 100%;
    max-width: min(var(--app-content-max, 80rem), 100%);
    margin-left: auto;
    margin-right: auto;
    padding-top: var(
        --client-nav-safe-offset,
        var(--client-nav-offset, calc(var(--app-topbar-height, 4rem) + clamp(1.5rem, 2.5vw, 2.25rem)))
    );
    padding-left: clamp(1.25rem, 3vw, 2.5rem);
    padding-right: clamp(1.25rem, 3vw, 2.5rem);
    padding-bottom: clamp(3rem, 6vw, 5rem);
    min-height: 0;
    box-sizing: border-box;
}

.client-guest-main--wide {
    max-width: min(var(--app-content-max-wide, 96rem), 100%);
}

.client-guest-main--full {
    max-width: none;
    width: 100%;
    padding-left: clamp(0.75rem, 2vw, 1.75rem);
    padding-right: clamp(0.75rem, 2vw, 1.75rem);
}

.client-guest-main--full > .app-page-shell,
.client-guest-main--full > .booking-show-inner,
.client-guest-main--full .explore-stay-show__crumb,
.client-guest-main--full .explore-stay-hero,
.client-guest-main--full .explore-stay-show__content {
    --stay-max: var(--app-content-max, 80rem);
}

@media (min-width: 2560px) {
    .client-guest-main--full {
        padding-left: clamp(1rem, 4vw, 3rem);
        padding-right: clamp(1rem, 4vw, 3rem);
    }
}

/* Central portal pages — offset matches app navbars */
.portal-public-main {
    padding-top: var(--portal-public-nav-offset, var(--app-main-top-offset, 4rem));
    box-sizing: border-box;
}

.portal-public-main.main-container {
    padding-top: var(--portal-public-nav-offset, var(--app-main-top-offset, 4rem)) !important;
}

body.client-nav-page .client-guest-surface {
    background: var(--app-surface-bg, rgba(255, 255, 255, 0.88));
    border: 1px solid var(--app-surface-border, rgba(255, 255, 255, 0.65));
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}

@media (max-width: 768px) {
    body.client-nav-page .client-guest-main,
    body.explore-portal-page .portal-public-main.explore-stays-main {
        font-size: var(--text-fluid-sm);
        padding-left: var(--app-page-pad-inline);
        padding-right: var(--app-page-pad-inline);
    }

    body.client-nav-page .client-guest-surface {
        padding: var(--app-card-pad);
        font-size: var(--text-fluid-sm);
    }

    body.client-nav-page .client-guest-main :where(h1) {
        font-size: var(--text-fluid-lg, 0.8125rem) !important;
    }

    body.client-nav-page .client-guest-main :where(h2, h3) {
        font-size: var(--text-fluid-sm, 0.6875rem) !important;
    }

    body.client-nav-page .client-guest-main table,
    body.client-nav-page .client-guest-main .app-data-table {
        font-size: var(--app-table-font, 0.6875rem);
    }

    body.client-nav-page .client-guest-main table th,
    body.client-nav-page .client-guest-main table td {
        padding: var(--app-table-pad-y, 0.375rem) var(--app-table-pad-x, 0.5rem);
        line-height: 1.3;
    }

    .portal-public-main {
        font-size: var(--text-fluid-sm, 0.6875rem);
    }

    .portal-public-main table {
        font-size: var(--app-table-font, 0.6875rem);
    }
}
