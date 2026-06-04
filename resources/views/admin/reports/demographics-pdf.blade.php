<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    @include('admin.partials.favicon')
    <title>Demographics Report</title>
    <style>
        @include('reports.partials.municipal-pdf-header-styles')
        @include('reports.partials.municipal-pdf-body-styles')
        @include('reports.partials.municipal-pdf-footer-styles')
    </style>
</head>
<body>
    @php
        $docTracking = 'DR-'
            .$demographics['start_date']->format('Ymd')
            .$demographics['end_date']->format('Ymd')
            .'-'
            .strtoupper(substr(md5((string) ($demographics['scope_slug'] ?? 'all-tenants').$demographics['start_date']->toDateString().$demographics['end_date']->toDateString()), 0, 6));
    @endphp

    @include('reports.partials.municipal-pdf-header')

    <div class="pdf-body">
        <table class="pdf-metrics summary">
            <tr>
                <td>
                    <span class="pdf-metric__value value">{{ $demographics['total_bookings'] }}</span>
                    <span class="pdf-metric__label label">Bookings</span>
                </td>
                <td>
                    <span class="pdf-metric__value value">{{ $demographics['total_guests'] }}</span>
                    <span class="pdf-metric__label label">Guests</span>
                </td>
                <td>
                    <span class="pdf-metric__value value">{{ $demographics['profiled_bookings'] }}</span>
                    <span class="pdf-metric__label label">Profiled</span>
                </td>
                <td>
                    <span class="pdf-metric__value value">{{ $demographics['average_age'] ?? 'N/A' }}</span>
                    <span class="pdf-metric__label label">Avg age</span>
                </td>
            </tr>
        </table>

        <table class="pdf-columns columns">
            <tr>
                <td>
                    <div class="pdf-block">
                        <h2 class="pdf-block__title">Gender distribution</h2>
                        <table class="pdf-table">
                            <thead><tr><th>Gender</th><th class="num">Bookings</th></tr></thead>
                            <tbody>
                            @foreach($demographics['gender']['raw'] as $label => $count)
                                <tr><td>{{ ucfirst($label) }}</td><td class="num">{{ $count }}</td></tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="pdf-block">
                        <h2 class="pdf-block__title">Location totals</h2>
                        <table class="pdf-table">
                            <thead><tr><th>Type</th><th class="num">Bookings</th></tr></thead>
                            <tbody>
                            @foreach($demographics['location']['raw'] as $label => $count)
                                <tr><td>{{ ucfirst($label) }}</td><td class="num">{{ $count }}</td></tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </td>
                <td>
                    <div class="pdf-block">
                        <h2 class="pdf-block__title">Age distribution</h2>
                        <table class="pdf-table">
                            <thead><tr><th>Bucket</th><th class="num">Bookings</th></tr></thead>
                            <tbody>
                            @foreach($demographics['age']['raw'] as $bucket => $count)
                                <tr><td>{{ $bucket }}</td><td class="num">{{ $count }}</td></tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="pdf-block">
                        <h2 class="pdf-block__title">Location breakdown</h2>
                        <table class="pdf-table">
                            <thead><tr><th>Area</th><th class="num">Bookings</th></tr></thead>
                            <tbody>
                            @forelse($demographics['location']['breakdown']['local_labels'] as $i => $place)
                                <tr><td>Local: {{ $place }}</td><td class="num">{{ $demographics['location']['breakdown']['local_counts'][$i] ?? 0 }}</td></tr>
                            @empty
                                <tr><td colspan="2" class="pdf-empty">No local place data</td></tr>
                            @endforelse
                            @forelse($demographics['location']['breakdown']['foreign_labels'] as $i => $country)
                                <tr><td>Foreign: {{ $country }}</td><td class="num">{{ $demographics['location']['breakdown']['foreign_counts'][$i] ?? 0 }}</td></tr>
                            @empty
                                <tr><td colspan="2" class="pdf-empty">No foreign country data</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    @include('reports.partials.municipal-pdf-footer', ['pdfFooterTracking' => $docTracking])
</body>
</html>
