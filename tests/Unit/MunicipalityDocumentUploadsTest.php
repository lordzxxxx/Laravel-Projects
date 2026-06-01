<?php

use App\Support\MunicipalityDocumentUploads;

test('municipality document uploads parse php ini size strings', function () {
    expect(MunicipalityDocumentUploads::parseIniSize('8M'))->toBe(8 * 1024 * 1024);
    expect(MunicipalityDocumentUploads::parseIniSize('512K'))->toBe(512 * 1024);
    expect(MunicipalityDocumentUploads::parseIniSize('2G'))->toBe(2 * 1024 * 1024 * 1024);
});

test('municipality document uploads expose four required fields', function () {
    expect(MunicipalityDocumentUploads::FIELDS)->toBe([
        'business_permit',
        'mayors_permit',
        'barangay_clearance',
        'valid_id',
    ]);
});
