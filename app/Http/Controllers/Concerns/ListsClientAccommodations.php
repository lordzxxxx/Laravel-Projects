<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Accommodation;
use App\Models\Tenant;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

trait ListsClientAccommodations
{
    protected function paginatedClientAccommodations(Request $request): LengthAwarePaginator
    {
        $currentTenant = Tenant::current();

        $query = Accommodation::available()->with('owner');

        if ($currentTenant) {
            $query->forTenant($currentTenant->id);
        } else {
            $query->forCentralMunicipalityDirectory();
        }

        if ($request->filled('type')) {
            $query->ofType($request->type);
        }

        if ($request->filled('min_price')) {
            $query->where('price_per_night', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price_per_night', '<=', $request->max_price);
        }

        if ($request->filled('guests')) {
            $query->where('max_guests', '>=', $request->guests);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $perPage = $this->clientAccommodationsPerPage($request);

        return (clone $query)->latest()->paginate($perPage)->withQueryString();
    }

    /**
     * Explore / guest listing page size: 5 on small screens (via ?per_page=5), 12 otherwise.
     */
    protected function clientAccommodationsPerPage(Request $request): int
    {
        $perPage = (int) $request->input('per_page', 12);

        return in_array($perPage, [5, 12], true) ? $perPage : 12;
    }
}
