html, body { max-width: 100%; overflow-x: hidden; }
img, video, iframe { max-width: 100%; height: auto; }

@media (max-width: 768px) {
    .card, .panel { overflow-x: auto; }
    table { display: block; width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .main-content, main.main-content, .dashboard-layout > main { padding-left: 14px !important; padding-right: 14px !important; }
    .page-header h1, h1, .page-title { font-size: 1.5rem !important; }
    .page-header-row { align-items: stretch; }
    .page-header-row > * { width: 100%; }
    .kpi-grid, .stats-grid, .grid-2, .grid-3, .grid-4, .accommodations-grid { grid-template-columns: 1fr !important; gap: 14px !important; }
}

@media (max-width: 480px) {
    .main-content { padding-left: 10px !important; padding-right: 10px !important; }
    .page-header h1 { font-size: 1.25rem !important; }
    .nav-btn, .btn, button.primary, .btn-admin-primary, .btn-admin-secondary { width: 100%; justify-content: center; }
}
