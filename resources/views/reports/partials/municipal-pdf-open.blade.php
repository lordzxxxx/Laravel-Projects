{{--
    Opens municipal PDF document (borders + letterhead + report title).
    Required: $pdfReportTitle (string)
    Optional: $pdfReportSubtitle, $pdfOrientation ('landscape'|'portrait'), $documentTitle
--}}
@php
    use App\Support\MunicipalPdfAssets;

    $pdfReportTitle = $pdfReportTitle ?? 'Report';
    $pdfReportSubtitle = $pdfReportSubtitle ?? '';
    $pdfOrientation = in_array(($pdfOrientation ?? 'landscape'), ['landscape', 'portrait'], true)
        ? $pdfOrientation
        : 'landscape';
    $documentTitle = $documentTitle ?? $pdfReportTitle;

    $borders = MunicipalPdfAssets::borders($pdfOrientation);
    $logos = MunicipalPdfAssets::letterheadLogos();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $documentTitle }}</title>
    <style>
        @include('reports.partials.municipal-pdf-styles')
        @isset($pdfExtraStyles)
        {!! $pdfExtraStyles !!}
        @endisset
    </style>
</head>
<body class="pdf-orientation-{{ $pdfOrientation }}">
    @if($borders['left_border'])
        <img src="{{ $borders['left_border'] }}" class="report-border-left" alt="">
    @endif
    @if($borders['right_border'])
        <img src="{{ $borders['right_border'] }}" class="report-border-right" alt="">
    @endif

    <main class="report-canvas">
        <table class="letterhead-outer" width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td align="center">
                    <table class="letterhead" cellpadding="0" cellspacing="0" border="0"
                        width="{{ $pdfOrientation === 'portrait' ? '420' : '520' }}">
                        <tr>
                            <td class="letterhead-logos" width="{{ $pdfOrientation === 'portrait' ? '168' : '210' }}" valign="middle" align="center">
                                @if($logos['bagong'])
                                    <img src="{{ $logos['bagong'] }}" class="letterhead-logo letterhead-logo--bagong" alt="">
                                @endif
                                @if($logos['seal'])
                                    <img src="{{ $logos['seal'] }}" class="letterhead-logo letterhead-logo--seal" alt="">
                                @endif
                                @if($logos['love'])
                                    <img src="{{ $logos['love'] }}" class="letterhead-logo letterhead-logo--love" alt="">
                                @endif
                            </td>
                            <td class="letterhead-divider" width="2" valign="middle">&nbsp;</td>
                            <td class="letterhead-text" width="{{ $pdfOrientation === 'portrait' ? '250' : '308' }}" valign="middle" align="center">
                                <p class="country">Republic of the Philippines</p>
                                <p class="province">Province of Bukidnon</p>
                                <h1>Municipality of Impasug-ong</h1>
                                <p class="office">Office of the Municipal Mayor</p>
                                <p class="section">Tourism Section</p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table class="report-heading" width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td align="center">
                    <h2 class="report-title">{{ $pdfReportTitle }}</h2>
                    @if($pdfReportSubtitle !== '')
                        <p class="report-subtitle">{{ $pdfReportSubtitle }}</p>
                    @endif
                </td>
            </tr>
        </table>
