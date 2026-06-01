{{-- @deprecated Use reports.partials.municipal-pdf-open — kept for legacy includes. --}}
@include('reports.partials.municipal-pdf-open', [
    'pdfReportTitle' => $pdfReportTitle ?? 'Report',
    'pdfReportSubtitle' => $pdfReportSubtitle ?? '',
    'pdfOrientation' => $pdfOrientation ?? 'landscape',
])
