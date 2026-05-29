<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Concerns\ListsClientAccommodations;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Tenant;
use App\Support\PortalDetector;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    use ListsClientAccommodations;

    public function index(Request $request): View|\Illuminate\Http\RedirectResponse
    {
        $user = $request->user();
        $currentTenant = Tenant::current();
        $onTenantHost = Tenant::checkCurrent();

        if ($user) {
            if ($user->isOwner() || ($user->isAdmin() && $currentTenant && (int) $user->tenant_id === (int) $currentTenant->id)) {
                return redirect()->route('owner.dashboard');
            }

            if (! $user->isClient() && $onTenantHost) {
                return redirect()->route('owner.dashboard');
            }
        }

        $portalDirectory = PortalDetector::isPublicPortal($request) || ! $onTenantHost;

        $accommodations = $this->paginatedClientAccommodations($request);

        $unreadMessagesCount = 0;
        if ($user && $user->isClient()) {
            $unreadMessagesCount = Message::query()
                ->forUser($user->id)
                ->when($currentTenant, fn ($query) => $query->forTenant($currentTenant->id))
                ->unread()
                ->count();
        }

        return view('client.dashboard', compact(
            'accommodations',
            'unreadMessagesCount',
            'portalDirectory'
        ));
    }
}
