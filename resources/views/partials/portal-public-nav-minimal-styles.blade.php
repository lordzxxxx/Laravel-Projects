{{-- Minimal portal top bar — landing & other pages using navLayout=minimal --}}
:root {
    --nav-tribal-pattern-opacity: 0.42;
    --portal-nav-minimal-scrim: rgba(255, 255, 255, 0.78);
    --app-topbar-height: 4rem;
    --app-topbar-height-mobile: 3.5rem;
    --app-main-top-offset: 4rem;
    --portal-public-nav-offset: var(--app-main-top-offset);
    --portal-content-below-nav: calc(var(--app-topbar-height, 4rem) + clamp(1.25rem, 2vw, 1.875rem));
}

@media (max-width: 768px) {
    :root {
        --app-topbar-height: 3.5rem;
        --app-topbar-height-mobile: 3.5rem;
        --app-main-top-offset: 3.5rem;
        --portal-content-below-nav: calc(3.5rem + clamp(1rem, 2vw, 1.5rem));
    }
}

.portal-nav-minimal {
    position: relative;
    overflow: hidden;
    min-height: 0 !important;
    height: auto !important;
    padding: 0 !important;
    gap: 0 !important;
    background: transparent !important;
    border-bottom: 1px solid rgba(27, 94, 32, 0.1);
    box-shadow: 0 1px 0 rgba(255, 255, 255, 0.65), 0 8px 32px rgba(27, 94, 32, 0.06) !important;
    backdrop-filter: blur(10px) saturate(1.05);
    -webkit-backdrop-filter: blur(10px) saturate(1.05);
}

.portal-nav-minimal .navbar-tribal-accent {
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Landscape tribal banner — cover-fit inside the nav bar */
.portal-nav-minimal .navbar-tribal-accent__canvas {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    margin: 0;
    transform: none;
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center center;
    opacity: var(--nav-tribal-pattern-opacity, 0.42);
}

.portal-nav-minimal::after {
    display: block !important;
    background:
        linear-gradient(
            180deg,
            var(--portal-nav-minimal-scrim, rgba(255, 255, 255, 0.78)) 0%,
            rgba(255, 255, 255, 0.68) 55%,
            rgba(248, 250, 252, 0.72) 100%
        ),
        linear-gradient(90deg, rgba(46, 125, 50, 0.04) 0%, transparent 40%, transparent 60%, rgba(46, 125, 50, 0.04) 100%);
}

.portal-nav-minimal__inner,
.portal-nav-minimal__mobile-links {
    position: relative;
    z-index: 2;
}

.portal-nav-minimal .nav-toggle {
    display: none;
    background: transparent;
    border: 1px solid rgba(46, 125, 50, 0.25);
    color: var(--brand-dark, #1b5e20);
    width: 44px;
    height: 44px;
    min-width: 44px;
    min-height: 44px;
    border-radius: 10px;
    cursor: pointer;
    align-items: center;
    justify-content: center;
    font-size: 1.05rem;
    flex-shrink: 0;
}

.portal-nav-minimal .nav-toggle:focus-visible {
    outline: 2px solid var(--brand-primary, #2e7d32);
    outline-offset: 2px;
}

.portal-nav-minimal__inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: clamp(1rem, 3vw, 2rem);
    width: 100%;
    max-width: min(1280px, 100%);
    margin: 0 auto;
    padding: 0.625rem clamp(1rem, 3vw, 1.75rem);
    box-sizing: border-box;
}

@media (max-width: 768px) {
    .portal-nav-minimal:not(.navbar) .portal-nav-minimal__inner {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        grid-template-rows: auto auto auto;
        align-items: center;
        gap: 0;
        row-gap: 0;
    }

    .portal-nav-minimal:not(.navbar) .portal-nav-minimal__inner > .nav-logo {
        grid-column: 1;
        grid-row: 1;
        min-width: 0;
    }

    .portal-nav-minimal:not(.navbar) .portal-nav-minimal__inner > .nav-toggle {
        display: inline-flex;
        grid-column: 2;
        grid-row: 1;
        justify-self: end;
    }

    .portal-nav-minimal:not(.navbar) .portal-nav-minimal__links,
    .portal-nav-minimal:not(.navbar) .portal-nav-minimal__actions {
        display: none;
        grid-column: 1 / -1;
        flex-direction: column;
        align-items: stretch;
        width: 100%;
        padding: 0.5rem 0 0;
        margin: 0;
        border-top: 1px solid rgba(27, 94, 32, 0.1);
        background: rgba(255, 255, 255, 0.94);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    .portal-nav-minimal:not(.navbar) .portal-nav-minimal__links .portal-nav-minimal__link {
        width: 100%;
        box-sizing: border-box;
    }

    .portal-nav-minimal:not(.navbar) .portal-nav-minimal__actions {
        gap: 0.5rem;
        padding-bottom: 0.625rem;
        border-top-color: rgba(27, 94, 32, 0.08);
    }

    .portal-nav-minimal:not(.navbar).nav-open .portal-nav-minimal__inner > .portal-nav-minimal__links {
        display: flex;
        grid-row: 2;
    }

    .portal-nav-minimal:not(.navbar).nav-open .portal-nav-minimal__inner > .portal-nav-minimal__actions {
        display: flex;
        grid-row: 3;
    }

    .portal-nav-minimal:not(.navbar).nav-open {
        box-shadow: 0 8px 24px rgba(27, 94, 32, 0.1);
    }
}

.portal-nav-minimal .nav-logo {
    gap: 0.625rem;
}

.portal-nav-minimal .nav-logo img {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 0.5rem;
}

@media (min-width: 768px) {
    .portal-nav-minimal .nav-logo img {
        width: 2.75rem;
        height: 2.75rem;
    }
}

.portal-nav-minimal .nav-brand-title {
    font-size: 0.9375rem;
    font-weight: 700;
    letter-spacing: -0.02em;
    line-height: 1.2;
}

.portal-nav-minimal .nav-brand-subtitle {
    font-size: 0.625rem;
    font-weight: 500;
    opacity: 0.72;
    letter-spacing: 0.02em;
    text-transform: uppercase;
}

@media (min-width: 768px) {
    .portal-nav-minimal .nav-brand-title {
        font-size: 1rem;
    }
    .portal-nav-minimal .nav-brand-subtitle {
        font-size: 0.6875rem;
    }
}

.portal-nav-minimal__links {
    display: none;
    list-style: none;
    margin: 0;
    padding: 0;
    align-items: center;
    gap: 0.25rem;
}

@media (min-width: 768px) {
    .portal-nav-minimal__links {
        display: flex;
        flex: 1;
        justify-content: center;
    }
}

.portal-nav-minimal__link i,
.portal-nav-minimal__action i {
    font-size: 0.8125rem;
    opacity: 0.88;
    flex-shrink: 0;
    line-height: 1;
}

.portal-nav-minimal__link {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.5rem 0.875rem;
    font-size: 0.875rem;
    font-weight: 500;
    line-height: 1.25;
    color: var(--nav-link-idle-color, var(--ink-600, rgba(15, 23, 42, 0.72)));
    text-decoration: none;
    border-radius: 0.5rem;
    transition: color 0.15s ease, background-color 0.15s ease;
}

.portal-nav-minimal__link:hover {
    color: var(--ui-accent-color, var(--chrome-icon-color, var(--brand-800, #34543f)));
    background: var(--ui-accent-surface, var(--chrome-surface-bg, rgba(249, 222, 229, 0.5)));
    box-shadow: inset 0 0 0 1px var(--ui-accent-border, var(--chrome-surface-border, transparent));
}

.portal-nav-minimal__link.is-active {
    color: #fff;
    font-weight: 600;
    background: var(--chrome-active-bg, var(--accent-pink-strong, #D37897));
    box-shadow: 0 2px 10px color-mix(in srgb, var(--chrome-active-bg, #D37897) 35%, transparent);
}

.portal-nav-minimal__actions {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-shrink: 0;
}

@media (min-width: 640px) {
    .portal-nav-minimal__actions {
        gap: 0.625rem;
    }
}

.portal-nav-minimal__action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    padding: 0.4375rem 0.75rem;
    font-size: 0.8125rem;
    font-weight: 600;
    line-height: 1.25;
    text-decoration: none;
    border-radius: 0.5rem;
    white-space: nowrap;
    transition: color 0.15s ease, background-color 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease;
}

@media (min-width: 640px) {
    .portal-nav-minimal__action {
        padding: 0.5rem 0.875rem;
        font-size: 0.875rem;
    }
}

.portal-nav-minimal__action--text {
    color: rgba(15, 23, 42, 0.78);
    background: transparent;
}

.portal-nav-minimal__action--text:hover {
    color: var(--brand-dark, #1b5e20);
    background: rgba(46, 125, 50, 0.06);
}

.portal-nav-minimal__action--outline {
    color: var(--brand-dark, #1b5e20);
    background: rgba(255, 255, 255, 0.5);
    border: 1px solid rgba(46, 125, 50, 0.35);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
}

.portal-nav-minimal__action--outline:hover {
    border-color: var(--brand-primary, #2e7d32);
    background: rgba(46, 125, 50, 0.06);
}

.portal-nav-minimal__action--primary {
    color: var(--action-primary-text, #fff);
    background: var(--action-primary-bg, var(--brand-700, #457359));
    border: 1px solid var(--action-primary-border, transparent);
    box-shadow: 0 1px 2px color-mix(in srgb, var(--action-primary-bg, #457359) 25%, transparent);
}

.portal-nav-minimal__action--primary:hover {
    background: var(--action-primary-hover, var(--brand-800, #34543f));
    box-shadow: 0 2px 8px color-mix(in srgb, var(--action-primary-bg, #457359) 35%, transparent);
}

.portal-nav-minimal__action.is-highlighted {
    box-shadow: 0 0 0 2px var(--brand-primary, #2e7d32);
}

.portal-nav-minimal__action:focus-visible {
    outline: 2px solid var(--brand-primary, #2e7d32);
    outline-offset: 2px;
}

/* Legacy mobile link row — replaced by burger drawer */
.portal-nav-minimal__mobile-links {
    display: none !important;
}

html.dark .portal-nav-minimal {
    background: transparent !important;
    border-bottom-color: rgba(255, 255, 255, 0.08);
    --nav-tribal-pattern-opacity: 0.28;
    --portal-nav-minimal-scrim: rgba(15, 23, 42, 0.78);
}

html.dark .portal-nav-minimal::after {
    background:
        linear-gradient(
            180deg,
            rgba(15, 23, 42, 0.82) 0%,
            rgba(15, 23, 42, 0.72) 55%,
            rgba(15, 23, 42, 0.76) 100%
        ),
        linear-gradient(90deg, rgba(46, 125, 50, 0.08) 0%, transparent 50%, rgba(46, 125, 50, 0.08) 100%);
}

html.dark .portal-nav-minimal__mobile-links {
    background: linear-gradient(180deg, rgba(15, 23, 42, 0.4) 0%, rgba(15, 23, 42, 0.15) 100%);
    border-top-color: rgba(255, 255, 255, 0.08);
}

html.dark .portal-nav-minimal__link {
    color: rgba(248, 250, 252, 0.75);
}

html.dark .portal-nav-minimal__action--text {
    color: rgba(248, 250, 252, 0.85);
}

/* ── Consistent bar height (match landing) ─────────────────────────────── */
.public-nav-tribal.portal-nav-minimal:not(.navbar) {
    min-height: 0 !important;
    padding: 0 !important;
}

@media (min-width: 768px) {
    /* Portal public nav (landing, explore) — fixed single-row height */
    nav.portal-nav-minimal.public-nav-tribal:not(.navbar) {
        height: var(--app-topbar-height, 4rem) !important;
        min-height: var(--app-topbar-height, 4rem) !important;
        max-height: var(--app-topbar-height, 4rem) !important;
        box-sizing: border-box;
    }

    /* App nav (guest, owner, admin) — min height only; grows if many links */
    .navbar.portal-nav-minimal.public-nav-tribal {
        height: auto !important;
        min-height: var(--app-topbar-height, 4rem) !important;
        max-height: none !important;
        box-sizing: border-box;
    }
}

@media (max-width: 768px) {
    nav.portal-nav-minimal.public-nav-tribal:not(.navbar):not(.nav-open) {
        height: var(--app-topbar-height-mobile, 3.5rem) !important;
        min-height: var(--app-topbar-height-mobile, 3.5rem) !important;
        max-height: var(--app-topbar-height-mobile, 3.5rem) !important;
    }

    nav.portal-nav-minimal.public-nav-tribal:not(.navbar).nav-open {
        height: auto !important;
        min-height: var(--app-topbar-height-mobile, 3.5rem) !important;
        max-height: none !important;
    }

    .navbar.portal-nav-minimal.public-nav-tribal:not(.nav-open) {
        height: var(--app-topbar-height-mobile, 3.5rem) !important;
        min-height: var(--app-topbar-height-mobile, 3.5rem) !important;
        max-height: var(--app-topbar-height-mobile, 3.5rem) !important;
        box-sizing: border-box;
    }

    .navbar.portal-nav-minimal.public-nav-tribal.nav-open {
        height: auto !important;
        min-height: var(--app-topbar-height-mobile, 3.5rem) !important;
        max-height: none !important;
        box-sizing: border-box;
    }
}

/* Tokens after ui-foundation (84px) on app + portal minimal nav pages */
body.explore-portal-page,
body.portal-landing-page,
body.about-portal-page,
body.tenant-landing-page,
body.owner-nav-page,
body.admin-central-portal {
    --app-topbar-height: 4rem;
    --app-topbar-height-mobile: 3.5rem;
    --app-main-top-offset: 4rem;
    --portal-public-nav-offset: 4rem;
    --app-content-offset: calc(4rem + clamp(1.25rem, 2vw, 1.875rem));
    --portal-content-below-nav: calc(4rem + clamp(1.25rem, 2vw, 1.875rem));
    --owner-topbar-height: 4rem;
    --owner-content-offset: var(--app-content-offset);
}

body.client-nav-page {
    --app-topbar-height: 4.5rem;
    --app-topbar-height-mobile: 4.5rem;
    --app-main-top-offset: 4.5rem;
    --portal-public-nav-offset: 4.5rem;
    --app-content-offset: calc(4.5rem + clamp(1.25rem, 2vw, 1.875rem));
    --portal-content-below-nav: calc(4.5rem + clamp(1.25rem, 2vw, 1.875rem));
    --client-nav-offset: calc(var(--app-topbar-height, 4.5rem) + clamp(1.25rem, 2vw, 1.875rem));
    --client-nav-safe-offset: calc(var(--app-topbar-height, 4.5rem) + clamp(1.75rem, 2.5vw, 2.5rem));
}

@media (max-width: 768px) {
    body.explore-portal-page,
    body.portal-landing-page,
    body.about-portal-page,
    body.tenant-landing-page,
    body.owner-nav-page,
    body.admin-central-portal,
    body.client-nav-page {
        --app-topbar-height: 3.5rem;
        --app-topbar-height-mobile: 3.5rem;
        --app-main-top-offset: 3.5rem;
        --portal-public-nav-offset: 3.5rem;
        --portal-content-below-nav: calc(3.5rem + clamp(1rem, 2vw, 1.5rem));
        --app-content-offset: calc(3.5rem + clamp(1rem, 2vw, 1.25rem));
        --owner-topbar-height: 3.5rem;
        --owner-content-offset: var(--app-content-offset);
        --client-nav-offset: calc(3.5rem + clamp(1rem, 2vw, 1.25rem));
        --client-nav-safe-offset: calc(3.5rem + clamp(1.5rem, 2vw, 2rem));
    }
}

@include('partials.mobile-nav-unified-styles')
