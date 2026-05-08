<?php

namespace Database\Seeders;

use App\Models\Accommodation;
use App\Models\Booking;
use App\Models\Tenant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeds ~20 bookings with realistic dates so owner dashboards and monthly reports show charts.
 *
 * Target tenant (first match wins):
 *   - DEMO_SEED_TENANT_ID=8
 *   - DEMO_SEED_DOMAIN=inns (matched as substring against tenants.domain)
 *   - Otherwise first tenant with owner_user_id set.
 *
 * Idempotent: removes prior rows seeded with special_requests = '[demo-visualization]'.
 *
 * Run: php artisan db:seed --class=DashboardVisualizationSeeder
 */
class DashboardVisualizationSeeder extends Seeder
{
    private const DEMO_MARKER = '[demo-visualization]';

    public function run(): void
    {
        $tenant = $this->resolveTenant();

        if (! $tenant) {
            $this->command?->error('No tenant found. Create a tenant or set DEMO_SEED_TENANT_ID / DEMO_SEED_DOMAIN in .env');

            return;
        }

        $ownerId = (int) ($tenant->owner_user_id ?? 0);
        if ($ownerId < 1) {
            $this->command?->error("Tenant #{$tenant->id} has no owner_user_id.");

            return;
        }

        Booking::query()
            ->where('tenant_id', (int) $tenant->id)
            ->where('special_requests', self::DEMO_MARKER)
            ->delete();

        $accommodations = Accommodation::query()
            ->where('tenant_id', (int) $tenant->id)
            ->get();

        if ($accommodations->isEmpty()) {
            $accommodations = collect([
                $this->makeDemoAccommodation($tenant->id, $ownerId, 'Demo Ridge Cabin', 'airbnb', 3200),
                $this->makeDemoAccommodation($tenant->id, $ownerId, 'Demo Town Inn Room', 'traveller-inn', 1400),
                $this->makeDemoAccommodation($tenant->id, $ownerId, 'Demo Garden Suite', 'daily-rental', 2100),
            ]);
            $this->command?->info('Created 3 demo accommodations (no listings existed for this tenant).');
        }

        $clients = User::query()
            ->where('role', User::ROLE_CLIENT)
            ->where(function ($q) use ($tenant) {
                $q->whereNull('tenant_id')
                    ->orWhere('tenant_id', (int) $tenant->id);
            })
            ->orderBy('id')
            ->limit(8)
            ->get();

        while ($clients->count() < 5) {
            $n = $clients->count() + 1;
            $email = 'demo.visual.client.'.$tenant->id.'.'.$n.'.'.uniqid().'@example.test';
            $user = User::create([
                'name' => 'Demo Guest '.$n,
                'email' => $email,
                'password' => Hash::make('password'),
                'role' => User::ROLE_CLIENT,
                'tenant_id' => (int) $tenant->id,
                'phone' => '+639170000'.str_pad((string) $n, 4, '0', STR_PAD_LEFT),
                'is_active' => true,
            ]);
            if (method_exists($user, 'syncRbacFromLegacyRole')) {
                $user->syncRbacFromLegacyRole();
            }
            $clients->push($user);
        }

        $statusCycle = [
            Booking::STATUS_PAID,
            Booking::STATUS_PAID,
            Booking::STATUS_CONFIRMED,
            Booking::STATUS_CONFIRMED,
            Booking::STATUS_COMPLETED,
            Booking::STATUS_COMPLETED,
            Booking::STATUS_PAID,
            Booking::STATUS_CONFIRMED,
            Booking::STATUS_COMPLETED,
            Booking::STATUS_PAID,
            Booking::STATUS_CONFIRMED,
            Booking::STATUS_PAID,
            Booking::STATUS_CONFIRMED,
            Booking::STATUS_COMPLETED,
            Booking::STATUS_PAID,
            Booking::STATUS_CONFIRMED,
            Booking::STATUS_COMPLETED,
            Booking::STATUS_PAID,
            Booking::STATUS_PENDING,
            Booking::STATUS_CANCELLED,
        ];

        $now = Carbon::now();
        $monthStart = $now->copy()->startOfMonth();
        $monthEnd = $now->copy()->endOfMonth();

        foreach ($statusCycle as $i => $status) {
            $acc = $accommodations[$i % max(1, $accommodations->count())];
            $client = $clients[$i % $clients->count()];

            $dayOffset = (int) (($i * 1.7) % max(1, $now->daysInMonth));
            $checkIn = $monthStart->copy()->addDays(min($dayOffset, $now->daysInMonth - 3));
            if ($checkIn->greaterThan($monthEnd->copy()->subDays(2))) {
                $checkIn = $monthEnd->copy()->subDays(4);
            }

            $nights = 2 + ($i % 4);
            $checkOut = $checkIn->copy()->addDays($nights);

            $createdAt = $now->copy()->subDays(min(29, (int) (($i * 3.1) % 30)))
                ->setTime(10 + ($i % 8), ($i * 7) % 60, 0);

            $guests = 1 + ($i % 5);
            $base = (float) $acc->price_per_night * $nights;
            $totalPrice = round($base * (1 + ($i % 3) * 0.05), 2);

            $payload = [
                'client_id' => $client->id,
                'accommodation_id' => $acc->id,
                'tenant_id' => (int) $tenant->id,
                'check_in_date' => $checkIn->toDateString(),
                'check_out_date' => $checkOut->toDateString(),
                'number_of_guests' => $guests,
                'guest_gender' => ['male', 'female', 'unspecified'][$i % 3],
                'guest_age' => 22 + ($i % 40),
                'guest_is_local' => (bool) ($i % 2),
                'guest_local_place' => ($i % 2) ? 'Impasugong' : null,
                'guest_country' => ($i % 2) ? null : 'Philippines',
                'total_price' => $totalPrice,
                'status' => $status,
                'special_requests' => self::DEMO_MARKER,
                'client_message' => null,
                'owner_response' => null,
                'payment_method' => in_array($status, [
                    Booking::STATUS_PAID,
                    Booking::STATUS_COMPLETED,
                    Booking::STATUS_CONFIRMED,
                ], true) ? 'demo_seed' : null,
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
                $payload['paid_at'] = $createdAt->copy()->addHours(5);
            }
            if ($status === Booking::STATUS_CANCELLED) {
                $payload['cancelled_at'] = $createdAt->copy()->addDay();
            }

            Booking::query()->insert($payload);
        }

        $this->command?->info("Seeded 20 demo bookings for tenant #{$tenant->id} ({$tenant->name}).");
        $this->command?->info('Monthly report uses check_in_date in the selected month; charts use created_at (last ~30 days).');
    }

    private function resolveTenant(): ?Tenant
    {
        $byId = env('DEMO_SEED_TENANT_ID');
        if ($byId !== null && $byId !== '') {
            $t = Tenant::query()->find((int) $byId);
            if ($t) {
                return $t;
            }
        }

        $domainNeedle = env('DEMO_SEED_DOMAIN');
        if (is_string($domainNeedle) && $domainNeedle !== '') {
            $t = Tenant::query()
                ->where('domain', 'like', '%'.$domainNeedle.'%')
                ->orderBy('id')
                ->first();
            if ($t) {
                return $t;
            }
        }

        return Tenant::query()
            ->whereNotNull('owner_user_id')
            ->orderBy('id')
            ->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model|Accommodation
     */
    private function makeDemoAccommodation(int $tenantId, int $ownerId, string $name, string $type, float $pricePerNight): Accommodation
    {
        return Accommodation::create([
            'owner_id' => $ownerId,
            'tenant_id' => $tenantId,
            'name' => $name,
            'type' => $type,
            'description' => 'Demo listing generated by DashboardVisualizationSeeder for charts and reports.',
            'address' => 'Demo Street, Impasugong, Bukidnon',
            'barangay' => 'Poblacion',
            'price_per_night' => $pricePerNight,
            'price_per_day' => round($pricePerNight * 0.85, 2),
            'bedrooms' => 2,
            'bathrooms' => 1,
            'max_guests' => 4,
            'amenities' => ['WiFi', 'Parking'],
            'images' => [],
            'rating' => 4.5,
            'total_reviews' => 0,
            'is_available' => true,
            'is_verified' => true,
            'is_featured' => false,
        ]);
    }
}
