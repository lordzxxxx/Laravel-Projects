<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    @include('admin.partials.favicon')
    <title>Monthly Booking Report - {{ $monthName }}</title>
    <style>
        @include('reports.partials.municipal-pdf-header-styles')
        @include('reports.partials.municipal-pdf-body-styles')
        @include('reports.partials.municipal-pdf-footer-styles')
    </style>
</head>
<body>
    @include('reports.partials.municipal-pdf-header', [
        'pdfReportTitle' => 'Monthly Booking Report',
        'pdfReportSubtitle' => $monthName.' · Tenant guest analytics',
    ])

    <div class="pdf-body">
        <div class="pdf-section">
            <div class="pdf-section__title">Report parameters</div>
            <table class="report-layout report-kv pdf-table">
                <tbody>
                    <tr>
                        <th scope="row">Reporting period</th>
                        <td>{{ $startDate->format('M j, Y') }} — {{ $endDate->format('M j, Y') }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Calendar month</th>
                        <td>{{ $monthName }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Generated</th>
                        <td>{{ now('Asia/Manila')->format('M j, Y g:i A') }} (PH)</td>
                    </tr>
                    <tr>
                        <th scope="row">Tenants with bookings</th>
                        <td>{{ $tenantBookings->count() }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="pdf-section">
            <div class="pdf-section__title">Consolidated totals</div>
            <table class="report-layout report-metrics pdf-table">
                <thead>
                    <tr>
                        <th scope="col">Bookings</th>
                        <th scope="col">Total guests</th>
                        <th scope="col">Avg guests / booking</th>
                        <th scope="col">Tenants</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $summary['total_bookings'] }}</td>
                        <td>{{ $summary['total_guests'] }}</td>
                        <td>{{ number_format((float) $summary['average_guests_per_booking'], 2) }}</td>
                        <td>{{ $summary['tenant_count'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="pdf-section">
            <div class="pdf-section__title">Tenant booking breakdown</div>

            @if($tenantBookings->count() > 0)
                <table class="report-detail pdf-table">
                    <thead>
                        <tr>
                            <th scope="col">Tenant</th>
                            <th scope="col" class="num-col">Bookings</th>
                            <th scope="col" class="num-col">Total guests</th>
                            <th scope="col" class="num-col">Avg guests</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tenantBookings as $booking)
                            <tr>
                                <td class="tenant-name">{{ $booking->name }}</td>
                                <td class="number">{{ $booking->booking_count }}</td>
                                <td class="number">{{ $booking->total_guests }}</td>
                                <td class="number">{{ number_format((float) $booking->avg_guests_per_booking, 2) }}</td>
                            </tr>
                        @endforeach
                        <tr class="summary-row">
                            <td>Total / weighted average</td>
                            <td class="number">{{ $summary['total_bookings'] }}</td>
                            <td class="number">{{ $summary['total_guests'] }}</td>
                            <td class="number">{{ number_format((float) $summary['average_guests_per_booking'], 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            @else
                <table class="report-layout report-empty pdf-table">
                    <tbody>
                        <tr>
                            <td>No booking data for {{ $monthName }} under the selected criteria.</td>
                        </tr>
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    @include('reports.partials.municipal-pdf-footer', [
        'pdfFooterTracking' => 'BR-'.$year.str_pad((string) $month, 2, '0', STR_PAD_LEFT).'-'.strtoupper(substr(md5(now('Asia/Manila')->toString()), 0, 6)),
    ])
</body>
</html>
