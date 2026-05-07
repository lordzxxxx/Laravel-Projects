<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (! DB::connection('landlord')->getSchemaBuilder()->hasTable('tenants')
            || ! DB::connection('landlord')->getSchemaBuilder()->hasColumn('tenants', 'db_username')) {
            return;
        }

        DB::connection('landlord')->statement('ALTER TABLE tenants MODIFY db_username TEXT NULL');

        DB::connection('landlord')
            ->table('tenants')
            ->select(['id', 'db_username'])
            ->orderBy('id')
            ->chunkById(200, function ($tenants): void {
                foreach ($tenants as $tenant) {
                    $value = $tenant->db_username ?? null;
                    if (! is_string($value) || $value === '' || $this->looksEncrypted($value)) {
                        continue;
                    }

                    DB::connection('landlord')
                        ->table('tenants')
                        ->where('id', $tenant->id)
                        ->update(['db_username' => Crypt::encryptString($value)]);
                }
            });
    }

    public function down(): void
    {
        // Irreversible hardening migration.
    }

    private function looksEncrypted(string $value): bool
    {
        try {
            Crypt::decryptString($value);

            return true;
        } catch (\Throwable) {
            return false;
        }
    }
};
