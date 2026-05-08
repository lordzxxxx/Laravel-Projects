<?php

namespace App\Models\Concerns;

trait UsesPermissionTablesConnection
{
    public function getConnectionName()
    {
        $landlordConnection = config('multitenancy.landlord_database_connection_name', config('database.default'));

        if (app()->environment('testing')) {
            return config('database.default');
        }

        return $landlordConnection;
    }
}
