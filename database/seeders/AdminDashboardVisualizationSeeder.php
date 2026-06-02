<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * @see AdminAccurateDemoSeeder
 */
class AdminDashboardVisualizationSeeder extends Seeder
{
    private const DEMO_MARKER = AdminAccurateDemoSeeder::DASHBOARD_MARKER;

    public function run(): void
    {
        $this->call(AdminAccurateDemoSeeder::class);
    }
}
