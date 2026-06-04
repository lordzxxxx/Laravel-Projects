{{-- Unified mobile top navigation — burger bar alignment across app, portal minimal, and public navs. --}}
@media (max-width: 768px) {
    :is(.navbar, nav.public-nav-tribal, nav.portal-nav-minimal) {
        box-sizing: border-box;
        width: 100%;
        max-width: 100%;
    }

    .nav-logo {
        display: inline-flex;
        align-items: center;
        align-self: center;
        gap: 0.5rem;
        min-width: 0;
        max-width: 100%;
        text-decoration: none;
        line-height: 1.2;
    }

    .nav-logo img {
        width: 2.25rem;
        height: 2.25rem;
        flex-shrink: 0;
    }

    .nav-logo .nav-brand-text {
        min-width: 0;
        flex: 1 1 auto;
        overflow: hidden;
    }

    .nav-logo .nav-brand-title {
        display: block;
        font-size: 0.8125rem;
        line-height: 1.2;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
    }

    .nav-logo .nav-brand-subtitle {
        display: block;
        font-size: 0.5625rem;
        line-height: 1.15;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
    }

    .nav-toggle {
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        align-self: center;
        justify-self: end;
        flex-shrink: 0;
        margin: 0;
        padding: 0;
    }

    /* App shell (.navbar) — logo + burger row, drawer below */
    .navbar {
        grid-template-columns: minmax(0, 1fr) auto !important;
        align-items: center;
        column-gap: 0.75rem;
        row-gap: 0;
        padding-inline: clamp(1rem, 4vw, 1.25rem) !important;
        padding-block: 0 !important;
        box-sizing: border-box;
    }

    /* Collapsed: single fixed-height row — logo and burger vertically centered */
    .navbar:not(.nav-open) {
        grid-template-rows: minmax(0, 1fr) !important;
        height: var(--app-topbar-height-mobile, 3.5rem) !important;
        min-height: var(--app-topbar-height-mobile, 3.5rem) !important;
        max-height: var(--app-topbar-height-mobile, 3.5rem) !important;
        align-content: stretch !important;
    }

    .navbar.nav-open {
        height: auto !important;
        max-height: none !important;
        min-height: var(--app-topbar-height-mobile, 3.5rem) !important;
        grid-template-rows: var(--app-topbar-height-mobile, 3.5rem) auto auto !important;
        align-content: start !important;
        padding-bottom: 0.5rem !important;
    }

    .navbar > .nav-logo {
        grid-column: 1;
        grid-row: 1;
        justify-self: start;
        align-self: center;
        align-items: center;
        max-width: min(100%, calc(100% - 3.25rem));
        height: auto;
        margin: 0;
    }

    .navbar > .nav-toggle {
        grid-column: 2;
        grid-row: 1;
        justify-self: end;
        align-self: center;
    }

    .navbar > .nav-links,
    .navbar > .nav-actions {
        grid-column: 1 / -1;
        width: 100%;
        justify-self: stretch;
    }

    .navbar > .nav-links {
        list-style: none;
        margin: 0;
        padding: 0.5rem 0 0.25rem;
    }

    .navbar > .nav-links li {
        display: block;
        width: 100%;
    }

    .navbar > .nav-links a {
        display: flex;
        align-items: center;
        width: 100%;
        box-sizing: border-box;
    }

    .navbar > .nav-actions {
        padding: 0.25rem 0 0.625rem;
        justify-content: flex-start;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .navbar.nav-open > .nav-links {
        border-top: 1px solid var(--app-surface-border, rgba(27, 94, 32, 0.12));
        margin-top: 0.25rem;
    }

    .navbar.nav-open > .nav-actions {
        border-top: 1px solid var(--app-surface-border, rgba(27, 94, 32, 0.08));
    }

    /* Portal minimal (explore / about / tenant) — inner grid */
    nav.portal-nav-minimal:not(.navbar) {
        display: block !important;
    }

    nav.portal-nav-minimal:not(.navbar) .portal-nav-minimal__inner {
        display: grid !important;
        grid-template-columns: minmax(0, 1fr) auto;
        align-items: center;
        column-gap: 0.75rem;
        row-gap: 0;
        padding-inline: clamp(1rem, 4vw, 1.25rem);
        padding-block: 0;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }

    nav.portal-nav-minimal:not(.navbar):not(.nav-open) .portal-nav-minimal__inner {
        grid-template-rows: minmax(0, 1fr) !important;
        height: var(--app-topbar-height-mobile, 3.5rem);
        min-height: var(--app-topbar-height-mobile, 3.5rem);
        max-height: var(--app-topbar-height-mobile, 3.5rem);
        align-content: stretch;
    }

    nav.portal-nav-minimal:not(.navbar).nav-open .portal-nav-minimal__inner {
        height: auto;
        max-height: none;
        min-height: var(--app-topbar-height-mobile, 3.5rem);
        grid-template-rows: var(--app-topbar-height-mobile, 3.5rem) auto auto;
        padding-bottom: 0.5rem;
    }

    nav.portal-nav-minimal:not(.navbar) .portal-nav-minimal__inner > .nav-logo {
        grid-column: 1;
        grid-row: 1;
        justify-self: start;
        align-self: center;
        align-items: center;
        max-width: min(100%, calc(100% - 3.25rem));
    }

    nav.portal-nav-minimal:not(.navbar) .portal-nav-minimal__inner > .nav-toggle {
        grid-column: 2;
        grid-row: 1;
        justify-self: end;
        align-self: center;
    }

    nav.portal-nav-minimal:not(.navbar) .portal-nav-minimal__links {
        list-style: none;
        margin: 0;
        padding: 0.5rem 0 0.25rem;
    }

    nav.portal-nav-minimal:not(.navbar) .portal-nav-minimal__links li {
        width: 100%;
    }

    nav.portal-nav-minimal:not(.navbar) .portal-nav-minimal__link {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        width: 100%;
        box-sizing: border-box;
    }

    nav.portal-nav-minimal:not(.navbar) .portal-nav-minimal__actions {
        flex-direction: column;
        align-items: stretch;
        gap: 0.375rem;
        padding-bottom: 0.625rem;
    }

    nav.portal-nav-minimal:not(.navbar) .portal-nav-minimal__actions .portal-nav-minimal__action {
        justify-content: center;
        width: 100%;
        box-sizing: border-box;
    }

    nav.portal-nav-minimal:not(.navbar).nav-open .portal-nav-minimal__inner > .portal-nav-minimal__links,
    nav.portal-nav-minimal:not(.navbar).nav-open .portal-nav-minimal__inner > .portal-nav-minimal__actions {
        grid-column: 1 / -1;
    }

    /* Public burger nav (default portal layout) */
    .public-nav-burgerable__bar {
        display: grid !important;
        grid-template-columns: minmax(0, 1fr) auto;
        grid-template-rows: minmax(0, 1fr);
        align-items: center;
        align-content: stretch;
        column-gap: 0.75rem;
        height: var(--app-topbar-height-mobile, 3.5rem);
        min-height: var(--app-topbar-height-mobile, 3.5rem);
        max-height: var(--app-topbar-height-mobile, 3.5rem);
        padding-inline: clamp(1rem, 4vw, 1.25rem) !important;
        padding-block: 0 !important;
        box-sizing: border-box;
    }

    .public-nav-burgerable__bar > .nav-logo {
        grid-column: 1;
        grid-row: 1;
        justify-self: start;
        align-self: center;
        align-items: center;
        min-width: 0;
        max-width: min(100%, calc(100% - 3.25rem));
    }

    .public-nav-burgerable__bar > .nav-toggle {
        grid-column: 2;
        grid-row: 1;
        justify-self: end;
        align-self: center;
    }

    .public-nav-burgerable:not(.nav-open) {
        min-height: var(--app-topbar-height-mobile, 3.5rem) !important;
    }

    .public-nav-burgerable__menu > ul a,
    .public-nav-burgerable__menu > .public-nav-burgerable__actions a {
        box-sizing: border-box;
    }
}

@media (max-width: 480px) {
    .nav-logo img {
        width: 2rem;
        height: 2rem;
    }

    .nav-logo .nav-brand-title {
        font-size: 0.75rem;
    }

    .navbar > .nav-logo,
    nav.portal-nav-minimal:not(.navbar) .portal-nav-minimal__inner > .nav-logo,
    .public-nav-burgerable__bar > .nav-logo {
        max-width: min(100%, calc(100% - 3rem));
    }
}
