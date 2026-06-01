<?php

namespace App\Support;

/**
 * Resolve municipal PDF images (letterhead logos, side borders) for DomPDF.
 */
class MunicipalPdfAssets
{
    /**
     * @return array{left_border: ?string, right_border: ?string}
     */
    public static function borders(string $orientation = 'landscape'): array
    {
        $suffix = $orientation === 'portrait' ? '-portrait' : '';
        $leftCandidates = [
            public_path('assets/report-border-left'.$suffix.'.png'),
            public_path('assets/report-border-left.png'),
        ];
        $rightCandidates = [
            public_path('assets/report-border-right'.$suffix.'.png'),
            public_path('assets/report-border-right.png'),
        ];

        return [
            'left_border' => self::dataUriFromCandidates($leftCandidates),
            'right_border' => self::dataUriFromCandidates($rightCandidates),
        ];
    }

    /**
     * @return array{bagong: ?string, seal: ?string, love: ?string}
     */
    public static function letterheadLogos(): array
    {
        return [
            'bagong' => self::dataUriFromCandidates([
                public_path('assets/Bagong_Pilipinas_logo.png'),
                public_path('assets/bagong-pilipinas-logo.png'),
                public_path('LBP.png'),
            ]),
            'seal' => self::dataUriFromCandidates([
                public_path('assets/Lgu Socmed Template-02.png'),
                public_path('Lgu Socmed Template-02.png'),
                public_path('SYSTEMLOGO.png'),
                public_path('report-headers/ca-left-logo.png'),
            ]),
            'love' => self::dataUriFromCandidates([
                public_path('assets/Love Impasugong-04.png'),
                public_path('Love Impasugong.png'),
                public_path('Love Impasugong 2.png'),
                public_path('report-headers/ca-right-logo.png'),
            ]),
        ];
    }

    /**
     * @param  array<int, string>  $paths
     */
    public static function dataUriFromCandidates(array $paths): ?string
    {
        foreach ($paths as $path) {
            if (! is_string($path) || $path === '' || ! is_file($path)) {
                continue;
            }

            $mime = function_exists('mime_content_type')
                ? (mime_content_type($path) ?: 'image/png')
                : 'image/png';

            return 'data:'.$mime.';base64,'.base64_encode((string) file_get_contents($path));
        }

        return null;
    }
}
