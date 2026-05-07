<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::connection('landlord')->getSchemaBuilder()->hasTable('tenants')) {
            // Encrypted payloads are longer than legacy varchar columns.
            DB::connection('landlord')->statement('ALTER TABLE tenants MODIFY payment_reference TEXT NULL');
            DB::connection('landlord')->statement('ALTER TABLE tenants MODIFY onboarding_stripe_session_id TEXT NULL');
        }

        DB::connection('landlord')
            ->table('users')
            ->select(['id', 'password'])
            ->orderBy('id')
            ->chunkById(200, function ($users): void {
                foreach ($users as $user) {
                    $password = (string) ($user->password ?? '');
                    if ($password === '' || $this->looksHashed($password)) {
                        continue;
                    }

                    DB::connection('landlord')
                        ->table('users')
                        ->where('id', $user->id)
                        ->update(['password' => Hash::make($password)]);
                }
            });

        DB::connection('landlord')
            ->table('tenants')
            ->select(['id', 'db_password', 'payment_reference', 'onboarding_stripe_session_id'])
            ->orderBy('id')
            ->chunkById(200, function ($tenants): void {
                foreach ($tenants as $tenant) {
                    $updates = [];

                    foreach (['db_password', 'payment_reference', 'onboarding_stripe_session_id'] as $column) {
                        $value = $tenant->{$column} ?? null;
                        if (! is_string($value) || $value === '') {
                            continue;
                        }

                        if ($this->looksEncrypted($value)) {
                            continue;
                        }

                        $updates[$column] = Crypt::encryptString($value);
                    }

                    if ($updates !== []) {
                        DB::connection('landlord')
                            ->table('tenants')
                            ->where('id', $tenant->id)
                            ->update($updates);
                    }
                }
            });
    }

    public function down(): void
    {
        // Irreversible hardening migration.
    }

    private function looksHashed(string $value): bool
    {
        return Hash::info($value)['algo'] !== null;
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
