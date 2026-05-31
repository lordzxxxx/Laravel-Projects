{{-- Guest Support / update-tickets — minimal layout --}}
body.client-nav-page .guest-support-main {
    padding-top: var(
        --client-nav-safe-offset,
        calc(var(--app-topbar-height, 4.5rem) + clamp(1.75rem, 2.5vw, 2.5rem))
    ) !important;
}

.guest-support-main {
    display: flex;
    flex-direction: column;
    gap: clamp(0.85rem, 1.75vw, 1.25rem);
    width: 100%;
    max-width: none;
    flex: 1 1 auto;
    min-height: calc(100dvh - var(--client-nav-safe-offset, 6.5rem));
    box-sizing: border-box;
}

.guest-support-hero {
    flex-shrink: 0;
    margin-bottom: 0;
    padding-bottom: 0.25rem;
    border-bottom: 1px solid rgba(15, 23, 42, 0.08);
}

.guest-support-hero__eyebrow {
    margin: 0 0 0.35rem;
    font-size: 0.6875rem;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--ui-accent-color, var(--accent-pink-deep, #B0436E));
}

.guest-support-hero__title {
    margin: 0 0 0.35rem;
    font-family: var(--app-font-display, inherit);
    font-size: clamp(1.5rem, 3vw, 2rem);
    font-weight: 700;
    line-height: 1.15;
    letter-spacing: -0.03em;
    color: var(--gray-900, #0f172a);
}

.guest-support-hero__lede {
    margin: 0;
    max-width: 40rem;
    font-size: 0.9375rem;
    line-height: 1.55;
    color: var(--gray-600, #4b5563);
}

.guest-support-workspace {
    flex: 1 1 auto;
    display: grid;
    grid-template-columns: 1fr;
    gap: clamp(0.85rem, 1.5vw, 1.15rem);
    min-height: 0;
    width: 100%;
    align-content: start;
}

@media (min-width: 1024px) {
    .guest-support-workspace {
        grid-template-columns: minmax(16rem, 22rem) minmax(0, 1fr);
        align-content: stretch;
        min-height: calc(100dvh - var(--client-nav-safe-offset, 6.5rem) - 8rem);
    }
}

.guest-support-panel {
    display: flex;
    flex-direction: column;
    min-height: 0;
    background: rgba(255, 255, 255, 0.94);
    border: 1px solid rgba(15, 23, 42, 0.08);
    border-radius: 0.75rem;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
    overflow: hidden;
}

@media (min-width: 1024px) {
    .guest-support-panel--tickets {
        min-height: 100%;
    }

    .guest-support-panel--tickets .guest-support-panel__body {
        display: flex;
        flex-direction: column;
    }
}

.guest-support-panel__head {
    flex-shrink: 0;
    padding: 0.75rem 1rem;
    border-bottom: 1px solid rgba(15, 23, 42, 0.06);
    background: rgba(248, 250, 252, 0.9);
}

.guest-support-panel__title {
    margin: 0;
    font-size: 0.8125rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    color: var(--gray-700, #374151);
}

.guest-support-panel__body {
    flex: 1 1 auto;
    padding: 1rem;
    min-height: 0;
}

.guest-support-panel__body--scroll {
    overflow-y: auto;
    overscroll-behavior: contain;
}

.guest-support-form {
    display: flex;
    flex-direction: column;
    gap: 0.875rem;
}

.guest-support-field label {
    display: block;
    margin-bottom: 0.35rem;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--gray-700, #374151);
}

.guest-support-field label span.optional {
    font-weight: 500;
    color: var(--gray-500, #6b7280);
}

.guest-support-input,
.guest-support-textarea,
.guest-support-file {
    width: 100%;
    padding: 0.5625rem 0.75rem;
    font-size: 0.8125rem;
    font-family: inherit;
    line-height: 1.45;
    color: var(--gray-900, #0f172a);
    background: #fff;
    border: 1px solid rgba(15, 23, 42, 0.12);
    border-radius: 0.5rem;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
}

.guest-support-textarea {
    min-height: 7rem;
    resize: vertical;
}

.guest-support-input:focus,
.guest-support-textarea:focus,
.guest-support-file:focus {
    outline: none;
    border-color: var(--ui-accent-color, var(--accent-pink-deep, #B0436E));
    box-shadow: 0 0 0 2px rgba(69, 115, 89, 0.18);
}

.guest-support-field-error {
    margin-top: 0.3rem;
    font-size: 0.75rem;
    color: #b91c1c;
}

.guest-support-submit {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    margin-top: 0.25rem;
    padding: 0.5rem 1rem;
    font-size: 0.8125rem;
    font-weight: 600;
    font-family: inherit;
    color: #fff;
    background: var(--action-primary-bg, var(--brand-700, #457359));
    border: none;
    border-radius: 0.5rem;
    cursor: pointer;
    transition: background-color 0.15s ease;
}

.guest-support-submit:hover {
    background: var(--action-primary-hover, var(--brand-800, #34543f));
}

.guest-support-submit:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.guest-support-ticket-list {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.guest-support-ticket {
    display: grid;
    grid-template-columns: 1fr auto;
    grid-template-rows: auto auto;
    gap: 0.35rem 0.75rem;
    padding: 0.75rem 0.875rem;
    text-decoration: none;
    color: inherit;
    background: #fff;
    border: 1px solid rgba(15, 23, 42, 0.08);
    border-radius: 0.5rem;
    transition: border-color 0.15s ease, box-shadow 0.15s ease, transform 0.15s ease;
}

.guest-support-ticket:hover {
    border-color: color-mix(in srgb, var(--ui-accent-color, #B0436E) 28%, rgba(15, 23, 42, 0.08));
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
    transform: translateY(-1px);
}

.guest-support-ticket__subject {
    grid-column: 1;
    margin: 0;
    font-size: 0.875rem;
    font-weight: 600;
    line-height: 1.35;
    color: var(--gray-900, #0f172a);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.guest-support-ticket__date {
    grid-column: 2;
    grid-row: 1 / 3;
    align-self: start;
    font-size: 0.6875rem;
    font-weight: 500;
    color: var(--gray-500, #6b7280);
    white-space: nowrap;
}

.guest-support-ticket__footer {
    grid-column: 1;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 0.5rem;
}

.guest-support-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.2rem 0.55rem;
    font-size: 0.6875rem;
    font-weight: 600;
    line-height: 1.2;
    border-radius: 999px;
}

.guest-support-badge--open {
    background: #fff7ed;
    color: #9a3412;
}

.guest-support-badge--resolved {
    background: #ecfdf5;
    color: #166534;
}

.guest-support-ticket__link {
    font-size: 0.6875rem;
    font-weight: 600;
    color: var(--ui-accent-color, var(--accent-pink-deep, #B0436E));
}

.guest-support-empty {
    flex: 1 1 auto;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 10rem;
    padding: 2rem 1rem;
    text-align: center;
    border: 1px dashed rgba(15, 23, 42, 0.12);
    border-radius: 0.5rem;
    background: rgba(248, 250, 252, 0.6);
}

.guest-support-empty i {
    display: block;
    font-size: 1.5rem;
    color: var(--gray-300, #d1d5db);
    margin-bottom: 0.65rem;
}

.guest-support-empty p {
    margin: 0;
    font-size: 0.8125rem;
    color: var(--gray-500, #6b7280);
}

.guest-support-pagination {
    flex-shrink: 0;
    margin-top: auto;
    padding-top: 0.75rem;
    display: flex;
    justify-content: center;
}

.guest-support-main .flash-alerts,
.guest-support-main > .alert {
    margin: 0;
    flex-shrink: 0;
}
