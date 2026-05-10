:root {
    --app-topbar-height: 76px;
    --app-topbar-height-mobile: 64px;
    --app-content-offset: var(--app-main-top-offset, 108px);
    --owner-topbar-height: var(--app-topbar-height);
    --owner-content-offset: var(--app-content-offset);
}

@include('partials.top-navbar-core-styles')
@include('partials.ui-foundation-styles')

body.owner-nav-page .dashboard-layout {
    padding-top: var(--app-content-offset) !important;
}

body.owner-nav-page .main-content.with-owner-nav {
    padding-top: var(--app-content-offset) !important;
}

/* Stable top bar: fix logo block height, reserve message badge slot; centered nav on desktop */
body.owner-nav-page .navbar {
    min-height: var(--app-topbar-height, 76px);
    box-sizing: border-box;
}

/* True visual center: equal side tracks so the link cluster sits in the middle of the bar */
@media (min-width: 961px) {
    body.owner-nav-page .navbar {
        grid-template-columns: minmax(0, 1fr) auto minmax(0, 1fr);
    }

    body.owner-nav-page .nav-logo {
        justify-self: start;
    }

    body.owner-nav-page .nav-links {
        justify-self: center;
        justify-content: center;
        width: max-content;
        max-width: 100%;
        flex-wrap: nowrap;
    }

    body.owner-nav-page .nav-actions {
        justify-self: end;
    }
}

/* Many links on mid-width screens: scroll without left-align jump */
@media (min-width: 961px) and (max-width: 1400px) {
    body.owner-nav-page .nav-links {
        overflow-x: auto;
        overflow-y: hidden;
        scrollbar-width: none;
        -ms-overflow-style: none;
        justify-content: center;
    }

    body.owner-nav-page .nav-links::-webkit-scrollbar {
        display: none;
    }
}

body.owner-nav-page .nav-logo {
    min-height: 48px;
    align-items: center;
}
body.owner-nav-page .nav-logo span {
    display: inline-flex;
    flex-direction: column;
    justify-content: center;
    gap: 2px;
    min-height: 2.25rem;
    line-height: 1.1;
}

body.owner-nav-page .nav-msg-count-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 18px;
    height: 18px;
    border-radius: 999px;
    padding: 0 5px;
    margin-left: 6px;
    font-size: 0.68rem;
    font-weight: 700;
    box-sizing: border-box;
}
body.owner-nav-page .nav-msg-count-badge.is-empty {
    visibility: hidden;
    pointer-events: none;
}
body.owner-nav-page .nav-msg-count-badge:not(.is-empty) {
    background: #EF4444;
    color: #fff;
}

/* Shared responsive helpers */
