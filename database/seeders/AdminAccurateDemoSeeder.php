<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Support\AdminOwnerUnitScope;
use Carbon\Carbon;
use Database\Seeders\Concerns\SeedsAdminDemoFromOwnerUnits;
use Illuminate\Database\Seeder;

/**
 * Accurate admin demo data: uses owner-uploaded units only (default 12) and
 * seeds bookings whose guest count sums to ADMIN_OWNER_TOTAL_GUESTS (default 20).
 *
 * Replaces synthetic Demo / Report Sample listings and prior inflated booking counts.
 *
 * Run:
 *   php artisan db:seed --class=AdminAccurateDemoSeeder
 *
 * Optional .env:
 *   ADMIN_OWNER_TENANT_ID=7
 *   ADMIN_OWNER_MAX_UNITS=12
 *   ADMIN_OWNER_TOTAL_GUESTS=20
 *   ADMIN_ACCURATE_BOOKING_COUNT=10
 */
class AdminAccurateDemoSeeder extends Seeder
{
    use SeedsAdminDemoFromOwnerUnits;

    public const DASHBOARD_MARKER = '[admin-dashboard-demo]';

    public const REPORT_MARKER = '[admin-report-sample]';

    public function run(): void
    {
        foreach (AdminOwnerUnitScope::DEMO_MARKERS as $marker) {
            Booking::query()->where('special_requests', $marker)->delete();
        }

        $this->pruneSyntheticAdminListings();

        $units = $this->resolveOwnerUnitsForAdminDemo();
        if ($units->isEmpty()) {
            return;
        }

        $tenantId = (int) $units->first()->tenant_id;
        $clients = $this->ensureDemoClientsForTenant($tenantId);

        $totalGuests = AdminOwnerUnitScope::targetGuestTotal();
        $bookingCount = max(4, min(24, (int) env('ADMIN_ACCURATE_BOOKING_COUNT', 10)));
        $guestParts = AdminOwnerUnitScope::partitionGuests($totalGuests, $bookingCount);

        $now = Carbon::now();
        $monthStart = $now->copy()->startOfMonth();
        $monthEnd = $now->copy()->endOfMonth();
        $daysInMonth = (int) $monthEnd->format('d');

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

        $currentMonthSlots = min($bookingCount, max(5, (int) ceil($bookingCount * 0.6)));

        for ($i = 0; $i < $bookingCount; $i++) {
            $inCurrentMonth = $i < $currentMonthSlots;
            $marker = $inCurrentMonth ? self::REPORT_MARKER : self::DASHBOARD_MARKER;

            if ($inCurrentMonth) {
                $day = 1 + ($i % max(1, $daysInMonth - 3));
                $createdAt = $monthStart->copy()->addDays($day)->setTime(9 + ($i % 6), ($i * 11) % 60, 0);
                $checkIn = $monthStart->copy()->addDays(min($day, $daysInMonth - 2));
            } else {
                $createdMonth = 1 + (($i - $currentMonthSlots) % 12);
                $createdAt = Carbon::create($now->year, $createdMonth, min(26, 4 + ($i % 20)), 10 + ($i % 5), ($i * 13) % 60, 0);
                $stayMonth = Carbon::create($now->year, $createdMonth, 1);
                $dim = (int) $stayMonth->format('t');
                $checkIn = $stayMonth->copy()->addDays(($i * 2) % max(1, $dim - 4));
            }

            $nights = 2 + ($i % 3);
            $checkOut = $checkIn->copy()->addDays($nights);

            if ($i % 9 === 0 && $inCurrentMonth) {
                $checkIn = $now->copy()->subDay()->startOfDay();
                $checkOut = $now->copy()->addDays(2)->startOfDay();
            }

            $this->insertAdminDemoBooking(
                marker: $marker,
                index: $i,
                units: $units,
                clients: $clients,
                statuses: $statuses,
                createdAt: $createdAt,
                checkIn: $checkIn,
                checkOut: $checkOut,
                guests: $guestParts[$i],
                paymentMethod: $inCurrentMonth ? 'report_sample_seed' : 'admin_demo_seed',
                clientMessage: $inCurrentMonth ? 'Current-month booking for admin reports #'.($i + 1) : null,
            );
        }

        $guestSum = (int) Booking::query()
            ->whereIn('special_requests', [self::DASHBOARD_MARKER, self::REPORT_MARKER])
            ->sum('number_of_guests');

        $unitCount = $units->count();

        $this->command?->info("Admin accurate demo: {$unitCount} owner unit(s), {$bookingCount} booking(s), {$guestSum} guest(s) (target {$totalGuests}).");
        $this->command?->line('Reload http://127.0.0.1:8000/admin/dashboard — KPIs should match owner portal listings.');
    }
}
