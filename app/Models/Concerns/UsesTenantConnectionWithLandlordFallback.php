<?php

namespace App\Models\Concerns;

trait UsesTenantConnectionWithLandlordFallback
{
    public function getConnectionName()
    {
        $defaultConnection = config('database.default');
        $landlordConnection = config('multitenancy.landlord_database_connection_name', $defaultConnection);

        // In single-db mode, always use landlord/default connection.
        if (app()->environment('testing')) {
            return $defaultConnection;
        }

        return $landlordConnection;
    }
}
