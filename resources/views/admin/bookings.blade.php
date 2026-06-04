<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    @include('admin.partials.favicon')
    <title>Bookings Management - Admin Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    <style>
        @include('partials.ui-foundation-styles')
        @include('admin.partials.admin-shell-styles')
        .search-input { flex: 1; min-width: 0; max-width: 100%; padding: 12px 20px; border: 2px solid var(--green-soft); border-radius: 10px; font-size: var(--text-fluid-base); outline: none; }
        .search-input:focus { border-color: var(--green-primary); }
        .filter-select { padding: 12px 20px; border: 2px solid var(--app-surface-border, var(--green-soft)); border-radius: 10px; font-size: var(--text-fluid-base); background: var(--app-surface-bg, white); color: var(--ink-800); cursor: pointer; min-width: 0; }
        .card-header h3 { font-size: var(--text-fluid-lg); color: var(--ink-800, var(--green-dark)); font-weight: 600; }
        .property-info { display: flex; align-items: center; gap: 15px; flex-wrap: wrap; }
        .property-thumb { width: 60px; height: 60px; border-radius: 10px; object-fit: cover; flex-shrink: 0; }
        .status-badge { display: inline-block; padding: 5px 12px; border-radius: 50px; font-size: var(--text-fluid-xs); font-weight: 600; }
        .status-badge.pending { background: #FFF3E0; color: #E65100; }
        .status-badge.confirmed { background: var(--green-soft); color: var(--green-dark); }
        .status-badge.cancelled { background: #FFEBEE; color: #C62828; }
        .status-badge.completed { background: #E3F2FD; color: #1565C0; }
        .status-badge.paid { background: #EDF4EA; color: #457359; }
        @media (min-width: 768px) {
            .search-input { max-width: 400px; }
        }
    </style>
</head>
<body class="admin-central-portal">
    @include('admin.partials.top-navbar', ['active' => 'bookings'])

    <div class="dashboard-layout">
        <main class="main-content">
            <div class="page-header">
                <h1>
                    <span class="page-title-icon"><i class="fa-solid fa-calendar-check"></i></span>
                    <span>Bookings Management</span>
                </h1>
                <p>Review every reservation across all tulogans on the platform.</p>
            </div>

            <div class="search-filter app-filter-bar">
                <input type="text" class="search-input app-filter-bar__keep" placeholder="Search bookings...">
                <select class="filter-select">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="completed">Completed</option>
                </select>
                <select class="filter-select">
                    <option value="">This Week</option>
                    <option value="">This Month</option>
                    <option value="">This Year</option>
                </select>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>All Bookings (892)</h3>
                    <button class="btn btn-secondary btn-sm">Export Report</button>
                </div>
                <div class="card-body">
                    <div class="app-table-responsive" role="region" aria-label="Bookings table" tabindex="0">
                        <table class="app-data-table">
                            <thead>
                                <tr>
                                    <th>Property</th>
                                    <th>Guest</th>
                                    <th>Check-in</th>
                                    <th>Check-out</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="property-info">
                                            <img src="/COMMUNAL.jpg" alt="Property" class="property-thumb">
                                            <div><strong>Mountain View Inn</strong><br><small>Traveller-Inn</small></div>
                                        </div>
                                    </td>
                                    <td class="app-data-table__cell-long">Juan Miguel<br><small>juan@email.com</small></td>
                                    <td class="app-data-table__cell-nowrap">Dec 15, 2024</td>
                                    <td class="app-data-table__cell-nowrap">Dec 18, 2024</td>
                                    <td class="app-data-table__cell-nowrap">₱4,500</td>
                                    <td><span class="status-badge pending">Pending</span></td>
                                    <td class="app-data-table__cell-nowrap">Dec 10, 2024</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="property-info">
                                            <img src="/1.jpg" alt="Property" class="property-thumb">
                                            <div><strong>Cozy Garden House</strong><br><small>Airbnb</small></div>
                                        </div>
                                    </td>
                                    <td class="app-data-table__cell-long">Sarah Chen<br><small>sarah@email.com</small></td>
                                    <td class="app-data-table__cell-nowrap">Dec 20, 2024</td>
                                    <td class="app-data-table__cell-nowrap">Dec 25, 2024</td>
                                    <td class="app-data-table__cell-nowrap">₱16,800</td>
                                    <td><span class="status-badge confirmed">Confirmed</span></td>
                                    <td class="app-data-table__cell-nowrap">Dec 8, 2024</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="property-info">
                                            <img src="/2.jpg" alt="Property" class="property-thumb">
                                            <div><strong>Riverside Apartment</strong><br><small>Daily Rental</small></div>
                                        </div>
                                    </td>
                                    <td class="app-data-table__cell-long">Robert Perez<br><small>robert@email.com</small></td>
                                    <td class="app-data-table__cell-nowrap">Dec 10, 2024</td>
                                    <td class="app-data-table__cell-nowrap">Dec 12, 2024</td>
                                    <td class="app-data-table__cell-nowrap">₱2,400</td>
                                    <td><span class="status-badge completed">Completed</span></td>
                                    <td class="app-data-table__cell-nowrap">Dec 5, 2024</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="property-info">
                                            <img src="/airbnb1.jpg" alt="Property" class="property-thumb">
                                            <div><strong>Forest Cabin</strong><br><small>Airbnb</small></div>
                                        </div>
                                    </td>
                                    <td class="app-data-table__cell-long">Maria Lopez<br><small>maria@email.com</small></td>
                                    <td class="app-data-table__cell-nowrap">Dec 22, 2024</td>
                                    <td class="app-data-table__cell-nowrap">Dec 28, 2024</td>
                                    <td class="app-data-table__cell-nowrap">₱24,500</td>
                                    <td><span class="status-badge paid">Paid</span></td>
                                    <td class="app-data-table__cell-nowrap">Dec 9, 2024</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="property-info">
                                            <img src="/inn1.jpg" alt="Property" class="property-thumb">
                                            <div><strong>Town Inn</strong><br><small>Traveller-Inn</small></div>
                                        </div>
                                    </td>
                                    <td class="app-data-table__cell-long">John Doe<br><small>john@email.com</small></td>
                                    <td class="app-data-table__cell-nowrap">Dec 12, 2024</td>
                                    <td class="app-data-table__cell-nowrap">Dec 14, 2024</td>
                                    <td class="app-data-table__cell-nowrap">₱2,400</td>
                                    <td><span class="status-badge cancelled">Cancelled</span></td>
                                    <td class="app-data-table__cell-nowrap">Dec 6, 2024</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
