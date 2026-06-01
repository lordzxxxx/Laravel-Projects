{{--
    Municipal PDF header (logos + government block + report title).
    Used by CA admin PDFs and owner/tenant monthly PDFs.
    Logos: Love Impasugong (left), LGU seal (right) — embedded as data URIs when present.
    Left: public/images/love-impasugong-transparent.png (fallback: report-headers/ca-left-logo.png).
    Right: public/Lgu Socmed Template-02 2.png (fallback: report-headers/ca-right-logo.png).
    Divider: public/images/pdf-header-tribal-divider.png (fallback: nav-tribal-pattern.png).
    Expects: $pdfReportTitle (string), $pdfReportSubtitle (string, optional)
--}}
@php
    $pdfReportTitle = $pdfReportTitle ?? '';
    $pdfReportSubtitle = $pdfReportSubtitle ?? '';

    $dividerCandidates = [
        public_path('images/pdf-header-tribal-divider.png'),
        public_path('images/nav-tribal-pattern.png'),
    ];
    $dividerData = null;
    foreach ($dividerCandidates as $candidatePath) {
        if (is_string($candidatePath) && $candidatePath !== '' && file_exists($candidatePath)) {
            $mime = function_exists('mime_content_type') ? (mime_content_type($candidatePath) ?: 'image/png') : 'image/png';
            $dividerData = 'data:'.$mime.';base64,'.base64_encode((string) file_get_contents($candidatePath));
            break;
        }
    }

    $leftLogoCandidates = [
        public_path('images/love-impasugong-transparent.png'),
        public_path('report-headers/ca-left-logo.png'),
    ];
    $leftLogoData = null;
    foreach ($leftLogoCandidates as $candidatePath) {
        if (is_string($candidatePath) && $candidatePath !== '' && file_exists($candidatePath)) {
            $mime = function_exists('mime_content_type') ? (mime_content_type($candidatePath) ?: 'image/png') : 'image/png';
            $leftLogoData = 'data:'.$mime.';base64,'.base64_encode((string) file_get_contents($candidatePath));
            break;
        }
    }

    $rightLogoCandidates = [
        public_path('Lgu Socmed Template-02 2.png'),
        public_path('report-headers/ca-right-logo.png'),
    ];
    $rightLogoData = null;
    foreach ($rightLogoCandidates as $candidatePath) {
        if (is_string($candidatePath) && $candidatePath !== '' && file_exists($candidatePath)) {
            $mime = function_exists('mime_content_type') ? (mime_content_type($candidatePath) ?: 'image/png') : 'image/png';
            $rightLogoData = 'data:'.$mime.';base64,'.base64_encode((string) file_get_contents($candidatePath));
            break;
        }
    }
@endphp
<div class="header">
    <table class="header-table" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td class="header-logo-cell header-logo-cell--left">
                @if($leftLogoData)
                    <div class="header-logo-slot">
                        <img src="{{ $leftLogoData }}" alt="Love Impasugong" class="header-side-logo header-side-logo--love" width="92" height="100">
                    </div>
                @endif
            </td>
            <td class="header-gap" width="50">&nbsp;</td>
            <td class="header-center">
                <div class="header-topline">Republic of the Philippines</div>
                <div class="header-main">Municipality of Impasug-ong</div>
                <div class="header-office">Tourism Management Office</div>
                <div class="header-report-line">Tulogan Monthly Report</div>
                @if($pdfReportTitle !== '')
                    <h1>{{ $pdfReportTitle }}</h1>
                @endif
                @if($pdfReportSubtitle !== '')
                    <p>{{ $pdfReportSubtitle }}</p>
                @endif
            </td>
            <td class="header-gap" width="50">&nbsp;</td>
            <td class="header-logo-cell header-logo-cell--right">
                @if($rightLogoData)
                    <div class="header-logo-slot">
                        <img src="{{ $rightLogoData }}" alt="LGU Impasug-ong" class="header-side-logo header-side-logo--lgu" width="94" height="94">
                    </div>
                @endif
            </td>
        </tr>
    </table>
    @if($dividerData)
        <div class="header-divider" style="background-image: url('{{ $dividerData }}');" role="presentation" aria-hidden="true"></div>
    @endif
</div>
