<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Support\PortalDetector;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Staff-only entry for the central admin port (municipality operations & Tulogans).
 */
class AdminLandingController extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        abort_unless(PortalDetector::isAdminPortal($request), 404);

        $user = $request->user();

        if ($user && $user->isAdmin() && $user->tenant_id === null) {
            return redirect()->route('admin.dashboard');
        }

        return view('portal.admin-landing', [
            'municipalityName' => (string) config('portals.municipality_name', 'Impasug-ong'),
            'pendingReviews' => Tenant::query()
                ->whereIn('onboarding_status', [Tenant::ONBOARDING_PENDING_APPROVAL, Tenant::ONBOARDING_AWAITING_PAYMENT])
                ->count(),
        ]);
    }
}
