{{-- Optional footer: $pdfFooterLeft, $pdfFooterCenter, $pdfFooterRight --}}
<div class="report-footer">
    <table class="report-footer-row">
        <tr>
            <td class="report-footer-left">{{ $pdfFooterLeft ?? '' }}</td>
            <td class="report-footer-center">{{ $pdfFooterCenter ?? '' }}</td>
            <td class="report-footer-right">{{ $pdfFooterRight ?? '' }}</td>
        </tr>
    </table>
</div>
