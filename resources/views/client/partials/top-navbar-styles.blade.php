/* Shared tenant client top nav — include only on client-facing pages */
:root {
    --client-nav-font: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    --app-topbar-height: 76px;
    --app-topbar-height-mobile: 64px;
    --client-nav-offset: var(--app-main-top-offset, 108px);
}

@include('partials.top-navbar-core-styles')
@include('partials.ui-foundation-styles')

.navbar {
    font-family: var(--client-nav-font);
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

.navbar a,
.navbar button {
    font-family: inherit;
}
