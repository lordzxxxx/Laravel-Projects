<?php

namespace Database\Seeders;

use App\Models\Accommodation;
use App\Models\Booking;
use App\Models\Tenant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

/**
 * Seeds demo bookings for `/admin/dashboard` (KPI cards, monthly charts, bookings-by-type, demographics).
 *
 * Creates verified listings per unit type when missing, plus demo guest users (landlord / single-DB).
 *
 * Idempotent marker: `special_requests = '[admin-dashboard-demo]'`
 *
 * Run:
 *   php artisan db:seed --class=AdminDashboardVisualizationSeeder
 *
 * Optional `.env`:
 *   ADMIN_DEMO_MAX_TENANTS=5       (default 3)
 *   ADMIN_DEMO_BOOKING_COUNT=36    (default 36; spread Jan–Dec for chart demo)
 *   ADMIN_DEMO_DEMOGRAPHICS_EXTRAS=18  (bookings in the *current* month with balanced gender/location/age for demographics charts)
 */
class AdminDashboardVisualizationSeeder extends Seeder
{
    private const DEMO_MARKER = '[admin-dashboard-demo]';

    private const TYPES = ['traveller-inn', 'airbnb', 'daily-rental'];

    public function run(): void
    {
        Booking::query()->where('special_requests', self::DEMO_MARKER)->delete();

        $tenants = $this->resolveTargetTenants();

        if ($tenants->isEmpty()) {
            $this->command?->error('No tenants with owner_user_id found. Create tenants first.');

            return;
        }

        $now = Carbon::now();
        $bookingCount = max(12, min(200, (int) env('ADMIN_DEMO_BOOKING_COUNT', 36)));

        $accommodationByTenant = [];
        $clientsByTenant = [];

        foreach ($tenants as $tenant) {
            $accommodationByTenant[$tenant->id] = $this->ensureTypeAccommodations($tenant);
            $clientsByTenant[$tenant->id] = $this->ensureDemoClients($tenant);
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
            Booking::STATUS_PAID,
            Booking::STATUS_CONFIRMED,
            Booking::STATUS_COMPLETED,
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

        $cycleLen = count($statusCycle);

        for ($i = 0; $i < $bookingCount; $i++) {
            $status = $statusCycle[$i % $cycleLen];
            /** @var Tenant $tenant */
            $tenant = $tenants[$i % $tenants->count()];
            $tenantId = (int) $tenant->id;
            $type = self::TYPES[$i % 3];

            $acc = $accommodationByTenant[$tenantId][$type];
            $clients = $clientsByTenant[$tenantId];
            $client = $clients[$i % $clients->count()];

            // Spread `created_at` across all 12 months so "Bookings per month" / "Guests per month" charts fill.
            $createdMonth = ($i % 12) + 1;
            $createdAt = Carbon::create($now->year, $createdMonth, min(28, 3 + ($i % 25)), 8 + ($i % 10), ($i * 11) % 60, 0);

            // Active stay overlapping "today" for same-month occupancy widgets (every ~9th row).
            if ($i % 9 === 0 && in_array($status, [Booking::STATUS_PAID, Booking::STATUS_CONFIRMED, Booking::STATUS_COMPLETED], true)) {
                $checkIn = $now->copy()->subDays(1)->startOfDay();
                $checkOut = $now->copy()->addDays(3)->startOfDay();
            } else {
                $stayMonth = Carbon::create($now->year, $createdMonth, 1);
                $dim = (int) $stayMonth->format('t');
                $checkIn = $stayMonth->copy()->addDays(($i * 2) % max(1, $dim - 4));
                $nights = 2 + ($i % 4);
                $checkOut = $checkIn->copy()->addDays($nights);
            }

            $guests = 1 + ($i % 5);
            $base = (float) $acc->price_per_night * max(1, $checkIn->diffInDays($checkOut));
            $totalPrice = round(max(800, $base) * (1 + ($i % 4) * 0.04), 2);

            // Spread demographics across months: same calendar month used to imply same `i % 12`, which skewed gender/location.
            $demographicsMix = $i + intdiv($i, 12) + ($tenantId % 11);
            $profile = $this->demographicsProfile($demographicsMix);

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
                'total_price' => $totalPrice,
                'status' => $status,
                'special_requests' => self::DEMO_MARKER,
                'client_message' => null,
                'owner_response' => null,
                'payment_method' => in_array($status, [
                    Booking::STATUS_PAID,
                    Booking::STATUS_COMPLETED,
                    Booking::STATUS_CONFIRMED,
                ], true) ? 'admin_demo_seed' : null,
                'payment_reference' => null,
                'confirmed_at' => null,
                'cancelled_at' => null,
                'paid_at' => null,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ];

            if (in_array($status, [Booking::STATUS_CONFIRMED, Booking::STATUS_PAID, Booking::STATUS_COMPLETED], true)) {
                $payload['confirmed_at'] = $createdAt->copy()->addHours(3);
            }
            if (in_array($status, [Booking::STATUS_PAID, Booking::STATUS_COMPLETED], true)) {
                $payload['paid_at'] = $createdAt->copy()->addHours(6);
            }
            if ($status === Booking::STATUS_CANCELLED) {
                $payload['cancelled_at'] = $createdAt->copy()->addDay();
            }

            Booking::query()->insert($payload);
        }

        $extras = max(0, min(72, (int) env('ADMIN_DEMO_DEMOGRAPHICS_EXTRAS', 18)));
        $dimNow = (int) $now->format('t');

        $demoStatusesForPanel = [
            Booking::STATUS_PAID,
            Booking::STATUS_CONFIRMED,
            Booking::STATUS_COMPLETED,
            Booking::STATUS_PENDING,
        ];

        for ($k = 0; $k < $extras; $k++) {
            /** @var Tenant $tenant */
            $tenant = $tenants[$k % $tenants->count()];
            $tenantId = (int) $tenant->id;
            $type = self::TYPES[$k % 3];
            $acc = $accommodationByTenant[$tenantId][$type];
            $clients = $clientsByTenant[$tenantId];
            $client = $clients[$k % $clients->count()];

            $dayOffset = max(0, min($dimNow - 1, 1 + (($k * 5) % max(1, $dimNow - 1))));
            $createdAt = $now->copy()->startOfMonth()->addDays($dayOffset)->setTime(10 + ($k % 8), ($k * 13) % 60, 0);

            $checkIn = $createdAt->copy()->addDays($k % 3)->startOfDay();
            $checkOut = $checkIn->copy()->addDays(2 + ($k % 4));

            $guests = 2 + ($k % 4);
            $base = (float) $acc->price_per_night * max(1, $checkIn->diffInDays($checkOut));
            $totalPrice = round(max(900, $base), 2);

            $status = $demoStatusesForPanel[$k % count($demoStatusesForPanel)];
            $mix = 900 + $k * 3 + ($tenantId % 13);
            $profile = $this->demographicsProfile($mix);

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
                'total_price' => $totalPrice,
                'status' => $status,
                'special_requests' => self::DEMO_MARKER,
                'client_message' => null,
                'owner_response' => null,
                'payment_method' => in_array($status, [
                    Booking::STATUS_PAID,
                    Booking::STATUS_COMPLETED,
                    Booking::STATUS_CONFIRMED,
                ], true) ? 'admin_demo_seed' : null,
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

            Booking::query()->insert($payload);
        }

        $this->command?->info("Seeded {$bookingCount} admin-dashboard demo bookings (+ {$extras} current-month demographics extras) across ".$tenants->count().' tenant(s).');
        $this->command?->line('Reload http://localhost:8000/admin/dashboard — KPIs and Chart.js widgets use this data.');
    }

    /**
     * Balanced guest profile for admin demographics (gender / local vs foreign / age buckets).
     *
     * @return array{guest_gender: string, guest_age: ?int, guest_is_local: ?bool, guest_local_place: ?string, guest_country: ?string}
     */
    private function demographicsProfile(int $mix): array
    {
        $genders = ['male', 'female', 'unspecified'];
        $guestGender = $genders[$mix % 3];

        $ageRepresentatives = [14, 21, 29, 39, 49, 61, null];
        $guestAge = $ageRepresentatives[intdiv($mix, 9) % 7];

        $locals = ['Impasugong', 'Malaybalay', 'Valencia City', 'Manolo Fortich', 'Maramag'];
        $countries = ['Philippines', 'United States', 'Japan', 'South Korea', 'Australia', 'Canada', 'Singapore', 'United Kingdom', 'Germany', 'France'];

        // Independent of gender (`$mix % 3`) so donut + location bars are not locked together.
        $locMode = intdiv($mix, 3) % 3;

        if ($locMode === 0) {
            return [
                'guest_gender' => $guestGender,
                'guest_age' => $guestAge,
                'guest_is_local' => true,
                'guest_local_place' => $locals[intdiv($mix, 27) % count($locals)],
                'guest_country' => null,
            ];
        }

        if ($locMode === 1) {
            return [
                'guest_gender' => $guestGender,
                'guest_age' => $guestAge,
                'guest_is_local' => false,
                'guest_local_place' => null,
                'guest_country' => $countries[intdiv($mix, 11) % count($countries)],
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
     * @return Collection<int, Tenant>
     */
    private function resolveTargetTenants(): Collection
    {
        $max = max(1, min(10, (int) env('ADMIN_DEMO_MAX_TENANTS', 3)));

        return Tenant::query()
            ->whereNotNull('owner_user_id')
            ->orderBy('id')
            ->limit($max)
            ->get();
    }

    /**
     * @return array<string, Accommodation>
     */
    private function ensureTypeAccommodations(Tenant $tenant): array
    {
        $ownerId = (int) $tenant->owner_user_id;
        $tenantId = (int) $tenant->id;
        $out = [];

        foreach (self::TYPES as $type) {
            $existing = Accommodation::query()
                ->where('tenant_id', $tenantId)
                ->where('type', $type)
                ->first();

            if ($existing) {
                $out[$type] = $existing;

                continue;
            }

            $labels = [
                'traveller-inn' => ['Demo Traveller Inn', 1400],
                'airbnb' => ['Demo Airbnb Stay', 2600],
                'daily-rental' => ['Demo Daily Rental', 1900],
            ];

            $out[$type] = Accommodation::create([
                'owner_id' => $ownerId,
                'tenant_id' => $tenantId,
                'name' => $labels[$type][0].' #'.$tenantId,
                'type' => $type,
                'description' => 'Auto-generated for admin dashboard visualization.',
                'address' => 'Demo Address, Bukidnon',
                'barangay' => 'Poblacion',
                'price_per_night' => $labels[$type][1],
                'price_per_day' => round($labels[$type][1] * 0.88, 2),
                'bedrooms' => 2,
                'bathrooms' => 1,
                'max_guests' => 4,
                'amenities' => ['WiFi'],
                'images' => [],
                'rating' => 4.5,
                'total_reviews' => 0,
                'is_available' => true,
                'is_verified' => true,
                'is_featured' => false,
            ]);
        }

        return $out;
    }

    /**
     * @return Collection<int, User>
     */
    private function ensureDemoClients(Tenant $tenant): Collection
    {
        $tenantId = (int) $tenant->id;

        $existing = User::query()
            ->where('role', User::ROLE_CLIENT)
            ->where(function ($q) use ($tenantId) {
                $q->whereNull('tenant_id')->orWhere('tenant_id', $tenantId);
            })
            ->orderBy('id')
            ->limit(6)
            ->get();

        while ($existing->count() < 3) {
            $n = $existing->count() + 1;
            $email = 'admin.demo.client.'.$tenantId.'.'.$n.'.'.uniqid('', true).'@example.test';
            $user = User::create([
                'name' => 'Admin Demo Guest '.$tenantId.'-'.$n,
                'email' => $email,
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
}
