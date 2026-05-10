<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\User;
use App\Notifications\Central\AdminImportantNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class CentralAdminNotifier
{
    /**
     * Notify Tulogans admins when a new owner registers on the central app and submits municipality onboarding documents.
     */
    public function notifyNewOwnerRegistered(Tenant $tenant, User $owner): void
    {
        try {
            $connection = (string) config('multitenancy.landlord_database_connection_name', 'landlord');
            $admins = User::on($connection)
                ->where('role', User::ROLE_ADMIN)
                ->whereNull('tenant_id')
                ->get();

            if ($admins->isEmpty()) {
                return;
            }

            $planKey = (string) ($tenant->plan ?? Tenant::PLAN_BASIC);
            $planLabel = Tenant::planLabel($planKey);
            $ownerName = (string) $owner->name;
            $ownerEmail = (string) $owner->email;
            $tenantName = (string) $tenant->name;
            $onboarding = (string) ($tenant->onboarding_status ?? '');

            Notification::send(
                $admins,
                new AdminImportantNotification(
                    title: 'New host application',
                    body: "{$ownerName} ({$ownerEmail}) submitted documents for {$tenantName} ({$planLabel} catalog tier). Current onboarding status: {$onboarding}. Review municipality uploads and approve or reject from Host management.",
                    actionUrl: '/admin/tenants?onboarding_status='.Tenant::ONBOARDING_PENDING_APPROVAL,
                    actionLabel: 'Review pending hosts',
                )
            );
        } catch (\Throwable $exception) {
            Log::warning('Central admin new-owner registration notification failed.', [
                'tenant_id' => $tenant->id,
                'owner_user_id' => $owner->id,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    public function notifyOnboardingPaymentPendingReview(Tenant $tenant): void
    {
        try {
            $connection = (string) config('multitenancy.landlord_database_connection_name', 'landlord');
            $admins = User::on($connection)
                ->where('role', User::ROLE_ADMIN)
                ->whereNull('tenant_id')
                ->get();

            if ($admins->isEmpty()) {
                return;
            }

            Notification::send(
                $admins,
                new AdminImportantNotification(
                    title: 'Onboarding payment snapshot (legacy)',
                    body: "{$tenant->name} has legacy onboarding payment artifacts on record. Municipality document review remains the primary approval path.",
                    actionUrl: '/admin/tenants',
                    actionLabel: 'Open Tulogans',
                )
            );
        } catch (\Throwable $exception) {
            Log::warning('Central admin onboarding notification failed.', [
                'tenant_id' => $tenant->id,
                'error' => $exception->getMessage(),
            ]);
        }
    }
}
