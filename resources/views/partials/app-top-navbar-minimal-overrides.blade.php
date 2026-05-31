{{-- App top bar (owner, admin, guest): portal minimal tribal shell; keeps grid, icons, dropdowns. --}}
:is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) .navbar.portal-nav-minimal.public-nav-tribal {
    padding: 0.625rem clamp(1rem, 3vw, 1.75rem) !important;
    display: grid !important;
    gap: clamp(0.5rem, 1.5vw, 0.75rem) !important;
    overflow: visible !important;
    align-items: center !important;
    background: transparent !important;
    border-bottom: 1px solid rgba(27, 94, 32, 0.1);
    box-shadow: 0 1px 0 rgba(255, 255, 255, 0.65), 0 8px 32px rgba(27, 94, 32, 0.06) !important;
    backdrop-filter: blur(10px) saturate(1.05);
    -webkit-backdrop-filter: blur(10px) saturate(1.05);
}

:is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) .navbar.portal-nav-minimal .nav-logo {
    gap: 0.625rem;
    min-height: 0;
}

:is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) .navbar.portal-nav-minimal .nav-logo img {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 0.5rem;
}

@media (min-width: 768px) {
    :is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) .navbar.portal-nav-minimal .nav-logo img {
        width: 2.75rem;
        height: 2.75rem;
    }
}

:is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) .navbar.portal-nav-minimal .nav-brand-title {
    font-size: 0.9375rem;
    font-weight: 700;
}

:is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) .navbar.portal-nav-minimal .nav-brand-subtitle {
    font-size: 0.625rem;
    font-weight: 500;
    opacity: 0.72;
    text-transform: uppercase;
    letter-spacing: 0.02em;
}

@media (min-width: 768px) {
    :is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) .navbar.portal-nav-minimal .nav-brand-title {
        font-size: 1rem;
    }
    :is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) .navbar.portal-nav-minimal .nav-brand-subtitle {
        font-size: 0.6875rem;
    }
}

:is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) .navbar.portal-nav-minimal > .nav-links a {
    padding: 0.4375rem 0.75rem;
    font-size: 0.875rem;
    font-weight: 500;
    line-height: 1.2;
    color: var(--nav-link-idle-color, var(--ink-600, rgba(15, 23, 42, 0.72)));
    border: none;
    background: transparent;
    box-shadow: none;
    backdrop-filter: none;
    -webkit-backdrop-filter: none;
    min-height: auto;
    border-radius: 0.5rem;
    gap: 0.4rem;
    text-shadow: none;
    transition: color 0.15s ease, background-color 0.15s ease;
}

:is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) .navbar.portal-nav-minimal > .nav-links a i {
    font-size: 0.8125rem;
    width: auto;
    opacity: 0.88;
}

:is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) .navbar.portal-nav-minimal > .nav-links a:hover {
    color: var(--ui-accent-color, var(--chrome-icon-color, var(--brand-800, #34543f)));
    background: var(--ui-accent-surface, var(--chrome-surface-bg, rgba(249, 222, 229, 0.5)));
    border: none;
    box-shadow: inset 0 0 0 1px var(--ui-accent-border, var(--chrome-surface-border, transparent));
}

:is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) .navbar.portal-nav-minimal > .nav-links a.active {
    color: #fff;
    font-weight: 600;
    background: var(--chrome-active-bg, var(--accent-pink-strong, #D37897));
    border: none;
    box-shadow: 0 2px 10px color-mix(in srgb, var(--chrome-active-bg, #D37897) 35%, transparent);
}

:is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) .navbar.portal-nav-minimal .user-display {
    padding: 0.25rem 0.5rem 0.25rem 0.25rem;
    max-height: 2.75rem;
    gap: 0.375rem;
    background: rgba(255, 255, 255, 0.5);
    border: 1px solid rgba(46, 125, 50, 0.12);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
}

:is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) .navbar.portal-nav-minimal .imp-notify-btn {
    width: 2.25rem;
    height: 2.25rem;
    min-height: 2.25rem;
    font-size: 0.8125rem;
    background: rgba(255, 255, 255, 0.5);
    border: 1px solid rgba(46, 125, 50, 0.12);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
}

:is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) .navbar.portal-nav-minimal .user-avatar {
    width: 1.75rem;
    height: 1.75rem;
    font-size: 0.6875rem;
}

:is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) .navbar.portal-nav-minimal .user-name {
    font-size: 0.75rem;
    line-height: 1.2;
}

:is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) .navbar.portal-nav-minimal .user-role {
    font-size: 0.5625rem;
    margin-top: 0;
}

@media (max-width: 960px) {
    :is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) .navbar.portal-nav-minimal > .nav-links {
        background: rgba(255, 255, 255, 0.94);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-top: 1px solid rgba(27, 94, 32, 0.1);
    }

    :is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) #appNavbar.nav-open .nav-actions {
        background: rgba(255, 255, 255, 0.94);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }
}

html.dark :is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) .navbar.portal-nav-minimal > .nav-links a {
    color: rgba(248, 250, 252, 0.75);
    background: transparent;
    border: none;
}

html.dark :is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) .navbar.portal-nav-minimal > .nav-links a:hover {
    color: var(--ui-accent-color, var(--chrome-icon-color, #F9DEE5));
    background: var(--ui-accent-surface, var(--chrome-surface-bg, rgba(249, 222, 229, 0.12)));
    border: none;
    box-shadow: inset 0 0 0 1px var(--ui-accent-border, transparent);
}

html.dark :is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) .navbar.portal-nav-minimal > .nav-links a.active {
    color: #fff;
    background: var(--chrome-active-bg, #D37897);
    border: none;
    box-shadow: 0 2px 10px color-mix(in srgb, var(--chrome-active-bg, #D37897) 40%, transparent);
}
