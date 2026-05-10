<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (! DB::getSchemaBuilder()->hasTable('bookings') || ! DB::getSchemaBuilder()->hasColumn('bookings', 'payment_reference')) {
            return;
        }

        // Encrypted payloads exceed legacy varchar length.
        DB::statement('ALTER TABLE bookings MODIFY payment_reference TEXT NULL');

        DB::table('bookings')
            ->select(['id', 'payment_reference'])
            ->orderBy('id')
            ->chunkById(200, function ($bookings): void {
                foreach ($bookings as $booking) {
                    $reference = $booking->payment_reference ?? null;
                    if (! is_string($reference) || $reference === '' || $this->looksEncrypted($reference)) {
                        continue;
                    }

                    DB::table('bookings')
                        ->where('id', $booking->id)
                        ->update(['payment_reference' => Crypt::encryptString($reference)]);
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
