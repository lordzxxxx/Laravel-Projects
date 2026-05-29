<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tenant_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained('tenants')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique('user_id');
            $table->index('tenant_id');
        });

        Schema::create('client_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained('tenants')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique('user_id');
            $table->index('tenant_id');
        });

        $now = now();

        $tenantUsers = DB::table('users')
            ->select(['id as user_id', 'tenant_id'])
            ->whereIn('role', ['owner', 'admin'])
            ->get()
            ->map(fn ($row): array => [
                'tenant_id' => $row->tenant_id,
                'user_id' => $row->user_id,
                'created_at' => $now,
                'updated_at' => $now,
            ])
            ->all();

        if ($tenantUsers !== []) {
            DB::table('tenant_users')->insert($tenantUsers);
        }

        $clientUsers = DB::table('users')
            ->select(['id as user_id', 'tenant_id'])
            ->where('role', 'client')
            ->get()
            ->map(fn ($row): array => [
                'tenant_id' => $row->tenant_id,
                'user_id' => $row->user_id,
                'created_at' => $now,
                'updated_at' => $now,
            ])
            ->all();

        if ($clientUsers !== []) {
            DB::table('client_users')->insert($clientUsers);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_users');
        Schema::dropIfExists('tenant_users');
    }
};

