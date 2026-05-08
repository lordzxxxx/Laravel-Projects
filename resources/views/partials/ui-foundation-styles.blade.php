/* Flash alerts */
.alert-stack {
    display: grid;
    gap: 10px;
    margin-bottom: 16px;
}
.alert {
    border-radius: 12px;
    border: 1px solid;
    padding: 10px 14px;
    font-size: 0.9rem;
    font-weight: 600;
}
.alert-success { background: #ecfdf5; border-color: #86efac; color: #166534; }
.alert-error { background: #fef2f2; border-color: #fecaca; color: #991b1b; }
.alert-warning { background: #fffbeb; border-color: #fcd34d; color: #92400e; }
.alert-info { background: #eff6ff; border-color: #bfdbfe; color: #1d4ed8; }

/* Reusable status badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    border-radius: 999px;
    padding: 6px 12px;
    font-size: 0.76rem;
    font-weight: 700;
}
.status-badge.pending { background: #fef3c7; color: #92400e; }
.status-badge.confirmed, .status-badge.open, .status-badge.active { background: #dcfce7; color: #166534; }
.status-badge.completed, .status-badge.resolved { background: #dbeafe; color: #1d4ed8; }
.status-badge.cancelled, .status-badge.inactive { background: #fee2e2; color: #991b1b; }

.payment-badge {
    display: inline-flex;
    align-items: center;
    border-radius: 999px;
    padding: 6px 12px;
    font-size: 0.76rem;
    font-weight: 700;
}
.payment-badge.neutral { background: #f3f4f6; color: #374151; }
.payment-badge.pending-review { background: #fff7ed; color: #9a3412; }
.payment-badge.paid { background: #ecfdf5; color: #166534; }

/* Loading button state */
[data-loading-button][disabled] {
    opacity: 0.75;
    cursor: wait;
}

/* Shared modern surfaces */
.ui-surface {
    background: rgba(255, 255, 255, 0.88);
    border: 1px solid rgba(209, 213, 219, 0.7);
    border-radius: 14px;
    box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}

.ui-section-muted {
    background: rgba(248, 250, 252, 0.78);
    border: 1px solid rgba(229, 231, 235, 0.9);
    border-radius: 12px;
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
}

.ui-heading {
    color: #0f172a;
    font-weight: 700;
    letter-spacing: 0.01em;
}

/* ── Canonical page title used system-wide (admin / owner / client / profile) ─
   Markup:
     <div class="page-header">
         <h1>
             <span class="page-title-icon"><i class="fa-solid fa-..."></i></span>
             <span>Page Title</span>
         </h1>
         <p>Description...</p>
     </div>
   The same selector also catches the legacy `.page-title` wrapper used by the
   profile page. `:where(...)` keeps specificity at 0 so per-page overrides win
   if a page truly needs a different title look.
   ───────────────────────────────────────────────────────────────────────── */
:where(.page-header, .page-title) h1 {
    display: flex;
    align-items: center;
    gap: 0.875rem;
    font-size: 1.75rem;
    font-weight: 800;
    color: #0F172A;
    line-height: 1.2;
    letter-spacing: -0.015em;
    margin: 0;
}

:where(.page-header, .page-title) h1 .page-title-icon,
:where(.page-header, .page-title) h1 > .icon-wrap {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 44px;
    height: 44px;
    border-radius: 0.75rem;
    background: #ECFDF5;
    color: #047857;
    border: 1px solid #D1FAE5;
    font-size: 18px;
    flex-shrink: 0;
}

:where(.page-header, .page-title) p {
    color: #64748B;
    font-size: 0.875rem;
    line-height: 1.6;
    margin: 0.5rem 0 0 3.65rem;
}

@media (max-width: 640px) {
    :where(.page-header, .page-title) h1 { font-size: 1.4rem; }
    :where(.page-header, .page-title) p { margin-left: 0; }
}
