<?php

namespace App\Support;

use App\Models\Accommodation;
use App\Models\Booking;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Admin dashboard/report metrics should reflect real unit-owner listings only
 * (not Demo / Report Sample rows created by visualization seeders).
 */
class AdminOwnerUnitScope
{
    /** @var list<string> */
    public const DEMO_MARKERS = [
        '[admin-dashboard-demo]',
        '[admin-report-sample]',
    ];

    /** @var list<string> */
    private const SYNTHETIC_NAME_PATTERNS = [
        'Demo %',
        'Report Sample%',
    ];

    public static function maxUnits(): int
    {
        return max(1, min(50, (int) env('ADMIN_OWNER_MAX_UNITS', 12)));
    }

    public static function targetGuestTotal(): int
    {
        return max(1, min(500, (int) env('ADMIN_OWNER_TOTAL_GUESTS', 20)));
    }

    public static function resolveOwnerTenantId(): ?int
    {
        $forced = (int) env('ADMIN_OWNER_TENANT_ID', 0);
        if ($forced > 0) {
            return $forced;
        }

        $best = Tenant::query()
            ->whereNotNull('owner_user_id')
            ->orderBy('id')
            ->get()
            ->sortByDesc(fn (Tenant $tenant) => self::ownerUploadedAccommodations((int) $tenant->id)->count())
            ->first();

        return $best !== null ? (int) $best->id : null;
    }

    /**
     * @param  Builder<Accommodation>  $query
     */
    public static function applyToAccommodations(Builder $query, ?int $tenantId = null): Builder
    {
        $query->whereNotNull('tenant_id')
            ->whereHas('tenant', function (Builder $tenantQuery) {
                $tenantQuery->whereNotNull('owner_user_id');
            });

        foreach (self::SYNTHETIC_NAME_PATTERNS as $pattern) {
            $query->where('name', 'not like', $pattern);
        }

        if ($tenantId !== null) {
            $query->where('tenant_id', $tenantId);
        }

        return $query;
    }

    /**
     * @return Builder<Accommodation>
     */
    public static function ownerUploadedAccommodations(?int $tenantId = null): Builder
    {
        $query = Accommodation::query();
        self::applyToAccommodations($query, $tenantId);

        $scopedTenantId = $tenantId ?? self::resolveOwnerTenantId();
        if ($scopedTenantId !== null && $tenantId === null) {
            $query->where('tenant_id', $scopedTenantId);
        }

        return $query->orderBy('id')->limit(self::maxUnits());
    }

    /**
     * @return Collection<int, Accommodation>
     */
    public static function ownerUploadedUnits(?int $tenantId = null): Collection
    {
        return self::ownerUploadedAccommodations($tenantId)->get();
    }

    /**
     * @return list<int>
     */
    public static function ownerUploadedAccommodationIds(?int $tenantId = null): array
    {
        return self::ownerUploadedAccommodations($tenantId)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    /**
     * @param  Builder<Booking>  $query
     */
    public static function applyToBookings(Builder $query, ?int $tenantId = null): Builder
    {
        $ids = self::ownerUploadedAccommodationIds($tenantId);

        if ($ids === []) {
            return $query->whereRaw('0 = 1');
        }

        return $query->whereIn('accommodation_id', $ids);
    }

    /**
     * Split a guest total across N bookings (each at least 1 guest).
     *
     * @return list<int>
     */
    public static function partitionGuests(int $totalGuests, int $bookingCount): array
    {
        $bookingCount = max(1, $bookingCount);
        $totalGuests = max($bookingCount, $totalGuests);

        $base = intdiv($totalGuests, $bookingCount);
        $remainder = $totalGuests % $bookingCount;
        $parts = [];

        for ($i = 0; $i < $bookingCount; $i++) {
            $parts[] = $base + ($i < $remainder ? 1 : 0);
        }

        return $parts;
    }

    public static function pruneSyntheticListings(): int
    {
        $deleted = 0;

        $candidates = Accommodation::query()
            ->where(function (Builder $query) {
                foreach (self::SYNTHETIC_NAME_PATTERNS as $pattern) {
                    $query->orWhere('name', 'like', $pattern);
                }
            })
            ->get();

        foreach ($candidates as $accommodation) {
            $hasNonDemoBooking = Booking::query()
                ->where('accommodation_id', $accommodation->id)
                ->where(function (Builder $query) {
                    $query->whereNull('special_requests')
                        ->orWhereNotIn('special_requests', self::DEMO_MARKERS);
                })
                ->exists();

            if ($hasNonDemoBooking) {
                continue;
            }

            Booking::query()
                ->where('accommodation_id', $accommodation->id)
                ->delete();

            $accommodation->delete();
            $deleted++;
        }

        return $deleted;
    }
}
