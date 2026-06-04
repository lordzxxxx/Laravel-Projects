{{-- Shared messaging surfaces (central admin + tenant owner/client/admin). Include inside <style>. --}}
:root {
    --msg-surface: var(--app-surface-bg, #ffffff);
    --msg-muted: var(--ink-500, #64748b);
    --msg-border: var(--app-surface-border, #e2e8f0);
    --msg-bg-chat: var(--app-page-bg, #f1f5f9);
    --msg-radius: 16px;
    --msg-radius-sm: 12px;
    --msg-shadow: var(--shadow-md, 0 4px 6px -1px rgba(15, 23, 42, 0.06), 0 12px 28px -8px rgba(15, 23, 42, 0.08));
    --msg-accent: var(--chrome-active-bg, #0d9488);
    --msg-accent-hover: var(--chrome-focus-ring, #0f766e);
    --msg-bubble-in: var(--ink-100, #f1f5f9);
    --msg-bubble-out-a: var(--chrome-active-bg, #0d9488);
    --msg-bubble-out-b: var(--chrome-focus-ring, #059669);
}

.msg-scrollbar {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 transparent;
}
.msg-scrollbar::-webkit-scrollbar {
    width: 9px;
    height: 9px;
}
.msg-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 999px;
}
.msg-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}

/* ── Central admin inbox / compose (full-viewport workspace) ─── */
.msg-admin-page .main-content {
    display: flex;
    flex-direction: column;
    min-height: calc(100vh - var(--app-main-top-offset, 108px));
    padding-bottom: 24px;
}

.msg-admin-main {
    width: 100%;
    max-width: none;
    margin: 0;
    flex: 1;
    min-height: 0;
    display: flex;
    flex-direction: column;
}

.msg-admin-page .page-header {
    margin-bottom: 1.5rem;
    flex-shrink: 0;
}

.msg-admin-page .page-header p {
    max-width: 52rem;
    margin-top: 0.35rem;
}

.msg-admin-workspace {
    flex: 1;
    min-height: 0;
    display: flex;
    flex-direction: column;
}

.msg-admin-layout {
    display: grid;
    gap: 1.25rem;
    grid-template-columns: 1fr;
    align-items: stretch;
    flex: 1;
    min-height: 0;
}
@media (min-width: 1024px) {
    .msg-admin-layout {
        grid-template-columns: minmax(280px, 320px) minmax(0, 1fr);
        gap: 1.5rem;
    }
}

.msg-compose-panel,
.msg-inbox-panel {
    display: flex;
    flex-direction: column;
    min-height: 0;
    height: 100%;
}

@media (min-width: 1024px) {
    .msg-admin-layout {
        min-height: calc(100vh - var(--app-main-top-offset, 108px) - 7.5rem);
    }
    .msg-compose-sticky {
        position: sticky;
        top: calc(var(--app-main-top-offset, 108px) + 1rem);
        align-self: start;
    }
}

.msg-inbox-panel .msg-card-header {
    flex-shrink: 0;
}

.msg-inbox-body {
    flex: 1;
    min-height: 0;
    display: flex;
    flex-direction: column;
}

.msg-thread-list--fill {
    flex: 1;
    min-height: 12rem;
    max-height: none;
    overflow-y: auto;
}

.msg-inbox-count {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--msg-muted);
    letter-spacing: 0.02em;
}

.msg-card {
    background: var(--msg-surface);
    border-radius: var(--radius-lg, 14px);
    border: 1px solid var(--msg-border);
    box-shadow: var(--shadow-sm, 0 1px 2px rgba(15, 23, 42, 0.05));
    overflow: hidden;
}

.msg-card-header {
    padding: 1.125rem 1.5rem;
    border-bottom: 1px solid var(--msg-border);
    background: var(--app-surface-muted-bg, #f8fafc);
}
.msg-card-header h2 {
    font-size: 0.8125rem;
    font-weight: 700;
    color: var(--ink-700, #334155);
    letter-spacing: 0.06em;
    text-transform: uppercase;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin: 0;
}
.msg-card-header h2 i {
    color: var(--chrome-icon-color, var(--msg-accent));
    font-size: 0.875rem;
}

.msg-card-body {
    padding: 1.5rem;
}

.msg-card-header-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}

.msg-field-label {
    display: block;
    font-weight: 650;
    font-size: 0.8125rem;
    color: var(--ink-700, #334155);
    margin-bottom: 0.4rem;
}
.msg-field-hint {
    font-size: 0.75rem;
    color: var(--msg-muted);
    line-height: 1.45;
    margin-top: 0.35rem;
}

.msg-label-optional {
    font-weight: 500;
    color: var(--ink-400, #94a3b8);
}

.msg-error {
    color: #b91c1c;
    font-size: 0.8125rem;
    margin-top: 0.35rem;
}

.msg-input,
.msg-select,
.msg-textarea {
    width: 100%;
    padding: 0.7rem 0.9rem;
    border-radius: 10px;
    border: 1px solid var(--app-surface-border, #e2e8f0);
    font-size: 0.9375rem;
    font-family: inherit;
    background: var(--app-surface-bg, #fff);
    color: var(--ink-900, #0f172a);
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
}
.msg-input:focus,
.msg-select:focus,
.msg-textarea:focus {
    outline: none;
    border-color: var(--chrome-focus-ring, var(--msg-accent));
    box-shadow: 0 0 0 3px color-mix(in srgb, var(--chrome-focus-ring, #0d9488) 22%, transparent);
}
.msg-textarea {
    min-height: 7rem;
    resize: vertical;
    line-height: 1.5;
}

.msg-btn-primary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.7rem 1.25rem;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.875rem;
    border: 1px solid var(--chrome-active-border, transparent);
    cursor: pointer;
    background: var(--chrome-active-bg, var(--msg-accent));
    color: #fff;
    box-shadow: var(--shadow-sm, 0 1px 2px rgba(15, 23, 42, 0.06));
    transition: background 0.15s ease, box-shadow 0.15s ease;
}
.msg-btn-primary:hover {
    background: var(--chrome-focus-ring, var(--msg-accent-hover));
    box-shadow: 0 4px 14px color-mix(in srgb, var(--chrome-active-bg, #457359) 28%, transparent);
}
.msg-btn-primary:disabled {
    opacity: 0.55;
    cursor: not-allowed;
    transform: none;
}

.msg-thread-list {
    display: flex;
    flex-direction: column;
}
.msg-thread-item {
    display: grid;
    grid-template-columns: auto 1fr auto;
    align-items: center;
    gap: 1rem 1.25rem;
    padding: 1.125rem 1.5rem;
    border-bottom: 1px solid var(--app-surface-border, #f1f5f9);
    text-decoration: none;
    color: inherit;
    transition: background 0.12s ease;
}
.msg-thread-body {
    flex: 1;
    min-width: 0;
}
.msg-thread-item:last-child {
    border-bottom: none;
}
.msg-thread-item:hover {
    background: var(--app-surface-muted-bg, #f8fafc);
}

.msg-thread-item-unread {
    background: color-mix(in srgb, var(--chrome-active-bg, #457359) 6%, var(--app-surface-bg, #fff));
    box-shadow: inset 3px 0 0 var(--chrome-active-bg, var(--msg-accent));
}

.msg-thread-avatar {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    background: var(--chrome-icon-bg, #edf4ea);
    color: var(--chrome-icon-color, var(--msg-accent));
    border: 1px solid var(--chrome-icon-border, #e2e8f0);
    font-weight: 700;
    font-size: 0.72rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.msg-thread-top {
    display: flex;
    align-items: baseline;
    justify-content: space-between;
    gap: 0.75rem;
    margin-bottom: 0.2rem;
}

.msg-thread-meta {
    font-size: 0.75rem;
    color: var(--msg-muted);
    white-space: nowrap;
    flex-shrink: 0;
}
.msg-thread-title {
    font-weight: 600;
    font-size: 0.9375rem;
    color: var(--ink-900, #0f172a);
    line-height: 1.3;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.msg-thread-tenant {
    display: inline-flex;
    align-items: center;
    font-size: 0.6875rem;
    font-weight: 600;
    color: var(--ink-600, #475569);
    letter-spacing: 0.03em;
    text-transform: uppercase;
    margin-bottom: 0.35rem;
}
.msg-thread-subject {
    font-size: 0.8125rem;
    font-weight: 500;
    color: var(--ink-700, #334155);
    margin-bottom: 0.25rem;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.msg-thread-preview {
    font-size: 0.8125rem;
    color: var(--ink-500, #64748b);
    line-height: 1.45;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.msg-thread-chevron {
    color: var(--ink-400, #94a3b8);
    font-size: 0.75rem;
    flex-shrink: 0;
    transition: color 0.12s ease, transform 0.12s ease;
}
.msg-thread-item:hover .msg-thread-chevron {
    color: var(--chrome-active-bg, var(--msg-accent));
    transform: translateX(2px);
}

.msg-dot-unread {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #ef4444;
    flex-shrink: 0;
}

.msg-pagination {
    padding: 1rem 1.5rem;
    border-top: 1px solid var(--msg-border);
    background: var(--app-surface-muted-bg, #fafafa);
    flex-shrink: 0;
}

.msg-empty {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 3.5rem 2rem;
    text-align: center;
    color: var(--msg-muted);
    font-size: 0.9375rem;
    line-height: 1.6;
    min-height: 16rem;
}

.msg-empty-icon {
    width: 3.5rem;
    height: 3.5rem;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--app-surface-muted-bg, #f1f5f9);
    color: var(--ink-400, #94a3b8);
    font-size: 1.25rem;
    margin-bottom: 1rem;
}

.msg-field-stack {
    display: flex;
    flex-direction: column;
    gap: 1.125rem;
}

/* ── Admin thread (full width) ───────────────────────────────── */
.msg-thread-page .main-content {
    min-height: calc(100vh - var(--app-main-top-offset, 108px));
    display: flex;
    flex-direction: column;
}

.msg-thread-main {
    width: 100%;
    max-width: none;
    margin: 0;
    flex: 1;
    display: flex;
    flex-direction: column;
    min-height: 0;
    padding-bottom: 1.5rem;
}

.msg-thread-main .msg-chat-shell {
    flex: 1;
    min-height: 0;
    display: flex;
    flex-direction: column;
}

.msg-thread-main .msg-chat-scroll {
    flex: 1;
    min-height: 14rem;
    max-height: none;
}

/* Tenant owner / client / legacy thread — full viewport workspace */
body.msg-thread-page:not(.client-nav-page) {
    background: var(--app-page-bg, #f4f8f5);
    min-height: 100vh;
    color: var(--ink-800, #1f2937);
}

body.msg-thread-page.client-nav-page {
    min-height: 100vh;
    color: var(--ink-800, #1f2937);
}

body.msg-thread-page .messages-show-main.main-content {
    width: 100%;
    max-width: none;
    margin: 0;
    padding: var(--app-main-top-offset, 108px) clamp(12px, 2vw, 28px) clamp(12px, 1.5vw, 20px);
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    gap: 0;
    box-sizing: border-box;
}

body.owner-nav-page.msg-thread-page .messages-show-main.main-content.with-owner-nav {
    padding-top: var(--owner-content-offset, var(--app-main-top-offset, 108px));
}

body.client-nav-page.msg-thread-page .messages-show-main.main-content {
    padding-top: var(--client-nav-offset, var(--app-main-top-offset, 108px));
}

.messages-show-main {
    flex: 1;
    min-height: 0;
    display: flex;
    flex-direction: column;
}

.messages-show-main .msg-chat-shell {
    flex: 1;
    min-height: 0;
    display: flex;
    flex-direction: column;
    border-radius: var(--msg-radius);
}

.messages-show-main .msg-chat-scroll {
    flex: 1;
    min-height: 12rem;
    max-height: none;
}

.msg-thread-toolbar {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem 1rem;
    margin-bottom: 1rem;
    flex-shrink: 0;
}

.msg-thread-toolbar .page-header {
    margin-bottom: 0;
    flex: 1;
    min-width: min(100%, 16rem);
}

.msg-thread-toolbar .page-header h1 {
    font-size: 1.5rem;
}

.msg-thread-toolbar .page-header p {
    margin-left: 0;
    margin-top: 0.35rem;
}

.msg-thread-toolbar__actions {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 0.5rem;
    flex-shrink: 0;
}

.msg-chat-head__row {
    display: flex;
    align-items: center;
    gap: 1rem;
    min-width: 0;
}

.msg-thread-avatar--lg {
    width: 3rem;
    height: 3rem;
    border-radius: 12px;
    font-size: 1rem;
    flex-shrink: 0;
}

.msg-chat-head__meta {
    min-width: 0;
    flex: 1;
}

.msg-chat-head__meta h2 {
    margin: 0 0 0.25rem;
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--ink-900, #0f172a);
    letter-spacing: -0.02em;
    line-height: 1.25;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.msg-chat-head__meta p {
    margin: 0;
    font-size: 0.8125rem;
    color: var(--msg-muted);
    line-height: 1.45;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.msg-composer__footer {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-end;
    justify-content: space-between;
    gap: 0.75rem 1rem;
    margin-top: 0.25rem;
}

.msg-composer__footer .msg-btn-primary {
    flex-shrink: 0;
}

.msg-bubble-row__in {
    display: flex;
    align-items: flex-end;
    gap: 0.65rem;
    max-width: min(640px, 92%);
}

.msg-bubble-row__in .msg-thread-avatar {
    width: 2rem;
    height: 2rem;
    border-radius: 9px;
    font-size: 0.65rem;
}

.msg-bubble-row__out {
    max-width: min(640px, 92%);
    margin-left: auto;
}

@media (max-width: 640px) {
    body.msg-thread-page .messages-show-main.main-content {
        padding-left: 12px;
        padding-right: 12px;
    }

    .msg-thread-toolbar {
        flex-direction: column;
        align-items: stretch;
    }

    .msg-thread-toolbar__actions {
        justify-content: flex-start;
    }
}

/* ── Thread view (admin + tenant detail) ─────────────────────── */
.msg-back-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    font-size: 0.8125rem;
    color: var(--ink-700, #334155);
    text-decoration: none;
    padding: 0.5rem 0.875rem;
    border-radius: 10px;
    background: var(--app-surface-bg, #fff);
    border: 1px solid var(--app-surface-border, #e2e8f0);
    margin-bottom: 0;
    flex-shrink: 0;
    transition: background 0.15s ease, border-color 0.15s ease, color 0.15s ease;
}
.msg-back-link:hover {
    background: var(--app-surface-muted-bg, #f8fafc);
    border-color: var(--chrome-active-border, #cbd5e1);
    color: var(--chrome-active-bg, var(--msg-accent));
}

.msg-chat-shell {
    background: var(--msg-surface);
    border-radius: var(--msg-radius);
    border: 1px solid var(--msg-border);
    box-shadow: var(--msg-shadow);
    overflow: hidden;
}

.msg-chat-head {
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--msg-border);
    background: var(--app-surface-muted-bg, #f8fafc);
    flex-shrink: 0;
}
.msg-chat-head h1 {
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--ink-900, #0f172a);
    letter-spacing: -0.02em;
    margin: 0 0 0.35rem;
}
.msg-chat-head p {
    font-size: 0.8125rem;
    color: var(--msg-muted);
    line-height: 1.5;
    margin: 0;
}
.msg-chat-head p strong {
    color: var(--ink-800, #1e293b);
    font-weight: 600;
}

.msg-chat-scroll {
    background: var(--msg-bg-chat);
    padding: clamp(1rem, 2vw, 1.5rem);
    min-height: min(52vh, 420px);
    max-height: min(62vh, 560px);
    overflow-y: auto;
}

.msg-bubble-row {
    display: flex;
    margin-bottom: 1rem;
}
.msg-bubble-row--in {
    justify-content: flex-start;
}
.msg-bubble-row--out {
    justify-content: flex-end;
}

.msg-bubble {
    max-width: min(560px, 85%);
    padding: 0.85rem 1.05rem;
    border-radius: 18px;
    font-size: 0.9375rem;
    line-height: 1.5;
    white-space: pre-wrap;
    word-break: break-word;
}
.msg-bubble--in {
    background: var(--app-surface-bg, #fff);
    border: 1px solid var(--app-surface-border, #e2e8f0);
    color: var(--ink-900, #0f172a);
    border-bottom-left-radius: 5px;
    box-shadow: var(--shadow-sm, 0 1px 2px rgba(15, 23, 42, 0.05));
}
.msg-bubble--out {
    background: linear-gradient(145deg, var(--msg-bubble-out-a), var(--msg-bubble-out-b));
    color: #fff;
    border-bottom-right-radius: 5px;
    box-shadow: 0 4px 14px rgba(5, 150, 105, 0.35);
}

.msg-bubble-attachment {
    display: block;
    margin-top: 0.5rem;
    max-width: min(280px, 100%);
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid var(--app-surface-border, #e2e8f0);
    background: var(--app-surface-bg, #fff);
    line-height: 0;
}
.msg-bubble-attachment--stacked {
    margin-top: 0.65rem;
}
.msg-bubble-attachment img {
    display: block;
    width: 100%;
    height: auto;
    max-height: 320px;
    object-fit: cover;
}
.msg-bubble-row--out .msg-bubble-attachment {
    margin-left: auto;
    border-color: rgba(255, 255, 255, 0.25);
}

.msg-file-field {
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
}
.msg-file-input {
    width: 100%;
    font-size: 0.8125rem;
    color: var(--ink-700, #334155);
}
.msg-file-input::file-selector-button {
    margin-right: 0.75rem;
    padding: 0.45rem 0.85rem;
    border: 1px solid var(--app-surface-border, #e2e8f0);
    border-radius: 8px;
    background: var(--app-surface-muted-bg, #f8fafc);
    font-weight: 600;
    font-size: 0.75rem;
    cursor: pointer;
}
.msg-preview-thumb {
    display: none;
    margin-top: 0.5rem;
    max-width: 120px;
    border-radius: 10px;
    border: 1px solid var(--app-surface-border, #e2e8f0);
    overflow: hidden;
}
.msg-preview-thumb.is-visible {
    display: block;
}
.msg-preview-thumb img {
    display: block;
    width: 100%;
    height: auto;
}

.msg-bubble-meta {
    margin-top: 0.35rem;
    font-size: 0.72rem;
    color: var(--msg-muted);
}
.msg-bubble-row--out .msg-bubble-meta {
    text-align: right;
    color: #94a3b8;
}

.msg-composer {
    padding: clamp(1rem, 2vw, 1.25rem) clamp(1rem, 2.5vw, 1.5rem);
    border-top: 1px solid var(--msg-border);
    background: var(--app-surface-bg, #fff);
    flex-shrink: 0;
}
.msg-composer textarea {
    width: 100%;
    min-height: 5rem;
    padding: 0.85rem 1rem;
    border-radius: var(--msg-radius-sm);
    border: 1px solid var(--app-surface-border, #cbd5e1);
    background: var(--app-surface-bg, #fff);
    font-family: inherit;
    font-size: 0.9375rem;
    line-height: 1.5;
    margin-bottom: 0.75rem;
    resize: vertical;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
}
.msg-composer textarea:focus {
    outline: none;
    border-color: var(--msg-accent);
    box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.18);
}

.msg-thread-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    margin-top: 0.85rem;
    align-items: center;
}

.msg-btn-danger {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    padding: 0.55rem 1rem;
    border-radius: var(--msg-radius-sm);
    font-weight: 700;
    font-size: 0.8125rem;
    cursor: pointer;
    border: 2px solid #dc2626;
    background: #fff;
    color: #b91c1c;
    transition: background 0.15s ease;
}
.msg-btn-danger:hover {
    background: #fef2f2;
}

@media (max-width: 640px) {
    .msg-thread-item {
        grid-template-columns: auto 1fr;
    }
    .msg-thread-chevron {
        display: none;
    }
}

html.dark .msg-card,
html.dark .msg-sidebar,
html.dark .msg-thread-panel,
html.dark .msg-compose-card {
    background: var(--app-surface-bg) !important;
    border-color: var(--app-surface-border) !important;
}

html.dark .msg-card-header,
html.dark .msg-sidebar-header,
html.dark .msg-thread-header {
    background: var(--app-surface-muted-bg) !important;
    border-color: var(--app-surface-border) !important;
}

html.dark .msg-thread-item {
    color: var(--ink-800);
}

html.dark .msg-thread-item:hover,
html.dark .msg-thread-item.is-active {
    background: var(--app-surface-muted-bg) !important;
}

html.dark .msg-thread-item strong {
    color: var(--ink-900) !important;
}

html.dark .msg-thread-preview,
html.dark .msg-thread-time {
    color: var(--ink-500) !important;
}

html.dark .msg-btn-danger {
    background: var(--app-surface-bg) !important;
    border-color: var(--status-danger) !important;
    color: #fca5a5 !important;
}

html.dark .msg-btn-danger:hover {
    background: color-mix(in srgb, var(--status-danger) 15%, var(--app-surface-bg)) !important;
}

/* Ultra-wide / 4K — cap messaging workspace width */
@media (min-width: 1920px) {
    .guest-messages-main .msg-card,
    .messages-show-main .msg-chat-shell,
    body.msg-thread-page .messages-show-main .msg-chat-shell {
        max-width: min(var(--app-content-max-wide, 96rem), 100%);
        margin-inline: auto;
        width: 100%;
    }
}
