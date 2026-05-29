/* Shared tenant client top nav — include only on client-facing pages */
</style>
@include('partials.appearance-boot')
<style>
:root {
    --client-nav-font: var(--app-font-sans);
    --app-topbar-height: 84px;
    --app-topbar-height-mobile: 72px;
    --client-nav-offset: var(--app-main-top-offset, 108px);
}

@include('partials.top-navbar-core-styles')
@include('partials.ui-foundation-styles')
@include('client.partials.guest-shell-styles')

.navbar {
    font-family: var(--client-nav-font);
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

.navbar a,
.navbar button {
    font-family: inherit;
}
