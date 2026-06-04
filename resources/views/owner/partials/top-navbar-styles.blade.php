</style>
@include('partials.appearance-boot')
<style>
@include('partials.top-navbar-core-styles')
@include('partials.ui-foundation-styles')
@include('partials.portal-public-nav-minimal-styles')
@include('partials.app-top-navbar-minimal-overrides')

@include('owner.partials.owner-shell-styles')
@include('owner.partials.owner-dark-styles')
@include('partials.mobile-nav-unified-styles')
@include('owner.partials.owner-nav-mobile-styles')

body.owner-nav-page .dashboard-layout {
    padding-top: var(--app-content-offset) !important;
}

/* Message badge slot (owner nav icons unchanged) */
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
