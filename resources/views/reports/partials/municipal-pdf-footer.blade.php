{{--
    Footer row for municipal PDFs (DomPDF).
    Standard: $pdfFooterDate, $pdfFooterTracking
    Custom columns: $pdfFooterLeftLabel + $pdfFooterLeftValue (and Center / Right)
    Legacy raw HTML: $pdfFooterLeft / $pdfFooterCenter / $pdfFooterRight ({!! !!})
--}}
<div class="report-footer">
    <table class="report-footer-row" width="100%">
        <tr>
            <td class="report-footer-left">
                @if(isset($pdfFooterLeft) && $pdfFooterLeft !== '')
                    {!! $pdfFooterLeft !!}
                @elseif(isset($pdfFooterLeftLabel))
                    <span class="report-footer-label">{{ $pdfFooterLeftLabel }}</span>
                    <span class="report-footer-value">{{ $pdfFooterLeftValue ?? '' }}</span>
                @else
                    <span class="report-footer-label">Date:</span>
                    <span class="report-footer-value">{{ $pdfFooterDate ?? '' }}</span>
                @endif
            </td>
            <td class="report-footer-center">
                @if(isset($pdfFooterCenter) && $pdfFooterCenter !== '')
                    {!! $pdfFooterCenter !!}
                @elseif(isset($pdfFooterCenterLabel))
                    <span class="report-footer-label">{{ $pdfFooterCenterLabel }}</span>
                    <span class="report-footer-value">{{ $pdfFooterCenterValue ?? '' }}</span>
                @else
                    <span class="report-footer-label">Doc Tracking:</span>
                    <span class="report-footer-value">{{ $pdfFooterTracking ?? '' }}</span>
                @endif
            </td>
            <td class="report-footer-right">
                @if(isset($pdfFooterRight) && $pdfFooterRight !== '')
                    {!! $pdfFooterRight !!}
                @elseif(isset($pdfFooterRightLabel))
                    <span class="report-footer-label">{{ $pdfFooterRightLabel }}</span>
                    <span class="report-footer-value">{{ $pdfFooterRightValue ?? '' }}</span>
                @else
                    <script type="text/php">
                        if (isset($pdf)) {
                            $font = $fontMetrics->get_font('DejaVu Sans', 'normal');
                            $size = 9;
                            $color = [0.07, 0.09, 0.15];
                            $text = 'Page: {PAGE_NUM} / {PAGE_COUNT}';
                            $textWidth = $fontMetrics->get_text_width($text, $font, $size);
                            $x = $pdf->get_width() - 62 - $textWidth;
                            $y = $pdf->get_height() - 38;
                            $pdf->page_text($x, $y, $text, $font, $size, $color);
                        }
                    </script>
                @endif
            </td>
        </tr>
    </table>
</div>
