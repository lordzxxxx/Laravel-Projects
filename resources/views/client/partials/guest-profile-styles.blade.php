{{-- Guest Profile / Settings — minimal full-viewport layout --}}
body.client-nav-page .profile-main.guest-profile-main {
    width: 100%;
    max-width: none;
    margin: 0;
    padding-top: var(
        --client-nav-safe-offset,
        calc(var(--app-topbar-height, 4.5rem) + clamp(1.75rem, 2.5vw, 2.5rem))
    ) !important;
    /* Horizontal + bottom padding come from .client-guest-main */
    min-height: calc(100dvh - var(--client-nav-safe-offset, 6.5rem));
    gap: clamp(0.85rem, 1.75vw, 1.25rem);
    box-sizing: border-box;
}

.guest-profile-hero {
    flex-shrink: 0;
    margin-bottom: 0;
    padding-bottom: 0.25rem;
    border-bottom: 1px solid rgba(15, 23, 42, 0.08);
}

.guest-profile-hero__eyebrow {
    margin: 0 0 0.35rem;
    font-size: 0.6875rem;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--ui-accent-color, var(--accent-pink-deep, #B0436E));
}

.guest-profile-hero__title {
    margin: 0 0 0.35rem;
    font-family: var(--app-font-display, inherit);
    font-size: clamp(1.5rem, 3vw, 2rem);
    font-weight: 700;
    line-height: 1.15;
    letter-spacing: -0.03em;
    color: var(--gray-900, #0f172a);
}

.guest-profile-hero__lede {
    margin: 0;
    max-width: 40rem;
    font-size: 0.9375rem;
    line-height: 1.55;
    color: var(--gray-600, #4b5563);
}

body.client-nav-page .guest-profile-main .profile-grid {
    flex: 1 1 auto;
    min-height: 0;
    width: 100%;
    gap: clamp(0.85rem, 1.5vw, 1.15rem);
    align-content: start;
}

@media (min-width: 1024px) {
    body.client-nav-page .guest-profile-main .profile-grid {
        grid-template-columns: minmax(0, 1.35fr) minmax(280px, 0.85fr);
        align-items: stretch;
    }
}

body.client-nav-page .guest-profile-main .profile-panel {
    background: rgba(255, 255, 255, 0.94);
    border: 1px solid rgba(15, 23, 42, 0.08);
    border-radius: 0.75rem;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
}

body.client-nav-page .guest-profile-main .profile-panel__head {
    padding: 0.75rem 1rem;
    background: rgba(248, 250, 252, 0.9);
    border-bottom: 1px solid rgba(15, 23, 42, 0.06);
}

body.client-nav-page .guest-profile-main .profile-panel__head h2 {
    font-size: 0.8125rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    color: var(--gray-700, #374151);
}

body.client-nav-page .guest-profile-main .profile-panel__head p {
    font-size: 0.75rem;
    color: var(--gray-500, #6b7280);
}

body.client-nav-page .guest-profile-main .profile-panel__body {
    padding: 1rem;
}

body.client-nav-page .guest-profile-main .profile-actions {
    background: rgba(255, 255, 255, 0.94);
    border: 1px solid rgba(15, 23, 42, 0.08);
    border-radius: 0.75rem;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
    padding: 0.75rem 1rem;
}

body.client-nav-page .guest-profile-main .btn-save {
    background: var(--action-primary-bg, var(--brand-700, #457359));
    border-radius: 0.5rem;
}

body.client-nav-page .guest-profile-main .btn-save:hover {
    background: var(--action-primary-hover, var(--brand-800, #34543f));
}

body.client-nav-page .guest-profile-main .btn-secondary {
    border-radius: 0.5rem;
}

body.client-nav-page .guest-profile-main .btn-secondary:hover {
    background: rgba(15, 23, 42, 0.04);
}

body.client-nav-page .guest-profile-main .profile-flash {
    border-radius: 0.5rem;
    flex-shrink: 0;
}

body.client-nav-page .guest-profile-main .profile-user {
    border-bottom-color: rgba(15, 23, 42, 0.06);
}

body.client-nav-page .guest-profile-main .profile-user__avatar {
    background: var(--chrome-active-bg, var(--accent-pink-strong, #D37897));
}

body.client-nav-page .guest-profile-main .avatar-upload {
    border-color: rgba(15, 23, 42, 0.12);
    border-radius: 0.5rem;
    background: rgba(248, 250, 252, 0.8);
}

body.client-nav-page .guest-profile-main .avatar-upload__preview {
    background: var(--chrome-active-bg, var(--accent-pink-strong, #D37897));
}

body.client-nav-page .guest-profile-main .notify-item__icon {
    background: var(--ui-accent-surface, var(--accent-pink-soft, #F9DEE5));
    color: var(--ui-accent-color, var(--accent-pink-deep, #B0436E));
}

body.client-nav-page .guest-profile-main .switch input:checked + .slider {
    background: var(--action-primary-bg, var(--brand-700, #457359));
}

body.client-nav-page .guest-profile-main .field input:focus,
body.client-nav-page .guest-profile-main .field textarea:focus {
    border-color: var(--ui-accent-color, var(--accent-pink-deep, #B0436E));
    box-shadow: 0 0 0 2px color-mix(in srgb, var(--ui-accent-color, #B0436E) 22%, transparent);
}

body.client-nav-page .guest-profile-main .role-chip.client {
    background: var(--ui-accent-surface, var(--accent-pink-soft, #F9DEE5));
    color: var(--ui-accent-color, var(--accent-pink-deep, #B0436E));
}
