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

        return (clone $query)->latest()->paginate(12);
    }
}
