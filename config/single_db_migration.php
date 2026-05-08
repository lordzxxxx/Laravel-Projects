<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Single-DB Migration Feature Flags
    |--------------------------------------------------------------------------
    |
    | When enabled with allow_tenant_switching=false (unified schema), one MySQL
    | database holds landlord tables (tenants, global admin users) and all
    | tenant-scoped rows (tenant_id). Use `php artisan single-db:migrate` or
    | `php artisan migrate` with DB_CONNECTION=landlord. Do not use tenants:migrate.
    |
    | These flags also support incremental rollout from per-tenant databases.
    |
    */
    'enabled' => env('SINGLE_DB_MIGRATION_ENABLED', true),
    'single_db_reads' => env('SINGLE_DB_READS', true),
    'single_db_writes' => env('SINGLE_DB_WRITES', true),
    'shadow_reads' => env('SINGLE_DB_SHADOW_READS', false),
    'allow_legacy_provisioning' => env('SINGLE_DB_ALLOW_LEGACY_PROVISIONING', false),
    'allow_tenant_switching' => env('SINGLE_DB_ALLOW_TENANT_SWITCHING', false),
];
