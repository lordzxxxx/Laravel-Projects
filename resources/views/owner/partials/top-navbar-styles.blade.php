:root {
    --app-topbar-height: 76px;
    --app-topbar-height-mobile: 64px;
    --app-content-offset: 92px;
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

@media (max-width: 768px) {
    :root { --app-content-offset: 80px; }
}

/* Shared responsive helpers */
@include('partials.global-responsive-helpers')
