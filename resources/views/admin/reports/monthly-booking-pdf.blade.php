<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    @include('admin.partials.favicon')
    <title>Monthly Booking Report - {{ $monthName }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: "Times New Roman", Times, serif;
            color: #000000;
            line-height: 1.6;
            font-size: 12px;
        }
        
        .container {
            padding: 20px;
            max-width: 1120px;
            margin: 0 auto;
        }
        
        @include('reports.partials.municipal-pdf-header-styles')

        .pdf-section {
            margin-bottom: 14px;
            page-break-inside: avoid;
        }

        .pdf-section-title {
            color: #000000;
            font-size: 12px;
            font-weight: 700;
            margin: 0 0 6px 0;
            padding-bottom: 4px;
            border-bottom: 1px solid #C8E6C9;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        table.report-layout {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
            font-size: 12px;
            border: 1px solid #bdbdbd;
        }

        table.report-layout th,
        table.report-layout td {
            padding: 7px 8px;
            border: 1px solid #bdbdbd;
            vertical-align: top;
        }

        table.report-kv th {
            width: 32%;
            background: #f3f4f6;
            color: #000000;
            font-weight: 600;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        table.report-kv td {
            background: #fff;
            color: #000000;
        }

        table.report-metrics thead th {
            background: #E8F5E9;
            color: #000000;
            font-weight: 700;
            text-align: center;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.35px;
            border: 1px solid #81C784;
        }

        table.report-metrics tbody td {
            text-align: center;
            font-weight: 700;
            font-size: 13px;
            color: #000000;
            background: #fafafa;
            border: 1px solid #bdbdbd;
        }

        /* Tenant detail grid */
        .table-section {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }

        table.report-detail {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 12px;
            border: 1px solid #9e9e9e;
        }

        table.report-detail thead th {
            background: #2E7D32;
            color: white;
            padding: 7px 8px;
            text-align: left;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            border: 1px solid #1B5E20;
        }

        table.report-detail thead th.num-col {
            text-align: right;
        }

        table.report-detail tbody td {
            padding: 7px 8px;
            border: 1px solid #e0e0e0;
            font-size: 12px;
        }

        table.report-detail tbody tr:nth-child(even) {
            background: #F9FAFB;
        }
        
        .tenant-name {
            font-weight: 600;
            color: #000000;
        }
        
        .number {
            text-align: right;
            font-weight: 600;
            color: #000000;
        }
        
        
        /* Summary Row */
        .summary-row {
            background: #E8F5E9;
            font-weight: 700;
            color: #000000;
        }
        
        .summary-row td {
            font-weight: 700;
            color: #000000;
            border: 1px solid #81C784 !important;
            background: #E8F5E9 !important;
        }

        table.report-empty td {
            text-align: center;
            color: #000000;
            padding: 20px;
            font-style: italic;
        }
        
        /* Footer */
        .footer {
            margin-top: 14px;
            padding-top: 10px;
            border-top: 1px solid #d1d5db;
            font-size: 12px;
            color: #000000;
        }
        .footer-row {
            width: 100%;
            border-collapse: collapse;
        }
        .footer-row td {
            border: none;
            font-size: 12px;
            color: #000000;
            padding: 2px 0;
        }
        .footer-left { text-align: left; }
        .footer-center { text-align: center; }
        .footer-right { text-align: right; }
        .pagenum:before { content: counter(page); }
        .pagecount:before { content: counter(pages); }
        
        /* Print Styles */
        @media print {
            body {
                background: white;
            }
            
            .container {
                padding: 15px;
            }
            
            .page-break {
                page-break-after: always;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        @include('reports.partials.municipal-pdf-header', [
            'pdfReportTitle' => 'Monthly Booking Report',
            'pdfReportSubtitle' => $monthName.' - Tenant Guest Analytics',
        ])

        <div class="pdf-section">
            <div class="pdf-section-title">1. Report parameters</div>
            <table class="report-layout report-kv">
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
                        <td>{{ now('Asia/Manila')->format('M j, Y') }} (Philippines)</td>
                    </tr>
                    <tr>
                        <th scope="row">Tenants with bookings (this period)</th>
                        <td>{{ $tenantBookings->count() }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="pdf-section">
            <div class="pdf-section-title">2. Consolidated totals</div>
            <table class="report-layout report-metrics">
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

        <div class="table-section pdf-section">
            <div class="pdf-section-title">3. Tenant booking breakdown</div>

            @if($tenantBookings->count() > 0)
                <table class="report-detail">
                    <thead>
                        <tr>
                            <th scope="col">Tenant</th>
                            <th scope="col" class="num-col">Bookings</th>
                            <th scope="col" class="num-col">Total guests</th>
                            <th scope="col" class="num-col">Avg guests / booking</th>
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
                            <td><strong>Total / weighted average</strong></td>
                            <td class="number"><strong>{{ $summary['total_bookings'] }}</strong></td>
                            <td class="number"><strong>{{ $summary['total_guests'] }}</strong></td>
                            <td class="number"><strong>{{ number_format((float) $summary['average_guests_per_booking'], 2) }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            @else
                <table class="report-layout report-empty">
                    <tbody>
                        <tr>
                            <td>No booking data recorded for {{ $monthName }} under the selected criteria.</td>
                        </tr>
                    </tbody>
                </table>
            @endif
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <table class="footer-row">
                <tr>
                    <td class="footer-left"><strong>Date:</strong> {{ now('Asia/Manila')->format('M d, Y h:i A') }}</td>
                    <td class="footer-center"><strong>Doc Tracking:</strong> BR-{{ $year }}{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}-{{ strtoupper(substr(md5(now('Asia/Manila')->toString()), 0, 6)) }}</td>
                    <td class="footer-right"><strong>Page:</strong> <span class="pagenum"></span> / <span class="pagecount"></span></td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
