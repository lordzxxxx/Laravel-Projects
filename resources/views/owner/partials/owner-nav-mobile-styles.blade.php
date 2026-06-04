{{-- Owner tenant portal (/owner/*) — mobile top bar alignment (loads last). --}}
@media (max-width: 768px) {
    body.owner-nav-page {
        --app-topbar-height: 3.5rem;
        --app-topbar-height-mobile: 3.5rem;
        --app-main-top-offset: 3.5rem;
        --owner-topbar-height: 3.5rem;
        --owner-content-offset: calc(3.5rem + clamp(1rem, 2vw, 1.25rem));
        --app-content-offset: var(--owner-content-offset);
    }

    body.owner-nav-page .navbar.portal-nav-minimal.public-nav-tribal {
        display: grid !important;
        grid-template-columns: minmax(0, 1fr) auto !important;
        align-items: center !important;
        align-content: stretch !important;
        column-gap: 0.75rem !important;
        row-gap: 0 !important;
        padding-block: 0 !important;
        padding-inline: clamp(1rem, 4vw, 1.25rem) !important;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        left: 0 !important;
        right: 0 !important;
        margin: 0 !important;
    }

    body.owner-nav-page .navbar.portal-nav-minimal.public-nav-tribal:not(.nav-open) {
        height: var(--app-topbar-height-mobile, 3.5rem) !important;
        min-height: var(--app-topbar-height-mobile, 3.5rem) !important;
        max-height: var(--app-topbar-height-mobile, 3.5rem) !important;
        grid-template-rows: minmax(0, 1fr) !important;
    }

    body.owner-nav-page .navbar.portal-nav-minimal.public-nav-tribal.nav-open {
        height: auto !important;
        min-height: var(--app-topbar-height-mobile, 3.5rem) !important;
        max-height: none !important;
        grid-template-rows: var(--app-topbar-height-mobile, 3.5rem) auto auto !important;
        padding-bottom: 0.5rem !important;
    }

    body.owner-nav-page .navbar.portal-nav-minimal > .nav-logo,
    body.owner-nav-page .navbar.portal-nav-minimal > .nav-toggle {
        grid-row: 1;
        align-self: center;
        margin: 0;
    }

    body.owner-nav-page .navbar.portal-nav-minimal > .nav-logo {
        grid-column: 1;
        justify-self: start;
        display: inline-flex !important;
        align-items: center !important;
        max-width: min(100%, calc(100% - 3.25rem));
    }

    body.owner-nav-page .navbar.portal-nav-minimal > .nav-toggle {
        grid-column: 2;
        justify-self: end;
    }

    body.owner-nav-page .navbar.portal-nav-minimal > .nav-logo img {
        width: 2.25rem;
        height: 2.25rem;
        margin: 0;
    }

    body.owner-nav-page .navbar > .nav-logo .nav-brand-text {
        min-width: 0;
        max-width: 100%;
        justify-content: center;
    }

    body.owner-nav-page .navbar > .nav-logo .nav-brand-title,
    body.owner-nav-page .navbar > .nav-logo .nav-brand-subtitle {
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
        white-space: nowrap;
    }

    body.owner-nav-page .navbar.nav-open > .nav-links,
    body.owner-nav-page .navbar.nav-open > .nav-actions {
        grid-column: 1 / -1;
        width: 100%;
    }

    body.owner-nav-page .navbar.nav-open > .nav-actions {
        justify-content: flex-start;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.5rem;
        padding-inline: 0;
    }
}
