{{-- Burger menu for Tailwind-based public nav bars (central + portal default) --}}
.public-nav-burgerable .nav-toggle {
    display: none;
    background: transparent;
    border: 1px solid rgba(46, 125, 50, 0.25);
    color: var(--brand-dark, #1b5e20);
    width: 44px;
    height: 44px;
    min-width: 44px;
    min-height: 44px;
    border-radius: 10px;
    cursor: pointer;
    align-items: center;
    justify-content: center;
    font-size: 1.05rem;
    flex-shrink: 0;
}

.public-nav-burgerable .nav-toggle:focus-visible {
    outline: 2px solid var(--brand-primary, #2e7d32);
    outline-offset: 2px;
}

@media (max-width: 768px) {
    .public-nav-burgerable {
        flex-direction: column !important;
        align-items: stretch !important;
        height: auto !important;
        min-height: var(--app-topbar-height-mobile, 3.5rem) !important;
        padding: 0 !important;
        gap: 0 !important;
    }

    .public-nav-burgerable__bar {
        width: 100%;
        box-sizing: border-box;
    }

    .public-nav-burgerable__bar > .nav-toggle {
        display: inline-flex;
    }

    .public-nav-burgerable__menu {
        display: none;
        flex-direction: column;
        align-items: stretch;
        gap: 0.75rem;
        width: 100%;
        padding: 0 12px 12px;
        box-sizing: border-box;
        border-top: 1px solid rgba(27, 94, 32, 0.1);
        background: rgba(255, 255, 255, 0.96);
    }

    .public-nav-burgerable__menu > ul {
        display: flex !important;
        flex-direction: column;
        align-items: stretch;
        gap: 0.25rem;
        width: 100%;
        list-style: none;
        margin: 0;
        padding: 0.5rem 0 0;
    }

    .public-nav-burgerable__menu > ul a {
        width: 100%;
        justify-content: flex-start;
    }

    .public-nav-burgerable__menu > .public-nav-burgerable__actions {
        display: flex;
        flex-direction: column;
        align-items: stretch;
        gap: 0.5rem;
        width: 100%;
    }

    .public-nav-burgerable__menu > .public-nav-burgerable__actions a {
        width: 100%;
        justify-content: center;
    }

    .public-nav-burgerable.nav-open .public-nav-burgerable__menu {
        display: flex;
    }

    .public-nav-burgerable.nav-open {
        box-shadow: 0 10px 25px rgba(27, 94, 32, 0.12);
    }
}

@media (min-width: 768px) {
    .public-nav-burgerable {
        flex-direction: row !important;
        align-items: center !important;
        justify-content: space-between !important;
        padding: 0.5rem clamp(14px, 2vw, 28px) !important;
        gap: 12px !important;
    }

    .public-nav-burgerable__bar {
        display: contents;
    }

    .public-nav-burgerable__bar > .nav-toggle {
        display: none !important;
    }

    .public-nav-burgerable__menu {
        display: contents;
    }

    .public-nav-burgerable__menu > ul {
        display: flex !important;
    }

    .public-nav-burgerable__menu > .public-nav-burgerable__actions {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.625rem;
    }
}

@include('partials.mobile-nav-unified-styles')
