<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Support\MunicipalityDocumentUploads;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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

        MunicipalityDocumentUploads::validate($request);

        foreach (Tenant::MUNICIPALITY_DOCUMENTS as $meta) {
            $previousPath = (string) ($tenant->{$meta['column']} ?? '');
            if ($previousPath !== '') {
                Storage::disk(MunicipalityDocumentUploads::DISK)->delete($previousPath);
            }
        }

        $documentPaths = MunicipalityDocumentUploads::storeAll($request);

        $tenant->forceFill([
            ...$documentPaths,
            'municipality_requirements_submitted_at' => now(),
            'onboarding_status' => Tenant::ONBOARDING_PENDING_APPROVAL,
        ]);

        $tenant->save();

        return redirect()->route('owner.onboarding.status')
            ->with('success', 'Your documents were uploaded and sent for municipality review.');
    }
}
