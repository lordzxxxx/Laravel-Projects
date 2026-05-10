<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Support\PortalDetector;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicLandingController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless(PortalDetector::isPublicPortal($request), 404);

        $municipalityName = (string) config('portals.municipality_name', 'Impasug-ong');

        $carouselQuery = Accommodation::query()
            ->with(['owner', 'tenant'])
            ->available()
            ->forCentralMunicipalityDirectory()
            ->orderByDesc('is_featured')
            ->orderByDesc('updated_at');

        $carouselAccommodations = (clone $carouselQuery)->take(12)->get();

        return view('portal.landing', [
            'carouselAccommodations' => $carouselAccommodations,
            'municipalityName' => $municipalityName,
        ]);
    }
}
