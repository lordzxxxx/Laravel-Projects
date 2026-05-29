{{-- Guest / client portal — communal photo background + main content shell (matches /dashboard). --}}
:root {
    --client-guest-bg-image: linear-gradient(
        135deg,
        rgba(255, 255, 255, 0.95) 0%,
        rgba(255, 255, 255, 0.85) 50%,
        rgba(27, 94, 32, 0.1) 100%
    ), url('/COMMUNAL.jpg');
}

body.client-nav-page {
    min-height: 100vh;
    font-family: var(--app-font-sans);
    color: var(--ink-800, #1f2937);
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    background-color: #f8fafc;
    background-image: var(--client-guest-bg-image);
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
}

body.profile-page.client-nav-page {
    background-color: #f8fafc;
    background-image: var(--client-guest-bg-image);
}

body.client-nav-page.msg-thread-page {
    background-color: #f8fafc;
    background-image: var(--client-guest-bg-image);
}

.client-guest-main {
    width: 100%;
    max-width: min(1280px, 100%);
    margin-left: auto;
    margin-right: auto;
    padding-top: var(--client-nav-offset, var(--app-main-top-offset, 108px));
    padding-left: clamp(1.25rem, 3vw, 2.5rem);
    padding-right: clamp(1.25rem, 3vw, 2.5rem);
    padding-bottom: clamp(3rem, 6vw, 5rem);
    min-height: calc(100vh - var(--client-nav-offset, 108px));
    box-sizing: border-box;
}

.client-guest-main--wide {
    max-width: min(1800px, 100%);
}

.client-guest-main--full {
    max-width: none;
    width: 100%;
    padding-left: clamp(0.75rem, 2vw, 1.75rem);
    padding-right: clamp(0.75rem, 2vw, 1.75rem);
}

body.client-nav-page .client-guest-surface {
    background: rgba(255, 255, 255, 0.88);
    border: 1px solid rgba(255, 255, 255, 0.65);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}
