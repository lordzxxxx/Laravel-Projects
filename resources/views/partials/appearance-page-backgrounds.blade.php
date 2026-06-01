{{-- Communal photo page backgrounds — respect light/dark display mode tokens. --}}
:root {
    --communal-bg-overlay-light: linear-gradient(
        135deg,
        rgba(255, 255, 255, 0.95) 0%,
        rgba(255, 255, 255, 0.88) 50%,
        rgba(27, 94, 32, 0.08) 100%
    ), url('/COMMUNAL.jpg');
    --communal-bg-overlay-dark: linear-gradient(
        135deg,
        rgba(15, 23, 42, 0.94) 0%,
        rgba(15, 23, 42, 0.88) 50%,
        rgba(27, 94, 32, 0.18) 100%
    ), url('/COMMUNAL.jpg');
    --client-guest-bg-image: var(--communal-bg-overlay-light);
    --owner-page-bg-image: var(--communal-bg-overlay-light);
}

html.dark {
    --client-guest-bg-image: var(--communal-bg-overlay-dark);
    --owner-page-bg-image: var(--communal-bg-overlay-dark);
}

html.dark body.explore-portal-page,
html.dark body.about-portal-page,
html.dark body.tenant-landing-page {
    background-color: var(--app-page-bg) !important;
    background-image: var(--communal-bg-overlay-dark) !important;
}
