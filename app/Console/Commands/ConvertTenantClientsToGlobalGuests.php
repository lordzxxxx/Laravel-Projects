<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ConvertTenantClientsToGlobalGuests extends Command
{
    protected $signature = 'users:guests-globalize
        {--dry-run : Show how many users would change without updating}
        {--tenant= : Only convert guests currently on this tenant_id}
        {--email= : Only convert one user by email}';

    protected $description = 'Convert tenant-bound client accounts into municipality-wide guests (tenant_id = null)';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $tenantFilter = $this->option('tenant');
        $emailFilter = $this->option('email');

        $query = User::query()
            ->where('role', User::ROLE_CLIENT)
            ->whereNotNull('tenant_id');

        if (is_string($tenantFilter) && $tenantFilter !== '') {
            $query->where('tenant_id', (int) $tenantFilter);
        }

        if (is_string($emailFilter) && $emailFilter !== '') {
            $query->where('email', $emailFilter);
        }

        $count = (clone $query)->count();

        if ($count === 0) {
            $this->info('No tenant-bound guest accounts found to convert.');

            return self::SUCCESS;
        }

        $this->line('Tenant-bound guest accounts to convert: '.$count);

        if ($dryRun) {
            $this->warn('Dry run only — no changes were made.');

            return self::SUCCESS;
        }

        $updated = $query->update(['tenant_id' => null]);

        $this->info("Converted {$updated} guest account(s) to municipality-wide (tenant_id = null).");

        return self::SUCCESS;
    }
}

