{{-- Shared messaging surfaces (central admin + tenant owner/client/admin). Include inside <style>. --}}
:root {
    --msg-surface: #ffffff;
    --msg-muted: #64748b;
    --msg-border: #e2e8f0;
    --msg-bg-chat: #f1f5f9;
    --msg-radius: 16px;
    --msg-radius-sm: 12px;
    --msg-shadow: 0 4px 6px -1px rgba(15, 23, 42, 0.06), 0 12px 28px -8px rgba(15, 23, 42, 0.08);
    --msg-accent: #0d9488;
    --msg-accent-hover: #0f766e;
    --msg-bubble-in: #f1f5f9;
    --msg-bubble-out-a: #0d9488;
    --msg-bubble-out-b: #059669;
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

/* ── Central admin inbox / compose ───────────────────────────── */
.msg-admin-main {
    max-width: min(1240px, 100%);
    margin-left: auto;
    margin-right: auto;
    padding-left: clamp(16px, 3vw, 36px);
    padding-right: clamp(16px, 3vw, 36px);
}

.msg-admin-layout {
    display: grid;
    gap: 1.25rem;
    grid-template-columns: 1fr;
    align-items: start;
}
@media (min-width: 1080px) {
    .msg-admin-layout {
        grid-template-columns: minmax(300px, 380px) minmax(0, 1fr);
        gap: 1.5rem;
    }
    .msg-compose-sticky {
        position: sticky;
        top: 92px;
    }
}

.msg-card {
    background: var(--msg-surface);
    border-radius: var(--msg-radius);
    border: 1px solid var(--msg-border);
    box-shadow: var(--msg-shadow);
    overflow: hidden;
}

.msg-card-header {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--msg-border);
    background: linear-gradient(180deg, #f8fafc 0%, #fff 100%);
}
.msg-card-header h2 {
    font-size: 1rem;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.02em;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.msg-card-header h2 i {
    color: var(--msg-accent);
}

.msg-card-body {
    padding: 1.25rem;
}

.msg-field-label {
    display: block;
    font-weight: 650;
    font-size: 0.8125rem;
    color: #334155;
    margin-bottom: 0.4rem;
}
.msg-field-hint {
    font-size: 0.75rem;
    color: var(--msg-muted);
    line-height: 1.45;
    margin-top: 0.35rem;
}

.msg-input,
.msg-select,
.msg-textarea {
    width: 100%;
    padding: 0.65rem 0.85rem;
    border-radius: var(--msg-radius-sm);
    border: 1px solid #cbd5e1;
    font-size: 0.9375rem;
    font-family: inherit;
    background: #fff;
    color: #0f172a;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
}
.msg-input:focus,
.msg-select:focus,
.msg-textarea:focus {
    outline: none;
    border-color: var(--msg-accent);
    box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.2);
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
    padding: 0.65rem 1.25rem;
    border-radius: var(--msg-radius-sm);
    font-weight: 700;
    font-size: 0.9rem;
    border: none;
    cursor: pointer;
    background: linear-gradient(135deg, var(--msg-accent-hover), var(--msg-accent));
    color: #fff;
    box-shadow: 0 4px 14px rgba(13, 148, 136, 0.35);
    transition: transform 0.12s ease, box-shadow 0.12s ease, filter 0.12s ease;
}
.msg-btn-primary:hover {
    filter: brightness(1.05);
    transform: translateY(-1px);
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
    display: flex;
    flex-wrap: wrap;
    align-items: flex-start;
    gap: 0.85rem 1rem;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #f1f5f9;
    text-decoration: none;
    color: inherit;
    transition: background 0.12s ease;
}
.msg-thread-body {
    flex: 1;
    min-width: min(0, 100%);
}
.msg-thread-item:last-child {
    border-bottom: none;
}
.msg-thread-item:hover {
    background: #f8fafc;
}

.msg-thread-item-unread {
    background: linear-gradient(90deg, rgba(13, 148, 136, 0.06) 0%, transparent 48%);
    border-left: 3px solid var(--msg-accent);
    padding-left: calc(1.25rem - 3px);
}

.msg-thread-avatar {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: linear-gradient(145deg, #14b8a6, #059669);
    color: #fff;
    font-weight: 800;
    font-size: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    align-self: flex-start;
}

.msg-thread-meta {
    font-size: 0.72rem;
    color: var(--msg-muted);
    white-space: nowrap;
}
.msg-thread-title {
    font-weight: 750;
    font-size: 0.92rem;
    color: #0f172a;
    margin-bottom: 0.15rem;
}
.msg-thread-tenant {
    font-size: 0.78rem;
    font-weight: 650;
    color: var(--msg-accent-hover);
    text-transform: uppercase;
    letter-spacing: 0.04em;
    margin-bottom: 0.25rem;
}
.msg-thread-preview {
    font-size: 0.8125rem;
    color: #475569;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.msg-thread-open {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-weight: 700;
    font-size: 0.8125rem;
    color: var(--msg-accent-hover);
    flex-shrink: 0;
    margin-left: auto;
    align-self: center;
}
.msg-thread-item:hover .msg-thread-open {
    color: #0f766e;
}

.msg-dot-unread {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #ef4444;
    flex-shrink: 0;
}

.msg-pagination {
    padding: 1rem 1.25rem;
    border-top: 1px solid var(--msg-border);
    background: #fafafa;
}

.msg-empty {
    padding: 3rem 1.5rem;
    text-align: center;
    color: var(--msg-muted);
    font-size: 0.9375rem;
    line-height: 1.55;
}

.msg-empty-icon {
    font-size: 2.5rem;
    margin-bottom: 0.75rem;
    opacity: 0.35;
}

/* ── Thread view (admin + tenant detail) ─────────────────────── */
.msg-back-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 700;
    font-size: 0.875rem;
    color: var(--msg-accent-hover);
    text-decoration: none;
    padding: 0.5rem 1rem;
    border-radius: 999px;
    background: #fff;
    border: 1px solid #ccfbf1;
    margin-bottom: 1.25rem;
    transition: background 0.15s ease, border-color 0.15s ease;
}
.msg-back-link:hover {
    background: #f0fdfa;
    border-color: #99f6e4;
}

.msg-chat-shell {
    background: var(--msg-surface);
    border-radius: var(--msg-radius);
    border: 1px solid var(--msg-border);
    box-shadow: var(--msg-shadow);
    overflow: hidden;
}

.msg-chat-head {
    padding: 1.15rem 1.35rem;
    border-bottom: 1px solid var(--msg-border);
    background: linear-gradient(180deg, #f8fafc 0%, #fff 100%);
}
.msg-chat-head h1 {
    font-size: 1.15rem;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.02em;
    margin-bottom: 0.35rem;
}
.msg-chat-head p {
    font-size: 0.8125rem;
    color: var(--msg-muted);
    line-height: 1.45;
}

.msg-chat-scroll {
    background: var(--msg-bg-chat);
    padding: 1.25rem;
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
    background: #fff;
    border: 1px solid #e2e8f0;
    color: #0f172a;
    border-bottom-left-radius: 5px;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.05);
}
.msg-bubble--out {
    background: linear-gradient(145deg, var(--msg-bubble-out-a), var(--msg-bubble-out-b));
    color: #fff;
    border-bottom-right-radius: 5px;
    box-shadow: 0 4px 14px rgba(5, 150, 105, 0.35);
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
    padding: 1.15rem 1.35rem;
    border-top: 1px solid var(--msg-border);
    background: #fff;
}
.msg-composer textarea {
    width: 100%;
    min-height: 5.5rem;
    padding: 0.85rem 1rem;
    border-radius: var(--msg-radius-sm);
    border: 1px solid #cbd5e1;
    font-family: inherit;
    font-size: 0.9375rem;
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
    .msg-thread-open {
        flex-basis: 100%;
        margin-left: 0;
        justify-content: flex-end;
    }
}
