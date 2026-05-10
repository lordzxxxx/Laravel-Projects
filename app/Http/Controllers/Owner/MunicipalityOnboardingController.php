<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MunicipalityOnboardingController extends Controller
{
    /**
     * Show registration / requirements status before admin approval.
     */
    public function status(Request $request): View|\Illuminate\Http\RedirectResponse
    {
        $user = Auth::user();
        abort_unless($user && $user->isOwner(), Response::HTTP_NOT_FOUND);

        $tenant = $user->relationLoaded('ownedTenant') ? $user->ownedTenant : $user->ownedTenant()->first();

        abort_unless($tenant instanceof Tenant, Response::HTTP_NOT_FOUND);

        if ((string) $tenant->onboarding_status === Tenant::ONBOARDING_APPROVED) {
            return redirect()->route('owner.dashboard');
        }

        return view('owner.onboarding.municipality-status', compact('tenant'));
    }

    /**
     * Re-submit Municipality requirements after rejection.
     */
    public function requirementsForm(Request $request): View|\Illuminate\Http\RedirectResponse
    {
        $user = Auth::user();
        abort_unless($user && $user->isOwner(), Response::HTTP_NOT_FOUND);

        $tenant = $user->ownedTenant;

        abort_unless($tenant instanceof Tenant, Response::HTTP_NOT_FOUND);

        if ($tenant->onboarding_status !== Tenant::ONBOARDING_REJECTED) {
            return redirect()->route('owner.onboarding.status');
        }

        return view('owner.onboarding.municipality-requirements', compact('tenant'));
    }

    public function updateRequirements(Request $request): RedirectResponse
    {
        $user = Auth::user();
        abort_unless($user && $user->isOwner(), Response::HTTP_NOT_FOUND);

        $tenant = $user->ownedTenant;

        abort_unless($tenant instanceof Tenant, Response::HTTP_NOT_FOUND);

        if ($tenant->onboarding_status !== Tenant::ONBOARDING_REJECTED) {
            return redirect()->route('owner.onboarding.status');
        }

        $validated = $request->validate([
            'business_permit' => ['required', 'file', 'max:10240', 'mimes:pdf,jpg,jpeg,png'],
            'mayors_permit' => ['required', 'file', 'max:10240', 'mimes:pdf,jpg,jpeg,png'],
            'barangay_clearance' => ['required', 'file', 'max:10240', 'mimes:pdf,jpg,jpeg,png'],
            'valid_id' => ['required', 'file', 'max:10240', 'mimes:pdf,jpg,jpeg,png'],
        ]);

        $tenant->forceFill([
            'municipality_business_permit_path' => $request->file('business_permit')->store('owner-municipality-docs', 'public'),
            'municipality_mayors_permit_path' => $request->file('mayors_permit')->store('owner-municipality-docs', 'public'),
            'municipality_barangay_clearance_path' => $request->file('barangay_clearance')->store('owner-municipality-docs', 'public'),
            'municipality_valid_id_path' => $request->file('valid_id')->store('owner-municipality-docs', 'public'),
            'municipality_requirements_submitted_at' => now(),
            'onboarding_status' => Tenant::ONBOARDING_PENDING_APPROVAL,
        ]);

        $tenant->save();

        return redirect()->route('owner.onboarding.status')
            ->with('success', 'Your documents were uploaded and sent for municipality review.');
    }
}
