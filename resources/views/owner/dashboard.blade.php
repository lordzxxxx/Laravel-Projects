<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    @include('partials.tenant-favicon')
    <title>Owner Dashboard - ImpaStay</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @include('owner.partials.owner-page-fonts')
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        :root {
            @include('partials.tenant-theme-css-vars')
            --blue-500: #3B82F6; --orange-500: #F97316; --purple-500: #8B5CF6;
        }
        
        .owner-dash-top {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            justify-content: space-between;
            gap: 0.75rem 1.25rem;
            flex-shrink: 0;
        }

        .owner-dash-top .page-header {
            margin-bottom: 0;
            flex: 1 1 14rem;
            min-width: min(100%, 16rem);
        }

        .owner-dash-top .page-header h1 {
            font-size: clamp(1.35rem, 2.2vw, 1.65rem);
            letter-spacing: -0.02em;
        }

        .owner-dash-top .page-header p {
            margin-top: 0.35rem;
            max-width: 36rem;
            font-size: 0.875rem;
            line-height: 1.5;
            color: var(--gray-500);
        }

        .owner-dash-kpis {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: clamp(0.65rem, 1.2vw, 1rem);
            flex-shrink: 0;
        }

        .owner-dash-kpi {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: clamp(0.85rem, 1.5vw, 1.1rem) clamp(0.9rem, 1.5vw, 1.15rem);
            background: var(--app-surface-bg, #fff);
            border: 1px solid var(--app-surface-border, var(--gray-200));
            border-radius: 0.75rem;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .owner-dash-kpi:hover {
            border-color: color-mix(in srgb, var(--green-primary) 28%, var(--gray-200));
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.05);
        }

        .owner-dash-kpi__icon {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            flex-shrink: 0;
        }

        .owner-dash-kpi__icon--green { background: #ecfdf5; color: #047857; }
        .owner-dash-kpi__icon--blue { background: #eff6ff; color: #1d4ed8; }
        .owner-dash-kpi__icon--orange { background: #fffbeb; color: #b45309; }
        .owner-dash-kpi__icon--slate { background: #f8fafc; color: #475569; }

        .owner-dash-kpi__text { min-width: 0; }

        .owner-dash-kpi__value {
            font-size: clamp(1.25rem, 2vw, 1.5rem);
            font-weight: 700;
            line-height: 1.15;
            color: var(--ink-900, var(--gray-900));
            letter-spacing: -0.03em;
        }

        .owner-dash-kpi__label {
            margin-top: 0.15rem;
            font-size: 0.6875rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: var(--gray-500);
        }

        .owner-dash-body {
            flex: 1;
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(17rem, 22rem);
            gap: clamp(1rem, 2vw, 1.5rem);
            align-items: stretch;
            min-height: 0;
        }

        .owner-dash-primary {
            display: flex;
            flex-direction: column;
            gap: clamp(1rem, 1.8vw, 1.35rem);
            min-width: 0;
            min-height: 0;
        }

        .owner-dash-charts {
            display: grid;
            grid-template-columns: minmax(0, 1.55fr) minmax(0, 1fr);
            gap: clamp(0.75rem, 1.5vw, 1rem);
            flex: 1;
            min-height: clamp(240px, 32vh, 380px);
        }

        .owner-dash-block {
            background: var(--app-surface-bg, #fff);
            border: 1px solid var(--app-surface-border, var(--gray-200));
            border-radius: 0.75rem;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
            display: flex;
            flex-direction: column;
            min-height: 0;
            overflow: hidden;
        }

        .owner-dash-block__head {
            padding: 0.85rem 1.15rem;
            border-bottom: 1px solid var(--gray-100);
            flex-shrink: 0;
        }

        .owner-dash-block__head h2,
        .owner-dash-block__head h3 {
            margin: 0;
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--gray-800);
            display: flex;
            align-items: center;
            gap: 0.45rem;
            letter-spacing: 0.01em;
        }

        .owner-dash-block__head h2 i,
        .owner-dash-block__head h3 i {
            color: var(--green-primary);
            font-size: 0.8rem;
        }

        .owner-dash-block__caption {
            margin: 0.2rem 0 0;
            font-size: 0.6875rem;
            color: var(--gray-500);
            line-height: 1.4;
        }

        .owner-dash-block__body {
            flex: 1;
            padding: 1rem 1.15rem 1.1rem;
            min-height: 0;
            display: flex;
            flex-direction: column;
        }

        .owner-dash-block__body--flush {
            padding: 0;
        }

        .chart-container {
            position: relative;
            flex: 1;
            min-height: 200px;
            width: 100%;
        }

        .owner-dash-tables {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: clamp(0.75rem, 1.5vw, 1rem);
            flex: 1;
            min-height: 0;
        }

        .owner-dash-aside {
            display: flex;
            flex-direction: column;
            gap: clamp(0.75rem, 1.5vw, 1rem);
            min-height: 0;
        }

        .owner-dash-actions {
            display: flex;
            flex-direction: column;
            gap: 0.45rem;
            padding: 0.75rem;
            background: var(--app-surface-bg, #fff);
            border: 1px solid var(--app-surface-border, var(--gray-200));
            border-radius: 0.75rem;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        }

        .owner-dash-actions__label {
            margin: 0 0 0.25rem 0.15rem;
            font-size: 0.6875rem;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--gray-500);
        }

        .owner-dash-action {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.6rem 0.7rem;
            border-radius: 0.5rem;
            border: 1px solid transparent;
            text-decoration: none;
            color: inherit;
            transition: background 0.15s ease, border-color 0.15s ease;
        }

        .owner-dash-action:hover {
            background: var(--green-white, #edf4ea);
            border-color: color-mix(in srgb, var(--green-primary) 22%, transparent);
        }

        .owner-dash-action__icon {
            width: 2rem;
            height: 2rem;
            border-radius: 0.4rem;
            background: var(--green-white, #edf4ea);
            color: var(--green-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            flex-shrink: 0;
        }

        .owner-dash-action__title {
            margin: 0;
            font-size: 0.8125rem;
            font-weight: 600;
            color: var(--ink-900, var(--gray-900));
        }

        .owner-dash-action__desc {
            margin: 0.1rem 0 0;
            font-size: 0.6875rem;
            color: var(--gray-500);
            line-height: 1.3;
        }

        .owner-dash-aside .owner-avail-card {
            flex: 1;
            width: 100%;
            min-height: 0;
            max-width: none;
        }

        .property-table { width: 100%; border-collapse: collapse; }
        .property-table th, .property-table td {
            padding: 0.75rem 1rem;
            text-align: left;
            border-bottom: 1px solid var(--gray-100);
            vertical-align: middle;
            font-size: 0.8125rem;
        }
        .property-table th {
            font-weight: 600;
            color: var(--gray-500);
            font-size: 0.625rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            background: var(--gray-50);
        }
        .property-table tbody tr:hover { background: var(--green-white, #f6faf4); }
        .property-table tbody tr:last-child td { border-bottom: none; }

        .property-cell {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .property-thumb {
            width: 2.75rem;
            height: 2.75rem;
            border-radius: 0.5rem;
            object-fit: cover;
            flex-shrink: 0;
        }

        .property-name {
            font-weight: 600;
            color: var(--gray-800);
            font-size: 0.8125rem;
        }

        .property-address {
            font-size: 0.75rem;
            color: var(--gray-500);
            margin-top: 0.1rem;
        }

        .property-type-pill {
            background: var(--green-soft, #edf4ea);
            padding: 0.2rem 0.5rem;
            border-radius: 0.35rem;
            font-size: 0.75rem;
        }

        .property-edit-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.4rem 0.75rem;
            background: var(--green-soft, #edf4ea);
            color: var(--green-dark);
            border-radius: 0.4rem;
            text-decoration: none;
            font-size: 0.75rem;
            font-weight: 600;
            transition: background 0.15s ease;
        }

        .property-edit-btn:hover {
            background: color-mix(in srgb, var(--green-primary) 18%, #fff);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.6rem;
            border-radius: 999px;
            font-size: 0.6875rem;
            font-weight: 600;
        }
        .status-badge.active { background: #dcfce7; color: var(--green-dark); border: 1px solid #bbf7d0; }
        .status-badge.inactive { background: var(--gray-200); color: var(--gray-600); }
        .status-badge.pending { background: #fffbeb; color: #B45309; border: 1px solid #fde68a; }
        .status-badge.confirmed { background: #eff6ff; color: #1D4ED8; border: 1px solid #bfdbfe; }
        .status-badge.cancelled { background: #fee2e2; color: #DC2626; border: 1px solid #fecaca; }

        .pagination-clean .pagination {
            display: flex;
            list-style: none;
            gap: 8px;
            margin: 0;
            padding: 0;
            align-items: center;
            flex-wrap: wrap;
            justify-content: center;
        }

        .pagination-clean .page-item { list-style: none; }

        .pagination-clean .page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 34px;
            height: 34px;
            padding: 0 10px;
            border-radius: 8px;
            border: 1px solid var(--app-surface-border, var(--gray-200));
            background: var(--app-surface-bg, #fff);
            color: var(--ink-700, var(--gray-700));
            text-decoration: none;
            font-size: 0.86rem;
            font-weight: 600;
        }

        .pagination-clean .page-item.active .page-link {
            background: var(--green-primary);
            border-color: var(--green-primary);
            color: #fff;
        }

        .pagination-clean .page-item.disabled .page-link {
            opacity: 0.45;
            cursor: not-allowed;
        }

        .pagination-clean p.small.text-muted {
            margin-top: 8px;
            width: 100%;
            text-align: center;
            color: var(--gray-500);
            font-size: 0.8rem;
        }
        
        .owner-dash-btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            margin-top: 0.75rem;
            padding: 0.55rem 1rem;
            background: var(--green-primary);
            color: #fff;
            border-radius: 0.5rem;
            text-decoration: none;
            font-size: 0.8125rem;
            font-weight: 600;
        }

        .owner-dash-pagination {
            margin-top: 0.75rem;
            padding: 0 1rem 1rem;
            display: flex;
            justify-content: center;
        }

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
        
        .owner-dash-top .business-status-pill {
            margin-top: 0;
            flex-shrink: 0;
        }

        .business-status-pill {
            display: inline-flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 6px 8px;
            margin-top: 0;
            padding: 8px 14px;
            border-radius: 12px;
            font-size: 0.82rem;
            font-weight: 600;
            border: 1px solid rgba(15, 23, 42, 0.12);
            background: rgba(255, 255, 255, 0.9);
            color: var(--gray-800);
            max-width: 100%;
            box-sizing: border-box;
        }
        .business-status-pill i { color: var(--green-primary); }
        .business-status-pill .biz-label { font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.06em; color: var(--gray-500); font-weight: 700; }
        .business-status-pill .biz-main { color: var(--gray-800); }
        .business-status-pill.tone-success { border-color: rgba(16, 185, 129, 0.35); background: #ecfdf5; }
        .business-status-pill.tone-warning { border-color: rgba(245, 158, 11, 0.45); background: #fffbeb; color: #92400e; }
        .business-status-pill.tone-warning .biz-main { color: #92400e; }
        .business-status-pill.tone-danger { border-color: rgba(248, 113, 113, 0.5); background: #fef2f2; color: #b91c1c; }
        .business-status-pill.tone-danger .biz-main { color: #b91c1c; }

        .owner-dash-table-scroll {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .owner-dash-empty {
            text-align: center;
            padding: clamp(2rem, 5vw, 3rem) 1.25rem;
            color: var(--gray-500);
        }

        .owner-dash-empty i {
            font-size: 2.5rem;
            margin-bottom: 0.75rem;
            color: var(--gray-300);
        }

        @media (max-width: 1280px) {
            .owner-dash-body {
                grid-template-columns: 1fr;
            }

            .owner-dash-aside {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(14rem, 1fr));
            }

            .owner-avail-card {
                flex: none;
            }
        }

        @media (max-width: 1100px) {
            .owner-dash-kpis { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .owner-dash-tables { grid-template-columns: 1fr; }
        }

        @media (max-width: 900px) {
            .owner-dash-charts {
                grid-template-columns: 1fr;
                min-height: auto;
            }

            .owner-dash-aside {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .owner-dash-kpis { grid-template-columns: 1fr; }
        }

        @include('partials.pbi-visual-surface-styles')
        @include('owner.partials.top-navbar-styles')
    </style>
</head>
<body class="owner-nav-page">
    @include('owner.partials.top-navbar')
    
        <main class="main-content with-owner-nav owner-app-main owner-dashboard-main">
            <header class="owner-dash-top owner-page-top">
                <div class="owner-page-hero owner-page-hero--flush">
                    <p class="owner-page-hero__eyebrow">Overview</p>
                    <h1 class="owner-page-hero__title">Unit Management Dashboard</h1>
                    <p class="owner-page-hero__lede">Monitor properties, bookings, and availability at a glance.</p>
                </div>
                @if(!empty($businessStatus))
                    <span class="business-status-pill tone-{{ $businessStatus['tone'] }}">
                        <i class="fas fa-clipboard-check" aria-hidden="true"></i>
                        <span class="biz-label">Business status</span>
                        <span class="biz-main">Registration: {{ $businessStatus['registration'] }}</span>
                    </span>
                @endif
            </header>

            <section class="owner-dash-kpis" aria-label="Key metrics">
                <div class="owner-dash-kpi">
                    <div class="owner-dash-kpi__icon owner-dash-kpi__icon--green"><i class="fas fa-building" aria-hidden="true"></i></div>
                    <div class="owner-dash-kpi__text">
                        <div class="owner-dash-kpi__value">{{ $stats['total_properties'] ?? 0 }}</div>
                        <div class="owner-dash-kpi__label">Total units</div>
                    </div>
                </div>
                <div class="owner-dash-kpi">
                    <div class="owner-dash-kpi__icon owner-dash-kpi__icon--blue"><i class="fas fa-check-circle" aria-hidden="true"></i></div>
                    <div class="owner-dash-kpi__text">
                        <div class="owner-dash-kpi__value">{{ $stats['active_properties'] ?? 0 }}</div>
                        <div class="owner-dash-kpi__label">Active units</div>
                    </div>
                </div>
                <div class="owner-dash-kpi">
                    <div class="owner-dash-kpi__icon owner-dash-kpi__icon--orange"><i class="fas fa-calendar-check" aria-hidden="true"></i></div>
                    <div class="owner-dash-kpi__text">
                        <div class="owner-dash-kpi__value">{{ $stats['total_bookings'] ?? 0 }}</div>
                        <div class="owner-dash-kpi__label">Total bookings</div>
                    </div>
                </div>
                <div class="owner-dash-kpi">
                    <div class="owner-dash-kpi__icon owner-dash-kpi__icon--slate"><i class="fas fa-clock" aria-hidden="true"></i></div>
                    <div class="owner-dash-kpi__text">
                        <div class="owner-dash-kpi__value">{{ $stats['pending_bookings'] ?? 0 }}</div>
                        <div class="owner-dash-kpi__label">Pending requests</div>
                    </div>
                </div>
            </section>

            <div class="owner-dash-body">
                <div class="owner-dash-primary">
                    <div class="owner-dash-charts">
                        <section class="owner-dash-block" aria-labelledby="owner-trend-heading">
                            <div class="owner-dash-block__head">
                                <h2 id="owner-trend-heading"><i class="fas fa-chart-line" aria-hidden="true"></i> Revenue &amp; bookings</h2>
                                <p class="owner-dash-block__caption">Last 30 days</p>
                            </div>
                            <div class="owner-dash-block__body">
                                <div class="chart-container">
                                    <canvas id="ownerTrendChart"></canvas>
                                </div>
                            </div>
                        </section>
                        <section class="owner-dash-block" aria-labelledby="owner-status-heading">
                            <div class="owner-dash-block__head">
                                <h2 id="owner-status-heading"><i class="fas fa-chart-pie" aria-hidden="true"></i> Booking status</h2>
                                <p class="owner-dash-block__caption">All-time mix</p>
                            </div>
                            <div class="owner-dash-block__body">
                                <div class="chart-container">
                                    <canvas id="ownerStatusChart"></canvas>
                                </div>
                            </div>
                        </section>
                    </div>

                    <div class="owner-dash-tables">
                        <section class="owner-dash-block" aria-labelledby="owner-units-heading">
                            <div class="owner-dash-block__head">
                                <h3 id="owner-units-heading"><i class="fas fa-list" aria-hidden="true"></i> My units</h3>
                            </div>
                            <div class="owner-dash-block__body owner-dash-block__body--flush">
                            @if(isset($properties) && count($properties) > 0)
                                <div class="owner-dash-table-scroll">
                                    <table class="property-table">
                                        <thead>
                                            <tr>
                                                <th>Property</th>
                                                <th>Type</th>
                                                <th>Price</th>
                                                <th>Bookings</th>
                                                <th>Status</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($properties as $property)
                                                <tr>
                                                    <td>
                                                        <div class="property-cell">
                                                            <img src="{{ $property->primary_image_url }}" alt="" class="property-thumb">
                                                            <div>
                                                                <div class="property-name">{{ $property->name }}</div>
                                                                @if($property->address)
                                                                    <div class="property-address">{{ $property->address }}</div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><span class="property-type-pill">{{ ucfirst(str_replace('-', ' ', $property->type)) }}</span></td>
                                                    <td><strong>₱{{ number_format($property->price_per_night, 0, '.', ',') }}</strong></td>
                                                    <td>{{ $property->bookings_count ?? 0 }}</td>
                                                    <td>
                                                        <span class="status-badge {{ $property->is_available ? 'active' : 'inactive' }}">
                                                            {{ $property->is_available ? 'Active' : 'Inactive' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('owner.accommodations.edit', $property) }}" class="property-edit-btn">
                                                            <i class="fas fa-edit" aria-hidden="true"></i> Edit
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @if(method_exists($properties, 'hasPages') && $properties->hasPages())
                                    <div class="owner-dash-pagination pagination-clean">
                                        {{ $properties->onEachSide(1)->links('pagination::bootstrap-5') }}
                                    </div>
                                @endif
                            @else
                                <div class="owner-dash-empty">
                                    <i class="fas fa-home" aria-hidden="true"></i>
                                    <p>No properties yet.</p>
                                    <a href="/owner/accommodations/create" class="owner-dash-btn-primary">
                                        <i class="fas fa-plus" aria-hidden="true"></i> Add property
                                    </a>
                                </div>
                            @endif
                            </div>
                        </section>

                        <section class="owner-dash-block" aria-labelledby="owner-bookings-heading">
                            <div class="owner-dash-block__head">
                                <h3 id="owner-bookings-heading"><i class="fas fa-calendar-check" aria-hidden="true"></i> Recent requests</h3>
                            </div>
                            <div class="owner-dash-block__body owner-dash-block__body--flush">
                            @if(isset($recent_bookings) && count($recent_bookings) > 0)
                                <div class="owner-dash-table-scroll">
                                    <table class="property-table">
                                        <thead>
                                            <tr>
                                                <th>Guest</th>
                                                <th>Property</th>
                                                <th>Check-in</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recent_bookings as $booking)
                                                <tr>
                                                    <td>{{ $booking->client->name ?? 'N/A' }}</td>
                                                    <td>{{ $booking->accommodation->name ?? 'N/A' }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }}</td>
                                                    <td><strong>₱{{ number_format($booking->total_price, 0, '.', ',') }}</strong></td>
                                                    <td><span class="status-badge {{ $booking->status }}">{{ ucfirst($booking->status) }}</span></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="owner-dash-empty">
                                    <i class="fas fa-calendar-times" aria-hidden="true"></i>
                                    <p>No booking requests yet.</p>
                                </div>
                            @endif
                            </div>
                        </section>
                    </div>
                </div>

                <aside class="owner-dash-aside" aria-label="Shortcuts and availability">
                    <nav class="owner-dash-actions" aria-label="Quick actions">
                        <p class="owner-dash-actions__label">Quick actions</p>
                        <a href="/owner/accommodations/create" class="owner-dash-action">
                            <span class="owner-dash-action__icon"><i class="fas fa-plus-circle" aria-hidden="true"></i></span>
                            <span>
                                <p class="owner-dash-action__title">Add unit</p>
                                <p class="owner-dash-action__desc">List accommodation</p>
                            </span>
                        </a>
                        <a href="/owner/accommodations" class="owner-dash-action">
                            <span class="owner-dash-action__icon"><i class="fas fa-edit" aria-hidden="true"></i></span>
                            <span>
                                <p class="owner-dash-action__title">Manage units</p>
                                <p class="owner-dash-action__desc">Edit details</p>
                            </span>
                        </a>
                        <a href="{{ route('owner.bookings.index') }}" class="owner-dash-action">
                            <span class="owner-dash-action__icon"><i class="fas fa-tasks" aria-hidden="true"></i></span>
                            <span>
                                <p class="owner-dash-action__title">Bookings</p>
                                <p class="owner-dash-action__desc">Review requests</p>
                            </span>
                        </a>
                        @if(Auth::user()?->isAdmin())
                        <a href="/owner/users" class="owner-dash-action">
                            <span class="owner-dash-action__icon"><i class="fas fa-users-cog" aria-hidden="true"></i></span>
                            <span>
                                <p class="owner-dash-action__title">Users</p>
                                <p class="owner-dash-action__desc">Staff &amp; access</p>
                            </span>
                        </a>
                        @endif
                        <a href="{{ route('messages.index', [], false) }}" class="owner-dash-action">
                            <span class="owner-dash-action__icon"><i class="fas fa-reply" aria-hidden="true"></i></span>
                            <span>
                                <p class="owner-dash-action__title">Messages</p>
                                <p class="owner-dash-action__desc">Guest inquiries</p>
                            </span>
                        </a>
                    </nav>

                    @include('owner.partials.owner-availability-card', [
                        'calendarId' => 'ownerDashCal',
                        'availabilityAccommodations' => $availabilityAccommodations ?? collect(),
                        'availabilityEventsByAccommodation' => $availabilityEventsByAccommodation ?? [],
                        'headingId' => 'owner-avail-heading',
                    ])
                </aside>
            </div>
        </main>

    <script>
        const trendCtx = document.getElementById('ownerTrendChart');
        if (trendCtx) {
            new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: @json($trendLabels ?? []),
                    datasets: [
                        {
                            label: 'Revenue (PHP)',
                            data: @json($revenueTrend ?? []),
                            borderColor: '#457359',
                            backgroundColor: 'rgba(46, 125, 50, 0.15)',
                            tension: 0.35,
                            fill: true,
                            yAxisID: 'yRevenue'
                        },
                        {
                            label: 'Bookings',
                            data: @json($bookingsTrend ?? []),
                            borderColor: '#047857',
                            backgroundColor: 'rgba(4, 120, 87, 0.12)',
                            tension: 0.35,
                            fill: false,
                            yAxisID: 'yBookings'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { position: 'top', labels: { color: '#334155', font: { size: 11, weight: '600' } } },
                        tooltip: {
                            backgroundColor: 'rgba(15, 61, 36, 0.94)',
                            titleColor: '#ecfdf5',
                            bodyColor: '#d1fae5',
                            padding: 12,
                            cornerRadius: 8
                        }
                    },
                    scales: {
                        yRevenue: {
                            type: 'linear',
                            position: 'left',
                            ticks: {
                                callback: function(value) {
                                    return 'P' + Number(value).toLocaleString();
                                }
                            }
                        },
                        yBookings: {
                            type: 'linear',
                            position: 'right',
                            grid: { drawOnChartArea: false },
                            ticks: { precision: 0 }
                        }
                    }
                }
            });
        }

        const statusCtx = document.getElementById('ownerStatusChart');
        if (statusCtx) {
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'Confirmed', 'Paid', 'Completed', 'Cancelled'],
                    datasets: [{
                        data: [
                            {{ $bookingStatusBreakdown['pending'] ?? 0 }},
                            {{ $bookingStatusBreakdown['confirmed'] ?? 0 }},
                            {{ $bookingStatusBreakdown['paid'] ?? 0 }},
                            {{ $bookingStatusBreakdown['completed'] ?? 0 }},
                            {{ $bookingStatusBreakdown['cancelled'] ?? 0 }}
                        ],
                        backgroundColor: ['#d97706', '#86efac', '#22c55e', '#166534', '#f87171'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' },
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
    </script>
</body>
</html>
