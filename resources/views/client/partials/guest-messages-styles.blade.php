{{-- Guest Messages inbox — minimal split-pane layout (tenant subdomain) --}}
body.client-nav-page .guest-messages-main {
    padding-top: var(
        --client-nav-safe-offset,
        calc(var(--app-topbar-height, 4.5rem) + clamp(1.75rem, 2.5vw, 2.5rem))
    ) !important;
    padding-left: clamp(0.75rem, 2vw, 1.75rem) !important;
    padding-right: clamp(0.75rem, 2vw, 1.75rem) !important;
    padding-bottom: clamp(0.75rem, 2vw, 1.25rem) !important;
}

.guest-messages-main {
    display: flex;
    flex-direction: column;
    gap: clamp(0.5rem, 1vw, 0.75rem);
    width: 100%;
    max-width: none !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
    flex: 1 1 auto;
    min-height: calc(100dvh - var(--client-nav-safe-offset, 6.5rem));
    box-sizing: border-box;
}

.guest-messages-hero {
    display: flex;
    flex-shrink: 0;
    align-items: flex-end;
    justify-content: space-between;
    gap: clamp(0.75rem, 2vw, 1.25rem);
    margin-bottom: 0;
    padding-bottom: 0.25rem;
    border-bottom: 1px solid var(--app-surface-border, rgba(15, 23, 42, 0.08));
}

.guest-messages-hero__eyebrow {
    margin: 0 0 0.3rem;
    font-size: 0.6875rem;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--ui-accent-color, var(--accent-pink-deep, #B0436E));
}

.guest-messages-hero__title {
    margin: 0 0 0.3rem;
    font-family: var(--app-font-display, inherit);
    font-size: clamp(1.375rem, 2.75vw, 1.75rem);
    font-weight: 700;
    line-height: 1.15;
    letter-spacing: -0.03em;
    color: var(--gray-900, #0f172a);
}

.guest-messages-hero__lede {
    margin: 0;
    max-width: 32rem;
    font-size: 0.875rem;
    line-height: 1.5;
    color: var(--gray-600, #4b5563);
}

.guest-messages-hero__cta {
    display: inline-flex;
    flex-shrink: 0;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    padding: 0.5rem 1rem;
    font-size: 0.8125rem;
    font-weight: 600;
    line-height: 1.2;
    color: #fff;
    text-decoration: none;
    border-radius: 0.5rem;
    background: var(--action-primary-bg, var(--brand-700, #457359));
    transition: background-color 0.15s ease;
}

.guest-messages-hero__cta:hover {
    background: var(--action-primary-hover, var(--brand-800, #34543f));
}

.guest-messages-workspace {
    flex: 1 1 auto;
    display: grid;
    grid-template-columns: 1fr;
    grid-template-rows: minmax(12rem, auto) minmax(0, 1fr);
    gap: clamp(0.65rem, 1.25vw, 0.85rem);
    min-height: 0;
    width: 100%;
}

@media (min-width: 1024px) {
    .guest-messages-workspace {
        grid-template-columns: minmax(16rem, 22rem) minmax(0, 1fr);
        grid-template-rows: minmax(0, 1fr);
        min-height: calc(100dvh - var(--client-nav-safe-offset, 6.5rem) - 7.5rem);
    }
}

.guest-messages-inbox,
.guest-messages-chat {
    display: flex;
    flex-direction: column;
    min-height: 0;
    overflow: hidden;
    background: rgba(255, 255, 255, 0.94);
    border: 1px solid rgba(15, 23, 42, 0.08);
    border-radius: 0.75rem;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
}

@media (min-width: 1024px) {
    .guest-messages-inbox,
    .guest-messages-chat {
        height: 100%;
    }
}

.guest-messages-inbox__head,
.guest-messages-chat__head {
    display: flex;
    flex-shrink: 0;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    border-bottom: 1px solid rgba(15, 23, 42, 0.06);
    background: rgba(248, 250, 252, 0.9);
}

.guest-messages-inbox__title,
.guest-messages-chat__title {
    margin: 0;
    font-size: 0.8125rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    color: var(--gray-700, #374151);
}

.guest-messages-chat__subtitle {
    margin: 0.2rem 0 0;
    font-size: 0.75rem;
    color: var(--gray-500, #6b7280);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.guest-messages-mark-read {
    padding: 0.35rem 0.65rem;
    font-size: 0.6875rem;
    font-weight: 600;
    font-family: inherit;
    color: var(--brand-dark, #1b5e20);
    background: rgba(46, 125, 50, 0.08);
    border: 1px solid rgba(46, 125, 50, 0.18);
    border-radius: 0.4375rem;
    cursor: pointer;
    transition: background-color 0.15s ease;
}

.guest-messages-mark-read:hover {
    background: rgba(46, 125, 50, 0.14);
}

.guest-messages-thread-list {
    flex: 1 1 auto;
    min-height: 0;
    overflow-y: auto;
    overscroll-behavior: contain;
}

.guest-messages-thread {
    display: flex;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    text-decoration: none;
    color: inherit;
    border-bottom: 1px solid rgba(15, 23, 42, 0.04);
    transition: background-color 0.12s ease;
}

.guest-messages-thread:hover {
    background: rgba(15, 23, 42, 0.03);
}

.guest-messages-thread.is-active {
    background: rgba(46, 125, 50, 0.08);
    box-shadow: inset 0 0 0 1px rgba(46, 125, 50, 0.12);
}

.guest-messages-thread.is-unread {
    border-left: 3px solid var(--ui-accent-color, var(--accent-pink-deep, #B0436E));
    padding-left: calc(1rem - 3px);
    background: var(--ui-accent-surface, var(--accent-pink-soft, #F9DEE5));
}

.guest-messages-thread__avatar {
    flex-shrink: 0;
    width: 2.75rem;
    height: 2.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.5rem;
    font-size: 0.75rem;
    font-weight: 700;
    color: #fff;
    background: var(--chrome-active-bg, var(--accent-pink-strong, #D37897));
}

.guest-messages-thread__body {
    flex: 1;
    min-width: 0;
}

.guest-messages-thread__row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.5rem;
    margin-bottom: 0.15rem;
}

.guest-messages-thread__name {
    font-size: 0.8125rem;
    font-weight: 600;
    color: var(--gray-900, #0f172a);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.guest-messages-thread__time {
    flex-shrink: 0;
    font-size: 0.6875rem;
    font-weight: 500;
    color: var(--gray-500, #6b7280);
}

.guest-messages-thread__subject {
    font-size: 0.75rem;
    font-weight: 500;
    color: var(--gray-700, #374151);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.guest-messages-thread__preview {
    font-size: 0.6875rem;
    color: var(--gray-500, #6b7280);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.guest-messages-chat__head-inner {
    min-width: 0;
    flex: 1;
}

.guest-messages-chat__head-actions {
    flex-shrink: 0;
}

.guest-messages-delete {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.4rem 0.65rem;
    font-size: 0.6875rem;
    font-weight: 600;
    font-family: inherit;
    color: #b91c1c;
    background: #fff;
    border: 1px solid rgba(185, 28, 28, 0.2);
    border-radius: 0.4375rem;
    cursor: pointer;
    transition: background-color 0.15s ease;
}

.guest-messages-delete:hover {
    background: #fef2f2;
}

.guest-messages-chat__stream {
    flex: 1 1 auto;
    min-height: 0;
    overflow-y: auto;
    overscroll-behavior: contain;
    padding: 1rem;
    background: rgba(241, 245, 249, 0.65);
}

.guest-messages-bubble-row {
    display: flex;
    align-items: flex-end;
    gap: 0.5rem;
    margin-bottom: 0.75rem;
}

.guest-messages-bubble-row--mine {
    justify-content: flex-end;
}

.guest-messages-bubble-row__avatar {
    flex-shrink: 0;
    width: 2rem;
    height: 2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.4375rem;
    font-size: 0.6875rem;
    font-weight: 700;
    color: #fff;
    background: var(--gray-700, #374151);
}

.guest-messages-bubble-wrap {
    max-width: min(85%, 28rem);
}

.guest-messages-bubble {
    padding: 0.625rem 0.875rem;
    font-size: 0.8125rem;
    line-height: 1.5;
    border-radius: 0.75rem;
    word-break: break-word;
}

.guest-messages-bubble--mine {
    color: #fff;
    background: var(--chrome-active-bg, var(--accent-pink-strong, #D37897));
    border-bottom-right-radius: 0.25rem;
}

.guest-messages-bubble--theirs {
    color: var(--gray-900, #0f172a);
    background: #fff;
    border: 1px solid rgba(15, 23, 42, 0.08);
    border-bottom-left-radius: 0.25rem;
}

.guest-messages-bubble-meta {
    margin-top: 0.35rem;
    font-size: 0.625rem;
    font-weight: 500;
    color: var(--gray-500, #6b7280);
}

.guest-messages-bubble-row--mine .guest-messages-bubble-meta {
    text-align: right;
}

.guest-messages-chat__placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 12rem;
    padding: 2rem 1rem;
    text-align: center;
    border: 1px dashed rgba(15, 23, 42, 0.12);
    border-radius: 0.625rem;
    background: rgba(255, 255, 255, 0.85);
}

.guest-messages-chat__placeholder i {
    font-size: 1.5rem;
    color: var(--gray-300, #d1d5db);
    margin-bottom: 0.65rem;
}

.guest-messages-chat__placeholder h3 {
    margin: 0 0 0.35rem;
    font-size: 0.9375rem;
    font-weight: 600;
    color: var(--gray-800, #1f2937);
}

.guest-messages-chat__placeholder p {
    margin: 0;
    font-size: 0.8125rem;
    color: var(--gray-500, #6b7280);
    max-width: 18rem;
}

.guest-messages-reply {
    flex-shrink: 0;
    padding: 0.75rem 1rem;
    border-top: 1px solid rgba(15, 23, 42, 0.06);
    background: #fff;
}

.guest-messages-reply__textarea {
    width: 100%;
    min-height: 5rem;
    padding: 0.625rem 0.75rem;
    font-size: 0.8125rem;
    font-family: inherit;
    line-height: 1.5;
    color: var(--gray-900, #0f172a);
    background: #fff;
    border: 1px solid rgba(15, 23, 42, 0.12);
    border-radius: 0.5rem;
    resize: vertical;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
}

.guest-messages-reply__textarea:focus {
    outline: none;
    border-color: var(--ui-accent-color, var(--accent-pink-deep, #B0436E));
    box-shadow: 0 0 0 2px rgba(69, 115, 89, 0.2);
}

.guest-messages-reply__textarea::placeholder {
    color: var(--gray-400, #9ca3af);
}

.guest-messages-reply__error {
    margin-top: 0.35rem;
    font-size: 0.75rem;
    color: #b91c1c;
}

.guest-messages-reply__file {
    margin-top: 0.5rem;
}

.guest-messages-reply__file label {
    display: block;
    margin-bottom: 0.25rem;
    font-size: 0.6875rem;
    font-weight: 600;
    color: var(--gray-600, #4b5563);
}

.guest-messages-reply__actions {
    display: flex;
    justify-content: flex-end;
    margin-top: 0.65rem;
}

.guest-messages-reply__send {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    padding: 0.5rem 1.25rem;
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

.guest-messages-reply__send:hover {
    background: var(--action-primary-hover, var(--brand-800, #34543f));
}

.guest-messages-reply__send:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.guest-messages-empty {
    flex: 1 1 auto;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: min(52vh, calc(100dvh - var(--client-nav-safe-offset, 6.5rem) - 10rem));
}

.guest-messages-empty__card {
    max-width: 24rem;
    width: 100%;
    padding: clamp(2rem, 5vw, 2.75rem) 1.5rem;
    text-align: center;
    background: rgba(255, 255, 255, 0.94);
    border: 1px solid rgba(15, 23, 42, 0.08);
    border-radius: 0.75rem;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
}

.guest-messages-empty__card i {
    font-size: 1.75rem;
    color: var(--ui-accent-color, var(--accent-pink-deep, #B0436E));
    margin-bottom: 0.75rem;
}

.guest-messages-empty__card h3 {
    margin: 0 0 0.35rem;
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-900, #0f172a);
}

.guest-messages-empty__card p {
    margin: 0 0 1rem;
    font-size: 0.8125rem;
    line-height: 1.55;
    color: var(--gray-500, #6b7280);
}

.guest-messages-empty__cta {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    font-weight: 600;
    color: #fff;
    text-decoration: none;
    border-radius: 0.5rem;
    background: var(--action-primary-bg, var(--brand-700, #457359));
    transition: background-color 0.15s ease;
}

.guest-messages-empty__cta:hover {
    background: var(--action-primary-hover, var(--brand-800, #34543f));
}

.guest-messages-main .flash-alerts,
.guest-messages-main > .alert {
    margin: 0;
    flex-shrink: 0;
}

/* Attachment previews inherit .msg-bubble-attachment from messaging-ui-styles */
.guest-messages-chat__stream .msg-bubble-attachment {
    margin-top: 0.35rem;
}
