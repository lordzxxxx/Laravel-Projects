{{--
    Municipal tourism PDF shell (DomPDF).
    Expects optional: $pdfOrientation = 'landscape' | 'portrait'
--}}
@php
    $pdfOrientation = in_array(($pdfOrientation ?? 'landscape'), ['landscape', 'portrait'], true)
        ? $pdfOrientation
        : 'landscape';
@endphp
@page { margin: 0; }

body {
    margin: 0;
    font-family: DejaVu Sans, sans-serif;
    color: #111827;
    font-size: 10px;
}

.report-border-left,
.report-border-right {
    position: fixed;
    top: 0;
    bottom: 0;
    width: 46px;
    height: 100%;
    z-index: 0;
}

.report-border-left { left: 0; }
.report-border-right { right: 0; }

.report-canvas {
    position: relative;
    z-index: 1;
    @if($pdfOrientation === 'portrait')
    padding: 22px 58px 30px;
    @else
    padding: 24px 62px 34px;
    @endif
}

.letterhead-outer {
    width: 100%;
    margin: 0 0 14px;
    border: none;
}

.letterhead {
    border-collapse: collapse;
    margin: 0 auto;
}

.letterhead td {
    vertical-align: middle;
    padding: 0;
    border: none;
}

.letterhead-logos {
    text-align: center;
    white-space: nowrap;
    padding-right: 10px;
}

.letterhead-logo {
    height: auto;
    vertical-align: middle;
    border: none;
}

.letterhead-logo--bagong {
    width: @if($pdfOrientation === 'portrait') 48px @else 58px @endif;
    margin-right: 8px;
}

.letterhead-logo--seal {
    width: @if($pdfOrientation === 'portrait') 50px @else 60px @endif;
    margin-right: 8px;
}

.letterhead-logo--love {
    width: @if($pdfOrientation === 'portrait') 48px @else 58px @endif;
}

.letterhead-divider {
    width: 2px;
    padding: 0;
    background-color: #000000;
    font-size: 1px;
    line-height: 1px;
}

.letterhead-text {
    padding-left: 16px;
    text-align: center;
    text-transform: uppercase;
    color: #000000;
}

.letterhead-text p,
.letterhead-text h1 {
    margin: 0;
    padding: 0;
    line-height: 1.2;
    text-align: center;
}

.letterhead-text .country,
.letterhead-text h1 {
    font-family: "Times New Roman", DejaVu Serif, serif;
    font-weight: 700;
}

.letterhead-text .country {
    font-size: @if($pdfOrientation === 'portrait') 11px @else 13px @endif;
}

.letterhead-text h1 {
    font-size: @if($pdfOrientation === 'portrait') 14px @else 17px @endif;
    margin-top: 2px;
    margin-bottom: 2px;
}

.letterhead-text .province,
.letterhead-text .office,
.letterhead-text .section {
    font-family: Arial, DejaVu Sans, sans-serif;
    font-weight: 400;
    font-size: @if($pdfOrientation === 'portrait') 8.5px @else 9.5px @endif;
}

.report-heading {
    width: 100%;
    margin: 0 0 14px;
    border: none;
}

.report-heading td {
    text-align: center;
    padding: 0;
    border: none;
}

.report-title {
    margin: 0 0 6px;
    padding: 0;
    text-align: center;
    font-family: "Times New Roman", DejaVu Serif, serif;
    font-size: @if($pdfOrientation === 'portrait') 12px @else 14px @endif;
    font-weight: 700;
    text-transform: uppercase;
    color: #000000;
    text-decoration: underline;
}

.report-subtitle {
    margin: 0;
    padding: 0;
    text-align: center;
    font-size: 9px;
    color: #374151;
}

.summary,
.report-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 12px;
    table-layout: fixed;
}

.summary {
    margin-bottom: 12px;
    border: 1px solid #9ca3af;
}

.summary td,
.summary th {
    padding: 6px 8px;
    border: 1px solid #cbd5e1;
    font-size: 9.6px;
    vertical-align: top;
}

.report-table th,
.report-table td {
    border: 1px solid #d1d5db;
    padding: 4px 5px;
    font-size: 8.7px;
    line-height: 1.22;
    word-wrap: break-word;
    overflow-wrap: anywhere;
    vertical-align: top;
}

.report-table th {
    background: #e8f3ec;
    color: #10261d;
    font-size: 8.3px;
    text-transform: uppercase;
}

.report-kv th {
    width: 32%;
    background: #f3f4f6;
    font-weight: 600;
    text-align: left;
}

.text-right { text-align: right; }
.text-center { text-align: center; }

.report-footer {
    margin-top: 16px;
    border-top: 1px solid #d1d5db;
    padding-top: 8px;
    font-size: 9px;
    color: #111827;
}

.report-footer-row {
    width: 100%;
    border-collapse: collapse;
}

.report-footer-row td {
    border: none;
    padding: 2px 0;
    vertical-align: top;
}

.report-footer-left { text-align: left; width: 34%; }
.report-footer-center { text-align: center; width: 32%; }
.report-footer-right { text-align: right; width: 34%; }

.report-footer-label {
    font-weight: 700;
}

.report-footer-value {
    font-weight: 400;
}


.signature-image {
    width: 200px;
    height: 100px;
    object-fit: contain;
    display: block;
    margin: 0 auto 10px;
}

.signatory td {
    padding-top: 18px;
    vertical-align: top;
}

.signatory-wrap {
    text-align: center;
}

.signatory-name {
    display: block;
    font-weight: 700;
    margin-top: 4px;
}

.pdf-section-title {
    margin: 0 0 6px;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    color: #10261d;
    border-bottom: 1px solid #c8e6c9;
    padding-bottom: 4px;
}
