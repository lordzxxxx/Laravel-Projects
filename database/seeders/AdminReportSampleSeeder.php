<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * @see AdminAccurateDemoSeeder
 */
class AdminReportSampleSeeder extends Seeder
{
    public const DEMO_MARKER = AdminAccurateDemoSeeder::REPORT_MARKER;

    public function run(): void
    {
        $this->call(AdminAccurateDemoSeeder::class);
    }
}
