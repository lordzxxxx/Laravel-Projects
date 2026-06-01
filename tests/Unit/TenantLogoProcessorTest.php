<?php

use App\Support\TenantLogoProcessor;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(Tests\TestCase::class);

test('tenant logo processor stores png with transparent corners for white jpeg', function () {
    if (! extension_loaded('gd')) {
        test()->markTestSkipped('GD extension is not available.');
    }

    $canvas = imagecreatetruecolor(40, 40);
    $white = imagecolorallocate($canvas, 255, 255, 255);
    $red = imagecolorallocate($canvas, 200, 20, 20);
    imagefill($canvas, 0, 0, $white);
    imagefilledellipse($canvas, 20, 20, 24, 24, $red);

    $temp = tempnam(sys_get_temp_dir(), 'logo-jpeg-');
    imagejpeg($canvas, $temp, 90);
    imagedestroy($canvas);

    $file = new UploadedFile($temp, 'logo.jpg', 'image/jpeg', null, true);
    $pngBytes = TenantLogoProcessor::toTransparentPngBytes($file);

    expect($pngBytes)->not->toBeEmpty();

    $decoded = imagecreatefromstring($pngBytes);
    expect($decoded)->not->toBeFalse();

    $cornerAlpha = (imagecolorat($decoded, 0, 0) >> 24) & 0x7F;
    $centerAlpha = (imagecolorat($decoded, 20, 20) >> 24) & 0x7F;

    imagedestroy($decoded);

    expect($cornerAlpha)->toBeGreaterThanOrEqual(120);
    expect($centerAlpha)->toBeLessThan(120);
});

test('tenant logo processor store writes png under tenant-logos', function () {
    if (! extension_loaded('gd')) {
        test()->markTestSkipped('GD extension is not available.');
    }

    Storage::fake('public');

    $file = UploadedFile::fake()->image('brand.png', 80, 80);
    $path = TenantLogoProcessor::store($file);

    expect($path)->toStartWith('tenant-logos/');
    expect($path)->toEndWith('.png');
    Storage::disk('public')->assertExists($path);
});
