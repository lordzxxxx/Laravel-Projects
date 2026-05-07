<?php

namespace App\Multitenancy\Tasks;

use App\Support\SingleDbMigrationMode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Multitenancy\Concerns\UsesMultitenancyConfig;
use Spatie\Multitenancy\Contracts\IsTenant;
use Spatie\Multitenancy\Exceptions\InvalidConfiguration;
use Spatie\Multitenancy\Tasks\SwitchTenantTask;

class SwitchTenantDatabaseConnectionTask implements SwitchTenantTask
{
    use UsesMultitenancyConfig;

    protected array $tenantConnectionDefaults;

    public function __construct()
    {
        $tenantConnectionName = $this->tenantDatabaseConnectionName();
        $this->tenantConnectionDefaults = (array) config("database.connections.{$tenantConnectionName}", []);
    }

    public function makeCurrent(IsTenant $tenant): void
    {
        if (! SingleDbMigrationMode::allowTenantSwitching()) {
            return;
        }

        $fallbackHost = $this->tenantConnectionDefaults['host']
            ?? config('database.connections.mysql.host')
            ?? config('database.connections.landlord.host');
        $fallbackPort = $this->tenantConnectionDefaults['port']
            ?? config('database.connections.mysql.port')
            ?? config('database.connections.landlord.port');
        $fallbackUsername = $this->tenantConnectionDefaults['username']
            ?? config('database.connections.mysql.username')
            ?? config('database.connections.landlord.username');
        $fallbackPassword = $this->tenantConnectionDefaults['password']
            ?? config('database.connections.mysql.password')
            ?? config('database.connections.landlord.password');

        $this->setTenantConnectionConfig([
            'database' => $tenant->database,
            'host' => $tenant->db_host ?: $fallbackHost,
            'port' => $tenant->db_port ?: $fallbackPort,
            'username' => $tenant->db_username ?: $fallbackUsername,
            'password' => $tenant->db_password ?: $fallbackPassword,
        ]);
    }

    public function forgetCurrent(): void
    {
        if (! SingleDbMigrationMode::allowTenantSwitching()) {
            return;
        }

        $this->setTenantConnectionConfig([
            'database' => $this->tenantConnectionDefaults['database'] ?? null,
            'host' => $this->tenantConnectionDefaults['host'] ?? null,
            'port' => $this->tenantConnectionDefaults['port'] ?? null,
            'username' => $this->tenantConnectionDefaults['username'] ?? null,
            'password' => $this->tenantConnectionDefaults['password'] ?? null,
        ]);
    }

    protected function setTenantConnectionConfig(array $overrides): void
    {
        $tenantConnectionName = $this->tenantDatabaseConnectionName();

        if ($tenantConnectionName === $this->landlordDatabaseConnectionName()) {
            throw InvalidConfiguration::tenantConnectionIsEmptyOrEqualsToLandlordConnection();
        }

        if (is_null(config("database.connections.{$tenantConnectionName}"))) {
            throw InvalidConfiguration::tenantConnectionDoesNotExist($tenantConnectionName);
        }

        $connectionConfig = array_merge($this->tenantConnectionDefaults, $overrides);

        config([
            "database.connections.{$tenantConnectionName}" => $connectionConfig,
        ]);

        app('db')->extend($tenantConnectionName, function ($config, $name) use ($connectionConfig) {
            $config = array_merge($config, $connectionConfig);

            return app('db.factory')->make($config, $name);
        });

        DB::purge($tenantConnectionName);
        Model::setConnectionResolver(app('db'));
    }
}
