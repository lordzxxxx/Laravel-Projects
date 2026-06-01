@php
    $pdfExtraStyles = <<<'CSS'
        table.report-detail {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 8.7px;
            border: 1px solid #9e9e9e;
        }
        table.report-detail thead th {
            background: #2E7D32;
            color: #fff;
            padding: 5px 6px;
            text-align: left;
            font-size: 8.3px;
            font-weight: 700;
            text-transform: uppercase;
            border: 1px solid #1B5E20;
        }
        table.report-detail thead th.num-col { text-align: right; }
        table.report-detail tbody td {
            padding: 5px 6px;
            border: 1px solid #e0e0e0;
        }
        table.report-detail tbody tr:nth-child(even) { background: #F9FAFB; }
        table.report-detail .summary-row td {
            font-weight: 700;
            background: #E8F5E9 !important;
            border-color: #81C784 !important;
        }
        table.report-metrics thead th { text-align: center; }
        table.report-metrics tbody td {
            text-align: center;
            font-weight: 700;
            background: #fafafa;
        }
        table.report-empty td {
            text-align: center;
            font-style: italic;
            padding: 16px;
        }
        .number { text-align: right; }
    CSS;
@endphp
@include('reports.partials.municipal-pdf-open', [
    'pdfOrientation' => 'landscape',
    'pdfReportTitle' => 'Monthly Booking Report',
    'pdfReportSubtitle' => $monthName.' — Tenant Guest Analytics',
    'documentTitle' => 'Monthly Booking Report — '.$monthName,
    'pdfExtraStyles' => $pdfExtraStyles,
])

<p class="pdf-section-title">1. Report parameters</p>
<table class="summary report-table report-kv">
    <tbody>
        <tr>
            <th>Reporting period</th>
            <td>{{ $startDate->format('M j, Y') }} — {{ $endDate->format('M j, Y') }}</td>
        </tr>
        <tr>
            <th>Calendar month</th>
            <td>{{ $monthName }}</td>
        </tr>
        <tr>
            <th>Generated</th>
            <td>{{ now('Asia/Manila')->format('M j, Y') }} (Philippines)</td>
        </tr>
        <tr>
            <th>Tenants with bookings</th>
            <td>{{ $tenantBookings->count() }}</td>
        </tr>
    </tbody>
</table>

<p class="pdf-section-title">2. Consolidated totals</p>
<table class="report-table report-metrics">
    <thead>
        <tr>
            <th>Bookings</th>
            <th>Total guests</th>
            <th>Avg guests / booking</th>
            <th>Tenants</th>
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

<p class="pdf-section-title">3. Tenant booking breakdown</p>
@if($tenantBookings->count() > 0)
    <table class="report-detail">
        <thead>
            <tr>
                <th>Tenant</th>
                <th class="num-col">Bookings</th>
                <th class="num-col">Total guests</th>
                <th class="num-col">Avg guests / booking</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tenantBookings as $booking)
                <tr>
                    <td>{{ $booking->name }}</td>
                    <td class="number">{{ $booking->booking_count }}</td>
                    <td class="number">{{ $booking->total_guests }}</td>
                    <td class="number">{{ number_format((float) $booking->avg_guests_per_booking, 2) }}</td>
                </tr>
            @endforeach
            <tr class="summary-row">
                <td><strong>Total / weighted average</strong></td>
                <td class="number"><strong>{{ $summary['total_bookings'] }}</strong></td>
                <td class="number"><strong>{{ $summary['total_guests'] }}</strong></td>
                <td class="number"><strong>{{ number_format((float) $summary['average_guests_per_booking'], 2) }}</strong></td>
            </tr>
        </tbody>
    </table>
@else
    <table class="report-table report-empty">
        <tbody>
            <tr>
                <td>No booking data recorded for {{ $monthName }} under the selected criteria.</td>
            </tr>
        </tbody>
    </table>
@endif

@include('reports.partials.municipal-pdf-footer', [
    'pdfFooterLeft' => '<strong>Date:</strong> '.now('Asia/Manila')->format('M d, Y h:i A'),
    'pdfFooterCenter' => '<strong>Doc Tracking:</strong> BR-'.$year.str_pad((string) $month, 2, '0', STR_PAD_LEFT).'-'.strtoupper(substr(md5(now('Asia/Manila')->toString()), 0, 6)),
    'pdfFooterRight' => '<strong>Page:</strong> <span class="pagenum"></span> / <span class="pagecount"></span>',
])

@include('reports.partials.municipal-pdf-close')
