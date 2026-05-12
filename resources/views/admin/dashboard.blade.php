<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('admin.partials.favicon')
    <title>Admin Dashboard - IMPASUGONG TOURISM</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    <style>
        @include('admin.partials.admin-shell-styles')
        @include('partials.ui-foundation-styles')

        .page-header {
            margin-bottom: 30px;
        }
        /* Title styles inherited from admin-shell-styles for consistency */
        
        /* KPI snapshot — flat tints (system greens + neutral slate), minimal chrome */
        .kpi-surface {
            margin-bottom: 22px;
            display: flex;
            flex-direction: column;
            gap: 18px;
        }
        .kpi-region-title {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: #64748b;
            margin: 0 0 10px 2px;
        }
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 0;
            width: 100%;
            align-items: stretch;
        }
        .kpi-card {
            background: var(--white);
            padding: 18px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.06);
            display: flex;
            align-items: stretch;
            gap: 16px;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            border: 1px solid rgba(27, 94, 32, 0.34);
            min-height: 96px;
            height: 100%;
            font-family: inherit;
        }
        .kpi-card:hover {
            border-color: rgba(27, 94, 32, 0.55);
            box-shadow: 0 6px 20px rgba(27, 94, 32, 0.08);
        }
        .kpi-card--link {
            text-decoration: none;
            color: inherit;
            cursor: pointer;
        }
        .kpi-card--link:hover .kpi-info h3 {
            color: #14532d;
        }
        .kpi-card--link:focus-visible {
            outline: 2px solid var(--green-primary);
            outline-offset: 2px;
        }
        .kpi-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
            border: 1px solid transparent;
            align-self: center;
        }
        /* Flat icon tiles — hue variations stay within brand + one neutral */
        .kpi-icon.tone-primary {
            background: #e8f5e9;
            border-color: #c8e6c9;
            color: var(--green-dark);
        }
        .kpi-icon.tone-mint {
            background: #ecfdf5;
            border-color: #d1fae5;
            color: #047857;
        }
        .kpi-icon.tone-neutral {
            background: #f8fafc;
            border-color: #e2e8f0;
            color: #475569;
        }
        .kpi-icon.tone-amber {
            background: #fffbeb;
            border-color: #fde68a;
            color: #b45309;
        }
        .kpi-icon.tone-warm {
            background: #fff7ed;
            border-color: #fdba74;
            color: #c2410c;
        }
        .kpi-info {
            min-width: 0;
            flex: 1;
            display: grid;
            grid-template-rows: 1.85rem 1.15rem 1rem;
            align-content: center;
            row-gap: 2px;
        }
        .kpi-info h3 {
            font-size: 1.55rem;
            color: var(--green-dark);
            margin: 0;
            font-weight: 700;
            line-height: 1.15;
            letter-spacing: -0.02em;
            display: flex;
            align-items: end;
        }
        .kpi-info h3.kpi-value-compact {
            font-size: 1rem;
            font-weight: 700;
            line-height: 1.18;
            word-break: break-word;
            align-items: center;
        }
        .kpi-info h3.kpi-value-empty {
            font-size: 1.25rem;
            font-weight: 600;
            color: #94a3b8;
        }
        .kpi-info p {
            color: var(--gray-500);
            font-size: 0.8rem;
            font-weight: 500;
            line-height: 1.2;
            margin: 0;
            display: flex;
            align-items: center;
        }
        .kpi-info .kpi-sub {
            display: block;
            font-size: 0.72rem;
            color: #94a3b8;
            margin: 0;
            font-weight: 500;
            line-height: 1.2;
            min-height: 1rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .kpi-sub--blank {
            visibility: hidden;
        }
        
        /* Dashboard Card */
        .dashboard-card {
            background: var(--white);
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(27, 94, 32, 0.08);
            padding: 18px;
            margin-bottom: 18px;
            border: 1px solid var(--green-soft);
        }
        .dashboard-card h3 { 
            font-size: 1rem; 
            color: var(--gray-800); 
            margin-bottom: 14px; 
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .dashboard-card h3 .icon { color: var(--green-primary); }

        .filter-card {
            background: var(--white);
            border: 1px solid var(--green-soft);
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(27, 94, 32, 0.08);
            padding: 18px;
            margin-bottom: 18px;
        }
        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 12px;
            align-items: end;
        }
        .filter-field label {
            display: block;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            color: var(--gray-500);
            margin-bottom: 6px;
            font-weight: 600;
        }
        .filter-field input,
        .filter-field select {
            width: 100%;
            border: 1px solid var(--gray-300);
            border-radius: 10px;
            padding: 9px 11px;
            font-size: 0.9rem;
            color: var(--gray-700);
            background: var(--white);
        }
        .btn-filter {
            border-radius: 10px;
            padding: 10px 16px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 42px;
            line-height: 1.2;
            text-decoration: none;
            box-sizing: border-box;
            transition:
                background 0.15s ease,
                border-color 0.15s ease,
                box-shadow 0.15s ease,
                color 0.15s ease,
                transform 0.12s ease;
        }
        .btn-filter:active:not(:disabled) {
            transform: translateY(1px);
        }
        .btn-filter:focus-visible {
            outline: 2px solid var(--green-primary);
            outline-offset: 2px;
        }
        .btn-filter.primary {
            background: var(--green-primary);
            color: white;
            border: 1px solid rgba(27, 94, 32, 0.35);
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.06);
        }
        .btn-filter.primary:hover {
            background: var(--green-dark);
        }
        .btn-filter.secondary {
            background: var(--gray-100);
            color: var(--gray-700);
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        }
        .btn-filter.secondary:hover {
            background: #e2e8f0;
            border-color: #cbd5e1;
        }
        /* Demographics: full-width action bar under filters */
        .demographics-report-actions {
            margin-top: 14px;
            padding-top: 14px;
            border-top: 1px solid rgba(184, 214, 186, 0.65);
            display: flex;
            flex-wrap: wrap;
            align-items: stretch;
            gap: 10px;
        }
        .demographics-report-actions .demographics-export-form {
            display: inline;
            margin: 0;
        }
        .btn-filter--view {
            background: #ffffff;
            color: #14532d;
            border: 1px solid #86efac;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.06);
        }
        .btn-filter--view:hover {
            background: #ecfdf5;
            border-color: #4ade80;
        }
        .btn-filter--pdf {
            background: linear-gradient(180deg, #ffffff 0%, #fff1f2 100%);
            color: #9f1239;
            border: 1px solid #fecdd3;
            box-shadow: 0 1px 2px rgba(159, 18, 57, 0.08);
        }
        .btn-filter--pdf:hover {
            background: #ffe4e6;
            border-color: #fb7185;
        }
        .btn-filter--csv {
            background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
            color: #0f172a;
            border: 1px solid #cbd5e1;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.05);
        }
        .btn-filter--csv:hover {
            background: #e2e8f0;
            border-color: #94a3b8;
        }
        /* Booking Demographics — KPI summary table + chart panels */
        .pbi-demographics-inner {
            padding: 14px 16px 16px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }
        .pbi-callout-warn {
            margin: 14px 16px 0;
            padding: 11px 14px;
            border-radius: 10px;
            border: 1px solid #FCD34D;
            background: #FFFBEB;
            color: #92400E;
            font-size: 0.88rem;
            line-height: 1.45;
        }
        .pbi-callout-warn code {
            font-size: 0.82rem;
        }
        .pbi-kpi-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid rgba(34, 197, 94, 0.22);
            box-shadow: 0 2px 14px rgba(22, 101, 52, 0.06);
            background: #fff;
        }
        .pbi-kpi-table thead th {
            background: #ecfdf5;
            color: #14532d;
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 10px 12px;
            text-align: center;
            border-bottom: 1px solid rgba(34, 197, 94, 0.25);
            border-right: 1px solid rgba(34, 197, 94, 0.12);
        }
        .pbi-kpi-table thead th:last-child { border-right: none; }
        .pbi-kpi-table tbody td {
            padding: 14px 12px;
            text-align: center;
            vertical-align: middle;
            border-right: 1px solid var(--gray-200);
            background: #fff;
        }
        .pbi-kpi-table tbody td:last-child { border-right: none; }
        .pbi-kpi-value {
            display: inline-block;
            font-size: 1.35rem;
            font-weight: 800;
            color: #166534;
            font-variant-numeric: tabular-nums;
            line-height: 1.2;
        }
        .pbi-demographics-charts {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            align-items: stretch;
        }
        .pbi-chart-panel {
            display: flex;
            flex-direction: column;
            min-width: 0;
            border-radius: 12px;
            border: 1px solid rgba(34, 197, 94, 0.22);
            background: #fff;
            box-shadow: 0 4px 18px rgba(22, 101, 52, 0.07);
            overflow: hidden;
        }
        .pbi-chart-panel-head {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 12px;
            font-size: 0.88rem;
            font-weight: 600;
            color: #14532d;
            background: #f7fcf8;
            border-bottom: 1px solid rgba(34, 197, 94, 0.12);
        }
        .pbi-chart-panel-head i {
            color: var(--green-primary);
            font-size: 1rem;
        }
        .pbi-chart-panel-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 8px 10px 12px;
            min-height: 0;
        }
        .pbi-chart-panel-body .chart-container-sm {
            height: 220px;
        }
        .pbi-breakdown-grid {
            margin-top: 10px;
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px;
        }
        .pbi-mini-table-wrap {
            background: #fafdfb;
            border: 1px solid rgba(34, 197, 94, 0.15);
            border-radius: 8px;
            padding: 8px 6px 6px;
            min-height: 0;
        }
        .pbi-mini-table-title {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #166534;
            padding: 0 4px 6px;
            margin: 0;
        }
        .pbi-mini-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.78rem;
        }
        .pbi-mini-table th,
        .pbi-mini-table td {
            padding: 5px 6px;
            text-align: left;
            border-bottom: 1px solid rgba(226, 232, 240, 0.9);
        }
        .pbi-mini-table thead th {
            color: var(--gray-600);
            font-weight: 600;
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            background: transparent;
        }
        .pbi-mini-table thead th.pbi-num { text-align: right; }
        .pbi-mini-table tbody tr:last-child td {
            border-bottom: none;
        }
        .pbi-mini-table td.pbi-num {
            text-align: right;
            font-variant-numeric: tabular-nums;
            font-weight: 700;
            color: #14532d;
        }
        .pbi-mini-table td.pbi-muted {
            color: var(--gray-500);
            font-style: italic;
        }
        
        /* Content Grid */
        .content-grid { display: grid; grid-template-columns: 320px 1fr; gap: 20px; }
        .content-left { display: flex; flex-direction: column; gap: 15px; }
        .content-right { display: flex; flex-direction: column; gap: 15px; }
        
        /* Chart Container */
        .chart-container { position: relative; height: 280px; }
        .chart-container-sm { position: relative; height: 250px; }

        /* Power BI–inspired visual wrapper (read-only analytics card — not actual Power BI embed) */
        .dashboard-card.pbi-visual {
            padding: 0;
            background: transparent;
            border: none;
            box-shadow: none;
        }
        .pbi-visual {
            padding: 0;
            overflow: hidden;
            border-radius: 14px;
            border: 1px solid rgba(34, 197, 94, 0.22);
            box-shadow: 0 8px 28px rgba(22, 101, 52, 0.07);
            background: #fff;
        }
        .pbi-visual-header {
            background: #166534;
            color: #ffffff;
            padding: 12px 16px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
        }
        .pbi-visual-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            font-size: 0.95rem;
            letter-spacing: 0.01em;
            color: #ffffff;
        }
        .pbi-visual-title i {
            color: #bbf7d0;
            font-size: 1rem;
        }
        .pbi-visual-meta {
            font-size: 0.7rem;
            color: #ecfdf5;
            text-align: right;
            line-height: 1.35;
        }
        .pbi-visual-meta .pbi-meta-subtle {
            color: rgba(255, 255, 255, 0.78);
            display: inline-block;
            margin-top: 2px;
        }
        .pbi-visual-body {
            background: #fafdfb;
            padding: 14px 14px 10px;
        }
        .pbi-visual-body--demographics {
            padding: 0;
            background: #fafdfb;
        }
        .scroll-target {
            scroll-margin-top: calc(var(--app-main-top-offset, 108px) + 12px);
        }
        .pill-guest-count {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 999px;
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--green-dark);
            background: #e8f5e9;
            border: 1px solid #c8e6c9;
        }
        .btn-pdf-sm {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--green-primary);
            color: #fff;
            border: 1px solid rgba(27, 94, 32, 0.35);
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 600;
            transition: background 0.15s ease;
        }
        .btn-pdf-sm:hover {
            background: var(--green-dark);
        }
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th, .data-table td { padding: 14px; text-align: left; border-bottom: 1px solid var(--gray-200); }
        .data-table th { font-weight: 600; color: var(--gray-600); font-size: 0.8rem; text-transform: uppercase; background: var(--cream); }
        .data-table tr:hover { background: var(--green-white); }
        
        /* Status Badges */
        .status-badge { display: inline-flex; align-items: center; padding: 6px 12px; border-radius: 50px; font-size: 0.75rem; font-weight: 600; }
        .status-badge.active { background: #dcfce7; color: var(--green-dark); border: 1px solid #bbf7d0; }
        .status-badge.pending { background: #fffbeb; color: #B45309; border: 1px solid #fde68a; }
        .status-badge.confirmed { background: #eff6ff; color: #1D4ED8; border: 1px solid #bfdbfe; }
        .status-badge.cancelled { background: #f3f4f6; color: var(--gray-600); border: 1px solid #e5e7eb; }
        .status-badge.completed { background: #e8f5e9; color: var(--green-dark); border: 1px solid #c8e6c9; }
        .status-badge.past-due { background: #fee2e2; color: #B91C1C; border: 1px solid #fecaca; }
        .status-badge.trialing { background: #fffbeb; color: #92400E; border: 1px solid #fde68a; }

        .table-note {
            color: var(--gray-500);
            font-size: 0.85rem;
            margin-bottom: 14px;
        }
        
        /* Quick Stats Grid */
        .quick-stats-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
        .quick-stat-card {
            background: #fafdfb;
            padding: 16px;
            border-radius: 12px;
            text-align: center;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            border: 1px solid #e0ebe1;
            min-height: 110px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .quick-stat-card:hover { 
            background: #f4faf5;
            border-color: #c8e6c9;
            box-shadow: 0 4px 14px rgba(27, 94, 32, 0.07);
        }
        .quick-stat-card .icon { font-size: 1.6rem; color: var(--green-primary); margin-bottom: 6px; }
        .quick-stat-card h4 { font-size: 1.3rem; color: var(--green-dark); margin-bottom: 3px; font-weight: 700; }
        .quick-stat-card p { color: var(--gray-600); font-size: 0.8rem; }
        
        /* Gear Icon (Settings) - Icon Only */
        .settings-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: #e8f5e9;
            border: 1px solid #c8e6c9;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--green-dark);
            font-size: 1.2rem;
            transition: background 0.2s ease, color 0.2s ease, border-color 0.2s ease;
            cursor: pointer;
        }
        .settings-icon:hover {
            background: var(--green-primary);
            border-color: var(--green-primary);
            color: white;
            transform: rotate(90deg);
        }

        @media (max-width: 1200px) {
            .content-grid { grid-template-columns: 1fr; }
            .pbi-demographics-charts { grid-template-columns: 1fr; }
        }

        @media (max-width: 1100px) {
            .kpi-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media (max-width: 768px) {
            .kpi-grid { grid-template-columns: 1fr; }
            .pbi-breakdown-grid { grid-template-columns: 1fr; }
            .pbi-kpi-table { display: block; overflow-x: auto; -webkit-overflow-scrolling: touch; }
            .pbi-kpi-table thead th { font-size: 0.6rem; padding: 8px 8px; }
            .pbi-kpi-table tbody td { padding: 12px 8px; }
            .pbi-kpi-value { font-size: 1.15rem; }
        }
        
        /* Animations */
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate { animation: fadeInUp 0.6s ease forwards; }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }

    </style>
</head>
<body class="admin-central-portal">
    <!-- Navigation -->
    @include('admin.partials.top-navbar', ['active' => 'dashboard'])
    
    <!-- Dashboard Layout -->
    <div class="dashboard-layout">
        <!-- Main Content -->
        <main class="main-content">
            @include('partials.flash-alerts')

            <!-- Page Header -->
            <div class="page-header animate">
                <h1>
                    <span class="page-title-icon"><i class="fa-solid fa-gauge-high"></i></span>
                    <span>Admin Dashboard</span>
                </h1>
                <p>Platform activity, bookings, and guest demographics.</p>
            </div>

            <div class="filter-card animate delay-1">
                <h3 style="margin-bottom: 12px; display:flex; align-items:center; gap:8px;"><i class="fas fa-filter icon"></i>Demographics Filters & Reports</h3>
                <form method="GET" action="{{ route('admin.dashboard', [], false) }}" class="filters-grid">
                    <div class="filter-field">
                        <label for="tenant_id">Tenant Scope</label>
                        <select id="tenant_id" name="tenant_id">
                            <option value="">All tenants</option>
                            @foreach($tenantFilterOptions as $tenantOption)
                                <option value="{{ $tenantOption->id }}" @selected((int) ($selectedTenantId ?? 0) === (int) $tenantOption->id)>{{ $tenantOption->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-field">
                        <label for="start_date">Start Date</label>
                        <input id="start_date" type="date" name="start_date" value="{{ optional($demographicsStartDate)->toDateString() }}">
                    </div>
                    <div class="filter-field">
                        <label for="end_date">End Date</label>
                        <input id="end_date" type="date" name="end_date" value="{{ optional($demographicsEndDate)->toDateString() }}">
                    </div>
                    <div class="filter-field" style="display:flex; gap:8px;">
                        <button type="submit" class="btn-filter primary"><i class="fas fa-chart-line"></i> Apply</button>
                        <a href="{{ route('admin.dashboard', [], false) }}" class="btn-filter secondary">Reset</a>
                    </div>
                </form>
                <div class="demographics-report-actions" role="toolbar" aria-label="Demographics reports and exports">
                    <a class="btn-filter btn-filter--view"
                       href="{{ route('admin.reports.demographics', array_filter(['tenant_id' => $selectedTenantId, 'start_date' => optional($demographicsStartDate)->toDateString(), 'end_date' => optional($demographicsEndDate)->toDateString()]), false) }}">
                        <i class="fas fa-eye" aria-hidden="true"></i>
                        <span>View Demographics Report</span>
                    </a>
                    <form method="POST" action="{{ route('admin.reports.demographics.export', [], false) }}" class="demographics-export-form" data-loading-form>
                        @csrf
                        <input type="hidden" name="format" value="pdf">
                        @if($selectedTenantId !== null)
                            <input type="hidden" name="tenant_id" value="{{ $selectedTenantId }}">
                        @endif
                        <input type="hidden" name="start_date" value="{{ optional($demographicsStartDate)->toDateString() }}">
                        <input type="hidden" name="end_date" value="{{ optional($demographicsEndDate)->toDateString() }}">
                        <button type="submit" class="btn-filter btn-filter--pdf" data-loading-button data-loading-label="Preparing PDF…">
                            <i class="fas fa-file-pdf" aria-hidden="true"></i>
                            <span>Export PDF</span>
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.reports.demographics.export', [], false) }}" class="demographics-export-form" data-loading-form>
                        @csrf
                        <input type="hidden" name="format" value="csv">
                        @if($selectedTenantId !== null)
                            <input type="hidden" name="tenant_id" value="{{ $selectedTenantId }}">
                        @endif
                        <input type="hidden" name="start_date" value="{{ optional($demographicsStartDate)->toDateString() }}">
                        <input type="hidden" name="end_date" value="{{ optional($demographicsEndDate)->toDateString() }}">
                        <button type="submit" class="btn-filter btn-filter--csv" data-loading-button data-loading-label="Preparing CSV…">
                            <i class="fas fa-file-csv" aria-hidden="true"></i>
                            <span>Export CSV</span>
                        </button>
                    </form>
                </div>
            </div>

            <section class="kpi-surface animate delay-1" aria-label="Key metrics">
                <div class="kpi-region">
                    <h2 class="kpi-region-title">Volume &amp; engagement</h2>
                    <div class="kpi-grid">
                        <a href="#admin-demographics" class="kpi-card kpi-card--link">
                            <div class="kpi-icon tone-primary"><i class="fas fa-ticket-alt" aria-hidden="true"></i></div>
                            <div class="kpi-info">
                                <h3>{{ number_format($kpis['total_bookings'] ?? 0) }}</h3>
                                <p>Total bookings</p>
                                <span class="kpi-sub">Tied to demographics scope below</span>
                            </div>
                        </a>
                        <a href="#admin-demographics" class="kpi-card kpi-card--link">
                            <div class="kpi-icon tone-mint"><i class="fas fa-users" aria-hidden="true"></i></div>
                            <div class="kpi-info">
                                <h3>{{ number_format($kpis['active_clients'] ?? 0) }}</h3>
                                <p>Active guests</p>
                                <span class="kpi-sub kpi-sub--blank" aria-hidden="true">&nbsp;</span>
                            </div>
                        </a>
                        <a href="{{ route('admin.tenants', [], false) }}" class="kpi-card kpi-card--link">
                            <div class="kpi-icon tone-neutral"><i class="fas fa-user-group" aria-hidden="true"></i></div>
                            <div class="kpi-info">
                                <h3>{{ number_format($kpis['total_users'] ?? 0) }}</h3>
                                <p>Total users</p>
                                <span class="kpi-sub kpi-sub--blank" aria-hidden="true">&nbsp;</span>
                            </div>
                        </a>
                        <a href="#admin-tenant-bookings" class="kpi-card kpi-card--link">
                            <div class="kpi-icon tone-warm"><i class="fas fa-clock" aria-hidden="true"></i></div>
                            <div class="kpi-info">
                                <h3>{{ number_format($kpis['pending_bookings'] ?? 0) }}</h3>
                                <p>Pending bookings</p>
                                <span class="kpi-sub kpi-sub--blank" aria-hidden="true">&nbsp;</span>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="kpi-region">
                    <h2 class="kpi-region-title">Pipeline &amp; portfolio</h2>
                    <div class="kpi-grid">
                        <a href="{{ route('admin.tenants', ['onboarding_status' => \App\Models\Tenant::ONBOARDING_PENDING_APPROVAL]) }}" class="kpi-card kpi-card--link">
                            <div class="kpi-icon {{ ($kpis['pending_host_applications'] ?? 0) > 0 ? 'tone-amber' : 'tone-mint' }}"><i class="fas fa-file-signature" aria-hidden="true"></i></div>
                            <div class="kpi-info">
                                <h3>{{ number_format($kpis['pending_host_applications'] ?? 0) }}</h3>
                                <p>Pending host applications</p>
                                <span class="kpi-sub kpi-sub--blank" aria-hidden="true">&nbsp;</span>
                            </div>
                        </a>
                        <a href="#admin-activity-overview" class="kpi-card kpi-card--link">
                            <div class="kpi-icon tone-mint"><i class="fas fa-percent" aria-hidden="true"></i></div>
                            <div class="kpi-info">
                                <h3>{{ number_format($kpis['occupancy_rate'] ?? 0, 1) }}%</h3>
                                <p>Occupancy (this month)</p>
                                <span class="kpi-sub">Booked nights vs capacity</span>
                            </div>
                        </a>
                        <a href="{{ route('admin.owner.accommodations.index', [], false) }}" class="kpi-card kpi-card--link">
                            <div class="kpi-icon tone-primary"><i class="fas fa-check-circle" aria-hidden="true"></i></div>
                            <div class="kpi-info">
                                <h3>{{ number_format($kpis['verified_properties'] ?? 0) }}</h3>
                                <p>Verified units</p>
                                <span class="kpi-sub kpi-sub--blank" aria-hidden="true">&nbsp;</span>
                            </div>
                        </a>
                        @if($topTenantByBookings)
                            <a href="{{ route('admin.tenants', [], false) }}" class="kpi-card kpi-card--link">
                                <div class="kpi-icon tone-amber"><i class="fas fa-trophy" aria-hidden="true"></i></div>
                                <div class="kpi-info">
                                    <h3 class="kpi-value-compact">{{ $topTenantByBookings->name }}</h3>
                                    <p>Top tenant by bookings</p>
                                    <span class="kpi-sub">Current period leader</span>
                                </div>
                            </a>
                        @else
                            <div class="kpi-card" role="status">
                                <div class="kpi-icon tone-neutral"><i class="fas fa-trophy" aria-hidden="true"></i></div>
                                <div class="kpi-info">
                                    <h3 class="kpi-value-empty">&mdash;</h3>
                                    <p>Top tenant by bookings</p>
                                    <span class="kpi-sub">No leader in the current period</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </section>

            <div id="admin-demographics" class="dashboard-card pbi-visual animate delay-1 scroll-target">
                <div class="pbi-visual-header">
                    <div class="pbi-visual-title">
                        <i class="fas fa-people-group" aria-hidden="true"></i>
                        <span>Booking Demographics</span>
                    </div>
                    <div class="pbi-visual-meta">
                        {{ $demographics['scope_label'] ?? 'All tenants' }}<br>
                        <span class="pbi-meta-subtle">{{ optional($demographicsStartDate)->toFormattedDateString() }} – {{ optional($demographicsEndDate)->toFormattedDateString() }}</span>
                    </div>
                </div>
                <div class="pbi-visual-body pbi-visual-body--demographics">
                    @if(empty($demographics['columns_ready']))
                        <div class="pbi-callout-warn" role="status">
                            @if(\App\Support\SingleDbMigrationMode::unifiedSchema())
                                Demographic columns are not on the unified <code>bookings</code> table yet. With single-database mode, tenant data lives in the same database as the landlord schema. Apply migrations:
                                <code style="display:block; margin-top:8px; padding:8px 10px; background:#fff; border-radius:6px;">php artisan single-db:migrate</code>
                            @else
                                Demographic columns are not on tenant <code>bookings</code> tables yet. Bookings live in each tenant database; plain <code>php artisan migrate</code> only touches the landlord DB. Run tenant schema migrations:
                                <code style="display:block; margin-top:8px; padding:8px 10px; background:#fff; border-radius:6px;">php artisan tenants:migrate</code>
                                One tenant: <code>php artisan tenants:migrate YOUR_TENANT_ID</code>
                            @endif
                        </div>
                    @endif

                    <div class="pbi-demographics-inner">
                        <table class="pbi-kpi-table" aria-label="Demographics summary for selected scope">
                            <thead>
                                <tr>
                                    <th scope="col">Bookings in scope</th>
                                    <th scope="col">Guests in scope</th>
                                    <th scope="col">Profiled bookings</th>
                                    <th scope="col">Average age</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="pbi-kpi-value">{{ number_format($demographics['total_bookings'] ?? 0) }}</span></td>
                                    <td><span class="pbi-kpi-value">{{ number_format($demographics['total_guests'] ?? 0) }}</span></td>
                                    <td><span class="pbi-kpi-value">{{ number_format($demographics['profiled_bookings'] ?? 0) }}</span></td>
                                    <td><span class="pbi-kpi-value">{{ isset($demographics['average_age']) && $demographics['average_age'] !== null ? $demographics['average_age'] : 'N/A' }}</span></td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="pbi-demographics-charts">
                            <div class="pbi-chart-panel">
                                <div class="pbi-chart-panel-head">
                                    <i class="fas fa-venus-mars" aria-hidden="true"></i>
                                    Gender distribution
                                </div>
                                <div class="pbi-chart-panel-body">
                                    <div class="chart-container-sm">
                                        <canvas id="genderChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="pbi-chart-panel">
                                <div class="pbi-chart-panel-head">
                                    <i class="fas fa-map-marked-alt" aria-hidden="true"></i>
                                    Location distribution
                                </div>
                                <div class="pbi-chart-panel-body">
                                    <div class="chart-container-sm">
                                        <canvas id="locationChart"></canvas>
                                    </div>
                                    <div class="pbi-breakdown-grid">
                                        <div class="pbi-mini-table-wrap">
                                            <p class="pbi-mini-table-title">Local places</p>
                                            <table class="pbi-mini-table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Place</th>
                                                        <th scope="col" class="pbi-num">Count</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse(collect($demographics['location']['breakdown']['local_labels'] ?? [])->take(5) as $i => $label)
                                                        <tr>
                                                            <td>{{ $label }}</td>
                                                            <td class="pbi-num">{{ $demographics['location']['breakdown']['local_counts'][$i] ?? 0 }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="2" class="pbi-muted">No local breakdown yet</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="pbi-mini-table-wrap">
                                            <p class="pbi-mini-table-title">Foreign countries</p>
                                            <table class="pbi-mini-table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Country</th>
                                                        <th scope="col" class="pbi-num">Count</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse(collect($demographics['location']['breakdown']['foreign_labels'] ?? [])->take(5) as $i => $label)
                                                        <tr>
                                                            <td>{{ $label }}</td>
                                                            <td class="pbi-num">{{ $demographics['location']['breakdown']['foreign_counts'][$i] ?? 0 }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="2" class="pbi-muted">No foreign breakdown yet</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="pbi-chart-panel">
                                <div class="pbi-chart-panel-head">
                                    <i class="fas fa-user-clock" aria-hidden="true"></i>
                                    Age distribution
                                </div>
                                <div class="pbi-chart-panel-body">
                                    <div class="chart-container-sm">
                                        <canvas id="ageChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Content Grid -->
            <div class="content-grid">
                <!-- Left Column -->
                <div class="content-left">
                    <!-- Business KPI Overview -->
                    <div id="admin-activity-overview" class="dashboard-card animate delay-2 scroll-target">
                        <h3><i class="fas fa-bullseye icon"></i>Activity overview</h3>
                        <div class="quick-stats-grid">
                            <div class="quick-stat-card">
                                <div class="icon"><i class="fas fa-percent"></i></div>
                                <h4>{{ number_format($kpis['occupancy_rate'] ?? 0, 1) }}%</h4>
                                <p>Occupancy (this month)</p>
                            </div>
                            <div class="quick-stat-card">
                                <div class="icon"><i class="fas fa-clock"></i></div>
                                <h4>{{ number_format($kpis['pending_bookings'] ?? 0) }}</h4>
                                <p>Pending Bookings</p>
                            </div>
                            <div class="quick-stat-card">
                                <div class="icon"><i class="fas fa-home"></i></div>
                                <h4>{{ number_format($kpis['total_accommodations'] ?? 0) }}</h4>
                                <p>Total Units</p>
                            </div>
                            <div class="quick-stat-card">
                                <div class="icon"><i class="fas fa-check-circle"></i></div>
                                <h4>{{ number_format($kpis['verified_properties'] ?? 0) }}</h4>
                                <p>Verified Units</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="dashboard-card animate delay-3">
                        <h3><i class="fas fa-chart-pie icon"></i>Bookings by unit type (this month)</h3>
                        <div class="chart-container-sm">
                            <canvas id="bookingsByTypeChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column -->
                <div class="content-right">
                    <div class="dashboard-card pbi-visual animate delay-2">
                        <div class="pbi-visual-header">
                            <div class="pbi-visual-title">
                                <i class="fas fa-chart-column" aria-hidden="true"></i>
                                <span>Bookings per month</span>
                            </div>
                            <div class="pbi-visual-meta">
                                Calendar year · Central aggregate<br>
                                <span class="pbi-meta-subtle">Hover for counts · Line = 3-mo rolling avg</span>
                            </div>
                        </div>
                        <div class="pbi-visual-body">
                            <div class="chart-container">
                                <canvas id="bookingsTrendChart"></canvas>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Guests Per Month (Power BI–style visual, green theme) -->
                    <div class="dashboard-card pbi-visual animate delay-3">
                        <div class="pbi-visual-header">
                            <div class="pbi-visual-title">
                                <i class="fas fa-users" aria-hidden="true"></i>
                                <span>Guests Per Month</span>
                            </div>
                            <div class="pbi-visual-meta">
                                Calendar year · Guest headcount<br>
                                <span class="pbi-meta-subtle">Hover for counts</span>
                            </div>
                        </div>
                        <div class="pbi-visual-body">
                            <div class="chart-container">
                                <canvas id="guestsChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tenant Bookings Today -->
            <div id="admin-tenant-bookings" class="dashboard-card animate delay-4 scroll-target">
                <h3><i class="fas fa-users-check icon"></i>Today's Tenant Bookings</h3>
                <p class="table-note"><i class="fas fa-info-circle"></i> Shows number of guests per tenant with active check-ins today</p>
                @if(isset($tenantBookingsToday) && count($tenantBookingsToday) > 0)
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-building"></i> Tenant Name</th>
                                <th><i class="fas fa-calendar-check"></i> Active Bookings</th>
                                <th><i class="fas fa-users"></i> Total Guests</th>
                                <th><i class="fas fa-download"></i> Monthly Report</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tenantBookingsToday as $booking)
                                <tr>
                                    <td><strong>{{ $booking->name }}</strong></td>
                                    <td>{{ $booking->booking_count }}</td>
                                    <td><span class="pill-guest-count">{{ $booking->total_guests }} guests</span></td>
                                    <td>
                                        <form action="{{ route('admin.monthly-booking-pdf', [], false) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="year" value="{{ now()->year }}">
                                            <input type="hidden" name="month" value="{{ now()->month }}">
                                            <button type="submit" class="btn-pdf-sm">
                                                <i class="fas fa-download"></i> PDF
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            <tr style="background: var(--cream); font-weight: 600;">
                                <td colspan="1"><strong>Total This Month:</strong></td>
                                <td colspan="3">
                                    <span style="color: var(--green-dark);">
                                        {{ $tenantBookingsToday->sum('booking_count') }} Bookings | 
                                        {{ $tenantBookingsToday->sum('total_guests') }} Guests
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                @else
                    <div style="text-align: center; padding: 40px; color: var(--gray-400);">
                        <i class="fas fa-calendar-alt" style="font-size: 3rem; margin-bottom: 15px; color: var(--gray-300);"></i>
                        <p>No active bookings today</p>
                    </div>
                @endif
            </div>

        </main>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const demographics = @json($demographics ?? []);

            const genderChartEl = document.getElementById('genderChart');
            if (genderChartEl) {
                new Chart(genderChartEl.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: demographics.gender?.labels ?? ['Male', 'Female', 'Unspecified'],
                        datasets: [{
                            data: demographics.gender?.counts ?? [0, 0, 0],
                            backgroundColor: ['#14532d', '#22c55e', '#cbd5e1'],
                            borderWidth: 2,
                            borderColor: '#ffffff',
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '58%',
                        animation: { duration: 750, easing: 'easeOutQuart' },
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    padding: 14,
                                    color: '#334155',
                                    font: { size: 11, weight: '500' }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(15, 61, 36, 0.94)',
                                titleColor: '#ecfdf5',
                                bodyColor: '#d1fae5',
                                padding: 12,
                                cornerRadius: 8
                            }
                        }
                    }
                });
            }

            const locationChartEl = document.getElementById('locationChart');
            if (locationChartEl) {
                const locCounts = demographics.location?.counts ?? [0, 0, 0];
                const locMaxRaw = Math.max(...locCounts, 0);
                const locYMax = locMaxRaw === 0 ? 5 : Math.max(4, Math.ceil(locMaxRaw * 1.12));
                new Chart(locationChartEl.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: demographics.location?.labels ?? ['Local', 'Foreign', 'Unspecified'],
                        datasets: [{
                            label: 'Bookings',
                            data: locCounts,
                            backgroundColor: ['#166534', '#4ade80', '#94a3b8'],
                            borderColor: ['#14532d', '#16a34a', '#64748b'],
                            borderWidth: 1,
                            borderRadius: 6,
                            borderSkipped: false,
                            maxBarThickness: 48,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: { duration: 750, easing: 'easeOutQuart' },
                        interaction: { mode: 'index', intersect: false },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(15, 61, 36, 0.94)',
                                titleColor: '#ecfdf5',
                                bodyColor: '#d1fae5',
                                padding: 12,
                                cornerRadius: 8,
                                callbacks: {
                                    label: function (ctx) {
                                        return 'Bookings: ' + (ctx.parsed.y ?? 0);
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { color: 'rgba(34, 197, 94, 0.1)', drawBorder: false },
                                ticks: { color: '#64748b', font: { size: 11 } }
                            },
                            y: {
                                beginAtZero: true,
                                max: locYMax,
                                ticks: { precision: 0, color: '#64748b' },
                                grid: { color: 'rgba(148, 163, 184, 0.2)' },
                                title: {
                                    display: true,
                                    text: 'Bookings',
                                    color: '#166534',
                                    font: { size: 11, weight: '600' }
                                }
                            }
                        }
                    }
                });
            }

            const ageChartEl = document.getElementById('ageChart');
            if (ageChartEl) {
                const actx = ageChartEl.getContext('2d');
                const ageGradient = actx.createLinearGradient(0, 0, 0, 220);
                ageGradient.addColorStop(0, 'rgba(187, 247, 208, 0.98)');
                ageGradient.addColorStop(0.5, 'rgba(74, 222, 128, 0.9)');
                ageGradient.addColorStop(1, 'rgba(22, 101, 52, 0.88)');
                const ageCounts = demographics.age?.counts ?? [0, 0, 0, 0, 0, 0, 0];
                const ageMaxRaw = Math.max(...ageCounts, 0);
                const ageYMax = ageMaxRaw === 0 ? 5 : Math.max(4, Math.ceil(ageMaxRaw * 1.12));
                new Chart(actx, {
                    type: 'bar',
                    data: {
                        labels: demographics.age?.labels ?? ['0-17', '18-24', '25-34', '35-44', '45-54', '55+', 'Unspecified'],
                        datasets: [{
                            label: 'Bookings',
                            data: ageCounts,
                            backgroundColor: ageGradient,
                            borderColor: 'rgba(22, 101, 52, 0.9)',
                            borderWidth: 1,
                            borderRadius: 5,
                            borderSkipped: false,
                            maxBarThickness: 28,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: { duration: 750, easing: 'easeOutQuart' },
                        interaction: { mode: 'index', intersect: false },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(15, 61, 36, 0.94)',
                                titleColor: '#ecfdf5',
                                bodyColor: '#d1fae5',
                                padding: 12,
                                cornerRadius: 8,
                                callbacks: {
                                    label: function (ctx) {
                                        return 'Bookings: ' + (ctx.parsed.y ?? 0);
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { color: '#64748b', maxRotation: 45, minRotation: 0, font: { size: 10 } }
                            },
                            y: {
                                beginAtZero: true,
                                max: ageYMax,
                                ticks: { precision: 0, color: '#64748b' },
                                grid: { color: 'rgba(148, 163, 184, 0.2)' },
                                title: {
                                    display: true,
                                    text: 'Bookings',
                                    color: '#166534',
                                    font: { size: 11, weight: '600' }
                                }
                            }
                        }
                    }
                });
            }

            const bookingsTrendEl = document.getElementById('bookingsTrendChart');
            if (bookingsTrendEl) {
                const monthlyLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                const monthlyBookings = [
                    {{ $monthlyBookingsData['jan'] ?? 0 }},
                    {{ $monthlyBookingsData['feb'] ?? 0 }},
                    {{ $monthlyBookingsData['mar'] ?? 0 }},
                    {{ $monthlyBookingsData['apr'] ?? 0 }},
                    {{ $monthlyBookingsData['may'] ?? 0 }},
                    {{ $monthlyBookingsData['jun'] ?? 0 }},
                    {{ $monthlyBookingsData['jul'] ?? 0 }},
                    {{ $monthlyBookingsData['aug'] ?? 0 }},
                    {{ $monthlyBookingsData['sep'] ?? 0 }},
                    {{ $monthlyBookingsData['oct'] ?? 0 }},
                    {{ $monthlyBookingsData['nov'] ?? 0 }},
                    {{ $monthlyBookingsData['dec'] ?? 0 }}
                ];
                const rollingAverage3 = monthlyBookings.map((_, i) => {
                    const start = Math.max(0, i - 2);
                    const slice = monthlyBookings.slice(start, i + 1);
                    if (!slice.length) return 0;
                    const sum = slice.reduce((a, b) => a + b, 0);
                    return Math.round((sum / slice.length) * 10) / 10;
                });
                const ctx = bookingsTrendEl.getContext('2d');
                const barGradient = ctx.createLinearGradient(0, 0, 0, 300);
                barGradient.addColorStop(0, 'rgba(56, 189, 248, 0.95)');
                barGradient.addColorStop(1, 'rgba(14, 165, 233, 0.45)');

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: monthlyLabels,
                        datasets: [
                            {
                                type: 'bar',
                                label: 'Bookings',
                                data: monthlyBookings,
                                backgroundColor: barGradient,
                                borderColor: 'rgba(14, 165, 233, 0.9)',
                                borderWidth: 1,
                                borderRadius: 6,
                                borderSkipped: false,
                                maxBarThickness: 38,
                                yAxisID: 'y',
                                order: 2
                            },
                            {
                                type: 'line',
                                label: '3-mo avg',
                                data: rollingAverage3,
                                borderColor: '#fbbf24',
                                backgroundColor: 'rgba(251, 191, 36, 0.12)',
                                borderWidth: 2.5,
                                tension: 0.35,
                                fill: true,
                                pointRadius: 3,
                                pointHoverRadius: 6,
                                pointBackgroundColor: '#fbbf24',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 1,
                                yAxisID: 'y',
                                order: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    usePointStyle: true,
                                    padding: 16,
                                    color: '#334155',
                                    font: { size: 12, weight: '600' }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(15, 23, 42, 0.94)',
                                titleColor: '#f1f5f9',
                                bodyColor: '#e2e8f0',
                                padding: 12,
                                cornerRadius: 8,
                                displayColors: true,
                                callbacks: {
                                    label: function (context) {
                                        const v = context.parsed.y;
                                        if (context.dataset.label === '3-mo avg') {
                                            return '3-mo avg: ' + v;
                                        }
                                        return 'Bookings: ' + v;
                                    }
                                }
                            }
                        },
                        animation: {
                            duration: 900,
                            easing: 'easeOutQuart'
                        },
                        scales: {
                            x: {
                                grid: { color: 'rgba(148, 163, 184, 0.2)', drawBorder: false },
                                ticks: { color: '#64748b', font: { size: 11 } }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: { precision: 0, color: '#64748b' },
                                grid: { color: 'rgba(148, 163, 184, 0.22)' },
                                title: {
                                    display: true,
                                    text: 'Count',
                                    color: '#94a3b8',
                                    font: { size: 11, weight: '600' }
                                }
                            }
                        }
                    }
                });
            }

            const guestsChartEl = document.getElementById('guestsChart');
            if (guestsChartEl) {
                const gctx = guestsChartEl.getContext('2d');
                const guestsGradient = gctx.createLinearGradient(0, 0, 0, 300);
                guestsGradient.addColorStop(0, 'rgba(187, 247, 208, 0.98)');
                guestsGradient.addColorStop(0.45, 'rgba(74, 222, 128, 0.92)');
                guestsGradient.addColorStop(1, 'rgba(22, 101, 52, 0.88)');

                new Chart(gctx, {
                    type: 'bar',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        datasets: [{
                            label: 'Guests',
                            data: [
                                {{ $monthlyGuestsData['jan'] ?? 0 }},
                                {{ $monthlyGuestsData['feb'] ?? 0 }},
                                {{ $monthlyGuestsData['mar'] ?? 0 }},
                                {{ $monthlyGuestsData['apr'] ?? 0 }},
                                {{ $monthlyGuestsData['may'] ?? 0 }},
                                {{ $monthlyGuestsData['jun'] ?? 0 }},
                                {{ $monthlyGuestsData['jul'] ?? 0 }},
                                {{ $monthlyGuestsData['aug'] ?? 0 }},
                                {{ $monthlyGuestsData['sep'] ?? 0 }},
                                {{ $monthlyGuestsData['oct'] ?? 0 }},
                                {{ $monthlyGuestsData['nov'] ?? 0 }},
                                {{ $monthlyGuestsData['dec'] ?? 0 }}
                            ],
                            backgroundColor: guestsGradient,
                            borderColor: 'rgba(22, 101, 52, 0.95)',
                            borderWidth: 1,
                            borderRadius: 6,
                            borderSkipped: false,
                            maxBarThickness: 38
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        plugins: {
                            legend: {
                                position: 'top',
                                align: 'end',
                                labels: {
                                    usePointStyle: true,
                                    pointStyle: 'rectRounded',
                                    padding: 16,
                                    color: '#14532d',
                                    font: { size: 12, weight: '600' }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(15, 61, 36, 0.94)',
                                titleColor: '#ecfdf5',
                                bodyColor: '#d1fae5',
                                padding: 12,
                                cornerRadius: 8,
                                displayColors: true,
                                callbacks: {
                                    label: function (context) {
                                        return 'Guests: ' + (context.parsed.y ?? 0);
                                    }
                                }
                            }
                        },
                        animation: {
                            duration: 900,
                            easing: 'easeOutQuart'
                        },
                        scales: {
                            x: {
                                grid: { color: 'rgba(34, 197, 94, 0.12)', drawBorder: false },
                                ticks: { color: '#64748b', font: { size: 11 } }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: { precision: 0, color: '#64748b' },
                                grid: { color: 'rgba(148, 163, 184, 0.22)' },
                                title: {
                                    display: true,
                                    text: 'Guests',
                                    color: '#166534',
                                    font: { size: 11, weight: '600' }
                                }
                            }
                        }
                    }
                });
            }

            const bookingsByTypeEl = document.getElementById('bookingsByTypeChart');
            if (bookingsByTypeEl) {
                new Chart(bookingsByTypeEl.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Traveller-Inn', 'Airbnb', 'Daily Rental'],
                        datasets: [{
                            label: 'Bookings',
                            data: [
                                {{ $bookingsByType['traveller-inn'] ?? 0 }},
                                {{ $bookingsByType['airbnb'] ?? 0 }},
                                {{ $bookingsByType['daily-rental'] ?? 0 }}
                            ],
                            backgroundColor: [
                                'rgb(46, 125, 50)',
                                'rgb(59, 162, 246)',
                                'rgb(249, 115, 22)'
                            ],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom' } }
                    }
                });
            }
        });
    </script>
    <script>
        document.querySelectorAll('.demographics-report-actions form[data-loading-form]').forEach((form) => {
            form.addEventListener('submit', () => {
                const button = form.querySelector('[data-loading-button]');
                if (! button || button.disabled) {
                    return;
                }
                const label = button.getAttribute('data-loading-label') || 'Working…';
                if (! button.dataset.originalHtml) {
                    button.dataset.originalHtml = button.innerHTML;
                }
                button.disabled = true;
                button.setAttribute('aria-busy', 'true');
                button.innerHTML = '<i class="fas fa-spinner fa-spin" aria-hidden="true"></i> <span></span>';
                button.querySelector('span').textContent = label;
            });
        });
    </script>
</body>
</html>
