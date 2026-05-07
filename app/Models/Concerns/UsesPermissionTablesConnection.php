<?php

namespace App\Models\Concerns;

use App\Models\Tenant;
use App\Support\SingleDbMigrationMode;

trait UsesPermissionTablesConnection
{
    public function getConnectionName()
    {
        $tenantConnection = config('multitenancy.tenant_database_connection_name', 'tenant');
        $landlordConnection = config('multitenancy.landlord_database_connection_name', config('database.default'));

        if (Tenant::checkCurrent()) {
            return $tenantConnection;
        }

        if (app()->environment('testing')) {
            return config('database.default');
        }

        if (SingleDbMigrationMode::readsEnabled()) {
            return $landlordConnection;
        }

        return $landlordConnection;
    }
}
