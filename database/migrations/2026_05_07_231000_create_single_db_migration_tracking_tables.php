<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('single_db_legacy_id_maps', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('source_database', 128);
            $table->string('table_name', 64);
            $table->unsignedBigInteger('legacy_id');
            $table->unsignedBigInteger('new_id');
            $table->timestamps();

            $table->unique(['tenant_id', 'table_name', 'legacy_id'], 'single_db_legacy_unique');
            $table->index(['tenant_id', 'table_name'], 'single_db_legacy_lookup_idx');
            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
        });

        Schema::create('single_db_migration_checkpoints', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('source_database', 128);
            $table->string('table_name', 64);
            $table->unsignedBigInteger('last_legacy_id')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'table_name'], 'single_db_checkpoint_unique');
            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('single_db_migration_checkpoints');
        Schema::dropIfExists('single_db_legacy_id_maps');
    }
};

