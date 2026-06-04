{{-- Owner portal responsive layout — 320px through 4K (raw CSS — include inside <style> only) --}}
body.owner-nav-page .main-content.with-owner-nav,
body.owner-nav-page main.with-owner-nav.owner-app-main,
body.owner-nav-page .page-shell.with-owner-nav {
    box-sizing: border-box;
    width: 100%;
    max-width: min(var(--app-content-max-wide, 96rem), 100%);
    margin-left: auto !important;
    margin-right: auto !important;
}

@media (min-width: 2560px) {
    body.owner-nav-page .main-content.with-owner-nav,
    body.owner-nav-page main.with-owner-nav.owner-app-main,
    body.owner-nav-page .page-shell.with-owner-nav {
        padding-left: clamp(2rem, 5vw, 4rem) !important;
        padding-right: clamp(2rem, 5vw, 4rem) !important;
    }
}

body.owner-nav-page .page-header h1 {
    font-size: var(--text-fluid-2xl);
}

@media (max-width: 768px) {
    body.owner-nav-page .page-header-row,
    body.owner-nav-page .owner-page-top {
        flex-direction: column;
        align-items: stretch;
    }

    body.owner-nav-page .page-header h1 {
        font-size: var(--text-fluid-lg) !important;
    }

    body.owner-nav-page .page-header p,
    body.owner-nav-page .panel-header p {
        font-size: var(--text-fluid-xs) !important;
    }

    body.owner-nav-page .panel-header h1 {
        font-size: var(--text-fluid-lg) !important;
    }

    body.owner-nav-page .main-content,
    body.owner-nav-page .owner-app-main {
        font-size: var(--text-fluid-sm);
    }

    body.owner-nav-page .main-content table,
    body.owner-nav-page .app-data-table {
        font-size: var(--app-table-font, 0.6875rem) !important;
    }

    body.owner-nav-page .main-content table th,
    body.owner-nav-page .main-content table td,
    body.owner-nav-page .app-data-table th,
    body.owner-nav-page .app-data-table td {
        padding: var(--app-table-pad-y, 0.375rem) var(--app-table-pad-x, 0.5rem) !important;
        line-height: 1.3;
    }

    body.owner-nav-page .status-badge {
        font-size: var(--text-fluid-xs) !important;
    }

    body.owner-nav-page input,
    body.owner-nav-page select,
    body.owner-nav-page textarea {
        font-size: var(--text-fluid-sm) !important;
    }
}

@media (max-width: 480px) {
    body.owner-nav-page .page-header h1,
    body.owner-nav-page .panel-header h1 {
        font-size: var(--text-fluid-base) !important;
    }
}

body.owner-nav-page .main-content table th,
body.owner-nav-page .main-content table td {
    font-size: inherit;
}

body.owner-nav-page .main-content table th {
    font-size: var(--app-table-header-font, var(--app-table-font));
}

body.owner-nav-page .kpi-value,
body.owner-nav-page .owner-dash-kpi .kpi-value,
body.owner-nav-page .kpi .kpi-value {
    font-size: var(--text-fluid-xl);
}

body.owner-nav-page .owner-dash-block,
body.owner-nav-page .card-padded {
    padding: var(--app-card-pad);
    font-size: var(--text-fluid-sm);
}

@media (max-width: 768px) {
    body.owner-nav-page .kpi-value,
    body.owner-nav-page .owner-dash-kpi .kpi-value,
    body.owner-nav-page .kpi .kpi-value {
        font-size: var(--text-fluid-lg) !important;
    }

    body.owner-nav-page .owner-dash-block,
    body.owner-nav-page .card {
        padding: var(--app-card-pad) !important;
    }

    body.owner-nav-page .main-content.with-owner-nav,
    body.owner-nav-page main.with-owner-nav.owner-app-main {
        padding-left: var(--app-page-pad-inline) !important;
        padding-right: var(--app-page-pad-inline) !important;
    }
}

@media (max-width: 480px) {
    body.owner-nav-page .kpi-value,
    body.owner-nav-page .kpi .kpi-value {
        font-size: var(--text-fluid-base) !important;
    }
}
