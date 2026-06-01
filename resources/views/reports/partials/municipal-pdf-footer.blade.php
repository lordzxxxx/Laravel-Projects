{{--
    Fixed bottom footer for municipal PDFs (admin + owner).
    Expects: $pdfFooterTracking (string). Optional: $pdfFooterDate (string).
    Requires DomPDF enable_php for page numbers (set on PDF::loadView in controller).
--}}
@php
    $pdfFooterDate = $pdfFooterDate ?? now('Asia/Manila')->format('M d, Y h:i A');
    $pdfFooterTracking = $pdfFooterTracking ?? '';
@endphp
<div class="pdf-page-footer">
    <table class="pdf-page-footer__row">
        <tr>
            <td class="pdf-page-footer__left"><strong>Date:</strong> {{ $pdfFooterDate }}</td>
            <td class="pdf-page-footer__center"><strong>Doc Tracking:</strong> {{ $pdfFooterTracking }}</td>
            <td class="pdf-page-footer__right">&nbsp;</td>
        </tr>
    </table>
</div>
<script type="text/php">
    if (isset($pdf)) {
        $font = $fontMetrics->get_font('dejavuserif', 'normal');
        $size = 10;
        $color = [0, 0, 0];
        $pageWidth = $pdf->get_width();
        $pageHeight = $pdf->get_height();
        $y = $pageHeight - 28;
        $pdf->page_text($pageWidth - 88, $y, 'Page {PAGE_NUM} / {PAGE_COUNT}', $font, $size, $color);
    }
</script>
