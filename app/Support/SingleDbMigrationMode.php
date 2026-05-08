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

    /**
     * One physical database: tenant-scoped rows use tenant_id on the landlord connection,
     * and per-tenant DB switching is disabled.
     */
    public static function unifiedSchema(): bool
    {
        return self::enabled() && ! self::allowTenantSwitching();
    }

    /**
     * True when landlord and tenant connection configs resolve to the same database name.
     */
    public static function tenantDatabaseNameMatchesLandlord(): bool
    {
        $landlord = (string) config('database.connections.'.config('multitenancy.landlord_database_connection_name', 'landlord').'.database');
        $tenant = (string) config('database.connections.'.config('multitenancy.tenant_database_connection_name', 'tenant').'.database');

        return $landlord !== '' && $landlord === $tenant;
    }
}
