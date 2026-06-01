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
 * Seeds admin PDF/report sample data (demographics + monthly booking by tenant).
 *
 * Creates at least 15 paid/confirmed/completed bookings in the current calendar month,
 * spread across multiple tenants, with guest demographics filled in.
 *
 * Idempotent marker: special_requests = '[admin-report-sample]'
 *
 * Run:
 *   php artisan db:seed --class=AdminReportSampleSeeder
 *
 * Optional .env:
 *   ADMIN_REPORT_SAMPLE_COUNT=18   (minimum 15)
 *   ADMIN_REPORT_SAMPLE_TENANTS=5
 */
class AdminReportSampleSeeder extends Seeder
{
    public const DEMO_MARKER = '[admin-report-sample]';

    private const TYPES = ['traveller-inn', 'airbnb', 'daily-rental'];

    public function run(): void
    {
        Booking::query()->where('special_requests', self::DEMO_MARKER)->delete();

        $tenants = Tenant::query()
            ->whereNotNull('owner_user_id')
            ->orderBy('id')
            ->limit(max(1, min(10, (int) env('ADMIN_REPORT_SAMPLE_TENANTS', 5))))
            ->get();

        if ($tenants->isEmpty()) {
            $this->command?->error('No tenants with owner_user_id. Run tenant onboarding or ExistingTenantDatabasesSeeder first.');

            return;
        }

        $count = max(15, min(60, (int) env('ADMIN_REPORT_SAMPLE_COUNT', 18)));
        $now = Carbon::now();
        $monthStart = $now->copy()->startOfMonth();
        $monthEnd = $now->copy()->endOfMonth();
        $daysInMonth = (int) $monthEnd->format('d');

        $accommodationByTenant = [];
        $clientsByTenant = [];

        foreach ($tenants as $tenant) {
            $accommodationByTenant[$tenant->id] = $this->ensureTypeAccommodations($tenant);
            $clientsByTenant[$tenant->id] = $this->ensureDemoClients($tenant);
        }

        $statuses = [
            Booking::STATUS_PAID,
            Booking::STATUS_CONFIRMED,
            Booking::STATUS_COMPLETED,
            Booking::STATUS_PAID,
            Booking::STATUS_CONFIRMED,
            Booking::STATUS_COMPLETED,
            Booking::STATUS_PAID,
            Booking::STATUS_CONFIRMED,
        ];

        $guestNames = [
            'Ana Reyes', 'Mark Dela Cruz', 'Liza Tan', 'James Lim', 'Sofia Gomez',
            'Carlos Bautista', 'Elena Cruz', 'Noah Santos', 'Mia Fernandez', 'Owen Garcia',
            'Priya Shah', 'Kenji Yamamoto', 'Emma Wilson', 'Lucas Mueller', 'Chloe Martin',
            'Diego Lopez', 'Hannah Park', 'Ryan O\'Connor',
        ];

        for ($i = 0; $i < $count; $i++) {
            /** @var Tenant $tenant */
            $tenant = $tenants[$i % $tenants->count()];
            $tenantId = (int) $tenant->id;
            $type = self::TYPES[$i % 3];
            $acc = $accommodationByTenant[$tenantId][$type];
            $client = $clientsByTenant[$tenantId][$i % $clientsByTenant[$tenantId]->count()];
            $status = $statuses[$i % count($statuses)];

            $day = 1 + ($i % max(1, $daysInMonth - 3));
            $createdAt = $monthStart->copy()->addDays($day)->setTime(9 + ($i % 8), ($i * 7) % 60, 0);

            $checkIn = $monthStart->copy()->addDays(min($day, $daysInMonth - 2));
            $nights = 2 + ($i % 3);
            $checkOut = $checkIn->copy()->addDays($nights);

            $guests = 1 + ($i % 4);
            $totalPrice = round((float) $acc->price_per_night * max(1, $checkIn->diffInDays($checkOut)) * (1 + ($i % 5) * 0.03), 2);
            $profile = $this->demographicsProfile($i);

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
                'special_requests' => self::DEMO_MARKER,
                'client_message' => 'Sample booking for admin report #'.($i + 1).' — '.$guestNames[$i % count($guestNames)],
                'owner_response' => null,
                'payment_method' => 'report_sample_seed',
                'payment_reference' => 'RPT-'.str_pad((string) ($i + 1), 4, '0', STR_PAD_LEFT),
                'confirmed_at' => $createdAt->copy()->addHours(2),
                'cancelled_at' => null,
                'paid_at' => $createdAt->copy()->addHours(4),
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ];

            Booking::query()->insert($payload);
        }

        $inMonth = Booking::query()
            ->where('special_requests', self::DEMO_MARKER)
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->count();

        $this->command?->info("Seeded {$count} admin report sample bookings ({$inMonth} in {$now->format('F Y')}) across ".$tenants->count().' tenant(s).');
        $this->command?->line('Admin reports:');
        $this->command?->line('  • Demographics: /admin/reports/demographics (export PDF)');
        $this->command?->line('  • Monthly booking PDF: admin dashboard → monthly booking report');
        $this->command?->line('Reload http://127.0.0.1:8000/admin/dashboard after seeding.');
    }

    /**
     * @return array{guest_gender: string, guest_age: ?int, guest_is_local: ?bool, guest_local_place: ?string, guest_country: ?string}
     */
    private function demographicsProfile(int $mix): array
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
                'traveller-inn' => ['Report Sample Inn', 1500],
                'airbnb' => ['Report Sample Airbnb', 2800],
                'daily-rental' => ['Report Sample Daily Rental', 2100],
            ];

            $out[$type] = Accommodation::create([
                'owner_id' => $ownerId,
                'tenant_id' => $tenantId,
                'name' => $labels[$type][0].' — '.$tenant->name,
                'type' => $type,
                'description' => 'Sample listing for admin PDF reports.',
                'address' => 'Impasugong, Bukidnon',
                'barangay' => 'Poblacion',
                'price_per_night' => $labels[$type][1],
                'price_per_day' => round($labels[$type][1] * 0.9, 2),
                'bedrooms' => 2,
                'bathrooms' => 1,
                'max_guests' => 4,
                'amenities' => ['WiFi', 'Parking'],
                'images' => [],
                'rating' => 4.6,
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
            ->limit(8)
            ->get();

        while ($existing->count() < 5) {
            $n = $existing->count() + 1;
            $user = User::create([
                'name' => 'Report Sample Guest '.$tenantId.'-'.$n,
                'email' => 'report.sample.'.$tenantId.'.'.$n.'.'.uniqid('', true).'@example.test',
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
