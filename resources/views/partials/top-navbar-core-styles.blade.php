@include('partials.navbar-tribal-shell-styles')

.navbar {
    position: fixed !important;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
    width: 100%;
    display: grid;
    grid-template-columns: auto 1fr auto;
    align-items: center;
    gap: 12px;
    overflow: visible;
    font-family: var(--app-font-sans);
    font-feature-settings: 'cv02', 'cv03', 'cv04', 'cv11';
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

.navbar:not(.portal-nav-minimal) {
    min-height: var(--app-topbar-height, 84px);
    height: auto;
    padding: 0.5rem clamp(14px, 2vw, 28px);
    background: var(--nav-bar-bg, rgba(255, 255, 255, 0.95));
    border-bottom: none;
    box-shadow: var(--nav-brand-shell-shadow, 0 2px 12px rgba(27, 94, 32, 0.08));
    backdrop-filter: blur(14px) saturate(1.15);
    -webkit-backdrop-filter: blur(14px) saturate(1.15);
    backface-visibility: hidden;
}

.navbar > *:not(.navbar-tribal-accent) {
    z-index: 10;
}

.navbar > .nav-logo {
    flex-shrink: 0;
    min-width: 0;
    max-width: min(100%, 42vw);
    overflow: visible;
}

.navbar > .nav-logo .nav-brand-text {
    flex: 1 1 auto;
    min-width: 0;
    max-width: none;
}

/* Title always full — never ellipsis; only long subdomain line may truncate */
.navbar > .nav-logo .nav-brand-title {
    white-space: nowrap;
    overflow: visible;
    text-overflow: clip;
    max-width: none;
    flex-shrink: 0;
}

.navbar > .nav-logo .nav-brand-subtitle {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 100%;
}

.nav-logo-title,
.nav-logo-subtitle {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 100%;
}

.nav-links {
    display: flex;
    gap: var(--nav-link-gap, 0.375rem);
    list-style: none;
    min-width: 0;
    margin: 0;
    padding: 0;
}
.nav-links li { display: flex; align-items: center; }

/* Desktop: equal side tracks so link cluster is visually centered on the bar */
@media (min-width: 961px) {
    .navbar {
        /* Side tracks share leftover space equally; center column fits nav pills */
        grid-template-columns: minmax(0, 1fr) auto minmax(0, 1fr);
        gap: clamp(10px, 1.5vw, 18px);
        padding: 0 clamp(12px, 2vw, 28px);
    }

    .navbar > .nav-logo {
        grid-column: 1;
        justify-self: start;
        align-self: center;
        max-width: none;
    }

    .navbar > .nav-logo .nav-brand-text {
        max-width: min(22rem, 38vw);
    }

    .navbar > .nav-toggle {
        display: none;
    }

    .navbar > .nav-links {
        grid-column: 2;
        justify-self: center;
        justify-content: center;
        width: max-content;
        max-width: 100%;
        flex-wrap: nowrap;
    }

    .navbar > .nav-actions {
        grid-column: 3;
        justify-self: end;
        align-self: center;
        max-width: min(280px, 32vw);
    }
}

/* Many nav items: compact pills only — brand title stays full width */
@media (min-width: 961px) {
    .navbar:has(.nav-links li:nth-child(6)) {
        grid-template-columns: minmax(12.5rem, 1fr) auto minmax(12rem, 1fr);
    }
}

@media (min-width: 961px) and (max-width: 1400px) {
    .navbar > .nav-links {
        overflow-x: auto;
        overflow-y: hidden;
        scrollbar-width: none;
        -ms-overflow-style: none;
        -webkit-overflow-scrolling: touch;
    }

    .navbar > .nav-links::-webkit-scrollbar {
        display: none;
    }
}
/* Nav links — premium SaaS pills (theme-aware) */
.navbar > .nav-links a {
    font-family: var(--app-font-sans);
    font-size: var(--nav-link-font-size, 0.8125rem);
    font-weight: var(--nav-link-font-weight, 500);
    letter-spacing: -0.011em;
    line-height: 1.2;
    text-decoration: none;
    color: var(--nav-link-idle-color, var(--nav-heading-color, #34543F));
    padding: var(--nav-link-padding-y, 0.4375rem) var(--nav-link-padding-x, 0.6875rem);
    border-radius: var(--radius-lg, 0.75rem);
    border: 1px solid var(--nav-link-idle-border, rgba(15, 23, 42, 0.08));
    background: var(--nav-link-idle-bg, rgba(255, 255, 255, 0.72));
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    min-height: var(--nav-link-min-height, 2.375rem);
    box-sizing: border-box;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
    transition:
        background-color 0.2s cubic-bezier(0.4, 0, 0.2, 1),
        color 0.2s cubic-bezier(0.4, 0, 0.2, 1),
        border-color 0.2s cubic-bezier(0.4, 0, 0.2, 1),
        box-shadow 0.2s cubic-bezier(0.4, 0, 0.2, 1),
        transform 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    display: inline-flex;
    align-items: center;
    gap: 0.4375rem;
    white-space: nowrap;
    text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);
}

.navbar > .nav-links a i {
    font-size: 0.75rem;
    width: 1em;
    text-align: center;
    opacity: 0.82;
    flex-shrink: 0;
}

.navbar > .nav-links a:hover {
    background: color-mix(in srgb, var(--brand-50, #f4f8f1) 88%, #fff);
    color: var(--brand-800, #34543f);
    border-color: color-mix(in srgb, var(--brand-300, #a8c4a2) 55%, transparent);
    box-shadow: 0 2px 6px rgba(15, 23, 42, 0.06);
    text-shadow: none;
}

.navbar > .nav-links a.active {
    font-weight: var(--nav-link-font-weight-active, 600);
    background: var(--chrome-active-bg, var(--brand-700, #457359));
    color: #fff;
    border-color: var(--chrome-active-border, rgba(69, 115, 89, 0.45));
    box-shadow: 0 2px 12px color-mix(in srgb, var(--chrome-active-bg, #457359) 38%, transparent);
    text-shadow: none;
}

.navbar > .nav-links a:focus-visible {
    outline: 2px solid var(--chrome-focus-ring, var(--brand-700, #457359));
    outline-offset: 2px;
}

html.dark .navbar > .nav-links a {
    background: var(--nav-link-idle-bg, rgba(30, 41, 59, 0.72));
    border-color: var(--nav-link-idle-border, rgba(148, 163, 184, 0.22));
    color: var(--ink-700, #cbd5e1);
    text-shadow: none;
}

html.dark .navbar > .nav-links a:hover,
html.dark .navbar > .nav-links a.active {
    background: var(--chrome-active-bg, var(--brand-600, #56856A));
    color: #fff;
    border-color: var(--chrome-active-border, rgba(86, 133, 106, 0.5));
}

@media (prefers-reduced-motion: reduce) {
    .navbar > .nav-links a,
    .nav-btn {
        transition-duration: 0.01ms !important;
    }
}

/* Dense nav (6+ links): tighter pills; subtitle truncates, title does not */
@media (min-width: 961px) {
    .navbar:has(.nav-links li:nth-child(6)) > .nav-links {
        gap: 0.25rem;
    }

    .navbar:has(.nav-links li:nth-child(6)) > .nav-links a {
        font-size: 0.75rem;
        padding: 0.375rem 0.5rem;
        min-height: 2.125rem;
        gap: 0.25rem;
    }

    .navbar:has(.nav-links li:nth-child(6)) > .nav-logo .nav-brand-text {
        max-width: min(20rem, 36vw);
    }

    .navbar:has(.nav-links li:nth-child(6)) .user-display {
        max-width: min(220px, 26vw);
    }

    .navbar:has(.nav-links li:nth-child(6)) .user-name {
        max-width: 9rem;
    }
}

.nav-actions {
    display: flex;
    gap: 0.625rem;
    align-items: center;
    justify-self: end;
    flex-shrink: 0;
}

/* Keep right-side controls from causing center-nav shifts */
.nav-actions > * {
    flex-shrink: 0;
}
.user-display {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.3125rem 0.5rem 0.3125rem 0.3125rem;
    background: var(--nav-link-idle-bg, rgba(255, 255, 255, 0.78));
    border-radius: var(--radius-xl, 1rem);
    border: 1px solid var(--nav-link-idle-border, rgba(15, 23, 42, 0.08));
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
    max-width: min(240px, 26vw);
    min-width: 0;
    font-family: var(--app-font-sans);
    transition: border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
}

.user-display:hover {
    border-color: color-mix(in srgb, var(--brand-300, #a8c4a2) 45%, transparent);
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06);
}
.user-menu {
    position: relative;
    display: flex;
    align-items: center;
}
.user-menu__button {
    cursor: pointer;
}
.user-menu__chevron {
    margin-left: 2px;
    color: var(--nav-brand-muted, var(--chrome-icon-color));
    font-size: 0.78rem;
    flex-shrink: 0;
}
.user-menu__panel {
    position: absolute;
    right: 0;
    top: calc(100% + 10px);
    z-index: 50;
    width: max-content;
    min-width: 220px;
    padding: 8px;
    border-radius: var(--radius-xl, 14px);
    border: 1px solid var(--app-surface-border, rgba(226, 232, 240, 0.95));
    background: var(--app-surface-bg, rgba(255, 255, 255, 0.95));
    box-shadow: var(--shadow-md, 0 12px 30px -24px rgba(15, 23, 42, 0.28));
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    display: none;
}
.user-menu.is-open .user-menu__panel {
    display: block;
}
.user-menu__item {
    width: 100%;
    text-decoration: none;
    border: 1px solid transparent;
    background: transparent;
    color: var(--ink-700, #334155);
    font-weight: 700;
    font-size: 0.85rem;
    border-radius: 10px;
    padding: 10px 10px;
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    line-height: 1.1;
}
.user-menu__item i { width: 18px; text-align: center; opacity: 0.9; }
.user-menu__item:hover {
    background: rgba(15, 23, 42, 0.04);
    border-color: rgba(226, 232, 240, 0.95);
    color: var(--ink-900, #0F172A);
}
.user-menu__sep {
    height: 1px;
    margin: 6px 2px;
    background: rgba(226, 232, 240, 0.95);
}
.user-menu__item--danger {
    color: var(--status-danger, #B91C1C);
}
.user-menu__item--danger:hover {
    background: rgba(185, 28, 28, 0.06);
    border-color: rgba(185, 28, 28, 0.18);
}
.user-menu__form {
    margin: 0;
}
.user-menu__button:focus-visible,
.user-menu__panel a:focus-visible,
.user-menu__panel button:focus-visible {
    outline: 2px solid var(--chrome-focus-ring, var(--green-primary));
    outline-offset: 2px;
}
.user-avatar {
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    background: var(--chrome-avatar-bg, var(--brand-700, #457359));
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.75rem;
    letter-spacing: -0.02em;
    flex-shrink: 0;
}
.user-info {
    text-align: left;
    min-width: 0;
    line-height: 1.2;
}
.user-name {
    font-weight: 600;
    color: var(--ink-800, #1e293b);
    font-size: 0.8125rem;
    letter-spacing: -0.02em;
    line-height: 1.25;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 11.5rem;
}
.user-role {
    font-size: 0.625rem;
    font-weight: 600;
    color: var(--ink-500, #64748b);
    text-transform: uppercase;
    letter-spacing: 0.08em;
    line-height: 1.2;
    margin-top: 0.0625rem;
}

/* Notification bell aligned with nav chrome */
.navbar .imp-notify-wrap {
    margin-right: 0;
}

.navbar .imp-notify-btn {
    width: var(--nav-link-min-height, 2.375rem);
    height: var(--nav-link-min-height, 2.375rem);
    border-radius: var(--radius-lg, 0.75rem);
    border: 1px solid var(--nav-link-idle-border, rgba(15, 23, 42, 0.08));
    background: var(--nav-link-idle-bg, rgba(255, 255, 255, 0.72));
    color: var(--nav-heading-color, var(--brand-800, #34543f));
    font-size: 0.875rem;
    transition: background-color 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
}

.navbar .imp-notify-btn:hover {
    background: color-mix(in srgb, var(--brand-50, #f4f8f1) 88%, #fff);
    border-color: color-mix(in srgb, var(--brand-300, #a8c4a2) 55%, transparent);
}

.navbar .nav-msg-count-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 1.125rem;
    height: 1.125rem;
    border-radius: 999px;
    padding: 0 0.3125rem;
    margin-left: 0.25rem;
    font-size: 0.625rem;
    font-weight: 700;
    letter-spacing: 0;
    box-sizing: border-box;
    vertical-align: middle;
}

.navbar .nav-msg-count-badge.is-empty {
    visibility: hidden;
    pointer-events: none;
}

.navbar .nav-msg-count-badge:not(.is-empty) {
    background: #ef4444;
    color: #fff;
}

html.dark .user-display {
    background: var(--nav-link-idle-bg, rgba(30, 41, 59, 0.78));
    border-color: var(--nav-link-idle-border, rgba(148, 163, 184, 0.22));
}

html.dark .user-name {
    color: var(--ink-800, #e2e8f0);
}

html.dark .user-role {
    color: var(--ink-500, #94a3b8);
}

html.dark .navbar .imp-notify-btn {
    background: var(--nav-link-idle-bg);
    border-color: var(--nav-link-idle-border);
    color: var(--ink-700, #cbd5e1);
}

.nav-btn {
    padding: 8px 12px;
    border-radius: 8px;
    font-weight: 700;
    font-size: 0.72rem;
    text-decoration: none;
    transition: background-color 0.2s ease, color 0.2s ease, box-shadow 0.2s ease;
    cursor: pointer;
    border: none;
    display: flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
}
.nav-btn.primary {
    background: var(--chrome-active-bg, var(--green-dark));
    color: var(--white);
    border: 1px solid var(--chrome-active-border, rgba(27, 94, 32, 0.4));
    transition: background-color 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
}
.nav-btn.primary:hover {
    background: var(--chrome-focus-ring, var(--green-primary));
    box-shadow: 0 4px 14px color-mix(in srgb, var(--chrome-active-bg, #457359) 35%, transparent);
}

.nav-toggle {
    display: none;
    background: transparent;
    border: 1px solid var(--chrome-surface-border, var(--green-soft));
    color: var(--nav-brand-color, var(--green-dark));
    width: 40px;
    height: 40px;
    border-radius: 10px;
    cursor: pointer;
    align-items: center;
    justify-content: center;
    font-size: 1.05rem;
}
.nav-toggle:focus-visible { outline: 2px solid var(--chrome-focus-ring, var(--green-primary)); outline-offset: 2px; }

@media (max-width: 960px) {
    .navbar {
        grid-template-columns: minmax(0, 1fr) auto;
        padding: 0 14px;
    }

    .navbar > .nav-logo {
        grid-column: 1;
        justify-self: start;
        min-width: 0;
    }

    .nav-toggle {
        display: inline-flex;
        grid-column: 2;
        justify-self: end;
        align-self: center;
    }
    .nav-links {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: var(--app-surface-bg, var(--white));
        flex-direction: column;
        align-items: stretch;
        padding: 12px 14px;
        gap: 6px;
        box-shadow: var(--shadow-md, 0 10px 25px rgba(27, 94, 32, 0.12));
        border-top: 1px solid var(--app-surface-border, var(--green-soft));
        max-height: calc(100vh - 64px);
        overflow-y: auto;
    }
    .navbar > .nav-links a { width: 100%; text-shadow: none; }
    #appNavbar.nav-open .nav-links { display: flex; }
    .nav-actions { display: none; }
    #appNavbar.nav-open .nav-actions {
        display: flex;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        padding: 0 14px 14px;
        background: var(--app-surface-bg, var(--white));
        flex-wrap: wrap;
        gap: 10px;
        box-shadow: var(--shadow-md, 0 10px 25px rgba(27, 94, 32, 0.12));
        transform: translateY(calc(100% + 1px));
    }
}

@media (max-width: 768px) {
    .navbar:not(.portal-nav-minimal) { padding: 0 12px; height: var(--app-topbar-height-mobile, 64px); }
    .navbar.portal-nav-minimal { padding-left: 1rem !important; padding-right: 1rem !important; }
    .user-display { max-width: 170px; }
}
