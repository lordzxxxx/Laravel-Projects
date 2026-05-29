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
            --gray-50: #F9FAFB; --gray-100: #F3F4F6; --gray-200: #E5E7EB;
            --gray-300: #D1D5DB; --gray-400: #9CA3AF; --gray-500: #6B7280;
            --gray-600: #4B5563; --gray-700: #374151; --gray-800: #1F2937;
            --blue-500: #3B82F6; --orange-500: #F97316; --purple-500: #8B5CF6;
        }
        
        body {
            background: var(--app-page-bg, #f4f8f5);
            min-height: 100vh;
            color: var(--ink-800, var(--gray-800));
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        /* Same top offset + padding as all other owner pages (shared top bar does not “jump”). */
        body.owner-nav-page .main-content.with-owner-nav.owner-dashboard-main {
            width: min(1600px, 100%);
            margin-left: auto;
            margin-right: auto;
            padding-left: clamp(20px, 3vw, 40px);
            padding-right: clamp(20px, 3vw, 40px);
            padding-bottom: 40px;
        }
        
        /* Page Header */
        .page-header { margin-bottom: 20px; }
        /* Title styling provided by ui-foundation-styles for cross-system consistency. */
        
        /* Stats Grid */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 14px; margin-bottom: 18px; }
        .stat-card {
            background: var(--app-surface-bg, var(--white));
            border: 1px solid var(--app-surface-border, var(--green-soft));
            padding: 16px;
            border-radius: 12px;
            box-shadow: var(--shadow-sm, 0 4px 15px rgba(27, 94, 32, 0.08));
            text-align: center;
            transition: all 0.3s;
            border: 1px solid var(--green-soft);
        }
        .stat-card:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 15px 40px rgba(27, 94, 32, 0.15);
        }
        .stat-icon { 
            width: 46px; 
            height: 46px; 
            border-radius: 10px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-size: 1.1rem;
            margin: 0 auto 10px;
            box-sizing: border-box;
        }
        .stat-icon.green { background: #e8f5e9; border: 1px solid #c8e6c9; color: var(--green-dark); }
        .stat-icon.blue { background: #ecfdf5; border: 1px solid #d1fae5; color: #047857; }
        .stat-icon.orange { background: #fffbeb; border: 1px solid #fde68a; color: #b45309; }
        .stat-icon.purple { background: #f8fafc; border: 1px solid #e2e8f0; color: #475569; }
        
        .stat-card .value { font-size: 1.35rem; font-weight: bold; color: var(--green-dark); margin-bottom: 3px; }
        .stat-card .label { color: var(--gray-500); font-size: 0.78rem; }
        
        /* Dashboard Card */
        .dashboard-card {
            background: var(--white);
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(27, 94, 32, 0.08);
            padding: 16px;
            margin-bottom: 16px;
            border: 1px solid var(--green-soft);
        }
        .dashboard-card h3 { 
            font-size: 1rem; 
            color: var(--gray-800); 
            margin-bottom: 12px; 
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .dashboard-card h3 .icon { color: var(--green-primary); }
        
        /* Quick Actions */
        .quick-actions { display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 12px; margin-bottom: 16px; }
        .quick-action-card {
            background: var(--white);
            padding: 14px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(27, 94, 32, 0.08);
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            border: 2px solid transparent;
            text-decoration: none;
            display: block;
            border: 1px solid var(--green-soft);
        }
        .quick-action-card:hover { 
            border-color: var(--green-primary); 
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(27, 94, 32, 0.15);
        }
        .quick-action-card .icon { 
            font-size: 1.7rem; 
            color: var(--green-primary); 
            margin-bottom: 8px; 
        }
        .quick-action-card h4 { color: var(--green-dark); margin-bottom: 5px; font-size: 0.9rem; }
        .quick-action-card p { color: var(--gray-500); font-size: 0.78rem; }

        .property-table { width: 100%; border-collapse: collapse; }
        .property-table th, .property-table td { padding: 10px; text-align: left; border-bottom: 1px solid var(--gray-200); }
        .property-table th { font-weight: 600; color: var(--gray-600); font-size: 0.8rem; text-transform: uppercase; background: var(--cream); }
        .property-table tr:hover { background: var(--green-white); }
        
        /* Status Badges */
        .status-badge { display: inline-flex; align-items: center; padding: 6px 12px; border-radius: 50px; font-size: 0.75rem; font-weight: 600; }
        .status-badge.active { background: #dcfce7; color: var(--green-dark); border: 1px solid #bbf7d0; }
        .status-badge.inactive { background: var(--gray-200); color: var(--gray-600); }
        .status-badge.pending { background: #fffbeb; color: #B45309; border: 1px solid #fde68a; }
        .status-badge.confirmed { background: #eff6ff; color: #1D4ED8; border: 1px solid #bfdbfe; }
        .status-badge.cancelled { background: #fee2e2; color: #DC2626; border: 1px solid #fecaca; }

        .pbi-visual-body .availability-board {
            background: transparent;
            border: none;
            box-shadow: none;
            padding: 0;
            margin: 0;
        }
        .pbi-visual-body .availability-board > h3 { display: none; }

        .chart-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 25px; }
        .chart-container { position: relative; height: 220px; }

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
        
        /* Business status (owner) */
        .business-status-pill {
            display: inline-flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 6px 8px;
            margin-top: 12px;
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

        /* Responsive */
        @media (max-width: 768px) {
            body.owner-nav-page .main-content.with-owner-nav.owner-dashboard-main {
                padding-left: 16px;
                padding-right: 16px;
            }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .chart-grid { grid-template-columns: 1fr; }
        }
        
        /* Animations */
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate { animation: fadeInUp 0.6s ease forwards; }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }

        @include('partials.pbi-visual-surface-styles')
        @include('owner.partials.top-navbar-styles')
    </style>
</head>
<body class="owner-nav-page">
    @include('owner.partials.top-navbar')
    
        <main class="main-content with-owner-nav owner-dashboard-main">
            <!-- Page Header -->
            <div class="page-header animate">
                <h1>
                    <span class="page-title-icon"><i class="fa-solid fa-house-laptop"></i></span>
                    <span>Unit Management Dashboard</span>
                </h1>
                <p>Monitor your properties and booking performance.</p>
                @if(!empty($businessStatus))
                    <span class="business-status-pill tone-{{ $businessStatus['tone'] }}">
                        <i class="fas fa-clipboard-check" aria-hidden="true"></i>
                        <span class="biz-label">Business status</span>
                        <span class="biz-main">Registration: {{ $businessStatus['registration'] }}</span>
                    </span>
                @endif
            </div>

            <div class="dashboard-card pbi-visual animate delay-1">
                <div class="pbi-visual-header">
                    <div class="pbi-visual-title">
                        <i class="fas fa-gauge-high" aria-hidden="true"></i>
                        <span>Key metrics</span>
                    </div>
                    <div class="pbi-visual-meta">
                        Live snapshot<br>
                        <span class="pbi-meta-subtle">Totals across your units</span>
                    </div>
                </div>
                <div class="pbi-visual-body">
                    <div class="stats-grid owner-pbi-stat-grid">
                        <div class="stat-card">
                            <div class="stat-icon green"><i class="fas fa-building"></i></div>
                            <div class="value">{{ $stats['total_properties'] ?? 0 }}</div>
                            <div class="label">Total Units</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon blue"><i class="fas fa-check-circle"></i></div>
                            <div class="value">{{ $stats['active_properties'] ?? 0 }}</div>
                            <div class="label">Active Units</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon orange"><i class="fas fa-calendar-check"></i></div>
                            <div class="value">{{ $stats['total_bookings'] ?? 0 }}</div>
                            <div class="label">Total Bookings</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon purple"><i class="fas fa-clock"></i></div>
                            <div class="value">{{ $stats['pending_bookings'] ?? 0 }}</div>
                            <div class="label">Pending Requests</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="quick-actions animate delay-2">
                <a href="/owner/accommodations/create" class="quick-action-card">
                    <div class="icon"><i class="fas fa-plus-circle"></i></div>
                    <h4>Add New Unit</h4>
                    <p>List a new accommodation</p>
                </a>
                <a href="/owner/accommodations" class="quick-action-card">
                    <div class="icon"><i class="fas fa-edit"></i></div>
                    <h4>Manage Units</h4>
                    <p>Edit property details</p>
                </a>
                <a href="{{ route('owner.bookings.index') }}" class="quick-action-card">
                    <div class="icon"><i class="fas fa-tasks"></i></div>
                    <h4>Booking Requests</h4>
                    <p>Review pending bookings</p>
                </a>
                @if(Auth::user()?->isAdmin())
                <a href="/owner/users" class="quick-action-card">
                    <div class="icon"><i class="fas fa-users-cog"></i></div>
                    <h4>User management</h4>
                    <p>Manage tenant staff &amp; access</p>
                </a>
                @endif
                <a href="{{ route('messages.index', [], false) }}" class="quick-action-card">
                    <div class="icon"><i class="fas fa-reply"></i></div>
                    <h4>Messages</h4>
                    <p>Respond to inquiries</p>
                </a>
            </div>

            <div class="dashboard-card pbi-visual animate delay-2">
                <div class="pbi-visual-header">
                    <div class="pbi-visual-title">
                        <i class="fas fa-calendar-days" aria-hidden="true"></i>
                        <span>Room availability</span>
                    </div>
                    <div class="pbi-visual-meta">
                        Per unit · Monthly grid<br>
                        <span class="pbi-meta-subtle">Blocked dates include pending &amp; confirmed holds</span>
                    </div>
                </div>
                <div class="pbi-visual-body">
                    <div class="availability-board">
                        @if(($availabilityAccommodations ?? collect())->isNotEmpty())
                            @include('partials.availability-calendar', [
                                'calendarId' => 'ownerDashCal',
                                'availabilityAccommodations' => $availabilityAccommodations,
                                'availabilityEventsByAccommodation' => $availabilityEventsByAccommodation ?? [],
                            ])
                        @else
                            <p style="color: var(--gray-500);">No units yet. Add your first accommodation to start tracking availability.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="chart-grid animate delay-3">
                <div class="dashboard-card pbi-visual">
                    <div class="pbi-visual-header">
                        <div class="pbi-visual-title">
                            <i class="fas fa-chart-line" aria-hidden="true"></i>
                            <span>Revenue &amp; bookings trend</span>
                        </div>
                        <div class="pbi-visual-meta">
                            Last 30 days<br>
                            <span class="pbi-meta-subtle">Revenue vs booking count</span>
                        </div>
                    </div>
                    <div class="pbi-visual-body">
                        <div class="chart-container">
                            <canvas id="ownerTrendChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="dashboard-card pbi-visual">
                    <div class="pbi-visual-header">
                        <div class="pbi-visual-title">
                            <i class="fas fa-chart-pie" aria-hidden="true"></i>
                            <span>Booking status mix</span>
                        </div>
                        <div class="pbi-visual-meta">
                            All-time share<br>
                            <span class="pbi-meta-subtle">By pipeline stage</span>
                        </div>
                    </div>
                    <div class="pbi-visual-body">
                        <div class="chart-container">
                            <canvas id="ownerStatusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- My Units Table -->
            <div class="dashboard-card animate delay-3">
                <h3><i class="fas fa-list icon"></i>My Units</h3>
                @if(isset($properties) && count($properties) > 0)
                    <table class="property-table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-building"></i> Property</th>
                                <th><i class="fas fa-tag"></i> Type</th>
                                <th><i class="fas fa-peso-sign"></i> Price</th>
                                <th><i class="fas fa-ticket-alt"></i> Bookings</th>
                                <th><i class="fas fa-star"></i> Rating</th>
                                <th><i class="fas fa-toggle-on"></i> Status</th>
                                <th><i class="fas fa-cog"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($properties as $property)
                                <tr>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 15px;">
                                            <img src="{{ $property->primary_image_url }}" alt="{{ $property->name }}" style="width: 50px; height: 50px; border-radius: 10px; object-fit: cover;">
                                            <div>
                                                <div style="font-weight: 600; color: var(--gray-800);">{{ $property->name }}</div>
                                                <div style="font-size: 0.85rem; color: var(--gray-500);">{{ $property->address }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span style="background: var(--green-soft); padding: 4px 10px; border-radius: 6px; font-size: 0.85rem;">{{ ucfirst(str_replace('-', ' ', $property->type)) }}</span></td>
                                    <td><strong>₱{{ number_format($property->price_per_night, 0, '.', ',') }}</strong></td>
                                    <td>{{ $property->bookings_count ?? 0 }}</td>
                                    <td>
                                        @if($property->rating > 0)
                                            <span style="color: var(--amber-500);"><i class="fas fa-star"></i> {{ number_format($property->rating, 1) }}</span>
                                        @else
                                            <span style="color: var(--gray-400);"><i class="fas fa-minus"></i> No ratings</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="status-badge {{ $property->is_available ? 'active' : 'inactive' }}">
                                            <i class="fas {{ $property->is_available ? 'fa-check' : 'fa-times' }}"></i>
                                            {{ $property->is_available ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('owner.accommodations.edit', $property) }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: var(--green-soft); color: var(--green-dark); border-radius: 8px; text-decoration: none; font-size: 0.85rem; font-weight: 500; transition: all 0.3s;">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if(method_exists($properties, 'hasPages') && $properties->hasPages())
                        <div class="pagination-clean" style="margin-top: 12px; display: flex; justify-content: center;">
                            {{ $properties->onEachSide(1)->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                @else
                    <div style="text-align: center; padding: 40px; color: var(--gray-400);">
                        <i class="fas fa-home" style="font-size: 3rem; margin-bottom: 15px; color: var(--gray-300);"></i>
                        <p>No properties yet. Add your first property!</p>
                        <a href="/owner/accommodations/create" style="display: inline-block; margin-top: 15px; padding: 12px 25px; background: var(--green-primary); color: white; border-radius: 8px; text-decoration: none;">
                            <i class="fas fa-plus"></i> Add Property
                        </a>
                    </div>
                @endif
            </div>
            
            <!-- Recent Booking Requests -->
            <div class="dashboard-card animate delay-4">
                <h3><i class="fas fa-calendar-check icon"></i>Recent Booking Requests</h3>
                @if(isset($recent_bookings) && count($recent_bookings) > 0)
                    <table class="property-table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-user"></i> Guest</th>
                                <th><i class="fas fa-building"></i> Property</th>
                                <th><i class="fas fa-calendar-alt"></i> Check-In</th>
                                <th><i class="fas fa-money-bill-wave"></i> Amount</th>
                                <th><i class="fas fa-info-circle"></i> Status</th>
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
                @else
                    <div style="text-align: center; padding: 40px; color: var(--gray-400);">
                        <i class="fas fa-calendar-times" style="font-size: 3rem; margin-bottom: 15px; color: var(--gray-300);"></i>
                        <p>No booking requests yet</p>
                    </div>
                @endif
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
