/* Standardized buttons — use .ui-btn + variant across portals, owner, admin, guest */
.ui-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    min-height: 2.75rem;
    padding: 0.625rem 1.125rem;
    border-radius: 0.625rem;
    border: 1px solid transparent;
    font-family: inherit;
    font-size: 0.875rem;
    font-weight: 600;
    line-height: 1.25;
    text-decoration: none;
    white-space: nowrap;
    cursor: pointer;
    transition:
        background-color 0.2s ease,
        border-color 0.2s ease,
        color 0.2s ease,
        box-shadow 0.2s ease,
        transform 0.15s ease;
}

.ui-btn:focus-visible {
    outline: 2px solid #2E7D32;
    outline-offset: 2px;
}

.ui-btn:disabled,
.ui-btn[aria-disabled="true"] {
    opacity: 0.55;
    cursor: not-allowed;
    pointer-events: none;
}

.ui-btn-sm {
    min-height: 2.25rem;
    padding: 0.45rem 0.875rem;
    font-size: 0.8125rem;
    border-radius: 0.5rem;
}

.ui-btn-lg {
    min-height: 3rem;
    padding: 0.75rem 1.375rem;
    font-size: 0.9375rem;
}

.ui-btn-primary {
    background: linear-gradient(135deg, #1B5E20, #2E7D32);
    color: #fff;
    box-shadow: 0 3px 12px rgba(46, 125, 50, 0.28);
}

.ui-btn-primary:hover {
    filter: brightness(1.06);
    box-shadow: 0 4px 16px rgba(46, 125, 50, 0.34);
}

.ui-btn-secondary {
    background: #fff;
    color: #1B5E20;
    border-color: #2E7D32;
}

.ui-btn-secondary:hover {
    background: #2E7D32;
    color: #fff;
}

.ui-btn-ghost {
    background: transparent;
    color: #1B5E20;
    border-color: #C8E6C9;
}

.ui-btn-ghost:hover {
    background: #E8F5E9;
    border-color: #81C784;
}

.ui-btn-danger {
    background: #FEF2F2;
    color: #B91C1C;
    border-color: #FECACA;
}

.ui-btn-danger:hover {
    background: #FEE2E2;
    border-color: #FCA5A5;
}

.ui-btn-info {
    background: #EFF6FF;
    color: #1D4ED8;
    border-color: #DBEAFE;
}

.ui-btn-info:hover {
    background: #DBEAFE;
    border-color: #BFDBFE;
}

.ui-btn-success-soft {
    background: #ECFDF5;
    color: #047857;
    border-color: #D1FAE5;
}

.ui-btn-success-soft:hover {
    background: #D1FAE5;
    border-color: #A7F3D0;
}

.ui-btn-block {
    width: 100%;
}

.ui-btn-icon {
    min-width: 2.75rem;
    padding-left: 0.75rem;
    padding-right: 0.75rem;
}

.ui-btn,
.ui-btn-responsive-stack .ui-btn {
    font-size: var(--text-fluid-sm, 0.8125rem);
}

@media (min-width: 768px) {
    .ui-btn,
    .ui-btn-responsive-stack .ui-btn {
        font-size: var(--text-fluid-base, 0.9375rem);
    }
}

@media (max-width: 480px) {
    .ui-btn-responsive-stack {
        width: 100%;
    }
}
