<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    @include('partials.tenant-favicon')
    @include('partials.app-vite-head')
    <title>Monthly Tenant Report - ImpaStay</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>
    <style>
        @include('owner.partials.owner-page-fonts')
        * { box-sizing: border-box; }

        :root {
            @include('partials.tenant-theme-css-vars')
            --gray-50: #F9FAFB;
            --gray-100: #F3F4F6;
            --gray-200: #E5E7EB;
            --gray-300: #D1D5DB;
            --gray-400: #9CA3AF;
            --gray-500: #6B7280;
            --gray-600: #4B5563;
            --gray-700: #374151;
            --gray-800: #1F2937;
            --gray-900: #0F172A;
            --shadow-xs: 0 1px 2px rgba(15, 23, 42, 0.04);
            --shadow-sm: 0 2px 4px rgba(15, 23, 42, 0.06);
            --shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
            --shadow-md: 0 10px 25px rgba(15, 23, 42, 0.08);
        }

        /* Title typography provided by ui-foundation-styles */

        /* ── Surface card primitive ────────────────────────────────────────── */
        .surface {
            background: var(--app-surface-bg, #ffffff);
            border: 1px solid var(--app-surface-border, var(--gray-200));
            border-radius: 16px;
            box-shadow: var(--shadow-xs);
            color: var(--ink-800);
        }
        .surface + .surface { margin-top: 16px; }

        .surface-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 16px 22px;
            border-bottom: 1px solid var(--app-surface-border, var(--gray-200));
            background: var(--app-surface-muted-bg, linear-gradient(180deg, #ffffff, #FAFBFC));
            border-radius: 16px 16px 0 0;
        }
        .surface-header h3 {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.98rem;
            color: var(--ink-900, var(--gray-900));
            font-weight: 700;
            margin: 0;
        }
        .surface-header h3 i {
            display: inline-flex;
            width: 30px;
            height: 30px;
            align-items: center;
            justify-content: center;
            background: var(--chrome-surface-bg, #ECFDF5);
            color: var(--chrome-icon-color, #047857);
            border: 1px solid var(--chrome-surface-border, #D1FAE5);
            border-radius: 9px;
            font-size: 0.82rem;
        }
        .surface-header .meta {
            font-size: 0.78rem;
            color: var(--ink-500, var(--gray-500));
            font-weight: 600;
        }
        .surface-body { padding: 18px 22px; }

        /* ── Filter card ───────────────────────────────────────────────────── */
        .filters-card {
            margin-bottom: 18px;
            padding: 18px 22px;
        }
        .filters {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
            align-items: flex-end;
        }
        .field {
            display: flex;
            flex-direction: column;
            gap: 6px;
            min-width: 170px;
        }
        .field label {
            font-size: 0.75rem;
            color: var(--gray-700);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .field label i { color: var(--green-primary); font-size: 0.78rem; }
        .field select {
            border: 1px solid var(--app-surface-border, var(--gray-200));
            border-radius: 10px;
            padding: 10px 12px;
            font-size: 0.9rem;
            background: var(--app-surface-bg, #ffffff);
            color: var(--ink-800, var(--gray-800));
            font-weight: 500;
            transition: border-color 0.15s, box-shadow 0.15s;
            cursor: pointer;
        }
        .field select:hover { border-color: var(--gray-300); }
        .field select:focus {
            outline: none;
            border-color: var(--green-primary);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
        }

        .filter-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: stretch;
        }

        .btn {
            border: 1px solid transparent;
            border-radius: 10px;
            padding: 10px 16px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.86rem;
            transition: transform 0.15s, box-shadow 0.15s, background 0.15s, color 0.15s, border-color 0.15s, filter 0.15s;
            white-space: nowrap;
        }
        .btn i { font-size: 0.85rem; }

        .btn.primary {
            background: linear-gradient(135deg, var(--green-primary), var(--green-dark));
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(16, 124, 89, 0.22);
        }
        .btn.primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 18px rgba(16, 124, 89, 0.28);
            filter: brightness(1.05);
        }

        .btn.secondary {
            background: #ECFDF5;
            color: #047857;
            border-color: #D1FAE5;
        }
        .btn.secondary:hover { background: #D1FAE5; border-color: #A7F3D0; }

        .btn.outline {
            background: var(--app-surface-bg, #ffffff);
            color: var(--ink-700, var(--gray-700));
            border-color: var(--app-surface-border, var(--gray-200));
        }
        .btn.outline:hover { background: var(--app-surface-muted-bg, var(--gray-50)); border-color: var(--ink-300, var(--gray-300)); color: var(--ink-900, var(--gray-900)); }

        /* ── KPI grid ──────────────────────────────────────────────────────── */
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 14px;
            margin-bottom: 18px;
        }
        .kpi {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 18px 20px;
            background: var(--app-surface-bg, #ffffff);
            border: 1px solid var(--app-surface-border, var(--gray-200));
            border-radius: 16px;
            box-shadow: var(--shadow-xs);
            transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
        }
        .kpi:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow);
            border-color: var(--gray-300);
        }
        .kpi-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            border-radius: 12px;
            font-size: 1.05rem;
            flex-shrink: 0;
        }
        .kpi-icon.emerald { background: #ECFDF5; color: #047857; border: 1px solid #D1FAE5; }
        .kpi-icon.amber   { background: #FFFBEB; color: #B45309; border: 1px solid #FDE68A; }
        .kpi-icon.blue    { background: #EFF6FF; color: #1D4ED8; border: 1px solid #DBEAFE; }
        .kpi-icon.violet  { background: #F5F3FF; color: #6D28D9; border: 1px solid #DDD6FE; }

        .kpi-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
            min-width: 0;
            flex: 1;
        }
        .kpi-label {
            color: var(--gray-500);
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin: 0;
        }
        .kpi-value {
            color: var(--gray-900);
            font-size: 1.45rem;
            font-weight: 800;
            line-height: 1.15;
            margin: 0;
            font-variant-numeric: tabular-nums;
            word-break: break-word;
        }
        .kpi-value.emerald { color: #047857; }
        .kpi-sub {
            color: var(--gray-500);
            font-size: 0.74rem;
            font-weight: 500;
            margin: 0;
        }

        /* ── Layout grid ───────────────────────────────────────────────────── */
        .content-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
        }

        /* ── Chart card ────────────────────────────────────────────────────── */
        .chart-card .surface-body { padding: 14px 22px 22px; }
        .chart-canvas-wrap {
            position: relative;
            width: 100%;
            height: 280px;
        }
        .chart-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 10px;
            height: 280px;
            color: var(--gray-500);
            font-size: 0.9rem;
        }
        .chart-empty i {
            font-size: 1.8rem;
            color: var(--gray-300);
        }

        /* ── Daily breakdown table ─────────────────────────────────────────── */
        .table-wrap {
            overflow-x: auto;
            border-radius: 0 0 16px 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 640px;
        }
        thead th {
            background: var(--app-surface-muted-bg, linear-gradient(180deg, #FAFBFC, #F3F4F6));
            color: var(--ink-600, var(--gray-600));
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 13px 22px;
            text-align: left;
            border-bottom: 1px solid var(--app-surface-border, var(--gray-200));
        }
        thead th.num { text-align: right; }
        tbody td {
            padding: 13px 22px;
            border-bottom: 1px solid var(--app-surface-border, var(--gray-100));
            font-size: 0.9rem;
            color: var(--ink-700, var(--gray-700));
        }
        tbody td.num {
            text-align: right;
            font-variant-numeric: tabular-nums;
            font-weight: 600;
            color: var(--ink-800, var(--gray-800));
        }
        tbody td.num.sales { color: var(--chrome-icon-color, #047857); font-weight: 700; }
        tbody tr { transition: background 0.15s; }
        tbody tr:nth-child(even) { background: var(--app-surface-muted-bg, #FCFDFD); }
        tbody tr:hover { background: var(--chrome-surface-bg, #ECFDF5); }
        tbody tr:last-child td { border-bottom: none; }

        tfoot td {
            padding: 14px 22px;
            background: var(--chrome-surface-bg, linear-gradient(180deg, #F0FDF4, #ECFDF5));
            border-top: 2px solid var(--chrome-surface-border, #D1FAE5);
            font-size: 0.9rem;
            font-weight: 800;
            color: var(--ink-900, var(--gray-900));
        }
        tfoot td.num {
            text-align: right;
            font-variant-numeric: tabular-nums;
        }
        tfoot td.num.sales { color: #047857; }

        .date-cell {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--gray-800);
            font-weight: 600;
        }
        .date-cell .day-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: 8px;
            background: #ECFDF5;
            color: #047857;
            border: 1px solid #D1FAE5;
            font-size: 0.78rem;
            font-weight: 700;
        }
        .date-cell .day-name {
            font-size: 0.74rem;
            color: var(--gray-500);
            font-weight: 500;
            margin-left: 2px;
        }

        /* ── Empty state ───────────────────────────────────────────────────── */
        .empty-state {
            text-align: center;
            padding: 56px 24px 64px;
        }
        .empty-state-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 76px;
            height: 76px;
            margin: 0 auto 16px;
            border-radius: 22px;
            background: #ECFDF5;
            color: #047857;
            border: 1px solid #D1FAE5;
            font-size: 1.7rem;
        }
        .empty-state h4 {
            color: var(--gray-900);
            font-size: 1.05rem;
            font-weight: 700;
            margin: 0 0 4px;
        }
        .empty-state p {
            color: var(--gray-500);
            font-size: 0.88rem;
            margin: 0;
            max-width: 360px;
            margin-left: auto;
            margin-right: auto;
        }

        @include('owner.partials.top-navbar-styles')

        /* ── Responsive ────────────────────────────────────────────────────── */
        @media (max-width: 768px) {
            .filters { flex-direction: column; align-items: stretch; }
            .field { min-width: 0; }
            .filter-actions { width: 100%; }
            .filter-actions .btn { flex: 1; justify-content: center; }
            .kpi-grid { grid-template-columns: repeat(2, 1fr); }
            .surface-header { padding: var(--app-card-pad); flex-direction: column; align-items: flex-start; }
            .surface-body, .filters-card { padding: var(--app-card-pad); }
            .kpi { padding: var(--app-card-pad); }
            .kpi-value { font-size: var(--text-fluid-lg) !important; }
            .kpi-label { font-size: var(--text-fluid-xs); }
            thead th, tbody td, tfoot td {
                padding: var(--app-table-pad-y) var(--app-table-pad-x);
                font-size: var(--app-table-font);
            }
            .chart-canvas-wrap { height: 200px; }
            .empty-state { padding: 2rem 1rem 2.5rem; }
            .empty-state-icon { width: 56px; height: 56px; font-size: var(--text-fluid-xl); }
            .empty-state h4 { font-size: var(--text-fluid-base); }
            .empty-state p { font-size: var(--text-fluid-sm); }
        }
        @media (max-width: 480px) {
            .kpi-grid { grid-template-columns: 1fr; }
            .kpi-value { font-size: var(--text-fluid-base) !important; }
        }
    </style>
</head>
<body class="owner-nav-page">
    @include('owner.partials.top-navbar', ['active' => 'reports'])

    <main class="main-content with-owner-nav owner-app-main">
        <header class="owner-page-hero">
            <p class="owner-page-hero__eyebrow">Analytics</p>
            <h1 class="owner-page-hero__title">Monthly Tenant Report</h1>
            <p class="owner-page-hero__lede">Track monthly sales, total guests catered, and booking activity for {{ $monthName }}.</p>
        </header>

        {{-- Filter bar --}}
        <section class="surface filters-card">
            <form method="GET" action="/owner/reports/monthly" class="filters">
                <div class="field">
                    <label for="year"><i class="fa-solid fa-calendar"></i> Year</label>
                    <select name="year" id="year">
                        @for($y = now()->year; $y >= 2020; $y--)
                            <option value="{{ $y }}" @selected($year === $y)>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <div class="field">
                    <label for="month"><i class="fa-solid fa-calendar-day"></i> Month</label>
                    <select name="month" id="month">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" @selected($month === $m)>{{ \Carbon\Carbon::create(2000, $m, 1)->format('F') }}</option>
                        @endfor
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn primary">
                        <i class="fa-solid fa-filter"></i>
                        <span>Apply filters</span>
                    </button>
                    <a href="/owner/reports/monthly/download-sales?year={{ $year }}&month={{ $month }}" class="btn secondary">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                        <span>Sales PDF</span>
                    </a>
                    <a href="/owner/reports/monthly/download-guests?year={{ $year }}&month={{ $month }}" class="btn outline">
                        <i class="fa-solid fa-users"></i>
                        <span>Guests PDF</span>
                    </a>
                </div>
            </form>
        </section>

        {{-- KPI grid --}}
        <div class="kpi-grid">
            <div class="kpi">
                <span class="kpi-icon emerald"><i class="fa-solid fa-calendar-day" aria-hidden="true"></i></span>
                <div class="kpi-info">
                    <p class="kpi-label">Reporting month</p>
                    <p class="kpi-value" style="font-size: 1.1rem;">{{ $monthName }}</p>
                    <p class="kpi-sub">Bookings checking-in within this period</p>
                </div>
            </div>
            <div class="kpi">
                <span class="kpi-icon amber"><i class="fa-solid fa-peso-sign" aria-hidden="true"></i></span>
                <div class="kpi-info">
                    <p class="kpi-label">Monthly sales</p>
                    <p class="kpi-value emerald">PHP {{ number_format((float) $monthlySales, 2) }}</p>
                    <p class="kpi-sub">Total revenue from qualified bookings</p>
                </div>
            </div>
            <div class="kpi">
                <span class="kpi-icon blue"><i class="fa-solid fa-users" aria-hidden="true"></i></span>
                <div class="kpi-info">
                    <p class="kpi-label">People catered</p>
                    <p class="kpi-value">{{ number_format((int) $monthlyGuests) }}</p>
                    <p class="kpi-sub">Total guests across all bookings</p>
                </div>
            </div>
            <div class="kpi">
                <span class="kpi-icon violet"><i class="fa-solid fa-clipboard-list" aria-hidden="true"></i></span>
                <div class="kpi-info">
                    <p class="kpi-label">Total bookings</p>
                    <p class="kpi-value">{{ number_format((int) $monthlyBookings) }}</p>
                    <p class="kpi-sub">Confirmed reservations this month</p>
                </div>
            </div>
        </div>

        {{-- Daily sales trend chart --}}
        @php
            $chartLabels = [];
            $chartSales = [];
            $chartBookings = [];
            $totalSales = 0.0;
            $totalGuests = 0;
            $totalBookings = 0;
            foreach ($dailyBreakdown as $row) {
                $chartLabels[] = \Carbon\Carbon::parse($row->report_date)->format('M j');
                $chartSales[] = (float) $row->total_sales;
                $chartBookings[] = (int) $row->booking_count;
                $totalSales += (float) $row->total_sales;
                $totalGuests += (int) $row->total_guests;
                $totalBookings += (int) $row->booking_count;
            }
        @endphp

        <div class="content-grid">
            <section class="surface chart-card">
                <div class="surface-header">
                    <h3>
                        <i class="fa-solid fa-chart-line" aria-hidden="true"></i>
                        <span>Daily sales trend</span>
                    </h3>
                    <span class="meta">{{ $monthName }}</span>
                </div>
                <div class="surface-body">
                    @if(count($chartSales) > 0)
                        <div class="chart-canvas-wrap">
                            <canvas id="dailySalesChart"></canvas>
                        </div>
                    @else
                        <div class="chart-empty">
                            <i class="fa-solid fa-chart-line" aria-hidden="true"></i>
                            <span>No sales data for {{ $monthName }} yet.</span>
                        </div>
                    @endif
                </div>
            </section>

            <section class="surface">
                <div class="surface-header">
                    <h3>
                        <i class="fa-solid fa-table-list" aria-hidden="true"></i>
                        <span>Daily breakdown</span>
                    </h3>
                    <span class="meta">{{ $dailyBreakdown->count() }} {{ $dailyBreakdown->count() === 1 ? 'day' : 'days' }}</span>
                </div>

                @if($dailyBreakdown->count() > 0)
                    <div class="table-wrap app-table-responsive" role="region" aria-label="Monthly report" tabindex="0">
                        <table class="app-data-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th class="num">Bookings</th>
                                    <th class="num">Guests</th>
                                    <th class="num">Sales</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dailyBreakdown as $row)
                                    @php $date = \Carbon\Carbon::parse($row->report_date); @endphp
                                    <tr>
                                        <td>
                                            <span class="date-cell">
                                                <span class="day-pill">{{ $date->format('d') }}</span>
                                                <span>
                                                    {{ $date->format('M Y') }}
                                                    <span class="day-name">{{ $date->format('D') }}</span>
                                                </span>
                                            </span>
                                        </td>
                                        <td class="num">{{ number_format((int) $row->booking_count) }}</td>
                                        <td class="num">{{ number_format((int) $row->total_guests) }}</td>
                                        <td class="num sales">PHP {{ number_format((float) $row->total_sales, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>Totals</td>
                                    <td class="num">{{ number_format($totalBookings) }}</td>
                                    <td class="num">{{ number_format($totalGuests) }}</td>
                                    <td class="num sales">PHP {{ number_format($totalSales, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <span class="empty-state-icon">
                            <i class="fa-solid fa-folder-open" aria-hidden="true"></i>
                        </span>
                        <h4>No qualified bookings</h4>
                        <p>There are no confirmed bookings checking-in during {{ $monthName }}. Try a different period or check back once new bookings come in.</p>
                    </div>
                @endif
            </section>
        </div>
    </main>

    @if(count($chartSales) > 0)
        <script>
            (function () {
                const init = function () {
                    if (typeof Chart === 'undefined') return;
                    const canvas = document.getElementById('dailySalesChart');
                    if (!canvas) return;

                    const labels = @json($chartLabels);
                    const salesData = @json($chartSales);
                    const bookingsData = @json($chartBookings);

                    const gradient = canvas.getContext('2d').createLinearGradient(0, 0, 0, 280);
                    gradient.addColorStop(0, 'rgba(16, 185, 129, 0.35)');
                    gradient.addColorStop(1, 'rgba(16, 185, 129, 0.02)');

                    new Chart(canvas, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [
                                {
                                    label: 'Sales (PHP)',
                                    data: salesData,
                                    borderColor: '#047857',
                                    backgroundColor: gradient,
                                    borderWidth: 2.5,
                                    fill: true,
                                    tension: 0.35,
                                    pointBackgroundColor: '#047857',
                                    pointBorderColor: '#ffffff',
                                    pointBorderWidth: 2,
                                    pointRadius: 4,
                                    pointHoverRadius: 6,
                                    yAxisID: 'y',
                                },
                                {
                                    label: 'Bookings',
                                    data: bookingsData,
                                    borderColor: '#1D4ED8',
                                    backgroundColor: 'transparent',
                                    borderWidth: 2,
                                    borderDash: [4, 4],
                                    tension: 0.35,
                                    pointBackgroundColor: '#1D4ED8',
                                    pointBorderColor: '#ffffff',
                                    pointBorderWidth: 2,
                                    pointRadius: 3,
                                    pointHoverRadius: 5,
                                    yAxisID: 'y1',
                                },
                            ],
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
                                        boxWidth: 12,
                                        boxHeight: 12,
                                        usePointStyle: true,
                                        pointStyle: 'circle',
                                        font: { size: 12, weight: '600' },
                                        color: '#374151',
                                        padding: 14,
                                    },
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(15, 23, 42, 0.94)',
                                    titleColor: '#ffffff',
                                    bodyColor: '#E5E7EB',
                                    titleFont: { weight: '700', size: 12 },
                                    bodyFont: { size: 12 },
                                    padding: 10,
                                    borderColor: 'rgba(255, 255, 255, 0.1)',
                                    borderWidth: 1,
                                    cornerRadius: 8,
                                    displayColors: true,
                                    boxPadding: 4,
                                    callbacks: {
                                        label: function (ctx) {
                                            if (ctx.dataset.label === 'Sales (PHP)') {
                                                return ' Sales: PHP ' + Number(ctx.parsed.y).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                                            }
                                            return ' Bookings: ' + ctx.parsed.y;
                                        },
                                    },
                                },
                            },
                            scales: {
                                x: {
                                    grid: { display: false },
                                    ticks: { color: '#6B7280', font: { size: 11, weight: '500' } },
                                    border: { color: '#E5E7EB' },
                                },
                                y: {
                                    position: 'left',
                                    beginAtZero: true,
                                    grid: { color: 'rgba(229, 231, 235, 0.7)', drawBorder: false },
                                    border: { display: false },
                                    ticks: {
                                        color: '#6B7280',
                                        font: { size: 11, weight: '500' },
                                        callback: function (value) {
                                            if (value >= 1000) return '₱' + (value / 1000).toFixed(1) + 'k';
                                            return '₱' + value;
                                        },
                                    },
                                },
                                y1: {
                                    position: 'right',
                                    beginAtZero: true,
                                    grid: { display: false },
                                    border: { display: false },
                                    ticks: {
                                        color: '#1D4ED8',
                                        font: { size: 11, weight: '600' },
                                        precision: 0,
                                    },
                                },
                            },
                        },
                    });
                };

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', init);
                } else {
                    init();
                }
                window.addEventListener('load', init, { once: true });
            })();
        </script>
    @endif
</body>
</html>
