<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Models\AccommodationFavorite;
use App\Models\User;
use App\Support\PortalDetector;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FavoriteAccommodationController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless(PortalDetector::isPublicPortal($request), 404);

        /** @var User $user */
        $user = $request->user();
        abort_unless($user->isClient(), 403);

        $favorites = AccommodationFavorite::query()
            ->where('user_id', $user->id)
            ->with(['accommodation.owner'])
            ->latest('id')
            ->paginate(12);

        return view('portal.wishlist', compact('favorites'));
    }

    public function toggle(Request $request, Accommodation $accommodation): RedirectResponse
    {
        abort_unless(PortalDetector::isPublicPortal($request), 404);

        /** @var User $user */
        $user = $request->user();
        abort_unless($user->isClient(), 403);

        $existing = AccommodationFavorite::query()
            ->where('user_id', $user->id)
            ->where('accommodation_id', $accommodation->id)
            ->first();

        if ($existing) {
            $existing->delete();

            return back()->with('success', 'Removed from favorites.');
        }

        AccommodationFavorite::create([
            'user_id' => $user->id,
            'accommodation_id' => $accommodation->id,
        ]);

        return back()->with('success', 'Saved to favorites.');
    }
}
