<?php

namespace App\Support;

class SingleDbMigrationMode
{
    public static function enabled(): bool
    {
        return (bool) config('single_db_migration.enabled', false);
    }

    public static function readsEnabled(): bool
    {
        return self::enabled() && (bool) config('single_db_migration.single_db_reads', false);
    }

    public static function writesEnabled(): bool
    {
        return self::enabled() && (bool) config('single_db_migration.single_db_writes', false);
    }

    public static function shadowReadsEnabled(): bool
    {
        return self::enabled() && (bool) config('single_db_migration.shadow_reads', false);
    }

    public static function allowLegacyProvisioning(): bool
    {
        if (! self::enabled()) {
            return true;
        }

        return (bool) config('single_db_migration.allow_legacy_provisioning', true);
    }

    public static function allowTenantSwitching(): bool
    {
        if (! self::enabled()) {
            return true;
        }

        return (bool) config('single_db_migration.allow_tenant_switching', true);
    }
}

