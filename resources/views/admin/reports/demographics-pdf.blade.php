@php
    $docTracking = 'DR-'
        .$demographics['start_date']->format('Ymd')
        .$demographics['end_date']->format('Ymd')
        .'-'
        .strtoupper(substr(md5((string) ($demographics['scope_slug'] ?? 'all-tenants').$demographics['start_date']->toDateString().$demographics['end_date']->toDateString()), 0, 6));
@endphp
@include('reports.partials.municipal-pdf-open', [
    'pdfOrientation' => 'portrait',
    'pdfReportTitle' => 'Demographics Report',
    'pdfReportSubtitle' => $demographics['scope_label'].' | '.$demographics['start_date']->toDateString().' to '.$demographics['end_date']->toDateString(),
    'documentTitle' => 'Demographics Report',
])

<table class="summary">
    <tr>
        <td class="text-center"><strong>{{ $demographics['total_bookings'] }}</strong><br>Bookings</td>
        <td class="text-center"><strong>{{ $demographics['total_guests'] }}</strong><br>Guests</td>
        <td class="text-center"><strong>{{ $demographics['profiled_bookings'] }}</strong><br>Profiled Bookings</td>
        <td class="text-center"><strong>{{ $demographics['average_age'] ?? 'N/A' }}</strong><br>Average Age</td>
    </tr>
</table>

<table class="report-table" style="margin-bottom: 10px;">
    <tr>
        <td style="width: 50%; vertical-align: top; border: none; padding: 0 6px 0 0;">
            <p class="pdf-section-title">Gender Distribution</p>
            <table class="report-table">
                <thead><tr><th>Gender</th><th>Bookings</th></tr></thead>
                <tbody>
                @foreach($demographics['gender']['raw'] as $label => $count)
                    <tr><td>{{ ucfirst($label) }}</td><td class="text-right">{{ $count }}</td></tr>
                @endforeach
                </tbody>
            </table>
            <p class="pdf-section-title" style="margin-top: 10px;">Location Totals</p>
            <table class="report-table">
                <thead><tr><th>Type</th><th>Bookings</th></tr></thead>
                <tbody>
                @foreach($demographics['location']['raw'] as $label => $count)
                    <tr><td>{{ ucfirst($label) }}</td><td class="text-right">{{ $count }}</td></tr>
                @endforeach
                </tbody>
            </table>
        </td>
        <td style="width: 50%; vertical-align: top; border: none; padding: 0 0 0 6px;">
            <p class="pdf-section-title">Age Distribution</p>
            <table class="report-table">
                <thead><tr><th>Bucket</th><th>Bookings</th></tr></thead>
                <tbody>
                @foreach($demographics['age']['raw'] as $bucket => $count)
                    <tr><td>{{ $bucket }}</td><td class="text-right">{{ $count }}</td></tr>
                @endforeach
                </tbody>
            </table>
            <p class="pdf-section-title" style="margin-top: 10px;">Location Breakdown</p>
            <table class="report-table">
                <thead><tr><th>Area</th><th>Bookings</th></tr></thead>
                <tbody>
                @forelse($demographics['location']['breakdown']['local_labels'] as $i => $place)
                    <tr><td>Local: {{ $place }}</td><td class="text-right">{{ $demographics['location']['breakdown']['local_counts'][$i] ?? 0 }}</td></tr>
                @empty
                    <tr><td colspan="2">No local place data</td></tr>
                @endforelse
                @forelse($demographics['location']['breakdown']['foreign_labels'] as $i => $country)
                    <tr><td>Foreign: {{ $country }}</td><td class="text-right">{{ $demographics['location']['breakdown']['foreign_counts'][$i] ?? 0 }}</td></tr>
                @empty
                    <tr><td colspan="2">No foreign country data</td></tr>
                @endforelse
                </tbody>
            </table>
        </td>
    </tr>
</table>

@include('reports.partials.municipal-pdf-footer', [
    'pdfFooterDate' => now('Asia/Manila')->format('M d, Y h:i A'),
    'pdfFooterTracking' => $docTracking,
])

@include('reports.partials.municipal-pdf-close')
