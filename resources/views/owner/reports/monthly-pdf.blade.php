<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    @include('partials.tenant-favicon')
    <title>Tenant Monthly Report - {{ $monthName }}</title>
    <style>
        @include('reports.partials.municipal-pdf-header-styles')
        @include('reports.partials.municipal-pdf-body-styles')
        @include('reports.partials.municipal-pdf-footer-styles')
    </style>
</head>
<body>
    @include('reports.partials.municipal-pdf-header', [
        'pdfReportTitle' => 'Tenant Monthly Report',
        'pdfReportSubtitle' => $monthName,
    ])

    <div class="pdf-body">
        <table class="pdf-kpi-row">
            <tr>
                <td>
                    <div class="kpi-title">Monthly sales</div>
                    <div class="kpi-value">PHP {{ number_format((float) $monthlySales, 2) }}</div>
                </td>
                <td>
                    <div class="kpi-title">People catered</div>
                    <div class="kpi-value">{{ number_format((int) $monthlyGuests) }}</div>
                </td>
                <td>
                    <div class="kpi-title">Total bookings</div>
                    <div class="kpi-value">{{ number_format((int) $monthlyBookings) }}</div>
                </td>
            </tr>
        </table>

        <div class="pdf-section">
            <div class="pdf-section__title">Daily breakdown</div>
            @if($dailyBreakdown->count() > 0)
                <table class="pdf-table">
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
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($row->report_date)->format('M d, Y') }}</td>
                                <td class="num">{{ (int) $row->booking_count }}</td>
                                <td class="num">{{ (int) $row->total_guests }}</td>
                                <td class="num">PHP {{ number_format((float) $row->total_sales, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <table class="pdf-table report-empty">
                    <tbody>
                        <tr><td class="pdf-empty">No qualified bookings found for this month.</td></tr>
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</body>
</html>
