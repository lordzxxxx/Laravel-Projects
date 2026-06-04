{{-- Premium SaaS typography — keep in sync with resources/css/typography.css --}}
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@600;700&display=swap');

:root {
    --font-sans: var(--app-font-sans);
    --app-font-sans: 'Inter', ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Roboto, Arial, 'Noto Sans', sans-serif;
    --app-font-display: 'Space Grotesk', var(--app-font-sans);
    --client-nav-font: var(--app-font-sans);

    --text-fluid-xs: clamp(0.6875rem, 0.65rem + 0.15vw, 0.75rem);
    --text-fluid-sm: clamp(0.75rem, 0.7rem + 0.2vw, 0.8125rem);
    --text-fluid-base: clamp(0.875rem, 0.85rem + 0.25vw, 1rem);
    --text-fluid-lg: clamp(1rem, 0.95rem + 0.35vw, 1.125rem);
    --text-fluid-xl: clamp(1.125rem, 1.05rem + 0.5vw, 1.375rem);
    --text-fluid-2xl: clamp(1.25rem, 1.15rem + 0.75vw, 1.625rem);
    --text-fluid-3xl: clamp(1.5rem, 1.35rem + 1vw, 2rem);
    --app-table-font: clamp(0.75rem, 0.7rem + 0.15vw, 0.875rem);
    --app-table-header-font: clamp(0.6875rem, 0.65rem + 0.12vw, 0.8125rem);
    --app-table-pad-y: clamp(0.5rem, 1.2vw, 0.9375rem);
    --app-table-pad-x: clamp(0.5rem, 1.5vw, 1rem);
    --app-card-pad: clamp(0.65rem, 1.2vw, 0.85rem);
    --app-card-gap: clamp(0.25rem, 0.8vw, 0.5rem);
    --app-card-radius: 0.75rem;
    --app-card-title: clamp(0.8125rem, 0.78rem + 0.2vw, 0.9375rem);
    --app-card-body: var(--text-fluid-sm);
    --app-card-meta: var(--text-fluid-xs);
    --app-card-price: clamp(0.8125rem, 0.78rem + 0.25vw, 0.9375rem);
    --app-card-media-ratio: 4 / 3;
    --app-card-media-max-height: none;
    --app-page-pad-inline: clamp(0.875rem, 2.5vw, 2rem);
    --text-xs: var(--text-fluid-xs);
    --text-sm: var(--text-fluid-sm);
    --text-base: var(--text-fluid-base);
    --text-md: var(--text-fluid-lg);
    --text-lg: var(--text-fluid-xl);
    --text-xl: var(--text-fluid-2xl);
    --text-2xl: var(--text-fluid-2xl);
    --text-3xl: var(--text-fluid-3xl);
    --app-content-max: 80rem;
    --app-content-max-wide: 96rem;
    --stay-max: var(--app-content-max);

    --leading-tight: 1.25;
    --leading-normal: 1.55;
    --leading-relaxed: 1.65;

    --tracking-tight: -0.015em;
    --tracking-normal: 0;
    --tracking-wide: 0.04em;
    --tracking-wider: 0.08em;
}

@media (max-width: 768px) {
    :root {
        --text-fluid-xs: clamp(0.625rem, 0.6rem + 0.12vw, 0.6875rem);
        --text-fluid-sm: clamp(0.6875rem, 0.66rem + 0.15vw, 0.75rem);
        --text-fluid-base: clamp(0.75rem, 0.72rem + 0.18vw, 0.8125rem);
        --text-fluid-lg: clamp(0.8125rem, 0.78rem + 0.22vw, 0.9375rem);
        --text-fluid-xl: clamp(0.875rem, 0.82rem + 0.3vw, 1rem);
        --text-fluid-2xl: clamp(0.9375rem, 0.88rem + 0.4vw, 1.125rem);
        --text-fluid-3xl: clamp(1rem, 0.92rem + 0.5vw, 1.25rem);
        --app-table-font: clamp(0.625rem, 0.6rem + 0.1vw, 0.6875rem);
        --app-table-header-font: 0.625rem;
        --app-table-pad-y: 0.375rem;
        --app-table-pad-x: 0.5rem;
        --app-card-pad: 0.5rem 0.6rem 0.65rem;
        --app-card-gap: 0.3rem;
        --app-card-title: 0.75rem;
        --app-card-body: var(--text-fluid-xs);
        --app-card-meta: 0.625rem;
        --app-card-price: 0.8125rem;
        --app-card-media-ratio: 16 / 10;
        --app-card-media-max-height: 9.5rem;
        --app-page-pad-inline: clamp(0.75rem, 2.5vw, 1rem);
    }
}

@media (max-width: 480px) {
    :root {
        --text-fluid-xs: 0.625rem;
        --text-fluid-sm: 0.6875rem;
        --text-fluid-base: 0.75rem;
        --text-fluid-lg: 0.8125rem;
        --text-fluid-xl: 0.875rem;
        --text-fluid-2xl: 0.9375rem;
        --text-fluid-3xl: 1rem;
        --app-table-font: 0.625rem;
        --app-table-header-font: 0.625rem;
        --app-table-pad-y: 0.3125rem;
        --app-table-pad-x: 0.4375rem;
        --app-card-pad: 0.4375rem 0.5rem 0.5625rem;
        --app-card-gap: 0.25rem;
        --app-card-title: 0.6875rem;
        --app-card-meta: 0.5625rem;
        --app-card-price: 0.75rem;
        --app-card-media-ratio: 2 / 1;
        --app-card-media-max-height: 8.5rem;
        --app-page-pad-inline: 0.75rem;
    }
}

@media (min-width: 1920px) {
    :root {
        --text-fluid-base: clamp(0.875rem, 0.5rem + 0.2vw, 1rem);
        --text-fluid-2xl: clamp(1.25rem, 0.8rem + 0.5vw, 1.5rem);
        --text-fluid-3xl: clamp(1.375rem, 0.9rem + 0.6vw, 1.75rem);
        --app-table-font: clamp(0.8125rem, 0.5rem + 0.12vw, 0.9375rem);
        --app-card-title: clamp(0.875rem, 0.5rem + 0.2vw, 1rem);
        --app-card-price: clamp(0.875rem, 0.5rem + 0.22vw, 1rem);
    }
}

html {
    font-family: var(--app-font-sans);
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-rendering: optimizeLegibility;
}

body {
    font-family: inherit;
    font-size: var(--text-fluid-base, var(--text-base));
    line-height: var(--leading-normal);
    letter-spacing: var(--tracking-normal);
}

:where(h1, .ui-heading, .nav-brand-title, .nav-logo-title) {
    font-family: var(--app-font-display);
    font-weight: 700;
    letter-spacing: var(--tracking-tight);
    line-height: var(--leading-tight);
}

:where(h2, h3, h4, h5, h6) {
    font-family: var(--app-font-sans);
    font-weight: 600;
    letter-spacing: var(--tracking-normal);
    line-height: var(--leading-tight);
}

:where(button, input, textarea, select, optgroup, label) {
    font-family: inherit;
}

:where(code, kbd, samp, pre, .font-mono) {
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', monospace;
    font-size: 0.9em;
}

/* Section eyebrows / panel labels (uppercase) — stay on UI sans */
:where(.profile-panel__head h2, .settings-panel__head h2, .panel-eyebrow, .section-eyebrow) {
    font-family: var(--app-font-sans);
    font-size: var(--text-sm);
    font-weight: 700;
    letter-spacing: var(--tracking-wider);
    text-transform: uppercase;
}
