{{-- Tribal pattern shell — shared by portal-public-nav (/explore) and app .navbar --}}
:root {
    --nav-tribal-pattern-opacity: 0.8;
    --nav-brand-soft-border: var(--brand-200, #CBDFC6);
    --nav-brand-shell-shadow: 0 2px 12px rgba(27, 94, 32, 0.08);
    --nav-heading-font: var(--app-font-display);
    --nav-heading-color: var(--brand-800, #34543F);
    --nav-heading-accent: var(--brand-800, #34543F);
}

.navbar:not(.portal-nav-minimal),
.public-nav-tribal:not(.portal-nav-minimal) {
    overflow: hidden;
}

.navbar.portal-nav-minimal.public-nav-tribal {
    overflow: visible;
}

/* Portal/public top bars use Tailwind `fixed` — do not override with relative */
nav.public-nav-tribal.fixed {
    position: fixed !important;
    top: 0;
    left: 0;
    right: 0;
    width: 100vw;
    max-width: 100vw;
    margin: 0;
    z-index: 1100;
    box-sizing: border-box;
}

.navbar {
    position: relative;
}

/* Portal / public explore nav — match app .navbar dimensions (size only) */
.public-nav-tribal:not(.portal-nav-minimal) {
    min-height: var(--app-topbar-height, 84px);
    height: auto;
    padding: 0.5rem clamp(14px, 2vw, 28px) !important;
    box-sizing: border-box;
    gap: 12px !important;
    align-items: center;
}

@media (min-width: 768px) {
    .public-nav-tribal {
        flex-direction: row !important;
        justify-content: space-between;
    }
}

@media (max-width: 767px) {
    .public-nav-tribal:not(.portal-nav-minimal) {
        min-height: var(--app-topbar-height-mobile, 72px);
        padding-left: 12px !important;
        padding-right: 12px !important;
    }
}

.navbar .navbar-tribal-accent,
.public-nav-tribal .navbar-tribal-accent {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 50%;
    right: auto;
    width: 100vw;
    max-width: 100vw;
    height: 100%;
    margin: 0;
    transform: translateX(-50%);
    overflow: hidden;
    pointer-events: none;
    z-index: 0;
}

.navbar .navbar-tribal-accent__canvas,
.public-nav-tribal .navbar-tribal-accent__canvas {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    transform: none;
    background-repeat: no-repeat;
    background-size: 100% 100%;
    background-position: center center;
    opacity: var(--nav-tribal-pattern-opacity, 0.85);
}

.navbar::after,
.public-nav-tribal::after {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    left: 50%;
    right: auto;
    width: 100vw;
    max-width: 100vw;
    transform: translateX(-50%);
    z-index: 1;
    pointer-events: none;
    background:
        linear-gradient(180deg, rgba(255, 255, 255, 0.38) 0%, rgba(255, 255, 255, 0.48) 50%, rgba(255, 255, 255, 0.52) 100%),
        rgba(15, 23, 42, 0.02);
}

.navbar > *:not(.navbar-tribal-accent),
.public-nav-tribal > *:not(.navbar-tribal-accent) {
    position: relative;
    z-index: 2;
}

.navbar .nav-logo,
.navbar .nav-brand-title,
.navbar .nav-brand-subtitle,
.public-nav-tribal .nav-logo,
.public-nav-tribal .nav-brand-title,
.public-nav-tribal .nav-brand-subtitle {
    text-shadow: 0 1px 0 rgba(255, 255, 255, 0.55);
}

html.dark .navbar::after,
html.dark .public-nav-tribal::after {
    background:
        linear-gradient(180deg, rgba(15, 23, 42, 0.72) 0%, rgba(15, 23, 42, 0.58) 50%, rgba(15, 23, 42, 0.68) 100%),
        rgba(0, 0, 0, 0.08);
}

html.dark .navbar .nav-logo,
html.dark .navbar .nav-brand-title,
html.dark .navbar .nav-brand-subtitle,
html.dark .public-nav-tribal .nav-logo,
html.dark .public-nav-tribal .nav-brand-title,
html.dark .public-nav-tribal .nav-brand-subtitle {
    text-shadow: 0 1px 0 rgba(0, 0, 0, 0.35);
}

/* Brand block typography (portal explore + app navbars) */
.nav-logo {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    text-decoration: none;
    flex-shrink: 0;
    min-width: 0;
}
@media (min-width: 768px) {
    .nav-logo { gap: 0.875rem; }
}
.nav-logo img {
    width: 3.5rem;
    height: 3.5rem;
    border-radius: 0.75rem;
    border: none;
    object-fit: contain;
    flex-shrink: 0;
}
.nav-logo img.nav-logo__custom,
.tenant-brand-logo {
    background: transparent;
    border-radius: 0;
}
@media (min-width: 768px) {
    .nav-logo img { width: 3.75rem; height: 3.75rem; }
}
@media (min-width: 1024px) {
    .nav-logo img { width: 4rem; height: 4rem; }
}
/* Match portal-public-nav: leading-tight stack + leading-none subtitle (no gap between lines) */
.nav-brand-text {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: center;
    gap: 0;
    line-height: 1.25;
    min-width: 0;
}
.nav-brand-title {
    display: block;
    margin: 0;
    padding: 0;
    font-family: var(--app-font-display);
    font-size: 1rem;
    font-weight: 800;
    color: var(--nav-heading-color, #34543F);
    line-height: 1.25;
    letter-spacing: -0.025em;
}
.nav-brand-subtitle {
    display: block;
    margin: 0;
    padding: 0;
    font-family: var(--app-font-sans);
    font-size: 0.68rem;
    font-weight: 600;
    color: var(--nav-heading-accent, #34543F);
    opacity: 1;
    line-height: 1;
    letter-spacing: 0.01em;
    font-variant-numeric: tabular-nums;
}
@media (min-width: 768px) {
    .nav-brand-title,
    .nav-logo-title,
    .nav-logo span:not(.nav-brand-subtitle) { font-size: 1.125rem; }
    .nav-brand-subtitle,
    .nav-logo-subtitle,
    .nav-logo span small { font-size: 0.75rem; }
}

/* Legacy aliases */
.nav-logo-text {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: center;
    gap: 0;
    line-height: 1.25;
    min-width: 0;
}
.nav-logo-title,
.nav-logo-subtitle {
    margin: 0;
    padding: 0;
    font-family: var(--nav-heading-font);
    font-weight: 800;
    color: var(--nav-heading-color);
    letter-spacing: -0.025em;
}
.nav-logo-title { line-height: 1.25; }
.nav-logo-subtitle {
    font-weight: 500;
    color: var(--nav-heading-accent);
    line-height: 1;
    letter-spacing: normal;
}
