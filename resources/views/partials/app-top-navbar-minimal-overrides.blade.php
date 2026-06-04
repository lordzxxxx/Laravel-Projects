{{-- App top bar (owner, admin, guest): portal minimal tribal shell; keeps grid, icons, dropdowns. --}}
:is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) .navbar.portal-nav-minimal.public-nav-tribal {
    padding: 0 !important;
    display: grid !important;
    gap: clamp(0.5rem, 1.5vw, 0.75rem) !important;
    overflow: visible !important;
    align-items: center !important;
    background: transparent !important;
    border-bottom: 1px solid rgba(27, 94, 32, 0.12);
    box-shadow: 0 4px 20px rgba(27, 94, 32, 0.08) !important;
    backdrop-filter: none;
    -webkit-backdrop-filter: none;
    width: 100vw;
    max-width: 100vw;
    left: 0;
    right: 0;
}

:is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) .navbar.portal-nav-minimal > .nav-logo {
    display: flex;
    align-items: center;
    align-self: center;
    min-height: var(--app-topbar-height, 4rem);
    padding: 0.5rem 0 0.5rem clamp(1rem, 3vw, 1.75rem);
    box-sizing: border-box;
}

:is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) .navbar.portal-nav-minimal > .nav-toggle {
    display: none;
}

@media (min-width: 961px) {
    :is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) .navbar.portal-nav-minimal > .nav-actions {
        padding: 0.625rem clamp(1rem, 3vw, 1.75rem) 0.625rem 0;
        overflow: visible;
    }
}

:is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) .navbar.portal-nav-minimal > .nav-actions,
:is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) .navbar.portal-nav-minimal .user-menu {
    overflow: visible;
}

:is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) .navbar.portal-nav-minimal .user-menu__panel {
    z-index: 2000;
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
    /*
     * Stack mobile menu inside the bar (grid rows) instead of absolute panels.
     * Header row height is fixed so tribal bg + logo/toggle stay put when menu opens.
     */
    :is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) .navbar.portal-nav-minimal.public-nav-tribal {
        --nav-mobile-header-height: var(--app-topbar-height, 4rem);
        display: grid !important;
        grid-template-columns: minmax(0, 1fr) auto;
        grid-template-rows: var(--nav-mobile-header-height);
        align-items: stretch !important;
        gap: 0 !important;
        min-height: var(--nav-mobile-header-height) !important;
        overflow: hidden !important;
        z-index: 1100;
    }

    :is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) #appNavbar.nav-open.portal-nav-minimal.public-nav-tribal {
        grid-template-rows: var(--nav-mobile-header-height) auto auto;
    }

    :is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) .navbar.portal-nav-minimal > .navbar-tribal-accent {
        position: absolute !important;
        top: 0 !important;
        bottom: auto !important;
        left: 50% !important;
        right: auto !important;
        width: 100vw !important;
        max-width: 100vw !important;
        height: var(--nav-mobile-header-height) !important;
        max-height: var(--nav-mobile-header-height) !important;
        transform: translateX(-50%) !important;
        grid-column: unset !important;
        grid-row: unset !important;
        z-index: 0 !important;
    }

    :is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) .navbar.portal-nav-minimal > .navbar-tribal-accent .navbar-tribal-accent__canvas {
        background-size: cover !important;
        background-position: center center !important;
    }

    :is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) .navbar.portal-nav-minimal::after {
        bottom: auto !important;
        height: var(--nav-mobile-header-height) !important;
        max-height: var(--nav-mobile-header-height) !important;
    }

    :is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) .navbar.portal-nav-minimal > .nav-logo {
        grid-column: 1;
        grid-row: 1;
        position: relative;
        z-index: 2;
        align-self: center;
        min-height: var(--nav-mobile-header-height) !important;
        max-height: var(--nav-mobile-header-height) !important;
        height: var(--nav-mobile-header-height) !important;
        padding: 0 0 0 clamp(1rem, 3vw, 1.75rem) !important;
        margin: 0 !important;
        box-sizing: border-box;
    }

    :is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) .navbar.portal-nav-minimal > .nav-toggle {
        display: inline-flex !important;
        grid-column: 2;
        grid-row: 1;
        position: relative;
        z-index: 2;
        align-self: center;
        justify-self: end;
        align-items: center;
        justify-content: center;
        width: 2.75rem;
        height: 2.75rem;
        min-width: 2.75rem;
        min-height: 2.75rem;
        padding: 0 !important;
        margin: 0 clamp(1rem, 3vw, 1.75rem) 0 0.75rem !important;
        box-sizing: border-box;
        flex-shrink: 0;
    }

    :is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) .navbar.portal-nav-minimal > .nav-links {
        display: none !important;
        grid-column: 1 / -1;
        grid-row: 2;
        position: static !important;
        top: auto !important;
        left: auto !important;
        right: auto !important;
        width: 100% !important;
        max-height: min(52vh, 22rem);
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
        margin: 0;
        padding: 0.35rem clamp(0.75rem, 3vw, 1rem) 0.25rem;
        box-shadow: none;
        transform: none !important;
        background: rgba(255, 255, 255, 0.96);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-top: 1px solid rgba(27, 94, 32, 0.1);
        z-index: 2;
    }

    :is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) #appNavbar.nav-open > .nav-links {
        display: flex !important;
        flex-direction: column;
        align-items: stretch;
        gap: 0.25rem;
    }

    :is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) .navbar.portal-nav-minimal > .nav-actions {
        display: none !important;
        grid-column: 1 / -1;
        grid-row: 3;
        position: static !important;
        top: auto !important;
        left: auto !important;
        right: auto !important;
        width: 100% !important;
        transform: none !important;
        margin: 0;
        padding: 0.35rem clamp(0.75rem, 3vw, 1rem) 0.65rem;
        box-shadow: none;
        background: rgba(255, 255, 255, 0.96);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-top: 1px solid rgba(27, 94, 32, 0.08);
        z-index: 2;
        justify-content: flex-start;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    :is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) #appNavbar.nav-open > .nav-actions {
        display: flex !important;
    }

    :is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) #appNavbar.nav-open.portal-nav-minimal.public-nav-tribal,
    :is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) #appNavbar:has(.user-menu.is-open).portal-nav-minimal.public-nav-tribal {
        overflow: visible !important;
    }

    :is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) #appNavbar.nav-open.portal-nav-minimal.public-nav-tribal > .navbar-tribal-accent,
    :is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) #appNavbar.nav-open.portal-nav-minimal.public-nav-tribal::after {
        height: var(--nav-mobile-header-height) !important;
        max-height: var(--nav-mobile-header-height) !important;
    }

    :is(body.owner-nav-page, body.admin-central-portal, body.client-nav-page) .navbar.portal-nav-minimal > .nav-links a {
        width: 100%;
        min-height: 44px;
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
