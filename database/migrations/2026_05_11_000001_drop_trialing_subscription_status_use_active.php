<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $conn = (string) config('database.connections.landlord.database', '');
        if ($conn === '' || $conn === ':memory:') {
            return;
        }

        DB::connection('landlord')->table('tenants')
            ->where('subscription_status', 'trialing')
            ->update([
                'subscription_status' => 'active',
                'trial_ends_at' => null,
            ]);
    }

    public function down(): void
    {
        // Intentionally no-op: historic rows are not restored to trialing.
    }
};
