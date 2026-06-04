{{-- Mobile burger menu for portal-nav-minimal (explore, landing, about) --}}
<style>
    .portal-nav-minimal__toggle {
        display: none;
    }

    @media (max-width: 767px) {
        .portal-nav-minimal.portal-nav-minimal--burger {
            --portal-nav-header-height: var(--app-topbar-height, 4.5rem);
        }

        .portal-nav-minimal.portal-nav-minimal--burger .portal-nav-minimal__inner {
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 0.375rem 0.5rem;
        }

        .portal-nav-minimal.portal-nav-minimal--burger .portal-nav-minimal__inner > .portal-nav-minimal__actions--header-desktop {
            display: none !important;
        }

        .portal-nav-minimal.portal-nav-minimal--burger .portal-nav-minimal__toggle {
            display: inline-flex;
            grid-column: 2;
            grid-row: 1;
            position: relative;
            align-self: center;
            justify-self: end;
            align-items: center;
            justify-content: center;
            width: 2.75rem;
            height: 2.75rem;
            min-width: 2.75rem;
            min-height: 2.75rem;
            padding: 0;
            margin: 0 clamp(0.75rem, 3vw, 1rem) 0 0;
            border: 1px solid rgba(211, 120, 151, 0.45);
            border-radius: 0.625rem;
            background: rgba(255, 255, 255, 0.55);
            color: var(--accent-pink-strong, #d37897);
            cursor: pointer;
            box-sizing: border-box;
            flex-shrink: 0;
            z-index: 5;
            pointer-events: auto;
            touch-action: manipulation;
            -webkit-tap-highlight-color: transparent;
        }

        .portal-nav-minimal.portal-nav-minimal--burger .portal-nav-minimal__toggle:focus-visible {
            outline: 2px solid var(--brand-primary, #2e7d32);
            outline-offset: 2px;
        }

        .portal-nav-minimal.portal-nav-minimal--burger .portal-nav-minimal__action--host-desktop {
            display: none !important;
        }

        .portal-nav-minimal.portal-nav-minimal--burger .portal-nav-minimal__action--host-mobile + .portal-nav-minimal__item--auth-mobile {
            margin-top: 0.25rem;
            padding-top: 0.35rem;
            border-top: 1px solid rgba(27, 94, 32, 0.12);
        }

        .portal-nav-minimal.portal-nav-minimal--burger .portal-nav-minimal__link--signup {
            color: #fff;
            font-weight: 600;
            background: var(--action-primary-bg, var(--brand-700, #457359));
            box-shadow: 0 1px 2px color-mix(in srgb, var(--action-primary-bg, #457359) 25%, transparent);
        }

        .portal-nav-minimal.portal-nav-minimal--burger .portal-nav-minimal__link--signup:hover {
            background: var(--action-primary-hover, var(--brand-800, #34543f));
        }

        .portal-nav-minimal.portal-nav-minimal--burger > .navbar-tribal-accent,
        .portal-nav-minimal.portal-nav-minimal--burger::after {
            height: var(--portal-nav-header-height) !important;
            max-height: var(--portal-nav-header-height) !important;
            bottom: auto !important;
        }

        .portal-nav-minimal.portal-nav-minimal--burger .portal-nav-minimal__mobile-links {
            display: none;
            flex-direction: column;
            align-items: stretch;
            gap: 0.25rem;
            padding: 0.35rem clamp(0.75rem, 3vw, 1rem) 0.65rem;
            margin: 0;
            max-height: min(52vh, 22rem);
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            border-top: 1px solid rgba(27, 94, 32, 0.1);
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            position: relative;
            z-index: 5;
        }

        .portal-nav-minimal.portal-nav-minimal--burger .portal-nav-minimal__mobile-links .portal-nav-minimal__link {
            flex: none;
            justify-content: flex-start;
            width: 100%;
            min-height: 44px;
            padding: 0.5rem 0.75rem;
        }

        .portal-nav-minimal.portal-nav-minimal--burger.nav-open {
            overflow: visible !important;
        }

        .portal-nav-minimal.portal-nav-minimal--burger.nav-open .portal-nav-minimal__mobile-links {
            display: flex;
        }

        .portal-nav-minimal.portal-nav-minimal--burger.nav-open > .navbar-tribal-accent,
        .portal-nav-minimal.portal-nav-minimal--burger.nav-open::after {
            height: var(--portal-nav-header-height) !important;
            max-height: var(--portal-nav-header-height) !important;
        }
    }

    @media (min-width: 768px) {
        .portal-nav-minimal.portal-nav-minimal--burger .portal-nav-minimal__inner > .portal-nav-minimal__actions--header-desktop {
            display: flex !important;
        }

        .portal-nav-minimal.portal-nav-minimal--burger .portal-nav-minimal__action--host-desktop {
            display: inline-flex !important;
        }

        .portal-nav-minimal.portal-nav-minimal--burger .portal-nav-minimal__action--host-mobile,
        .portal-nav-minimal.portal-nav-minimal--burger .portal-nav-minimal__item--auth-mobile {
            display: none !important;
        }
    }
</style>
