<?php

namespace App\Support;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

final class AccommodationAvailability
{
    /**
     * Build booking-occupied date ranges per accommodation for calendar UIs.
     * Pending / confirmed / paid stays block availability display.
     *
     * @param  array<int|string>  $accommodationIds
     * @return array<string|int, list<array{start: string, end: string, status: string}>>
     */
    public static function eventsForAccommodationIds(
        array $accommodationIds,
        ?int $tenantId = null,
        ?int $ownerUserId = null,
    ): array {
        $accommodationIds = array_values(array_filter(array_map(
            static fn ($id): int => (int) $id,
            $accommodationIds
        ), static fn (int $id): bool => $id > 0));

        if ($accommodationIds === []) {
            return [];
        }

        $query = Booking::query()
            ->whereIn('accommodation_id', $accommodationIds)
            ->whereIn('status', [
                Booking::STATUS_PENDING,
                Booking::STATUS_CONFIRMED,
                Booking::STATUS_PAID,
            ])
            ->whereDate('check_out_date', '>=', Carbon::today()->subMonths(1)->toDateString());

        if ($tenantId !== null) {
            $query->forTenant($tenantId);
        } elseif ($ownerUserId !== null) {
            $query->forOwner($ownerUserId);
        }

        /** @var EloquentCollection<int, Booking> $rows */
        $rows = $query->get(['accommodation_id', 'check_in_date', 'check_out_date', 'status']);

        return self::groupRows($rows);
    }

    /**
     * @param  Collection<int, Booking>|EloquentCollection<int, Booking>  $rows
     * @return array<string|int, list<array{start: string, end: string, status: string}>>
     */
    public static function groupRows(Collection|EloquentCollection $rows): array
    {
        return $rows
            ->groupBy('accommodation_id')
            ->map(function ($group) {
                return $group->map(function ($row) {
                    return [
                        'start' => Carbon::parse($row->check_in_date)->toDateString(),
                        'end' => Carbon::parse($row->check_out_date)->toDateString(),
                        'status' => (string) $row->status,
                    ];
                })->values()->all();
            })->all();
    }
}
