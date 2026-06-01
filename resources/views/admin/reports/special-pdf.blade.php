{{-- Special shoot / special booking type report. --}}
@include('reports.partials.municipal-pdf-open', [
    'pdfOrientation' => 'landscape',
    'pdfReportTitle' => 'Special Shoot Bookings Report',
    'pdfReportSubtitle' => $filterSummary ?? '',
    'documentTitle' => 'Special Shoot Bookings Report',
])

<table class="summary">
    <tr>
        <th>Filter summary</th>
        <td colspan="3">{{ $filterSummary ?? 'All records' }}</td>
    </tr>
    <tr>
        <th>Records</th>
        <td>{{ count($bookings ?? []) }}</td>
        <th>Generated</th>
        <td>{{ ($generatedAt ?? now())->timezone('Asia/Manila')->format('M d, Y h:i A') }}</td>
    </tr>
</table>

<table class="report-table">
    <thead>
        <tr>
            <th>Reference</th>
            <th>Guest</th>
            <th>Check-in</th>
            <th>Check-out</th>
            <th class="text-right">Guests</th>
            <th class="text-right">Amount</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($bookings ?? [] as $booking)
            <tr>
                <td>{{ $booking->id }}</td>
                <td>{{ $booking->client->name ?? '—' }}</td>
                <td>{{ optional($booking->check_in_date)->format('Y-m-d') }}</td>
                <td>{{ optional($booking->check_out_date)->format('Y-m-d') }}</td>
                <td class="text-right">{{ (int) $booking->number_of_guests }}</td>
                <td class="text-right">PHP {{ number_format((float) ($booking->payment_amount ?? $booking->total_price ?? 0), 2) }}</td>
                <td>{{ ucfirst((string) $booking->status) }}</td>
            </tr>
        @empty
            <tr><td colspan="7" class="text-center">No special bookings for the selected filters.</td></tr>
        @endforelse
    </tbody>
</table>

@include('reports.partials.municipal-pdf-footer', [
    'pdfFooterLeft' => '<strong>Prepared by:</strong> '.($admin->name ?? 'Administrator'),
    'pdfFooterCenter' => '<strong>Generated:</strong> '.($generatedAt ?? now())->timezone('Asia/Manila')->format('M d, Y h:i A'),
    'pdfFooterRight' => '<strong>Page:</strong> <span class="pagenum"></span> / <span class="pagecount"></span>',
])

@include('reports.partials.municipal-pdf-close')
