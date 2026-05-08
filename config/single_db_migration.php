<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Single-DB Migration Feature Flags
    |--------------------------------------------------------------------------
    |
    | These flags allow incremental rollout from per-tenant databases to a
    | single landlord-backed tenant-scoped schema.
    |
    */
    'enabled' => env('SINGLE_DB_MIGRATION_ENABLED', true),
    'single_db_reads' => env('SINGLE_DB_READS', true),
    'single_db_writes' => env('SINGLE_DB_WRITES', true),
    'shadow_reads' => env('SINGLE_DB_SHADOW_READS', false),
    'allow_legacy_provisioning' => env('SINGLE_DB_ALLOW_LEGACY_PROVISIONING', false),
    'allow_tenant_switching' => env('SINGLE_DB_ALLOW_TENANT_SWITCHING', false),
];

