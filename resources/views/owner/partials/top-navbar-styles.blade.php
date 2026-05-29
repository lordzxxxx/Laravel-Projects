</style>
@include('partials.appearance-boot')
<style>
:root {
    --app-topbar-height: 84px;
    --app-topbar-height-mobile: 72px;
    --app-content-offset: var(--app-main-top-offset, 108px);
    --owner-topbar-height: var(--app-topbar-height);
    --owner-content-offset: var(--app-content-offset);
}

@include('partials.top-navbar-core-styles')
@include('partials.ui-foundation-styles')

body.owner-nav-page {
    font-family: var(--app-font-sans);
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

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

body.owner-nav-page .nav-logo {
    min-height: 48px;
    align-items: center;
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
