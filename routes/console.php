<?php

use App\Models\Accommodation;
use App\Models\AppRelease;
use App\Models\Booking;
use App\Models\Tenant;
use App\Models\User;
use App\Services\ReleaseRegistryService;
use App\Services\TenantSelfUpdateService;
use App\Services\TenantUpdateService;
use App\Support\SingleDbMigrationMode;
use Database\Seeders\TenantRbacSeeder;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Str;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('tenants:migrate {tenantId?}', function (?string $tenantId = null) {
    if (! SingleDbMigrationMode::allowTenantSwitching()) {
        $this->warn('Tenant DB switching is disabled by single-db migration mode. Run landlord migrations instead.');

        return 0;
    }

    $id = ($tenantId !== null && $tenantId !== '') ? (int) $tenantId : null;

    $query = Tenant::query()->where('database_provisioned', true)->orderBy('id');

    if ($id !== null) {
        $query->whereKey($id);
    }

    $tenants = $query->get();

    if ($tenants->isEmpty()) {
        $this->error($id !== null ? 'No provisioned tenant found for that id.' : 'No provisioned tenants to migrate.');

        return 1;
    }

    $connection = config('multitenancy.tenant_database_connection_name', 'tenant');

    foreach ($tenants as $tenant) {
        if (! $tenant->database) {
            $this->warn("Skipping tenant {$tenant->id}: no database configured.");

            continue;
        }

        $this->line("Migrating tenant schema for {$tenant->id} ({$tenant->name})...");

        $tenant->makeCurrent();

        try {
            $migrateExit = Artisan::call('migrate', [
                '--database' => $connection,
                '--path' => 'database/migrations/tenant',
                '--force' => true,
            ]);
            $this->line(Artisan::output());

            if ($migrateExit !== 0) {
                $this->error("Tenant migrations failed for tenant {$tenant->id} (exit {$migrateExit}).");

                return 1;
            }
        } catch (\Throwable $e) {
            $this->error("Failed for tenant {$tenant->id}: ".$e->getMessage());

            return 1;
        } finally {
            Tenant::forgetCurrent();
        }
    }

    $this->info('Done.');

    return 0;
})->purpose('Run database/migrations/tenant against one or all provisioned tenant databases');

Artisan::command('tenants:provision-db {tenantId}', function (int $tenantId) {
    if (! SingleDbMigrationMode::allowLegacyProvisioning()) {
        $this->error('Legacy tenant database provisioning is disabled during single-db migration mode.');

        return 1;
    }

    /** @var Tenant|null $tenant */
    $tenant = Tenant::find($tenantId);

    if (! $tenant) {
        $this->error('Tenant not found.');

        return 1;
    }

    if (! $tenant->database) {
        $this->error('Tenant database name is missing.');

        return 1;
    }

    $database = preg_replace('/[^A-Za-z0-9_]/', '', $tenant->database);

    if (! $database) {
        $this->error('Invalid tenant database name.');

        return 1;
    }

    DB::connection('landlord')->statement("CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $this->info("Database created or already exists: {$database}");

    if ($tenant->db_username) {
        $username = preg_replace('/[^A-Za-z0-9_]/', '', $tenant->db_username);
        $password = str_replace("'", "''", (string) $tenant->db_password);

        DB::connection('landlord')->statement("CREATE USER IF NOT EXISTS '{$username}'@'%' IDENTIFIED BY '{$password}'");
        DB::connection('landlord')->statement("GRANT ALL PRIVILEGES ON `{$database}`.* TO '{$username}'@'%'");
        DB::connection('landlord')->statement('FLUSH PRIVILEGES');

        $this->info("Database user provisioned: {$username}");
    }

    $migrateExit = 1;
    $provisioningError = null;

    $tenant->makeCurrent();

    try {
        $migrateExit = Artisan::call('migrate', [
            '--database' => config('multitenancy.tenant_database_connection_name', 'tenant'),
            '--path' => 'database/migrations/tenant',
            '--force' => true,
        ]);

        $this->line(Artisan::output());
        if ($migrateExit === 0) {
            $this->info('Tenant schema migrated successfully.');

            try {
                $this->call(TenantRbacSeeder::class);
                $this->info('Tenant RBAC seeded.');
            } catch (\Throwable $e) {
                $this->error('Tenant RBAC seed failed: '.$e->getMessage());
                $migrateExit = 1;
                $provisioningError = 'Tenant RBAC seed failed: '.$e->getMessage();
            }
        } else {
            $this->error('Tenant migrations failed.');
            $provisioningError = 'Tenant migrate exit code: '.$migrateExit;
        }
    } finally {
        Tenant::forgetCurrent();
    }

    $tenant->refresh();

    if ($migrateExit !== 0) {
        $tenant->update([
            'database_provisioned' => false,
            'provisioning_error' => $provisioningError ?? 'Tenant migrate exit code: '.$migrateExit,
        ]);

        return 1;
    }

    $tenant->update([
        'database_provisioned' => true,
        'database_provisioned_at' => now(),
        'provisioning_error' => null,
    ]);

    $this->info('Tenant database provisioning completed.');

    return 0;
})->purpose('Create and grant a dedicated database for a tenant');

Artisan::command('tenants:sync-rbac {tenantId?}', function (?string $tenantId = null) {
    if (! SingleDbMigrationMode::allowTenantSwitching()) {
        $this->warn('Tenant DB switching is disabled by single-db migration mode. Sync RBAC through landlord schema tooling.');

        return 0;
    }

    $id = ($tenantId !== null && $tenantId !== '') ? (int) $tenantId : null;

    $query = Tenant::query()->orderBy('id');

    if ($id !== null) {
        $query->whereKey($id);
    }

    $tenants = $query->get();

    if ($tenants->isEmpty()) {
        $this->error($id !== null ? 'Tenant not found.' : 'No tenants to process.');

        return 1;
    }

    foreach ($tenants as $tenant) {
        if (! $tenant->database) {
            $this->warn("Skipping tenant {$tenant->id}: no database configured.");

            continue;
        }

        $this->line("Syncing RBAC for tenant {$tenant->id} ({$tenant->name})...");

        $tenant->makeCurrent();

        try {
            $migrateExit = Artisan::call('migrate', [
                '--database' => config('multitenancy.tenant_database_connection_name', 'tenant'),
                '--path' => 'database/migrations/tenant',
                '--force' => true,
            ]);
            $this->line(Artisan::output());

            if ($migrateExit !== 0) {
                $this->error("Tenant migrations failed for tenant {$tenant->id} (exit {$migrateExit}).");

                return 1;
            }

            $this->call(TenantRbacSeeder::class);
        } catch (\Throwable $e) {
            $this->error("Failed for tenant {$tenant->id}: ".$e->getMessage());

            return 1;
        } finally {
            Tenant::forgetCurrent();
        }
    }

    $this->info('Done.');

    return 0;
})->purpose('Migrate tenant schema (including Spatie tables), seed roles/permissions, and sync legacy user roles');

Artisan::command('tenants:rename-domains {--dry-run}', function () {
    $baseDomain = env('TENANT_BASE_DOMAIN', env('CENTRAL_DOMAIN', parse_url((string) config('app.url'), PHP_URL_HOST) ?: 'localhost'));
    $dryRun = (bool) $this->option('dry-run');

    $this->info('Backfilling tenant slugs/domains using Business/App Name...');
    $this->line('Base domain: '.$baseDomain);
    $this->line($dryRun ? 'Mode: DRY RUN (no changes will be saved)' : 'Mode: APPLY');

    $updated = 0;
    $skipped = 0;

    Tenant::query()->orderBy('id')->chunkById(100, function ($tenants) use ($baseDomain, $dryRun, &$updated, &$skipped) {
        foreach ($tenants as $tenant) {
            $sourceName = trim((string) ($tenant->app_title ?: $tenant->name ?: ('tenant-'.$tenant->id)));
            $baseSlug = Str::slug($sourceName);

            if ($baseSlug === '') {
                $baseSlug = 'tenant-'.$tenant->id;
            }

            $baseSlug = substr($baseSlug, 0, 48);

            $newSlug = $baseSlug;
            $newDomain = $newSlug.'.'.$baseDomain;

            if (Tenant::query()->where('id', '!=', $tenant->id)->where('slug', $newSlug)->exists()) {
                $suffix = '-'.$tenant->id;
                $newSlug = substr($baseSlug, 0, max(1, 63 - strlen($suffix))).$suffix;
                $newDomain = $newSlug.'.'.$baseDomain;
            }

            $counter = 2;
            while (Tenant::query()->where('id', '!=', $tenant->id)
                ->where(function ($query) use ($newSlug, $newDomain) {
                    $query->where('slug', $newSlug)->orWhere('domain', $newDomain);
                })->exists()) {
                $suffix = '-'.$tenant->id.'-'.$counter;
                $newSlug = substr($baseSlug, 0, max(1, 63 - strlen($suffix))).$suffix;
                $newDomain = $newSlug.'.'.$baseDomain;
                $counter++;
            }

            if ($tenant->slug === $newSlug && $tenant->domain === $newDomain) {
                $skipped++;
                $this->line("= Tenant {$tenant->id} unchanged ({$tenant->domain})");

                continue;
            }

            $this->line("~ Tenant {$tenant->id}: {$tenant->slug} -> {$newSlug} | {$tenant->domain} -> {$newDomain}");

            if (! $dryRun) {
                $tenant->update([
                    'slug' => $newSlug,
                    'domain' => $newDomain,
                ]);
            }

            $updated++;
        }
    });

    $this->newLine();
    $this->info("Done. Updated: {$updated}, Skipped: {$skipped}");

    return 0;
})->purpose('Rename existing tenant slugs/domains from Business/App Name');

Artisan::command('releases:sync', function (ReleaseRegistryService $releaseRegistryService) {
    $result = $releaseRegistryService->syncFromGitHub();
    $this->info("Synced releases. New: {$result['synced']}, Updated: {$result['updated']}, Skipped: {$result['skipped']}");

    return 0;
})->purpose('Sync global release registry from GitHub releases');

Artisan::command('tenants:backfill-updates {--tenant=} {--dry-run}', function (TenantUpdateService $tenantUpdateService, ReleaseRegistryService $releaseRegistryService) {
    $tenantId = (int) ($this->option('tenant') ?? 0);
    $dryRun = (bool) $this->option('dry-run');
    $latest = $releaseRegistryService->getLatestStableRelease();

    if (! $latest) {
        $this->error('No stable release found in app_releases. Run releases:sync first.');

        return 1;
    }

    $query = Tenant::query()->orderBy('id');
    if ($tenantId > 0) {
        $query->whereKey($tenantId);
    }

    $tenants = $query->get();
    if ($tenants->isEmpty()) {
        $this->warn('No tenants found to backfill.');

        return 0;
    }

    $count = 0;
    foreach ($tenants as $tenant) {
        if ($dryRun) {
            $this->line("[DRY RUN] Would backfill tenant {$tenant->id} to {$latest->tag}");
            $count++;

            continue;
        }

        $tenantUpdateService->backfillCurrentReleaseForTenant($tenant, $latest);
        $this->line("Backfilled tenant {$tenant->id} to {$latest->tag}");
        $count++;
    }

    $this->info("Backfill complete. Processed {$count} tenant(s).");

    return 0;
})->purpose('Backfill current release adoption records for tenants');

Artisan::command('tenant:update {tenantId} {releaseId}', function (TenantSelfUpdateService $tenantSelfUpdateService) {
    $tenantId = (int) $this->argument('tenantId');
    $releaseId = (int) $this->argument('releaseId');

    if (! AppRelease::query()->whereKey($releaseId)->exists()) {
        $this->error('Release not found.');

        return 1;
    }

    $result = $tenantSelfUpdateService->applyUpdate($tenantId, $releaseId);
    if (! $result['ok']) {
        $this->error($result['message']);

        return 1;
    }

    $this->info($result['message']);

    return 0;
})->purpose('Apply a release to a tenant and record adoption state');

Artisan::command('single-db:etl {--tenant=} {--tables=users,accommodations,bookings,messages} {--chunk=200} {--dry-run}', function () {
    $tenantId = (int) ($this->option('tenant') ?? 0);
    $chunk = max(50, (int) ($this->option('chunk') ?? 200));
    $dryRun = (bool) $this->option('dry-run');
    $tables = collect(explode(',', (string) $this->option('tables')))
        ->map(fn (string $value): string => trim($value))
        ->filter()
        ->values()
        ->all();

    $allowed = ['users', 'accommodations', 'bookings', 'messages'];
    foreach ($tables as $tableName) {
        if (! in_array($tableName, $allowed, true)) {
            $this->error("Unsupported table for ETL: {$tableName}");

            return 1;
        }
    }

    $tenantQuery = Tenant::query()
        ->whereNotNull('database')
        ->where('database', '!=', '')
        ->orderBy('id');

    if ($tenantId > 0) {
        $tenantQuery->whereKey($tenantId);
    }

    $tenants = $tenantQuery->get();
    if ($tenants->isEmpty()) {
        $this->warn('No tenants matched.');

        return 0;
    }

    $this->line($dryRun ? 'Mode: DRY RUN' : 'Mode: APPLY');
    $this->line('Tables: '.implode(', ', $tables));

    $processed = 0;
    foreach ($tenants as $tenant) {
        $this->newLine();
        $this->info("Tenant {$tenant->id} ({$tenant->name}) from DB {$tenant->database}");

        foreach ($tables as $tableName) {
            $connection = config('multitenancy.tenant_database_connection_name', 'tenant');
            $tenant->makeCurrent();

            try {
                if (! DB::connection($connection)->getSchemaBuilder()->hasTable($tableName)) {
                    $this->warn("Skipping {$tableName}: table not found in tenant DB.");

                    continue;
                }

                $checkpoint = DB::connection('landlord')->table('single_db_migration_checkpoints')
                    ->where('tenant_id', (int) $tenant->id)
                    ->where('table_name', $tableName)
                    ->first();

                $lastLegacyId = (int) ($checkpoint->last_legacy_id ?? 0);
                $this->line("- {$tableName}: starting after legacy id {$lastLegacyId}");

                DB::connection($connection)->table($tableName)
                    ->where('id', '>', $lastLegacyId)
                    ->orderBy('id')
                    ->chunkById($chunk, function ($rows) use ($tenant, $tableName, $dryRun, &$processed): void {
                        foreach ($rows as $row) {
                            $payload = (array) $row;
                            $legacyId = (int) ($payload['id'] ?? 0);
                            if ($legacyId <= 0) {
                                continue;
                            }

                            $existingMap = DB::connection('landlord')->table('single_db_legacy_id_maps')
                                ->where('tenant_id', (int) $tenant->id)
                                ->where('table_name', $tableName)
                                ->where('legacy_id', $legacyId)
                                ->value('new_id');
                            if ($existingMap) {
                                continue;
                            }

                            unset($payload['id']);
                            if (array_key_exists('tenant_id', $payload)) {
                                $payload['tenant_id'] = (int) $tenant->id;
                            }

                            $mapLegacy = function (string $refTable, ?int $refId) use ($tenant): ?int {
                                if (! $refId) {
                                    return null;
                                }

                                $newId = DB::connection('landlord')->table('single_db_legacy_id_maps')
                                    ->where('tenant_id', (int) $tenant->id)
                                    ->where('table_name', $refTable)
                                    ->where('legacy_id', $refId)
                                    ->value('new_id');

                                return $newId ? (int) $newId : null;
                            };

                            if ($tableName === 'accommodations' && array_key_exists('owner_id', $payload)) {
                                $payload['owner_id'] = $mapLegacy('users', isset($payload['owner_id']) ? (int) $payload['owner_id'] : null);
                            }

                            if ($tableName === 'bookings') {
                                if (array_key_exists('accommodation_id', $payload)) {
                                    $payload['accommodation_id'] = $mapLegacy('accommodations', isset($payload['accommodation_id']) ? (int) $payload['accommodation_id'] : null);
                                }
                                if (array_key_exists('client_id', $payload)) {
                                    $payload['client_id'] = $mapLegacy('users', isset($payload['client_id']) ? (int) $payload['client_id'] : null);
                                }
                            }

                            if ($tableName === 'messages') {
                                if (array_key_exists('sender_id', $payload)) {
                                    $payload['sender_id'] = $mapLegacy('users', isset($payload['sender_id']) ? (int) $payload['sender_id'] : null);
                                }
                                if (array_key_exists('receiver_id', $payload)) {
                                    $payload['receiver_id'] = $mapLegacy('users', isset($payload['receiver_id']) ? (int) $payload['receiver_id'] : null);
                                }
                                if (array_key_exists('booking_id', $payload)) {
                                    $payload['booking_id'] = $mapLegacy('bookings', isset($payload['booking_id']) ? (int) $payload['booking_id'] : null);
                                }
                            }

                            if (! $dryRun) {
                                $newId = DB::connection('landlord')->table($tableName)->insertGetId($payload);
                                DB::connection('landlord')->table('single_db_legacy_id_maps')->updateOrInsert(
                                    [
                                        'tenant_id' => (int) $tenant->id,
                                        'source_database' => (string) $tenant->database,
                                        'table_name' => $tableName,
                                        'legacy_id' => $legacyId,
                                    ],
                                    [
                                        'new_id' => $newId,
                                        'updated_at' => now(),
                                        'created_at' => now(),
                                    ]
                                );
                                DB::connection('landlord')->table('single_db_migration_checkpoints')->updateOrInsert(
                                    [
                                        'tenant_id' => (int) $tenant->id,
                                        'source_database' => (string) $tenant->database,
                                        'table_name' => $tableName,
                                    ],
                                    [
                                        'last_legacy_id' => $legacyId,
                                        'completed_at' => null,
                                        'updated_at' => now(),
                                        'created_at' => now(),
                                    ]
                                );
                            }

                            $processed++;
                        }
                    }, 'id');

                if (! $dryRun) {
                    DB::connection('landlord')->table('single_db_migration_checkpoints')
                        ->where('tenant_id', (int) $tenant->id)
                        ->where('table_name', $tableName)
                        ->update([
                            'completed_at' => now(),
                            'notes' => 'Completed via single-db:etl',
                            'updated_at' => now(),
                        ]);
                }

                $this->line("  done: {$tableName}");
            } finally {
                Tenant::forgetCurrent();
            }
        }
    }

    $this->newLine();
    $this->info("ETL finished. Rows processed: {$processed}");

    return 0;
})->purpose('Incrementally import tenant-db rows into landlord single-db tables with checkpoint tracking');

Artisan::command('single-db:reconcile {--tenant=} {--tables=users,accommodations,bookings,messages}', function () {
    $tenantId = (int) ($this->option('tenant') ?? 0);
    $tables = collect(explode(',', (string) $this->option('tables')))
        ->map(fn (string $value): string => trim($value))
        ->filter()
        ->values()
        ->all();

    $tenantQuery = Tenant::query()->orderBy('id');
    if ($tenantId > 0) {
        $tenantQuery->whereKey($tenantId);
    }

    $tenants = $tenantQuery->get();
    if ($tenants->isEmpty()) {
        $this->warn('No tenants matched.');

        return 0;
    }

    foreach ($tenants as $tenant) {
        $this->newLine();
        $this->info("Tenant {$tenant->id} ({$tenant->name})");

        foreach ($tables as $tableName) {
            $mappedCount = DB::connection('landlord')->table('single_db_legacy_id_maps')
                ->where('tenant_id', (int) $tenant->id)
                ->where('table_name', $tableName)
                ->count();

            $landlordCount = DB::connection('landlord')->table($tableName)
                ->when(
                    DB::connection('landlord')->getSchemaBuilder()->hasColumn($tableName, 'tenant_id'),
                    fn ($query) => $query->where('tenant_id', (int) $tenant->id)
                )
                ->count();

            $this->line("- {$tableName}: mapped={$mappedCount}, landlordScoped={$landlordCount}");
        }
    }

    return 0;
})->purpose('Show reconciliation counters for imported tenant rows in single-db migration');

Artisan::command('single-db:migrate {--force : Force the operation to run when in production}', function () {
    $connection = (string) config('multitenancy.landlord_database_connection_name', 'landlord');

    if (! SingleDbMigrationMode::unifiedSchema()) {
        $this->comment('Note: unified single-DB mode is not active (see SINGLE_DB_*). Migrating the landlord connection anyway.');
    }

    $this->info("Running migrations on [{$connection}] (canonical path for tenant + landlord tables in one database).");

    $params = ['--database' => $connection];
    if ($this->option('force')) {
        $params['--force'] = true;
    }

    $exit = Artisan::call('migrate', $params);
    $this->output->write(Artisan::output());

    return $exit;
})->purpose('Run schema migrations on the landlord/unified database (use instead of tenants:migrate in single-DB mode)');

Artisan::command('single-db:status', function () {
    $this->info('Single-DB migration flags');
    $this->table(
        ['Flag', 'Value'],
        [
            ['enabled', config('single_db_migration.enabled') ? 'true' : 'false'],
            ['single_db_reads', config('single_db_migration.single_db_reads') ? 'true' : 'false'],
            ['single_db_writes', config('single_db_migration.single_db_writes') ? 'true' : 'false'],
            ['shadow_reads', config('single_db_migration.shadow_reads') ? 'true' : 'false'],
            ['allow_legacy_provisioning', config('single_db_migration.allow_legacy_provisioning') ? 'true' : 'false'],
            ['allow_tenant_switching', config('single_db_migration.allow_tenant_switching') ? 'true' : 'false'],
        ]
    );

    $landlordKey = config('multitenancy.landlord_database_connection_name', 'landlord');
    $tenantKey = config('multitenancy.tenant_database_connection_name', 'tenant');
    $landlordDb = (string) config("database.connections.{$landlordKey}.database");
    $tenantDb = (string) config("database.connections.{$tenantKey}.database");
    $this->newLine();
    $this->line('Connection database names (from config)');
    $this->line("  {$landlordKey}: <fg=cyan>{$landlordDb}</>");
    $this->line("  {$tenantKey}:   <fg=cyan>{$tenantDb}</>");

    if (SingleDbMigrationMode::unifiedSchema()) {
        $this->line('  unified_schema: <fg=green>true</>');
    }

    if ($landlordDb !== '' && $landlordDb === $tenantDb) {
        $this->info('Landlord and tenant connections use the same database (standard single-DB deployment).');
    } elseif (config('single_db_migration.allow_tenant_switching')) {
        $this->comment('Tenant switching may override the tenant connection at runtime (legacy multi-database).');
    }

    return 0;
})->purpose('Show active single-db migration rollout flags');

Artisan::command('single-db:verify-shadow', function () {
    $mismatchCount = DB::connection('landlord')
        ->table('update_logs')
        ->where('channel_status', 'single_db_shadow_mismatch')
        ->count();

    $this->info("Shadow mismatches logged: {$mismatchCount}");

    return 0;
})->purpose('Summarize single-db shadow-read mismatch telemetry');

Artisan::command('single-db:cutover-readiness {--tenant=}', function () {
    $tenantId = (int) ($this->option('tenant') ?? 0);

    $tenantQuery = Tenant::query()->orderBy('id');
    if ($tenantId > 0) {
        $tenantQuery->whereKey($tenantId);
    }
    $tenants = $tenantQuery->get();

    if ($tenants->isEmpty()) {
        $this->warn('No tenants matched.');

        return 0;
    }

    $tables = ['users', 'accommodations', 'bookings', 'messages'];
    $notReady = 0;

    foreach ($tenants as $tenant) {
        foreach ($tables as $tableName) {
            $checkpoint = DB::connection('landlord')->table('single_db_migration_checkpoints')
                ->where('tenant_id', (int) $tenant->id)
                ->where('table_name', $tableName)
                ->first();

            if (! $checkpoint || ! $checkpoint->completed_at) {
                $notReady++;
                $this->warn("Tenant {$tenant->id} not ready for {$tableName}.");
            }
        }
    }

    if ($notReady > 0) {
        $this->error("Cutover not ready: {$notReady} incomplete table checkpoints found.");

        return 1;
    }

    $this->info('Cutover readiness passed: all required checkpoints are completed.');

    return 0;
})->purpose('Validate tenant ETL checkpoint readiness before final single-db cutover');

Artisan::command('single-db:final-delta {--tenant=} {--chunk=200}', function () {
    $tenant = $this->option('tenant');
    $chunk = (int) ($this->option('chunk') ?? 200);

    $args = [
        '--tables' => 'users,accommodations,bookings,messages',
        '--chunk' => max(50, $chunk),
    ];
    if ($tenant !== null && (string) $tenant !== '') {
        $args['--tenant'] = (string) $tenant;
    }

    $this->info('Running final incremental ETL pass...');
    $exit = Artisan::call('single-db:etl', $args);
    $this->line(Artisan::output());

    if ($exit !== 0) {
        $this->error('Final delta ETL failed.');

        return 1;
    }

    $exit = Artisan::call('single-db:cutover-readiness', $tenant ? ['--tenant' => (string) $tenant] : []);
    $this->line(Artisan::output());

    return $exit;
})->purpose('Run final incremental sync and readiness checks before cutover');

Artisan::command('single-db:decommission-legacy {--apply}', function () {
    $apply = (bool) $this->option('apply');
    $tenants = Tenant::query()->orderBy('id')->get(['id', 'name', 'database', 'db_host', 'db_username', 'database_provisioned']);

    $this->info($apply ? 'Mode: APPLY' : 'Mode: DRY RUN');
    $updated = 0;

    foreach ($tenants as $tenant) {
        if ($tenant->database === null && $tenant->db_host === null && $tenant->db_username === null) {
            continue;
        }

        if (! $apply) {
            $this->line("[DRY RUN] Would clear legacy DB credentials for tenant {$tenant->id} ({$tenant->name})");

            continue;
        }

        $tenant->update([
            'database' => null,
            'db_host' => null,
            'db_port' => null,
            'db_username' => null,
            'db_password' => null,
            'database_provisioned' => true,
            'provisioning_error' => null,
        ]);
        $updated++;
    }

    if ($apply) {
        $this->info("Legacy DB credential fields cleared for {$updated} tenant(s).");
    } else {
        $this->info('Dry-run complete. Re-run with --apply after backups are confirmed.');
    }

    return 0;
})->purpose('Dry-run/apply legacy tenant DB credential decommission after single-db cutover');

Artisan::command('demo:purge-sarah-chen-demo-data', function () {
    $tenant = Tenant::query()
        ->where(function ($q) {
            $q->where('name', 'like', '%Sarah Chen%')
                ->orWhere('slug', 'like', '%sarah-chen%');
        })
        ->orderBy('id')
        ->first();

    if (! $tenant) {
        $this->error('No tenant matching Sarah Chen was found.');

        return 1;
    }

    $tid = (int) $tenant->id;

    $demoBookingMarkers = [
        '[demo-visualization]',
        '[admin-dashboard-demo]',
    ];

    $deletedBookings = 0;
    $deletedAccommodations = 0;
    $deletedUsers = 0;

    DB::transaction(function () use ($tid, $demoBookingMarkers, &$deletedBookings, &$deletedAccommodations, &$deletedUsers): void {
        $deletedBookings = Booking::query()
            ->where('tenant_id', $tid)
            ->whereIn('special_requests', $demoBookingMarkers)
            ->delete();

        $deletedAccommodations = Accommodation::query()
            ->where('tenant_id', $tid)
            ->where(function ($q) {
                $q->where('description', 'like', '%DashboardVisualizationSeeder%')
                    ->orWhere('description', 'like', '%admin dashboard visualization.%')
                    ->orWhere('description', 'like', '%Auto-generated for admin dashboard visualization.%')
                    ->orWhere('address', 'Demo Address, Bukidnon')
                    ->orWhere('address', 'Demo Street, Impasugong, Bukidnon');
            })
            ->delete();

        $deletedUsers = User::query()
            ->where('tenant_id', $tid)
            ->where(function ($q) {
                $q->where('email', 'like', 'demo.visual.client.%')
                    ->orWhere('email', 'like', 'admin.demo.client.%');
            })
            ->delete();
    });

    $this->info("Tenant #{$tid}: {$tenant->name}");
    $this->line("  Deleted demo bookings: {$deletedBookings}");
    $this->line('  Deleted demo accommodations: '.$deletedAccommodations);
    $this->line('  Deleted demo guest users: '.$deletedUsers);

    return 0;
})->purpose('Remove visualization demo rows created by DashboardVisualizationSeeder / AdminDashboardVisualizationSeeder for Sarah Chen\'s Space');

Schedule::command('releases:sync')->dailyAt('02:00');
