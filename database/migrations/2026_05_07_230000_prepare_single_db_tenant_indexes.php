<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'tenant_id') && Schema::hasColumn('users', 'email')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->index(['tenant_id', 'email'], 'users_tenant_email_idx');
                $table->index(['tenant_id', 'role'], 'users_tenant_role_idx');
            });
        }

        if (Schema::hasTable('accommodations') && Schema::hasColumn('accommodations', 'tenant_id')) {
            Schema::table('accommodations', function (Blueprint $table): void {
                $table->index(['tenant_id', 'owner_id'], 'accommodations_tenant_owner_idx');
                $table->index(['tenant_id', 'created_at'], 'accommodations_tenant_created_idx');
            });
        }

        if (Schema::hasTable('bookings') && Schema::hasColumn('bookings', 'tenant_id')) {
            Schema::table('bookings', function (Blueprint $table): void {
                $table->index(['tenant_id', 'accommodation_id'], 'bookings_tenant_accommodation_idx');
                $table->index(['tenant_id', 'client_id'], 'bookings_tenant_client_idx');
                $table->index(['tenant_id', 'created_at'], 'bookings_tenant_created_idx');
            });
        }

        if (Schema::hasTable('messages') && Schema::hasColumn('messages', 'tenant_id')) {
            Schema::table('messages', function (Blueprint $table): void {
                $table->index(['tenant_id', 'sender_id', 'created_at'], 'messages_tenant_sender_created_idx');
                $table->index(['tenant_id', 'receiver_id', 'created_at'], 'messages_tenant_receiver_created_idx');
            });
        }

        if (Schema::hasTable('tenant_custom_roles') && Schema::hasColumn('tenant_custom_roles', 'tenant_id')) {
            Schema::table('tenant_custom_roles', function (Blueprint $table): void {
                $table->index(['tenant_id', 'created_at'], 'tenant_custom_roles_tenant_created_idx');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('tenant_custom_roles')) {
            Schema::table('tenant_custom_roles', function (Blueprint $table): void {
                $table->dropIndex('tenant_custom_roles_tenant_created_idx');
            });
        }

        if (Schema::hasTable('messages')) {
            Schema::table('messages', function (Blueprint $table): void {
                $table->dropIndex('messages_tenant_sender_created_idx');
                $table->dropIndex('messages_tenant_receiver_created_idx');
            });
        }

        if (Schema::hasTable('bookings')) {
            Schema::table('bookings', function (Blueprint $table): void {
                $table->dropIndex('bookings_tenant_accommodation_idx');
                $table->dropIndex('bookings_tenant_client_idx');
                $table->dropIndex('bookings_tenant_created_idx');
            });
        }

        if (Schema::hasTable('accommodations')) {
            Schema::table('accommodations', function (Blueprint $table): void {
                $table->dropIndex('accommodations_tenant_owner_idx');
                $table->dropIndex('accommodations_tenant_created_idx');
            });
        }

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->dropIndex('users_tenant_email_idx');
                $table->dropIndex('users_tenant_role_idx');
            });
        }
    }
};

