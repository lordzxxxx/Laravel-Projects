<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Support\PortalDetector;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicLandingController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        abort_unless(PortalDetector::isKnownCentralPortal($request), 404);

        $user = $request->user();
        if ($user && $user->isAdmin() && $user->tenant_id === null) {
            return redirect()->route('admin.dashboard');
        }

        $municipalityName = (string) config('portals.municipality_name', 'Impasug-ong');

        return view('portal.landing', [
            'municipalityName' => $municipalityName,
        ]);
    }
}
