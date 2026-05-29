<?php

namespace App\Http\Controllers;

use App\Models\Accommodation;
use App\Models\Tenant;
use App\Support\AppearancePreferences;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TenantLandingController extends Controller
{
    /**
     * Show the public landing page for the current tenant subdomain.
     */
    public function showPublic(Request $request): View
    {
        $tenant = Tenant::current();

        abort_unless($tenant, 404);

        $settings = $tenant->landingSettings();

        $featuredAccommodations = Accommodation::query()
            ->featured()
            ->latest('id')
            ->take(8)
            ->get();

        if ($featuredAccommodations->isEmpty()) {
            $featuredAccommodations = Accommodation::query()
                ->available()
                ->latest('id')
                ->take(8)
                ->get();
        }

        return view('tenant.landing', compact('tenant', 'settings', 'featuredAccommodations'));
    }

    /**
     * Show owner settings for tenant landing customization.
     */
    public function edit(Request $request): View
    {
        $tenant = Tenant::current();

        if (! $tenant) {
            $tenant = $request->user()->ensureTenant();
        }

        abort_unless($tenant, 404);

        $settings = $tenant->landingSettings();
        $appearance = $request->user()->normalizedAppearancePreferences();

        return view('owner.landing-settings', compact('tenant', 'settings', 'appearance'));
    }

    /**
     * Persist owner landing page customization.
     */
    public function update(Request $request): RedirectResponse
    {
        $tenant = Tenant::current();

        if (! $tenant) {
            $tenant = $request->user()->ensureTenant();
        }

        abort_unless($tenant, 404);

        $validated = $request->validate([
            'primary_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'accent_color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'appearance_theme' => ['required', 'string', 'in:impasugong,green'],
            'appearance_mode' => ['required', 'string', 'in:light,dark,system'],
            'gcash_qr' => ['nullable', 'image', 'max:5120', 'mimes:jpeg,jpg,png,webp'],
            'remove_gcash_qr' => ['nullable', 'boolean'],
            'logo' => ['nullable', 'image', 'max:5120', 'mimes:jpeg,jpg,png,webp'],
            'remove_logo' => ['nullable', 'boolean'],
        ]);

        $appearanceTheme = $validated['appearance_theme'];
        $appearanceMode = $validated['appearance_mode'];
        unset($validated['appearance_theme'], $validated['appearance_mode']);

        $landingPayload = array_merge($tenant->landingSettings(), $validated);

        $removeLogo = (bool) ($validated['remove_logo'] ?? false);
        unset($landingPayload['logo'], $landingPayload['remove_logo'], $landingPayload['gcash_qr'], $landingPayload['remove_gcash_qr']);

        if ($removeLogo && $tenant->logo_path) {
            Storage::disk('public')->delete($tenant->logo_path);
            $tenant->logo_path = null;
        }

        if ($request->hasFile('logo')) {
            if ($tenant->logo_path) {
                Storage::disk('public')->delete($tenant->logo_path);
            }

            $tenant->logo_path = $request->file('logo')->store('tenant-logos', 'public');
        }

        $removeGcashQr = (bool) ($validated['remove_gcash_qr'] ?? false);

        if ($removeGcashQr && $tenant->gcash_qr_path) {
            Storage::disk('public')->delete($tenant->gcash_qr_path);
            $tenant->gcash_qr_path = null;
        }

        if ($request->hasFile('gcash_qr')) {
            if ($tenant->gcash_qr_path) {
                Storage::disk('public')->delete($tenant->gcash_qr_path);
            }

            $tenant->gcash_qr_path = $request->file('gcash_qr')->store('tenant-gcash-qr', 'public');
        }

        $tenant->updateLandingSettings($landingPayload);
        $tenant->save();

        $user = $request->user();
        $user->appearance_preferences = AppearancePreferences::normalize([
            'theme' => $appearanceTheme,
            'mode' => $appearanceMode,
        ]);
        $user->save();

        return back()->with('success', 'Landing page settings updated successfully.');
    }
}
