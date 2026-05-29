<?php

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

function skipIfLandlordUnavailableForLogoTests(): void
{
    try {
        Tenant::query()->count();
    } catch (\Throwable) {
        test()->markTestSkipped('Landlord test database is not available.');
    }
}

function logoTestTenantUrl(Tenant $tenant, string $path): string
{
    $port = (int) env('TENANT_PORT', env('CENTRAL_PORT', 8000));

    return "http://{$tenant->domain}:{$port}{$path}";
}

function resolveLogoTestTenantWithOwner(): array
{
    $tenant = Tenant::query()
        ->whereNotNull('domain')
        ->where('domain', '!=', '')
        ->where('onboarding_status', Tenant::ONBOARDING_APPROVED)
        ->first();

    if (! $tenant) {
        test()->markTestSkipped('No approved tenant with domain in test database.');
    }

    $owner = User::query()
        ->where('tenant_id', $tenant->id)
        ->where('role', User::ROLE_OWNER)
        ->first();

    if (! $owner) {
        $owner = User::factory()->create([
            'role' => User::ROLE_OWNER,
            'tenant_id' => $tenant->id,
        ]);
        $tenant->update(['owner_user_id' => $owner->id]);
    }

    return ['tenant' => $tenant->fresh(), 'owner' => $owner->fresh()];
}

function landingSettingsPayload(Tenant $tenant, ?User $owner = null): array
{
    $settings = $tenant->landingSettings();
    $appearance = ($owner ?? auth()->user())?->normalizedAppearancePreferences() ?? [
        'theme' => 'impasugong',
        'mode' => 'light',
    ];

    return [
        'primary_color' => $settings['primary_color'],
        'accent_color' => $settings['accent_color'],
        'appearance_theme' => $appearance['theme'],
        'appearance_mode' => $appearance['mode'],
    ];
}

test('tenant brandLogoUrl returns love impasugong default when logo_path is empty', function () {
    skipIfLandlordUnavailableForLogoTests();

    $tenant = new Tenant(['logo_path' => null]);

    expect($tenant->getLogoUrl())->toBeNull();
    expect($tenant->brandLogoUrl())->toBe(Tenant::defaultBrandLogoUrl());
    expect(Tenant::defaultBrandLogoUrl())->toContain('love-impasugong-watermark');
});

test('tenant landing shows love impasugong logo when no custom logo uploaded', function () {
    skipIfLandlordUnavailableForLogoTests();

    ['tenant' => $tenant] = resolveLogoTestTenantWithOwner();

    $originalLogo = $tenant->logo_path;
    $tenant->update(['logo_path' => null]);

    try {
        $response = $this->get(logoTestTenantUrl($tenant, '/'));

        $response->assertOk();
        $response->assertSee('love-impasugong-watermark', false);
    } finally {
        $tenant->update(['logo_path' => $originalLogo]);
    }
});

test('tenant owner can upload optional business logo on landing settings', function () {
    skipIfLandlordUnavailableForLogoTests();

    Storage::fake('public');

    ['tenant' => $tenant, 'owner' => $owner] = resolveLogoTestTenantWithOwner();

    $originalLogo = $tenant->logo_path;
    $tenant->update(['logo_path' => null]);

    try {
        $response = $this->actingAs($owner)
            ->put(logoTestTenantUrl($tenant, '/owner/landing-page'), array_merge(
                landingSettingsPayload($tenant, $owner),
                ['logo' => UploadedFile::fake()->image('brand.png', 120, 120)]
            ));

        $response->assertRedirect();

        $tenant->refresh();
        expect($tenant->logo_path)->not->toBeNull();
        Storage::disk('public')->assertExists($tenant->logo_path);
    } finally {
        if ($tenant->fresh()->logo_path && $tenant->fresh()->logo_path !== $originalLogo) {
            Storage::disk('public')->delete($tenant->fresh()->logo_path);
        }
        $tenant->update(['logo_path' => $originalLogo]);
    }
});

test('tenant owner can remove uploaded logo and revert to default', function () {
    skipIfLandlordUnavailableForLogoTests();

    Storage::fake('public');

    ['tenant' => $tenant, 'owner' => $owner] = resolveLogoTestTenantWithOwner();

    $originalLogo = $tenant->logo_path;
    $path = UploadedFile::fake()->image('brand.png')->store('tenant-logos', 'public');
    $tenant->update(['logo_path' => $path]);

    try {
        $response = $this->actingAs($owner)
            ->put(logoTestTenantUrl($tenant, '/owner/landing-page'), array_merge(
                landingSettingsPayload($tenant, $owner),
                ['remove_logo' => '1']
            ));

        $response->assertRedirect();

        $tenant->refresh();
        expect($tenant->logo_path)->toBeNull();
        Storage::disk('public')->assertMissing($path);
    } finally {
        if ($originalLogo && Storage::disk('public')->exists($originalLogo)) {
            $tenant->update(['logo_path' => $originalLogo]);
        } else {
            $tenant->update(['logo_path' => $originalLogo]);
        }
    }
});

test('tenant owner saves appearance theme and mode on landing settings', function () {
    skipIfLandlordUnavailableForLogoTests();

    ['tenant' => $tenant, 'owner' => $owner] = resolveLogoTestTenantWithOwner();

    $response = $this->actingAs($owner)
        ->put(logoTestTenantUrl($tenant, '/owner/landing-page'), array_merge(
            landingSettingsPayload($tenant, $owner),
            [
                'appearance_theme' => 'green',
                'appearance_mode' => 'dark',
            ]
        ));

    $response->assertRedirect();

    $owner->refresh();

    expect($owner->appearanceTheme())->toBe('green');
    expect($owner->appearanceMode())->toBe('dark');
});
