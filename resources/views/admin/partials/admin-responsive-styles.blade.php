{{-- Admin portal responsive layout — 320px through 4K (raw CSS — include inside <style> only) --}}
.dashboard-layout {
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
}

.dashboard-layout > .main-content,
.dashboard-layout > main.main-content {
    box-sizing: border-box;
    width: 100%;
    max-width: min(var(--app-content-max-wide, 96rem), 100%);
    margin-inline: auto;
    padding: clamp(1rem, 2.5vw, 1.75rem) clamp(1rem, 3vw, 2.25rem);
}

@media (max-width: 768px) {
    .dashboard-layout > .main-content,
    .dashboard-layout > main.main-content {
        padding: clamp(0.875rem, 2vw, 1.25rem) clamp(0.875rem, 2.5vw, 1rem);
    }
}

@media (min-width: 2560px) {
    .dashboard-layout > .main-content,
    .dashboard-layout > main.main-content {
        padding-inline: clamp(2rem, 5vw, 4rem);
    }
}

.page-header h1 {
    font-size: var(--text-fluid-2xl);
}

.page-header p {
    font-size: var(--text-fluid-sm);
}

@media (max-width: 768px) {
    .page-header h1 {
        font-size: var(--text-fluid-lg) !important;
    }

    .page-header p {
        font-size: var(--text-fluid-xs) !important;
    }

    .page-header h1 .page-title-icon,
    .page-header h1 > .icon-wrap {
        width: 36px;
        height: 36px;
        font-size: 0.875rem !important;
    }

    .admin-central-portal .kpi-info h3 {
        font-size: var(--text-fluid-lg) !important;
    }

    .admin-central-portal .kpi-info h3.kpi-value-compact {
        font-size: var(--text-fluid-base) !important;
    }

    .admin-central-portal .kpi-info h3.kpi-value-empty {
        font-size: var(--text-fluid-sm) !important;
    }

    .admin-central-portal .kpi-icon {
        width: 40px;
        height: 40px;
        font-size: var(--text-fluid-base) !important;
    }

    .admin-central-portal .quick-stat-card h4,
    .admin-central-portal .dashboard-card h3 {
        font-size: var(--text-fluid-sm) !important;
    }

    .admin-central-portal .status-badge {
        font-size: var(--text-fluid-xs) !important;
        padding: 0.2rem 0.5rem;
    }

    .admin-central-portal .search-input,
    .admin-central-portal .filter-select,
    .admin-central-portal input[type="search"],
    .admin-central-portal input[type="text"],
    .admin-central-portal select {
        font-size: var(--text-fluid-sm) !important;
        padding: 0.5rem 0.75rem;
    }
}

@media (max-width: 480px) {
    .page-header h1 {
        font-size: var(--text-fluid-base) !important;
    }

    .admin-central-portal .kpi-info h3 {
        font-size: var(--text-fluid-base) !important;
    }
}

.page-header-row {
    flex-direction: column;
    align-items: stretch;
}

@media (min-width: 768px) {
    .page-header-row {
        flex-direction: row;
        align-items: flex-end;
    }
}

/* Modals / overlays */
dialog,
.modal-panel,
[role="dialog"] {
    max-height: 90dvh;
    overflow-y: auto;
}

/* Tenant / compliance blocks */
@media (max-width: 1024px) {
    .tenant-row-grid,
    .admin-tenant-actions {
        flex-direction: column;
        align-items: stretch;
    }
}

.admin-central-portal .card-padded {
    padding: var(--app-card-pad, clamp(1rem, 2.5vw, 1.75rem));
}

.admin-central-portal .main-content table,
.admin-central-portal .data-table,
.admin-central-portal .app-data-table {
    font-size: var(--app-table-font);
}

.admin-central-portal .main-content table th,
.admin-central-portal .main-content table td,
.admin-central-portal .data-table th,
.admin-central-portal .data-table td {
    padding: var(--app-table-pad-y) var(--app-table-pad-x);
    font-size: inherit;
    line-height: 1.35;
}

.admin-central-portal .main-content table th,
.admin-central-portal .data-table th {
    font-size: var(--app-table-header-font, var(--app-table-font));
}

@media (max-width: 768px) {
    .admin-central-portal .card-padded {
        padding: var(--app-card-pad) !important;
    }

    .dashboard-layout > .main-content,
    .dashboard-layout > main.main-content {
        padding-inline: var(--app-page-pad-inline, clamp(0.875rem, 2.5vw, 1rem));
    }
}

.btn-admin-primary,
.btn-admin-secondary {
    font-size: var(--text-fluid-sm);
}

@media (min-width: 768px) {
    .btn-admin-primary,
    .btn-admin-secondary {
        font-size: var(--text-fluid-base);
    }
}
