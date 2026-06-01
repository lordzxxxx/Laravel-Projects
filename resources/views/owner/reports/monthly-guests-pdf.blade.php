@include('reports.partials.municipal-pdf-open', [
    'pdfOrientation' => 'portrait',
    'pdfReportTitle' => 'Monthly Guests Catered Report',
    'pdfReportSubtitle' => 'Reporting month: '.$monthName,
    'documentTitle' => 'Monthly Guests Report — '.$monthName,
])

<table class="summary">
    <tr>
        <td class="text-center"><strong>{{ number_format((int) $monthlyGuests) }}</strong><br>Guests Catered</td>
        <td class="text-center"><strong>{{ number_format((int) $monthlyBookings) }}</strong><br>Total Bookings</td>
        <td class="text-center"><strong>PHP {{ number_format((float) $monthlySales, 2) }}</strong><br>Monthly Sales</td>
    </tr>
</table>

@if($dailyBreakdown->count() > 0)
    <table class="report-table">
        <thead>
            <tr>
                <th>Date</th>
                <th class="text-right">Bookings</th>
                <th class="text-right">Guests</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dailyBreakdown as $row)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($row->report_date)->format('M d, Y') }}</td>
                    <td class="text-right">{{ (int) $row->booking_count }}</td>
                    <td class="text-right">{{ (int) $row->total_guests }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p class="text-center" style="padding: 18px 0;">No qualified bookings found for this month.</p>
@endif

@include('reports.partials.municipal-pdf-footer', [
    'pdfFooterLeft' => '<strong>Generated:</strong> '.now('Asia/Manila')->format('M d, Y h:i A'),
    'pdfFooterCenter' => '<strong>Period:</strong> '.$monthName,
    'pdfFooterRight' => '<strong>Page:</strong> <span class="pagenum"></span> / <span class="pagecount"></span>',
])

@include('reports.partials.municipal-pdf-close')
