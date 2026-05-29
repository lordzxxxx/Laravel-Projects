{{--
  Love Impasugong watermark — fixed, centered in the main content viewport (does not scroll).
--}}
:root {
    --app-main-watermark-image: url("{{ asset('images/love-impasugong-watermark.png') }}");
    --app-main-watermark-opacity: 0.2;
    --app-main-watermark-size: min(960px, 94vw);
    --app-watermark-inset-top: var(--app-main-top-offset, 108px);
    /* Vertical center of the area below the top bar */
    --app-watermark-center-y: calc(
        var(--app-watermark-inset-top) + (100dvh - var(--app-watermark-inset-top)) / 2
    );
}

body.owner-nav-page {
    --app-watermark-inset-top: var(--owner-content-offset, var(--app-content-offset, var(--app-main-top-offset, 108px)));
}

body.profile-page {
    --app-watermark-inset-top: var(--profile-offset, var(--app-main-top-offset, 108px));
}

body.admin-central-portal::before,
body.owner-nav-page::before,
body.profile-page::before {
    content: '';
    position: fixed;
    left: 50%;
    top: var(--app-watermark-center-y);
    width: var(--app-main-watermark-size);
    height: var(--app-main-watermark-size);
    transform: translate(-50%, -50%);
    z-index: -2;
    pointer-events: none;
    background-image: var(--app-main-watermark-image);
    background-repeat: no-repeat;
    background-position: center center;
    background-size: contain;
    opacity: var(--app-main-watermark-opacity);
}

html.dark body.admin-central-portal::before,
html.dark body.owner-nav-page::before,
html.dark body.profile-page::before {
    opacity: calc(var(--app-main-watermark-opacity) * 0.85);
}

:where(
    .dashboard-layout,
    .main-content,
    .msg-admin-main,
    .msg-thread-main,
    .profile-main,
    .landing-settings-main,
    .messages-index-main,
    .messages-create-main
) {
    position: relative;
    z-index: 1;
}
