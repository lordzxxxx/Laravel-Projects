{{-- Design tokens + communal background for central public portal (/, /about, /explore). --}}
@include('partials.ui-foundation-styles')
@include('client.partials.guest-shell-styles')

:where(body.explore-portal-page, body.about-portal-page) {
    min-height: 100dvh;
    color: var(--ink-800, #1f2937);
    background-color: var(--app-page-bg, #f8fafc);
    background-image: var(--communal-bg-overlay-light);
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
}
