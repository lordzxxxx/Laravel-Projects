<?php

namespace Database\Seeders\Concerns;

use App\Models\Accommodation;
use App\Models\Booking;
use App\Models\Tenant;
use App\Models\User;
use App\Support\AdminOwnerUnitScope;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

trait SeedsAdminDemoFromOwnerUnits
{
    /**
     * @return Collection<int, Accommodation>
     */
    protected function resolveOwnerUnitsForAdminDemo(): Collection
    {
        $units = AdminOwnerUnitScope::ownerUploadedUnits();

        if ($units->isEmpty()) {
            $this->command?->error('No owner-uploaded units found. Upload listings on the owner portal first (tenant with verified accommodations).');

            return $units;
        }

        $tenantId = (int) $units->first()->tenant_id;
        $tenant = Tenant::query()->find($tenantId);

        $this->command?->line(sprintf(
            'Using %d owner-uploaded unit(s) from tenant #%d (%s).',
            $units->count(),
            $tenantId,
            $tenant?->name ?? 'unknown'
        ));

        return $units;
    }

    protected function purgeAdminDemoBookings(string $marker): void
    {
        Booking::query()->where('special_requests', $marker)->delete();
    }

    protected function pruneSyntheticAdminListings(): void
    {
        $removed = AdminOwnerUnitScope::pruneSyntheticListings();
        if ($removed > 0) {
            $this->command?->line("Removed {$removed} synthetic demo/report sample listing(s).");
        }
    }

    /**
     * @return Collection<int, User>
     */
    protected function ensureDemoClientsForTenant(int $tenantId): Collection
    {
        $existing = User::query()
            ->where('role', User::ROLE_CLIENT)
            ->where(function ($q) use ($tenantId) {
                $q->whereNull('tenant_id')->orWhere('tenant_id', $tenantId);
            })
            ->orderBy('id')
            ->limit(8)
            ->get();

        while ($existing->count() < 5) {
            $n = $existing->count() + 1;
            $user = User::create([
                'name' => 'Admin Demo Guest '.$tenantId.'-'.$n,
                'email' => 'admin.demo.'.$tenantId.'.'.$n.'.'.uniqid('', true).'@example.test',
                'password' => Hash::make('password'),
                'role' => User::ROLE_CLIENT,
                'tenant_id' => $tenantId,
                'is_active' => true,
            ]);
            if (method_exists($user, 'syncRbacFromLegacyRole')) {
                $user->syncRbacFromLegacyRole();
            }
            $existing->push($user);
        }

        return $existing;
    }

    /**
     * @param  list<string>  $statuses
     * @return array{guest_gender: string, guest_age: ?int, guest_is_local: ?bool, guest_local_place: ?string, guest_country: ?string}
     */
    protected function adminDemoDemographicsProfile(int $mix): array
    {
        $genders = ['male', 'female', 'unspecified'];
        $guestGender = $genders[$mix % 3];
        $ageRepresentatives = [16, 22, 31, 42, 55, 68, null];
        $guestAge = $ageRepresentatives[intdiv($mix, 5) % 7];

        $locals = ['Impasugong', 'Malaybalay', 'Bukidnon', 'Cagayan de Oro', 'Manolo Fortich'];
        $countries = ['United States', 'Japan', 'South Korea', 'Australia', 'Canada', 'Singapore', 'Germany'];

        $locMode = intdiv($mix, 3) % 3;

        if ($locMode === 0) {
            return [
                'guest_gender' => $guestGender,
                'guest_age' => $guestAge,
                'guest_is_local' => true,
                'guest_local_place' => $locals[$mix % count($locals)],
                'guest_country' => null,
            ];
        }

        if ($locMode === 1) {
            return [
                'guest_gender' => $guestGender,
                'guest_age' => $guestAge,
                'guest_is_local' => false,
                'guest_local_place' => null,
                'guest_country' => $countries[$mix % count($countries)],
            ];
        }

        return [
            'guest_gender' => $guestGender,
            'guest_age' => $guestAge,
            'guest_is_local' => null,
            'guest_local_place' => null,
            'guest_country' => null,
        ];
    }

    /**
     * @param  Collection<int, Accommodation>  $units
     * @param  Collection<int, User>  $clients
     * @param  list<string>  $statuses
     */
    protected function insertAdminDemoBooking(
        string $marker,
        int $index,
        Collection $units,
        Collection $clients,
        array $statuses,
        Carbon $createdAt,
        Carbon $checkIn,
        Carbon $checkOut,
        int $guests,
        string $paymentMethod,
        ?string $clientMessage = null,
    ): void {
        /** @var Accommodation $acc */
        $acc = $units[$index % $units->count()];
        $client = $clients[$index % $clients->count()];
        $tenantId = (int) $acc->tenant_id;
        $status = $statuses[$index % count($statuses)];

        $nights = max(1, $checkIn->diffInDays($checkOut));
        $totalPrice = round((float) $acc->price_per_night * $nights * (1 + ($index % 4) * 0.02), 2);
        $profile = $this->adminDemoDemographicsProfile($index);

        $payload = [
            'client_id' => $client->id,
            'accommodation_id' => $acc->id,
            'tenant_id' => $tenantId,
            'check_in_date' => $checkIn->toDateString(),
            'check_out_date' => $checkOut->toDateString(),
            'number_of_guests' => $guests,
            'guest_gender' => $profile['guest_gender'],
            'guest_age' => $profile['guest_age'],
            'guest_is_local' => $profile['guest_is_local'],
            'guest_local_place' => $profile['guest_local_place'],
            'guest_country' => $profile['guest_country'],
            'total_price' => max(750, $totalPrice),
            'status' => $status,
            'special_requests' => $marker,
            'client_message' => $clientMessage,
            'owner_response' => null,
            'payment_method' => $paymentMethod,
            'payment_reference' => null,
            'confirmed_at' => null,
            'cancelled_at' => null,
            'paid_at' => null,
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];

        if (in_array($status, [Booking::STATUS_CONFIRMED, Booking::STATUS_PAID, Booking::STATUS_COMPLETED], true)) {
            $payload['confirmed_at'] = $createdAt->copy()->addHours(2);
        }
        if (in_array($status, [Booking::STATUS_PAID, Booking::STATUS_COMPLETED], true)) {
            $payload['paid_at'] = $createdAt->copy()->addHours(4);
            $payload['payment_reference'] = 'ADM-'.str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT);
        }

        Booking::query()->insert($payload);
    }
}
