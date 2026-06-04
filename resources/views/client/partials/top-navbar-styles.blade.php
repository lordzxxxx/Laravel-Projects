/* Shared tenant client top nav — include only on client-facing pages */
</style>
@include('partials.appearance-boot')
<style>
@include('partials.top-navbar-core-styles')
@include('partials.ui-foundation-styles')
@include('partials.portal-public-nav-minimal-styles')
@include('partials.app-top-navbar-minimal-overrides')
@include('partials.mobile-nav-unified-styles')
@include('client.partials.guest-shell-styles')

.navbar {
    font-family: var(--app-font-sans);
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

.navbar a,
.navbar button {
    font-family: inherit;
}
